<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        // Load product + category + seller(optional)
        $product = Product::with('category', 'user')->findOrFail($id);

        return view('user.product-details', compact('product'));
    }
}
