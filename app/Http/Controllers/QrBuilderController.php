<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Qr\QrCrudService;
use App\Services\Qr\QrScanService;
use App\Services\Qr\QrRedirectService;
use App\Services\Qr\QrDomainService;

class QrBuilderController extends Controller
{
    protected QrCrudService $crud;
    protected QrScanService $scan;
    protected QrRedirectService $redirect;
    protected QrDomainService $domain;

    public function __construct(
        QrCrudService $crud,
        QrScanService $scan,
        QrRedirectService $redirect,
        QrDomainService $domain
    ) {
        $this->crud = $crud;
        $this->scan = $scan;
        $this->redirect = $redirect;
        $this->domain = $domain;
    }

    public function index()
    {
        return $this->crud->index();
    }

    public function create()
    {
        return $this->crud->create();
    }

    public function edit($id)
    {
        return $this->crud->edit($id);
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

    public function scan($code)
    {
        $qr = $this->scan->scan($code);
        return $this->redirect->redirectToTarget($qr);
    }

    public function visit($code)
    {
        return $this->scan->visit($code);
    }

    public function getDomain()
    {
        return $this->domain->getDomain();
    }
}
