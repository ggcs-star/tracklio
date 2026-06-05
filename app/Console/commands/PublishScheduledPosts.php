<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Services\Post\FacebookPostService;
use App\Services\Post\InstagramPostService;
use App\Services\Post\YouTubePostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublishScheduledPosts extends Command
{
    protected $signature =
        'posts:publish-scheduled';

    protected $description =
        'Publish scheduled social media posts';

    public function handle()
    {
        $posts = Post::where('status','scheduled'
        )->where('scheduled_at','<=',now()
        )->get();

        foreach ($posts as $post) {
        try {
                $post->update([
                    'status' => 'processing'
                ]);

                $platforms =
                    $post->platforms ?? [];

                $request =
                    new Request();

              if (
                    in_array(
                        'facebook',
                        $platforms
                    )
                ) {

                    $fbRequest = new Request([
                        'facebook_post_type' =>
                            $post->facebook_post_type
                            ?? 'post',
                    ]);

                    app(
                        FacebookPostService::class
                    )->publish(
                        $fbRequest,
                        $post
                    );
                }

             if (
                in_array(
                    'instagram',
                    $platforms
                )
            ) {

                $igRequest = new Request([
                    'ig_post_type' => $post->ig_post_type,
                    'instagram_profile_id' => $post->instagram_profile_id,
                ]);

                app(
                    InstagramPostService::class
                )->publish(
                    $igRequest,
                    $post
                );
            }

                if (
                    in_array(
                        'youtube',
                        $platforms
                    )
                ) {

                    app(
                        YouTubePostService::class
                    )->publish(
                        $request,
                        $post
                    );
                }

                $post->update([
                    'status' => 'published',
                    'published_at' => now(),
                ]);

            } catch (\Throwable $e) {

                Log::error(
                    'SCHEDULED POST FAILED',
                    [
                        'post_id' =>
                            $post->_id,

                        'error' =>
                            $e->getMessage(),
                    ]
                );

                $post->update([
                    'status' => 'failed',
                    'error_message' =>
                        $e->getMessage(),
                ]);
            }
        }

        return Command::SUCCESS;
    }
}