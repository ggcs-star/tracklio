<?php

namespace App\Services\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\SocialAccount;

class PostCrudService
{
    protected FacebookPostService $facebook;
    protected InstagramPostService $instagram;
    protected YouTubePostService $youtube;

    public function __construct(
        FacebookPostService $facebook,
        InstagramPostService $instagram,
        YouTubePostService $youtube
    ) {
        $this->facebook = $facebook;
        $this->instagram = $instagram;
        $this->youtube = $youtube;
    }

    public function create()
    {
        try {
            $userId = (string) auth()->user()->_id;

            $accounts = SocialAccount::where('user_id', $userId)
                ->where('status', 'connected')
                ->get()
                ->keyBy('platform');

            $facebookAccount = SocialAccount::where('user_id', $userId)
                ->where('platform', 'facebook')
                ->where('status', 'connected')
                ->first();

            $facebookPages = $facebookAccount->pages ?? [];

            Log::info('CREATE POST PAGE', [
                'user_id'  => $userId,
                'accounts' => $accounts->keys(),
                'fb_pages' => count($facebookPages),
            ]);

            return view('posts.create', compact('accounts', 'facebookPages'));
        } catch (\Throwable $e) {
            Log::critical('CREATE PAGE FAILED', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'publish_error' => 'Failed to load create post page'
            ]);
        }
    }

    public function store(Request $request)
    {
        Log::info('POST STORE HIT', $request->all());

        try {
            $request->validate([
                'content'          => 'nullable|string',
                'platforms'        => 'required|string',
                'facebook_page_id' => 'nullable|string',
                'media'            => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov',
                'media_url'        => 'nullable|url',
                'is_short'         => 'nullable|boolean',
            ]);

            if (
                !$request->filled('content') &&
                !$request->hasFile('media') &&
                !$request->filled('media_url')
            ) {
                throw new \Exception('Post content, media file, or media URL is required');
            }

            $platforms = json_decode($request->platforms, true);

            if (!is_array($platforms) || empty($platforms)) {
                throw new \Exception('Please select at least one platform');
            }

            $platforms = array_map('strtolower', $platforms);

            $post = Post::create([
                'user_id'   => (string) auth()->user()->_id,
                'content'   => $request->input('content'),
                'platforms' => $platforms,
                'media_url' => $request->media_url,
                'is_short'  => (bool) $request->is_short,
                'status'    => 'processing',
            ]);

            $success = [];
            $errors  = [];

            if (in_array('facebook', $platforms)) {
                try {
                    $this->facebook->publish($request, $post);
                    $success[] = 'Facebook';
                } catch (\Throwable $e) {
                    Log::error('FACEBOOK FAILED', ['error' => $e->getMessage()]);
                    $errors[] = 'Facebook: ' . $e->getMessage();
                }
            }

            if (in_array('instagram', $platforms)) {
                try {
                    $this->instagram->publish($request, $post);
                    $success[] = 'Instagram';
                } catch (\Throwable $e) {
                    Log::error('INSTAGRAM FAILED', ['error' => $e->getMessage()]);
                    $errors[] = 'Instagram: ' . $e->getMessage();
                }
            }

            if (in_array('youtube', $platforms)) {
                try {
                    $this->youtube->publish($request, $post);
                    $success[] = 'YouTube';
                } catch (\Throwable $e) {
                    Log::error('YOUTUBE FAILED', ['error' => $e->getMessage()]);
                    $errors[] = 'YouTube: ' . $e->getMessage();
                }
            }

          if (!empty($errors)) {
    $post->update(['status' => 'failed']);

  
    \App\Models\Notification::create([
        'user_id' => (string) auth()->user()->_id,
        'type'    => 'post_failed',
        'message' => 'Your post failed to publish on: ' . implode(', ', $platforms),
        'is_read' => false,
    ]);

    return back()->withErrors([
        'publish_error' => implode(' | ', $errors)
    ]);
}
$post->update(['status' => 'published']);


\App\Models\Notification::create([
    'user_id' => (string) auth()->user()->_id,
    'type'    => 'post_published',
    'message' => 'Your post was successfully published on: ' . implode(', ', $success),
    'is_read' => false,
]);

return back()->with(
    'success',
    'Post published successfully on: ' . implode(', ', $success)
);

        } catch (\Throwable $e) {
            Log::critical('POST STORE CRASH', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'publish_error' => $e->getMessage()
            ]);
        }
    }
}
