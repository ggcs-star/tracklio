<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Social\SocialAccountViewService;
use App\Services\Social\FacebookConnectService;
use App\Services\Social\YouTubeConnectService;
use App\Services\Social\InstagramConnectService;
use App\Services\Social\SocialDisconnectService;

class SocialAccountController extends Controller
{
    public function __construct(
        protected SocialAccountViewService $view,
        protected FacebookConnectService $facebook,
        protected YouTubeConnectService $youtube,
        protected InstagramConnectService $instagram,
        protected SocialDisconnectService $disconnect
    ) {}

    public function index()
    {
        return $this->view->index();
    }

    public function connectFacebook()
    {
        return $this->facebook->connect();
    }

    public function facebookCallback(Request $request)
    {
        return $this->facebook->callback($request);
    }

    public function connectYouTube()
    {
        return $this->youtube->connect();
    }

    public function youtubeCallback(Request $request)
    {
        return $this->youtube->callback($request);
    }

    public function connectInstagram()
    {
        return $this->instagram->connect();
    }

    public function disconnect(Request $request)
    {
        return $this->disconnect->handle($request);
    }
}
