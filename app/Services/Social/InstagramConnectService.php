<?php

namespace App\Services\Social;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SocialAccount;

class InstagramConnectService
{
    public function connect()
    {
        try {
            $businessId  = env('INSTAGRAM_BUSINESS_ID');
            $accessToken = env('INSTAGRAM_ACCESS_TOKEN');

            if (!$businessId || !$accessToken) {
                return back()->with('error', 'Instagram Business ID or Access Token missing in .env');
            }

            $check = Http::get(
                "https://graph.facebook.com/v19.0/{$businessId}",
                [
                    'fields' => 'id,username',
                    'access_token' => $accessToken,
                ]
            );

            if (!$check->successful()) {
                return back()->with('error', 'Invalid Instagram access token');
            }

            SocialAccount::updateOrCreate(
                ['user_id' => auth()->id(), 'platform' => 'instagram'],
                [
                    'status' => 'connected',
                    'credentials' => [
                        'instagram_business_id' => $businessId,
                        'access_token' => $accessToken,
                        'username' => $check->json('username'),
                    ],
                ]
            );
            
                \App\Models\Notification::create([
                    'user_id' => (string) auth()->id(),
                    'type'    => 'instagram_connected',
                    'message' => 'Instagram account connected successfully',
                    'is_read' => false,
                ]);


            return redirect()->route('accounts')->with('success', 'Instagram connected successfully')->with('instagram_connected_popup', true);
                } catch (\Throwable $e) {
                    Log::error('Instagram connect error', [
                        'user_id' => auth()->id(),
                        'error'   => $e->getMessage(),
                        'trace'   => $e->getTraceAsString(),
                    ]);

                    return back()->with('error', 'Failed to connect Instagram');
                }
            }
    public function facebookCallback($request)
    {
        try {
            $tokenResponse = Http::get(
                'https://graph.facebook.com/v19.0/oauth/access_token',
                [
                    'client_id' => env('FACEBOOK_CLIENT_ID'),
                    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
                    'redirect_uri' => route('instagram.facebook.callback'),
                    'code' => $request->code,
                ]
            );

            if (!$tokenResponse->successful()) {
                return back()->with('error', 'Failed to get Facebook access token');
            }

            $userAccessToken = $tokenResponse['access_token'];

            $pagesResponse = Http::get(
                'https://graph.facebook.com/v19.0/me/accounts',
                ['access_token' => $userAccessToken]
            );

            $pages = $pagesResponse['data'] ?? [];

            if (empty($pages)) {
                return back()->with('error', 'No Facebook pages found');
            }

            foreach ($pages as $page) {
                $pageId = $page['id'];
                $pageAccessToken = $page['access_token'];

                $igResponse = Http::get(
                    "https://graph.facebook.com/v19.0/{$pageId}",
                    [
                        'fields' => 'instagram_business_account',
                        'access_token' => $pageAccessToken,
                    ]
                );

                $instagramBusiness = $igResponse['instagram_business_account']['id'] ?? null;

                if ($instagramBusiness) {
                    $instagramResponse = Http::get(
                        "https://graph.facebook.com/v19.0/{$instagramBusiness}",
                        [
                            'fields' => 'id,username,profile_picture_url',
                            'access_token' => $pageAccessToken,
                        ]
                    );

                    $existingProfile = SocialAccount::where('user_id', auth()->id())
                        ->where('platform', 'instagram')
                        ->get()
                        ->first(function ($account) use ($instagramBusiness) {
                            return data_get($account->credentials, 'instagram_business_id') == $instagramBusiness;
                        });

                    if (!$existingProfile) {
                        $account = SocialAccount::create([
                            'user_id' => auth()->id(),
                            'platform' => 'instagram',
                            'status' => 'connected',
                            'credentials' => [
                                'facebook_page_id' => $pageId,
                                'instagram_business_id' => $instagramBusiness,
                                'page_access_token' => $pageAccessToken,
                                'username' => $instagramResponse['username'] ?? null,
                                'profile_picture' => $instagramResponse['profile_picture_url'] ?? null,
                            ],
                        ]);
                    } else {
                        $existingProfile->update([
                            
                            'status' => 'connected',
                            'credentials' => [
                                'facebook_page_id' => $pageId,
                                'instagram_business_id' => $instagramBusiness,
                                'page_access_token' => $pageAccessToken,
                                'username' => $instagramResponse['username'] ?? null,
                                'profile_picture' => $instagramResponse['profile_picture_url'] ?? null,
                            ],
                        ]);
                         $account = $existingProfile->fresh();
                    }

                    \App\Models\Notification::create([
                        'user_id' => (string) auth()->id(),
                        'type' => 'instagram_connected',
                        'message' => 'Instagram connected successfully',
                        'is_read' => false,
                    ]);

                    return redirect()
                        ->route('accounts')
                        ->with('success', 'Instagram connected successfully')
                        ->with('instagram_connected_popup', true)
                        ->with('instagram_connected_account_id', (string) $account->id);
                    }
            }

            return back()->with('error', 'No Instagram Business account connected to page');

        } catch (\Throwable $e) {
            Log::error('Instagram FB connect error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to connect Instagram');
        }
    }
    public function directCallback($request)
    {
        Log::info('Instagram direct callback started', [
            'has_code' => !empty($request->code),
            'user_id' => auth()->id()
        ]);

        try {

            if (!$request->code) {
                Log::warning('Instagram direct callback: No code provided');
                return back()->with('error', 'Authorization failed');
            }

            Log::info('Instagram direct callback: Code received', ['code' => substr($request->code, 0, 20) . '...']);

            $clientId = env('INSTAGRAM_CLIENT_ID');
            $clientSecret = env('INSTAGRAM_CLIENT_SECRET');
            $redirectUri = env('INSTAGRAM_REDIRECT_URI');

            Log::info('Instagram direct: Token exchange request', [
                'client_id' => $clientId ? substr($clientId, 0, 5) . '...' : 'missing',
                'has_secret' => !empty($clientSecret),
                'redirect_uri' => $redirectUri,
            ]);

            $tokenResponse = Http::asForm()->post(
                'https://api.instagram.com/oauth/access_token',
                [
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $redirectUri,
                    'code' => $request->code,
                ]
            );

            Log::info('Instagram direct: Token response', [
                'status' => $tokenResponse->status(),
                'successful' => $tokenResponse->successful(),
                'body' => $tokenResponse->body()
            ]);

            if (!$tokenResponse->successful()) {
                $errorBody = $tokenResponse->body();
                Log::error('Instagram direct: Token exchange failed', [
                    'status' => $tokenResponse->status(),
                    'error' => $errorBody
                ]);
                return back()->with('error', 'Failed to get Instagram token: ' . ($tokenResponse->json('error_message') ?? 'Unknown error'));
            }

            $responseData = $tokenResponse->json();
            
            Log::info('Instagram direct: Token received', [
                'has_access_token' => !empty($responseData['access_token']),
                'user_id' => $responseData['user_id'] ?? 'missing'
            ]);

            $accessToken = $responseData['access_token'];
            $instagramUserId = $responseData['user_id'];

            Log::info('Instagram direct: Fetching user info');
            
            $userResponse = Http::get(
                'https://graph.instagram.com/me',
                [
                    'fields' => 'id,username',
                    'access_token' => $accessToken,
                ]
            );

            Log::info('Instagram direct: User info response', [
                'status' => $userResponse->status(),
                'successful' => $userResponse->successful(),
                'body' => $userResponse->body()
            ]);

            if (!$userResponse->successful()) {
                Log::error('Instagram direct: Failed to fetch user info', [
                    'error' => $userResponse->body()
                ]);
            }

            // STEP 3 → Save User

            Log::info('Instagram direct: Saving account to database');

            SocialAccount::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'platform' => 'instagram',
                ],
                [
                    'status' => 'connected',
                    'credentials' => [
                        'instagram_user_id' => $instagramUserId,
                        'access_token' => $accessToken,
                        'username' => $userResponse['username'] ?? null,
                    ],
                ]
            );

            Log::info('Instagram direct: Account saved successfully', [
                'instagram_user_id' => $instagramUserId,
                'username' => $userResponse['username'] ?? null
            ]);

            \App\Models\Notification::create([
                'user_id' => (string) auth()->id(),
                'type' => 'instagram_connected',
                'message' => 'Instagram connected successfully',
                'is_read' => false,
            ]);

            Log::info('Instagram direct: Notification created, redirecting to accounts');

            return redirect()
                ->route('accounts')
                ->with('success', 'Instagram connected successfully')
                ->with('instagram_connected_popup', true);

        } catch (\Throwable $e) {

            Log::error('Instagram direct connect error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return back()->with('error', 'Failed to connect Instagram: ' . $e->getMessage());
        }
    }
}
