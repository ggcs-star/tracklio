<?php

namespace App\Services\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\SocialAccount;
use App\Jobs\InstagramPostJob;
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
            
            $facebookGroupedPages = [];
                if ($facebookAccount && !empty($facebookAccount->pages)) {
                    foreach ($facebookAccount->pages as $page) {
                        // Use stored profile_name from database
                        $profileId = $page['profile_name'] ?? substr(md5($page['page_access_token'] ?? ''), 0, 8);
                        $profileDisplayName = $page['profile_name'] ?? 'Facebook Profile';
                        
                        if (!isset($facebookGroupedPages[$profileId])) {
                            $facebookGroupedPages[$profileId] = [
                                'profile_name' => $profileDisplayName,
                                'pages' => []
                            ];
                        }
                        $facebookGroupedPages[$profileId]['pages'][] = $page;
                    }
                }
            $instagramProfiles = SocialAccount::where('user_id', $userId)
                ->where('platform', 'instagram')
                ->where('status', 'connected')
                ->get();
            $youtubeAccounts = SocialAccount::where('user_id', auth()->id())
                ->where('platform','youtube')
                ->get();
            Log::info('CREATE POST PAGE', [
                'user_id'  => $userId,
                'accounts' => $accounts->keys(),
                'fb_pages' => count($facebookPages),
            ]);

            return view('posts.create', compact('accounts', 'facebookPages', 'facebookGroupedPages', 'instagramProfiles', 'youtubeAccounts'));        
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
                'media_files'      => 'nullable|array|max:10',
                'media_files.*'    => 'file|max:10240',
                'media_url'        => 'nullable|url',
                'is_short'         => 'nullable|boolean',
                'scheduled_at' => 'nullable|date',
            ]);

            $content = $request->input('content', '');



$content = html_entity_decode($content);

$content = trim($content);

if (
    $content === '' &&
    !$request->hasFile('media') &&
    !$request->hasFile('media_files') &&
    !$request->filled('media_url')
) {
    throw new \Exception(
        'Post content, media file, or media URL is required'
    );
}

            $platforms = json_decode($request->platforms, true);

            if (!is_array($platforms) || empty($platforms)) {
                throw new \Exception('Please select at least one platform');
            }

            $platforms = array_map('strtolower', $platforms);
            $status = 'processing';
            if (
                $request->status === 'scheduled' &&
                !$request->filled('scheduled_at')
            ) {

                return back()->withErrors([
                    'publish_error' =>
                        'Please select schedule date and time'
                ]);
            }


                if (
                    $request->status === 'scheduled' &&
                    $request->filled('scheduled_at')
                ) {

                    $status = 'scheduled';
                }
            $mediaPath = null;
            $mediaPaths = [];

            if ($request->hasFile('media_files')) {
                foreach ($request->file('media_files') as $file) {
                    $mediaPaths[] = $file->store('posts', 'public');
                }
                $mediaPath = $mediaPaths[0] ?? null;
            }

            if ($request->hasFile('media')) {
                $mediaPath = $request->file('media')->store('posts', 'public');
                if (!empty($mediaPaths)) {
                    $mediaPaths = array_merge([$mediaPath], $mediaPaths);
                } else {
                    $mediaPaths = [$mediaPath];
                }
            }
            Log::info('Content before save:', ['content' => $request->input('content')]);
            $post = Post::create([
                'user_id'   => (string) auth()->user()->_id,
                'content' => $content,
                'platforms' => $platforms,
                'media_url' => $request->media_url,
                'media_path' => $mediaPath,
                'media_paths' => $mediaPaths,
                'facebook_page_id' => $request->facebook_page_id,
                'youtube_account_id' => $request->youtube_account_id,
                'facebook_post_type' => $request->input('facebook_post_type','post'),
                'ig_post_type' => $request->input('ig_post_type', 'post'),
                'instagram_profile_id' => $request->instagram_profile_id,
                'is_short'  => (bool) $request->is_short,
                'status' => $status,
                'scheduled_at' =>
                    $request->filled('scheduled_at')
                        ? \Carbon\Carbon::parse($request->scheduled_at)
                        : null,
                'location_id' => $request->location_id,
                'location_name' => $request->location_name,
                
            ]);
            if ($status === 'scheduled') {

            return back()->with(
                'success',
                'Post scheduled successfully'
            );
        }
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
                // Check both media and media_files
                $hasMedia = $request->hasFile('media') || $request->hasFile('media_files');
                if (!$hasMedia) {
                    throw new \Exception('Instagram requires image or video file.');
                }
                
                // If using media_files, use first file as media for Instagram
                if (!$request->hasFile('media') && $request->hasFile('media_files')) {
                    $files = $request->file('media_files');
                    if (count($files) > 0) {
                        $request->files->set('media', $files[0]);
                    }
                }
                
                $igPostType = $request->input('ig_post_type', 'post');
                if (is_array($igPostType)) {
                    $igPostType = $igPostType[0] ?? 'post';
                }
                
                // ✅ Direct call - no queue
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
