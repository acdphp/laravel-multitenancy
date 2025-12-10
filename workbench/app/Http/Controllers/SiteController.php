<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workbench\App\Models\Site;
use Workbench\App\Models\SiteNoAutoAssign;

class SiteController extends Controller
{
    public function index(): Collection
    {
        return Site::all();
    }

    public function show(Site $site): Site
    {
        return $site;
    }

    public function store(Request $request): Site
    {
        return Site::create($request->input());
    }

    public function storeNoAutoAssign(Request $request): Site
    {
        return SiteNoAutoAssign::create($request->input());
    }
}
