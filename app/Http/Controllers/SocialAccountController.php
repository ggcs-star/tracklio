<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Social\FacebookConnectService;
use App\Services\Social\YouTubeConnectService;
use App\Services\Social\InstagramConnectService;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Log;
class SocialAccountController extends Controller
{
    protected FacebookConnectService $facebook;
    protected YouTubeConnectService $youtube;
    protected InstagramConnectService $instagram;

    public function __construct(
        FacebookConnectService $facebook,
        YouTubeConnectService $youtube,
        InstagramConnectService $instagram
    ) {
        $this->facebook = $facebook;
        $this->youtube = $youtube;
        $this->instagram = $instagram;
    }

    public function index()
    {
        $accounts = SocialAccount::where('user_id', auth()->id())->get();
        return view('accounts.index', compact('accounts'));
    }

    // Facebook
    public function connectFacebook()
    {
        return $this->facebook->connect();
    }

    public function facebookCallback(Request $request)
    {
        return $this->facebook->callback($request);
    }

    // YouTube
    public function connectYouTube()
    {
        return $this->youtube->connect();
    }

    public function youtubeCallback(Request $request)
    {
        return $this->youtube->callback($request);
    }

    // Instagram
    public function connectInstagram()
    {
        return $this->instagram->connect();
    }

   public function instagramDirect()
{
    $redirectUri = env('INSTAGRAM_REDIRECT_URI');
    
    $query = http_build_query([
        'client_id' => env('INSTAGRAM_CLIENT_ID'),
        'redirect_uri' => $redirectUri,
        'scope' => 'user_profile,user_media',
        'response_type' => 'code',
    ]);

    return redirect('https://api.instagram.com/oauth/authorize?' . $query);
}

    public function instagramDirectCallback(Request $request)
    {
        return $this->instagram->directCallback($request);
    }
    public function instagramFacebook()
    {
        $clientId = env('FACEBOOK_CLIENT_ID');

        $configId = env('FACEBOOK_CONFIGURATION_ID');

        $redirectUri = urlencode(
            route('instagram.facebook.callback')
        );

        return redirect(
            "https://www.facebook.com/dialog/oauth" .
            "?client_id={$clientId}" .
            "&redirect_uri={$redirectUri}" .
            "&response_type=code" .
            "&auth_type=rerequest" .
            "&config_id={$configId}"
        );
    }
    public function instagramFacebookCallback(Request $request)
    {
        return $this->instagram->facebookCallback($request);
    }
        public function saveFacebookPages(Request $request)
    {
        return $this->facebook->savePages($request);
    }

    public function redirectToFacebook(Request $request)
    {
        $type = $request->get('type', 'page');
        return $this->facebook->redirectToFacebook($type);
    }
   public function disconnect(Request $request)
    {
        SocialAccount::where('user_id', auth()->id())
            ->where('id', $request->account_id)
            ->delete();

        return back()->with(
            'success',
            ucfirst($request->platform) . ' disconnected successfully'
        );
    }
}