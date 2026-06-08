<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;
use App\Services\Post\InstagramPostService;

class InstagramPostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public $timeout = 600;
    protected $post;
    protected $postType;

    public function __construct(Post $post, string $postType)
    {
        $this->post = $post;
        $this->postType = $postType;
    }

    public function handle(InstagramPostService $instagramService)
    {
        $request = new \Illuminate\Http\Request();
        $request->merge(['ig_post_type' => $this->postType]);
        
        $instagramService->publish($request, $this->post);
        
        $this->post->update(['status' => 'published', 'published_at' => now()]);
    }
}