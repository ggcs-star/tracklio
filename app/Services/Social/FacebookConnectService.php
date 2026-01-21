<?php

namespace App\Services\Social;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SocialAccount;

class FacebookConnectService
{
    protected string $version = 'v19.0';

    public function connect()
    {
        try {
            $authUrl = 'https://www.facebook.com/'.$this->version.'/dialog/oauth?' . http_build_query([
                'client_id'     => env('FACEBOOK_CLIENT_ID'),
                'redirect_uri'  => env('FACEBOOK_REDIRECT_URI'),
                'response_type' => 'code',
                'scope' => implode(',', [
                    'pages_show_list',
                    'pages_read_engagement',
                    'pages_manage_metadata',
                    'pages_manage_posts',
                    'instagram_basic',
                    'instagram_content_publish'
                ]),
            ]);

            return redirect($authUrl);
        } catch (\Throwable $e) {
            Log::critical('Facebook connect init failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to connect Facebook');
        }
    }

    public function callback(Request $request)
    {
        try {
            if (!$request->code) {
                return redirect()->route('accounts')
                    ->with('error', 'Facebook authorization failed');
            }

            $token = Http::asForm()->post(
                "https://graph.facebook.com/{$this->version}/oauth/access_token",
                [
                    'client_id'     => env('FACEBOOK_CLIENT_ID'),
                    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
                    'redirect_uri'  => env('FACEBOOK_REDIRECT_URI'),
                    'code'          => $request->code,
                ]
            )->json();

            if (empty($token['access_token'])) {
                return redirect()->route('accounts')
                    ->with('error', 'Failed to get Facebook access token');
            }

            $userToken = $token['access_token'];

            $pages = Http::get(
                "https://graph.facebook.com/{$this->version}/me/accounts",
                [
                    'fields' => 'id,name,access_token',
                    'access_token' => $userToken,
                ]
            )->json();

            if (!isset($pages['data']) || count($pages['data']) === 0) {
                return redirect()->route('accounts')
                    ->with('error', 'Facebook connected but no pages available.');
            }

            $page = $pages['data'][0];

            $ig = Http::get(
                "https://graph.facebook.com/{$this->version}/{$page['id']}",
                [
                    'fields' => 'instagram_business_account',
                    'access_token' => $page['access_token'],
                ]
            )->json();

            SocialAccount::updateOrCreate(
                ['user_id' => auth()->id(), 'platform' => 'facebook'],
                [
                    'status' => 'connected',
                    'credentials' => [
                        'user_access_token' => $userToken,
                    ],
                    'pages' => [[
                        'page_id' => $page['id'],
                        'page_name' => $page['name'],
                        'page_access_token' => $page['access_token'],
                    ]],
                ]
            );

            if (!empty($ig['instagram_business_account']['id'])) {
                SocialAccount::updateOrCreate(
                    ['user_id' => auth()->id(), 'platform' => 'instagram'],
                    [
                        'status' => 'connected',
                        'credentials' => [
                            'instagram_business_id' => $ig['instagram_business_account']['id'],
                            'page_id' => $page['id'],
                            'page_access_token' => $page['access_token'],
                        ],
                    ]
                );
            }
            
                \App\Models\Notification::create([
                    'user_id' => (string) auth()->id(),
                    'type'    => 'facebook_connected',
                    'message' => 'Facebook account connected successfully',
                    'is_read' => false,
                ]);

               
                if (!empty($ig['instagram_business_account']['id'])) {
                    \App\Models\Notification::create([
                        'user_id' => (string) auth()->id(),
                        'type'    => 'instagram_connected',
                        'message' => 'Instagram business account connected successfully',
                        'is_read' => false,
                    ]);
                }


            return redirect()->route('accounts')
                ->with('success', 'Facebook & Instagram connected successfully');
        } catch (\Throwable $e) {
            Log::critical('Facebook callback failed', [
                'user_id' => auth()->id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->route('accounts')
                ->with('error', 'Facebook connection failed');
        }
    }
}
