<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Post\PostCrudService;
use Illuminate\Support\Facades\Http;

class PostController extends Controller
{
    protected PostCrudService $posts;

    public function __construct(PostCrudService $posts)
    {
        $this->posts = $posts;
    }

    public function create()
    {
        return $this->posts->create();
    }

    public function store(Request $request)
    {
        return $this->posts->store($request);
    }
    public function show($id)
    {
        $post = \App\Models\Post::find($id);

        if (!$post) {
            $post = \App\Models\Post::where('facebook_post_id', $id)
                ->orWhere('instagram_post_id', $id)
                ->orWhere('youtube_video_id', $id)
                ->first();
        }

        if (!$post) {
            abort(404, 'Post not found');
        }

        $reactions = 0;
        $comments = 0;
        $shares = 0;
        
        $platform = $post->platforms[0] ?? 'facebook';
        
        if ($platform === 'facebook' && $post->facebook_post_id) {
            try {
                $account = SocialAccount::where('user_id', (string) $post->user_id)
                    ->where('platform', 'facebook')
                    ->first();
                
                $token = null;
                if ($account && !empty($account->pages)) {
                    foreach ($account->pages as $page) {
                        if ($page['page_id'] == $post->facebook_page_id) {
                            $token = $page['page_access_token'];
                            break;
                        }
                    }
                }
                
                if ($token) {
                    $fbPost = Http::timeout(5)->get("https://graph.facebook.com/v24.0/{$post->facebook_post_id}", [
                        'fields' => 'reactions.summary(true),comments.summary(true)',
                        'access_token' => $token
                    ])->json();
                    
                    $reactions = $fbPost['reactions']['summary']['total_count'] ?? 0;
                    $comments = $fbPost['comments']['summary']['total_count'] ?? 0;
                }
            } catch (\Throwable $e) {}
        }
        
        if ($platform === 'instagram') {
            try {
                $instagramAccount = SocialAccount::where('user_id', (string) $post->user_id)
                    ->where('platform', 'instagram')
                    ->where('_id', $post->instagram_profile_id)
                    ->first();
                
                $token = $instagramAccount->credentials['page_access_token'] ?? null;
                
                if ($token && $post->instagram_post_id) {
                    $igPost = Http::timeout(5)->get("https://graph.facebook.com/v24.0/{$post->instagram_post_id}", [
                        'fields' => 'like_count,comments_count',
                        'access_token' => $token
                    ])->json();
                    
                    $reactions = $igPost['like_count'] ?? 0;
                    $comments = $igPost['comments_count'] ?? 0;
                }
            } catch (\Throwable $e) {}
        }
        
        if ($platform === 'youtube' && $post->youtube_video_id) {
            try {
                $youtubeAccount = SocialAccount::where('user_id', (string) $post->user_id)
                    ->where('platform', 'youtube')
                    ->where('_id', $post->youtube_account_id)
                    ->first();
                
                if ($youtubeAccount) {
                    $creds = $youtubeAccount->credentials;
                    if (!empty($creds['refresh_token'])) {
                        $tokenRes = Http::timeout(10)->asForm()->post(
                            'https://oauth2.googleapis.com/token',
                            [
                                'client_id' => env('YOUTUBE_CLIENT_ID'),
                                'client_secret' => env('YOUTUBE_CLIENT_SECRET'),
                                'refresh_token' => $creds['refresh_token'],
                                'grant_type' => 'refresh_token',
                            ]
                        );
                        
                        if ($tokenRes->successful()) {
                            $accessToken = $tokenRes->json('access_token');
                            $ytVideo = Http::timeout(5)->withToken($accessToken)->get(
                                "https://www.googleapis.com/youtube/v3/videos",
                                [
                                    'part' => 'statistics',
                                    'id' => $post->youtube_video_id
                                ]
                            )->json();
                            
                            $stats = $ytVideo['items'][0]['statistics'] ?? [];
                            $reactions = $stats['likeCount'] ?? 0;
                            $comments = $stats['commentCount'] ?? 0;
                            $shares = 0;
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        $platformIcons = [
            'facebook' => ['icon' => 'fab fa-facebook-f', 'color' => 'bg-blue-600', 'text' => 'text-blue-600'],
            'instagram' => ['icon' => 'fab fa-instagram', 'color' => 'bg-pink-600', 'text' => 'text-pink-600'],
            'youtube' => ['icon' => 'fab fa-youtube', 'color' => 'bg-red-600', 'text' => 'text-red-600'],
        ];

        $iconData = $platformIcons[$platform] ?? $platformIcons['facebook'];

        $mediaUrls = [];

        if (!empty($post->media_paths)) {
            foreach ($post->media_paths as $path) {
                $mediaUrls[] = asset('storage/' . $path);
            }
        } elseif (!empty($post->media_path)) {
            $mediaUrls[] = asset('storage/' . $post->media_path);
        }

        return view('posts.show', compact('post', 'iconData', 'mediaUrls', 'platform', 'reactions', 'comments', 'shares'));
    }
}
