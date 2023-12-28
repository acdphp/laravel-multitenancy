<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workbench\App\Models\Something;

class SomethingController extends Controller
{
    public function index(): Collection
    {
        return Something::all();
    }

    public function show(Something $something): Something
    {
        return $something;
    }

    public function store(Request $request): Something
    {
        return Something::create($request->input());
    }
}
