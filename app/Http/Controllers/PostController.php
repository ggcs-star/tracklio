<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Post\PostCrudService;

class PostController extends Controller
{
    protected PostCrudService $posts;

    public function __construct(PostCrudService $posts)
    {
        $this->posts = $posts;
    }

    public function create()
    {
        return $this->posts->create();
    }

    public function store(Request $request)
    {
        return $this->posts->store($request);
    }
}
