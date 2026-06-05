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
        return view('social.facebook-connect');
    }
    public function redirectToFacebook()
    {
        $query = http_build_query([
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'redirect_uri' => env('FACEBOOK_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => implode(',', [
                'pages_show_list',
                'pages_manage_posts',
                'pages_manage_engagement',
                'pages_read_engagement',
                'read_insights',
                'business_management'
            ]),
            'config_id' => '1322825999814821',
        ]);

        return redirect(
            "https://www.facebook.com/v19.0/dialog/oauth?" . $query
        );
    }

    
    public function callback(Request $request)
    {
        try {

            $code = $request->code;

            if (!$code) {
                return redirect()->route('accounts')->with('error', 'Authorization failed');
            }

            $tokenResponse = Http::asForm()->post(
                "https://graph.facebook.com/{$this->version}/oauth/access_token",
                [
                    'client_id' => env('FACEBOOK_CLIENT_ID'),
                    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
                    'redirect_uri' => env('FACEBOOK_REDIRECT_URI'),
                    'code' => $code,
                ]
            )->json();

            if (empty($tokenResponse['access_token'])) {
                return redirect()->route('accounts')->with('error', 'Failed to get token');
            }

            $userToken = $tokenResponse['access_token'];
            $userInfo = Http::get("https://graph.facebook.com/{$this->version}/me", [
                'access_token' => $userToken,
                'fields' => 'id,name'
            ])->json();

            $profileName = $userInfo['name'] ?? 'Facebook Profile';
            // 🔴 YAHAN TAK

            $state = json_decode($request->state, true);

            $type = $state['state'] ?? 'page';

            if ($type === 'profile') {

                $profileResponse = Http::get(
                    "https://graph.facebook.com/{$this->version}/me",
                    [
                        'fields' => 'id,name,picture',
                        'access_token' => $userToken,
                    ]
                )->json();

                SocialAccount::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'platform' => 'facebook'
                    ],
                    [
                        'status' => 'connected',

                        'credentials' => [
                            'user_access_token' => $userToken
                        ],

                        'pages' => [
                            [
                                'page_id' => $profileResponse['id'],
                                'page_name' => $profileResponse['name'],
                                'type' => 'profile'
                            ]
                        ]
                    ]
                );

                return redirect('/accounts')
                    ->with('success', 'Facebook profile connected')
                    ->with('facebook_connected_popup', true);
            }

            $pagesResponse = Http::get(
                "https://graph.facebook.com/{$this->version}/me/accounts",
                [
                    'fields' => 'id,name,access_token,picture',
                    'access_token' => $userToken,
                ]
            )->json();

            if (empty($pagesResponse['data'])) {

                return redirect()
                    ->route('accounts')
                    ->with('error', 'No pages found');
            }

            session([
                'fb_user_token' => $userToken
            ]);

            return view('social.facebook-pages-list', [
                'pages' => $pagesResponse['data'],
                'userToken' => $userToken,
                'profileName' => $profileName,
            ]);

        } catch (\Throwable $e) {

            Log::error('Facebook callback failed', [
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('accounts')
                ->with('error', 'Connection failed');
        }
    }
     public function savePages(Request $request)
    {
        try {

            $selectedPages = $request->input('pages', []);
            $userToken = $request->input('user_token');
            $profileName = $request->input('profile_name', 'Facebook Profile');

            if (empty($selectedPages)) {

                return response()->json([
                    'success' => false,
                    'error' => 'No pages selected'
                ]);
            }

            $pagesArray = [];

            foreach ($selectedPages as $page) {

               $pagesArray[] = [
                    'page_id' => $page['id'],
                    'page_name' => $page['name'],
                    'page_access_token' => $page['access_token'],
                    'profile_name' => $profileName,  // Use the profile name from request// Add profile name
                ];
            }

            $existingAccount = SocialAccount::where('user_id', auth()->id())
                ->where('platform', 'facebook')
                ->first();

            if ($existingAccount) {
                $existingPages = $existingAccount->pages ?? [];
                $allPages = array_merge($existingPages, $pagesArray);
                $allPages = collect($allPages)
                    ->unique('page_id')
                    ->values()
                    ->toArray();
                
                $existingAccount->update([
                    'status' => 'connected',
                    'credentials' => ['user_access_token' => $userToken],
                    'pages' => $allPages,  
                ]);
            } else {
                // Create new account
                SocialAccount::create([
                    'user_id' => auth()->id(),
                    'platform' => 'facebook',
                    'status' => 'connected',
                    'credentials' => ['user_access_token' => $userToken],
                    'pages' => $pagesArray,
                ]);
            }
            session()->flash('facebook_connected_popup', true);

            return response()->json([
                'success' => true
            ]);

        } catch (\Throwable $e) {

            Log::error('Save pages failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
        }
    }
}