<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QrLink\QrLinkCrudService;
use App\Services\QrLink\QrLinkRedirectService;
use App\Services\QrLink\QrLinkQrService;

class QrLinkController extends Controller
{
    public function __construct(
        protected QrLinkCrudService $crud,
        protected QrLinkRedirectService $redirect,
        protected QrLinkQrService $qr
    ) {}

   public function index(Request $request)
{
    return $this->crud->index($request);
}
    public function show($id)
    {
        return $this->crud->show($id);
    }

    public function store(Request $request)
    {
        return $this->crud->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->crud->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->crud->destroy($id);
    }

    public function redirect(Request $request, $code)
    {
        return $this->redirect->handle($request, $code);
    }

    public function downloadSvg($id)
    {
        return $this->qr->downloadSvg($id);
    }
}
