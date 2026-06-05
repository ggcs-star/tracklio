<?php

namespace App\Services\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\SocialAccount;
use Cloudinary\Cloudinary;
use App\Helpers\TextFormatter;

class InstagramPostService
{
    protected string $fbVersion = 'v25.0';

    public function publish(Request $request, Post $post): void
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $postType = $post->ig_post_type ?? $request->input('ig_post_type', 'post');
        $instagramProfileId = $request->input('instagram_profile_id');

if (!$instagramProfileId) {
    throw new \Exception('Please select Instagram profile.');
}

$instagramAccount = SocialAccount::where('_id', $instagramProfileId)
    ->where('user_id', $post->user_id)
    ->where('platform', 'instagram')
    ->first();

if (!$instagramAccount) {
    throw new \Exception('Instagram account not found.');
}

$accessToken = $instagramAccount->credentials['page_access_token'] ?? null;
$igId = $instagramAccount->credentials['instagram_business_id'] ?? null;

if (!$accessToken || !$igId) {
    throw new \Exception('Instagram profile configuration invalid.');
}
        if (!$post->media_path || !Storage::disk('public')->exists($post->media_path)) {
            throw new \Exception('Instagram media file not found.');
        }

        $fullPath = Storage::disk('public')->path($post->media_path);
        $mimeType = mime_content_type($fullPath);
        $isVideo = str_starts_with($mimeType, 'video/');

        if ($postType === 'post' && $isVideo) {
            throw new \Exception('Instagram post supports only images.');
        }
        if ($postType === 'reel' && !$isVideo) {
            throw new \Exception('Instagram reel requires video.');
        }

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);

        $transformation = [];
        if ($postType === 'story' || $postType === 'reel') {
            $transformation = ['aspect_ratio' => '9:16', 'crop' => 'fill', 'width' => 1080, 'height' => 1920];
        } else {
            $transformation = ['aspect_ratio' => '1:1', 'crop' => 'fill', 'width' => 1080, 'height' => 1080];
        }

        $uploadResult = $cloudinary->uploadApi()->upload($fullPath, [
            'folder' => 'instagram',
            'resource_type' => $isVideo ? 'video' : 'image',
            'transformation' => $transformation
        ]);

        $publicUrl = $uploadResult['secure_url'];

        
        $params = ['access_token' => $accessToken];

        if ($postType === 'post' && !empty($post->media_paths) && count($post->media_paths) > 1) {
            $childrenIds = [];
            
            foreach ($post->media_paths as $mediaPath) {
                $fullPath = Storage::disk('public')->path($mediaPath);
                if (!file_exists($fullPath)) continue;
                
                $mimeType = mime_content_type($fullPath);
                if (!str_starts_with($mimeType, 'image')) {
                    throw new \Exception('Instagram carousel supports only images');
                }
                
                $uploadResult = $cloudinary->uploadApi()->upload($fullPath, [
                    'folder' => 'instagram',
                    'resource_type' => 'image',
                    'transformation' => ['aspect_ratio' => '1:1', 'crop' => 'fill', 'width' => 1080, 'height' => 1080]
                ]);
                
                $imageUrl = $uploadResult['secure_url'];
                
                $child = Http::asForm()->post(
                    "https://graph.facebook.com/{$this->fbVersion}/{$igId}/media",
                    [
                        'image_url' => $imageUrl,
                        'is_carousel_item' => true,
                        'access_token' => $accessToken,
                    ]
                );
                
                if (!$child->successful()) {
                    throw new \Exception('Failed to create carousel child: ' . $child->body());
                }
                
                $childrenIds[] = $child->json('id');
            }
            
            if (count($childrenIds) < 2) {
                throw new \Exception('At least 2 images required for carousel');
            }
            
            $caption = TextFormatter::convert($post->content ?? '');
            
            $carousel = Http::asForm()->post(
                "https://graph.facebook.com/{$this->fbVersion}/{$igId}/media",
                [
                    'media_type' => 'CAROUSEL',
                    'children' => json_encode($childrenIds),
                    'caption' => $caption,
                    'access_token' => $accessToken,
                ]
            );
            
            if (!$carousel->successful()) {
                throw new \Exception('Failed to create carousel: ' . $carousel->body());
            }
            
            $creationId = $carousel->json('id');
            $maxAttempts = 10;
            $ready = false;
            for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
                sleep(2);
                
                $status = Http::get("https://graph.facebook.com/{$this->fbVersion}/{$creationId}?fields=status_code&access_token={$accessToken}")->json();
                $statusCode = $status['status_code'] ?? 'UNKNOWN';
                
                \Log::info("Carousel ready check attempt $attempt: $statusCode");
                
                if ($statusCode === 'FINISHED') {
                    $ready = true;
                    break;
                }
            }

            if (!$ready) {
                \Log::warning('Carousel not ready after waiting, attempting publish anyway');
            }

            
            $publish = Http::asForm()->post(
                "https://graph.facebook.com/{$this->fbVersion}/{$igId}/media_publish",
                [
                    'creation_id' => $creationId,
                    'access_token' => $accessToken,
                ]
            );
            
            if (!$publish->successful()) {
                throw new \Exception('Failed to publish carousel: ' . $publish->body());
            }
            $post->instagram_post_id = $publish->json('id');
            $post->save();
            return;
        }

        switch ($postType) {
            case 'story':
            $storyParams = [
                'media_type' => 'STORIES',
                'access_token' => $accessToken,
            ];
            
            if ($isVideo) {
                $storyParams['video_url'] = $publicUrl;
            } else {
                $storyParams['image_url'] = $publicUrl;
            }
            
            $create = Http::withOptions(['timeout' => 60])->asForm()->post(
                "https://graph.facebook.com/v24.0/{$igId}/media",
                $storyParams
            );
            
            if (!$create->successful()) {
                throw new \Exception("Story container failed: " . $create->body());
            }
            
            $containerId = $create['id'];

            if ($isVideo) {
                $maxAttempts = 20;
                $published = false;
                
                for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
                    sleep(5);
                    
                    $status = Http::get("https://graph.facebook.com/v24.0/{$containerId}?fields=status_code&access_token={$accessToken}")->json();
                    $statusCode = $status['status_code'] ?? 'UNKNOWN';
                    
                    \Log::info("Video story attempt $attempt: $statusCode");
                    
                    if ($statusCode === 'FINISHED') {
                        for ($retry = 1; $retry <= 5; $retry++) {
                            if ($retry > 1) sleep(2);
                            
                            $publish = Http::asForm()->post(
                                "https://graph.facebook.com/v24.0/{$igId}/media_publish",
                                [
                                    'creation_id' => $containerId,
                                    'access_token' => $accessToken,
                                ]
                            );
                            
                            if ($publish->successful()) {
                                $published = true;
                                $post->instagram_post_id = $publish->json('id');
                                $post->save();
                                break 2;
                            }
                            
                            $errorSubcode = $publish->json('error.error_subcode');
                            if ($errorSubcode !== 2207027 && $errorSubcode !== 9007) {
                                throw new \Exception("Story publish failed: " . $publish->body());
                            }
                        }
                    }
                    
                    if ($statusCode === 'ERROR') {
                        throw new \Exception("Story processing failed: " . ($status['status'] ?? 'Unknown error'));
                    }
                }
                
                if (!$published) {
                    throw new \Exception("Video story processing timeout. Please try again.");
                }
            } else {
                $publish = Http::asForm()->post(
                    "https://graph.facebook.com/v24.0/{$igId}/media_publish",
                    [
                        'creation_id' => $containerId,
                        'access_token' => $accessToken,
                    ]
                );
                
                if (!$publish->successful()) {
                    throw new \Exception("Story publish failed: " . $publish->body());
                }
            }
            $post->instagram_post_id = $publish->json('id');
            $post->save();
            return;
            
                
            case 'reel':
                $params['media_type'] = 'REELS';
                $params['video_url'] = $publicUrl;
                $params['share_to_feed'] = 'true';  
                if ($post->content) {
                    $params['caption'] = TextFormatter::convert($post->content ?? '');
                }
                break;
                
            default: 
                $params['image_url'] = $publicUrl;
                if ($post->content) {
                    $params['caption'] = TextFormatter::convert($post->content ?? '');
                }
                break;
        }
        $create = Http::asForm()->post(
            "https://graph.facebook.com/{$this->fbVersion}/{$igId}/media",
            $params
        );

        if (!$create->successful()) {
            throw new \Exception($create->body());
        }

        $creationId = $create['id'];
        $maxAttempts = 30;
        $published = false;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            sleep(3);
            
            try {
                $statusCheck = Http::withOptions([
                    'timeout' => 30,
                    'verify' => false
                ])->get(
                    "https://graph.facebook.com/{$this->fbVersion}/{$creationId}?fields=status_code&access_token={$accessToken}"
                )->json();
            } catch (\Exception $e) {
                continue;
            }

            $statusCode = $statusCheck['status_code'] ?? 'UNKNOWN';
            
            \Log::info("Instagram Polling Attempt $attempt: $statusCode");
            
            if ($statusCode === 'FINISHED') {
                for ($retry = 1; $retry <= 3; $retry++) {
                    if ($retry > 1) sleep(2);
                    
                    try {
                        $publish = Http::withOptions([
                            'timeout' => 30,
                            'verify' => false
                        ])->asForm()->post(
                            "https://graph.facebook.com/{$this->fbVersion}/{$igId}/media_publish",
                            [
                                'creation_id' => $creationId,
                                'access_token' => $accessToken,
                            ]
                        );
                        
                        if ($publish->successful()) {
                            $published = true;
                            break 2;
                        }
                        
                        $errorSubcode = $publish->json('error.error_subcode');
                        if ($errorSubcode !== 2207027 && $errorSubcode !== 9007) {
                            throw new \Exception($publish->body());
                        }
                    } catch (\Exception $e) {
                        if ($retry >= 3) throw $e;
                    }
                }
            }
            
            if ($statusCode === 'ERROR') {
                continue;
            }
        }

    if (!$published) {

        $finalPublish = Http::withOptions(['timeout' => 60, 'verify' => false])->asForm()->post(
            "https://graph.facebook.com/{$this->fbVersion}/{$igId}/media_publish",
            [
                'creation_id' => $creationId,
                'access_token' => $accessToken,
            ]
        );
        
        if (!$finalPublish->successful()) {
            throw new \Exception('Instagram media processing timeout. Please try again.');
        }
        $post->instagram_post_id = $finalPublish->json('id');
        $post->save();
    }
        }
    }