<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Bio\{
    BioIndexService,
    BioSaveService,
    BioViewService
};

class BioPageController extends Controller
{
    public function index(Request $request, BioIndexService $service)
    {
        return $service->handle($request);
    }

    public function save(Request $request, BioSaveService $service)
    {
        return $service->handle($request);
    }

    public function edit($id)
    {
        return redirect()->route('bio.index', ['edit' => $id]);
    }

    public function delete(Request $request, BioSaveService $service)
    {
        return $service->delete($request);
    }

    public function view($slug, Request $request, BioViewService $service)
    {
        return $service->handle($slug, $request);
    }
}
