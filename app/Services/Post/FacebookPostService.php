<?php

namespace App\Services\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\SocialAccount;

class FacebookPostService
{
    protected string $fbVersion = 'v24.0';

    public function publish(Request $request, Post $post): void
    {
        if (!$request->facebook_page_id) {
            throw new \Exception('Facebook page not selected');
        }

        $account = SocialAccount::forUser(auth()->user()->_id)
            ->where('platform', 'facebook')
            ->first();

        if (!$account) {
            throw new \Exception('Facebook account not connected');
        }

        $page = collect($account->pages)
            ->firstWhere('page_id', $request->facebook_page_id);

        if (!$page || empty($page['page_access_token'])) {
            throw new \Exception('Facebook page access token missing');
        }

        if (!$request->hasFile('media')) {
            $res = Http::asForm()->post(
                "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/feed",
                [
                    'message' => $post->content,
                    'access_token' => $page['page_access_token'],
                ]
            );

            if (!$res->successful()) {
                throw new \Exception(
                    $res->json('error.message') ?? 'Facebook text post failed'
                );
            }

            return;
        }

        $file = $request->file('media');
        $mime = $file->getMimeType();

        if (str_starts_with($mime, 'image')) {
            $res = Http::attach(
                'source',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post(
                "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/photos",
                [
                    'caption' => $post->content ?? '',
                    'access_token' => $page['page_access_token'],
                ]
            );
        } elseif (str_starts_with($mime, 'video')) {
            $res = Http::attach(
                'source',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post(
                "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}/videos",
                [
                    'description' => $post->content ?? '',
                    'access_token' => $page['page_access_token'],
                ]
            );
        } else {
            throw new \Exception('Unsupported media type for Facebook');
        }

        if (!$res->successful()) {
            throw new \Exception(
                $res->json('error.message') ?? 'Facebook media upload failed'
            );
        }
    }
}
