<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // latest active products, eager-load category
        $products = Product::where('status', 'active')
            ->with('category')
            ->latest()
            ->take(8) // adjust how many you want on homepage
            ->get();

        return view('user.home', compact('products'));
    }
}
