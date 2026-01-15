<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;

class ListingsController extends Controller
{
    /**
     * Seller dashboard (Listings + Stats)
     */
    public function index()
    {
        $sellerId = Auth::id();

        /**
         * Categories (for add/edit modal)
         */
        $categories = Category::all();

        /**
         * Seller products
         * Only active & inactive (sold items hidden)
         */
        $products = Product::with('category')
            ->where('user_id', $sellerId)
            ->whereIn('status', ['active', 'inactive'])
            ->get();

        /**
         * TOTAL SALES
         * All successful orders (DO NOT shrink)
         */
        $totalSales = Order::where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'shipped', 'completed'])
            ->sum('total');

        /**
         * ACTIVE LISTINGS COUNT
         */
        $activeListingsCount = Product::where('user_id', $sellerId)
            ->where('status', 'active')
            ->count();

        /**
         * PENDING ORDERS
         * Paid but not shipped yet
         */
        $pendingOrdersCount = Order::where('seller_id', $sellerId)
            ->where('status', 'paid')
            ->whereNull('tracking_number')
            ->count();

        return view('seller.listings', compact(
            'categories',
            'products',
            'totalSales',
            'activeListingsCount',
            'pendingOrdersCount'
        ));
    }

    /**
     * Store new product listing
     */
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

        $imagePath = $request->file('image')->store('products', 'public');

        Product::create([
            'user_id'     => Auth::id(),
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

    /**
     * Update listing
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        // Prevent editing sold items
        if ($product->status === 'sold') {
            return back()->with('error', 'Sold products cannot be modified.');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'location'    => 'required|string|max:255',
            'status'      => 'required|in:active,inactive',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

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

        return back()->with('success', 'Listing updated successfully!');
    }

    /**
     * Update status only (dropdown)
     */
    public function updateStatus(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        if ($product->status === 'sold') {
            return back()->with('error', 'Sold products cannot be modified.');
        }

        $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        $product->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status updated.');
    }

    /**
     * Delete listing
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->back()->with('success', 'Listing deleted successfully!');
    }
}
