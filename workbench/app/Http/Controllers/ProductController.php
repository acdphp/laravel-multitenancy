<?php

namespace Workbench\App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Workbench\App\Models\Product;

class ProductController extends Controller
{
    public function index(): Collection
    {
        return Product::all();
    }

    public function show(Product $product): Product
    {
        return $product;
    }

    public function store(Request $request): Product
    {
        return Product::create($request->input());
    }
}
