<?php

namespace App\Services\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\SocialAccount;
use App\Helpers\TextFormatter;

class FacebookPostService
{
    protected string $fbVersion = 'v24.0';

    public function publish(Request $request, Post $post): void
    {
        if (
            !$request->facebook_page_id &&
            !$post->facebook_page_id
        ) {
            throw new \Exception('Facebook page not selected');
        }

        $account = SocialAccount::forUser($post->user_id)
            ->where('platform', 'facebook')
            ->first();

        if (!$account) {
            throw new \Exception('Facebook account not connected');
        }

        $pageId =
            $request->facebook_page_id
            ?: $post->facebook_page_id;

        $page = collect($account->pages)
            ->firstWhere(
                'page_id',
                $pageId
            );

        if (!$page || empty($page['page_access_token'])) {
            throw new \Exception('Facebook page access token missing');
        }

      $fbPostType = $request->facebook_post_type ?? $post->facebook_post_type ?? 'post';

        $mediaPaths = $post->media_paths ?? [];
        if (!empty($mediaPaths) && count($mediaPaths) > 1) {
            $attachedMedia = [];
            foreach ($mediaPaths as $path) {
                $fullPath = storage_path('app/public/' . $path);
                if (!file_exists($fullPath)) continue;
                
                $upload = Http::attach('source', file_get_contents($fullPath), basename($fullPath))
                    ->post("https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/photos", [
                        'published' => false,
                        'access_token' => $page['page_access_token'],
                    ]);
                
                if ($upload->successful()) {
                    $attachedMedia[] = ['media_fbid' => $upload->json('id')];
                }
            }
            
            if (!empty($attachedMedia)) {
                $res = Http::asForm()->post("https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/feed", [
                    'message' => TextFormatter::convert($post->content ?? ''),
                    'attached_media' => json_encode($attachedMedia),
                    'access_token' => $page['page_access_token'],
                ]);
                
                if (!$res->successful()) {
                    throw new \Exception($res->json('error.message') ?? 'Carousel post failed');
                }
                return;
            }
        }

        if (
            !$request->hasFile('media') &&
            !$post->media_path &&
            $fbPostType === 'post'
        ) {
            $content = TextFormatter::convert($post->content ?? '');

            if ($content === '') {
                throw new \Exception('Post content is required');
            }

            $params = [
                'message' => $content,
                'access_token' => $page['page_access_token'],
            ];

            if ($post->location_id && !str_starts_with($post->location_id, 'osm_')) {
                $params['place'] = json_encode(['id' => $post->location_id]);
            }

            $res = Http::asForm()->post(
                "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/feed",
                $params
            );

            if (!$res->successful()) {
                throw new \Exception($res->json('error.message') ?? 'Facebook text post failed');
            }
            $fbPostId = $res->json('id');
            $post->facebook_post_id = $fbPostId;
            $post->save();

            return;
        }
        if ($request->hasFile('media')) {

            $file = $request->file('media');

            $path = $file->getRealPath();

            $name = $file->getClientOriginalName();

            $mime = $file->getMimeType();
            $fbPostType =
                $request->facebook_post_type
                ?? $post->facebook_post_type
                ?? 'post';

        } else {

            $path = storage_path(
                'app/public/' . $post->media_path
            );

            $name = basename($path);

            $mime = mime_content_type($path);
            $fbPostType =
                $request->facebook_post_type
                ?? $post->facebook_post_type
                ?? 'post';
            
        }

      if (
        $fbPostType === 'story'
    ) {

        if (
            str_starts_with(
                $mime,
                'image'
            )
        ) {

        $upload = Http::attach(
            'source',
            file_get_contents($path),
            $name
        )->post(
            "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/photos",
            [
                'published' => false,

                'access_token' =>
                    $page['page_access_token'],
            ]
        );

        if (
            !$upload->successful()
        ) {

            throw new \Exception(
                'Facebook story image upload failed'
            );
        }

        $photoId =
            $upload->json('id');

        $res = Http::asForm()->post(
            "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/photo_stories",
            [
                'photo_id' =>
                    $photoId,

                'access_token' =>
                    $page['page_access_token'],
            ]
        );

    } elseif (
        str_starts_with(
            $mime,
            'video'
        )
    ) {

        $start = Http::asForm()->post(
            "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/video_stories",
            [
                'upload_phase' => 'start',

                'access_token' =>
                    $page['page_access_token'],
            ]
        );

        if (
            !$start->successful()
        ) {

            throw new \Exception(
                'Facebook story video start failed'
            );
        }

    $videoId =
        $start->json('video_id');

    $uploadUrl =
        $start->json('upload_url');

    $upload = Http::withHeaders([

        'Authorization' =>
            'OAuth ' .
            $page['page_access_token'],

        'offset' => '0',

        'file_size' =>
            (string) filesize($path),

    ])->withOptions([
        'verify' => false
    ])->withBody(
        file_get_contents($path),
        'application/octet-stream'
    )->post(
        $uploadUrl
    );

    if (
        !$upload->successful()
    ) {

        throw new \Exception(
            'Facebook story video upload failed'
        );
    }

    $res = Http::asForm()->post(
        "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/video_stories",
        [
            'upload_phase' => 'finish',

            'video_id' =>
                $videoId,

            'video_state' =>
                'PUBLISHED',

            'access_token' =>
                $page['page_access_token'],
        ]
    );

    } else {

        throw new \Exception(
            'Unsupported story media type'
        );
    }

    } else {

        if (
            str_starts_with(
                $mime,
                'image'
            )
        ) {

        $imageParams = [
            'caption' => TextFormatter::convert($post->content ?? ''),
            'access_token' => $page['page_access_token'],
        ];

        if ($post->location_id && !str_starts_with($post->location_id, 'osm_')) {
            $imageParams['place'] = json_encode(['id' => $post->location_id]);
        }

        $res = Http::attach(
            'source',
            file_get_contents($path),
            $name
        )->post(
            "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/photos",
            $imageParams
        );
        $fbPostId = $res->json('id');
        $post->facebook_post_id = $fbPostId;
        $post->save();

    } elseif (
        str_starts_with(
            $mime,
            'video'
        )
    ) {

        $videoParams = [
            'description' => TextFormatter::convert($post->content ?? ''),
            'access_token' => $page['page_access_token'],
        ];

        if ($post->location_id && !str_starts_with($post->location_id, 'osm_')) {
            $videoParams['place'] = json_encode(['id' => $post->location_id]);
        }

        $res = Http::attach(
            'source',
            file_get_contents($path),
            $name
        )->post(
            "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/videos",
            $videoParams
        );
        $fbPostId = $res->json('id');
                $post->facebook_post_id = $fbPostId;
                $post->save();

            } else {

                throw new \Exception(
                    'Unsupported media type for Facebook'
                );
            }
        }

        if (!$res->successful()) {
            throw new \Exception(
                $res->json('error.message') ?? 'Facebook media upload failed'
            );
        }
    }

    
}
