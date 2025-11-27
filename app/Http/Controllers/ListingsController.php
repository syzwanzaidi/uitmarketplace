<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


class ListingsController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        // Get only the logged-in seller’s products
        $products = Product::where('user_id', Auth::id())->get();

        return view('user.listings', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'location'    => 'required|string|max:255',
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Save image to storage/app/public/products
        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'user_id'     => $request->user()->id,
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'location'    => $request->location,
            'image'       => '/storage/' . $imagePath,
            'status'      => 'active',
        ]);

        return redirect()->back()->with('success', 'Product added successfully!');
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'location'    => 'required|string|max:255',
            'status'      => 'required|in:active,out_of_stock,hidden',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // If seller uploads new image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = '/storage/' . $imagePath;
        }

        $product->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price'       => $request->price,
            'location'    => $request->location,
            'status'      => $request->status,
        ]);

        return redirect()->back()->with('success', 'Listing updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->back()->with('success', 'Listing deleted successfully!');
    }


}
