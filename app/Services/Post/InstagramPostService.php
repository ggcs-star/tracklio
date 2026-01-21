<?php

namespace App\Services\Post;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Post;
use App\Models\SocialAccount;

class InstagramPostService
{
    protected string $fbVersion = 'v24.0';

    public function publish(Request $request, Post $post): void
    {
        if (!$post->media_url) {
            throw new \Exception('Instagram requires media URL');
        }

        $fbAccount = SocialAccount::forUser(auth()->user()->_id)
            ->where('platform', 'facebook')
            ->first();

        if (!$fbAccount) {
            throw new \Exception('Facebook account not connected');
        }

        $page = collect($fbAccount->pages)->first();

        $pageInfo = Http::get(
            "https://graph.facebook.com/{$this->fbVersion}/{$page['page_id']}",
            [
                'fields' => 'instagram_business_account',
                'access_token' => $page['page_access_token'],
            ]
        )->json();

        $igId = data_get($pageInfo, 'instagram_business_account.id');

        if (!$igId) {
            throw new \Exception('Instagram business account not linked');
        }

        $create = Http::asForm()->post(
            "https://graph.facebook.com/{$this->fbVersion}/{$igId}/media",
            [
                'image_url' => $post->media_url,
                'caption' => $post->content ?? '',
                'access_token' => $page['page_access_token'],
            ]
        );

        if (!$create->successful()) {
            throw new \Exception(
                $create->json('error.message') ?? $create->body()
            );
        }

        sleep(5);

        $publish = Http::asForm()->post(
            "https://graph.facebook.com/{$this->fbVersion}/{$igId}/media_publish",
            [
                'creation_id' => $create['id'],
                'access_token' => $page['page_access_token'],
            ]
        );

        if (!$publish->successful()) {
            throw new \Exception('Instagram publish failed');
        }
    }
}
