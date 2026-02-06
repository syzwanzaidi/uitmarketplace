<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Location;

class ListingsController extends Controller
{
    /**
     * Seller dashboard (Listings + Stats)
     */
    public function index(Request $request)
    {
        $sellerId = Auth::id();
        $search = $request->query('search');

        $categories = Category::all();

        $products = Product::with('category')
            ->where('user_id', $sellerId)
            ->whereIn('status', ['active', 'inactive'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->paginate(8)
            ->withQueryString(); // keeps search during pagination

        $totalSales = Order::where('seller_id', $sellerId)
            ->whereIn('status', ['paid', 'shipped', 'completed'])
            ->sum('total');

        $activeListingsCount = Product::where('user_id', $sellerId)
            ->where('status', 'active')
            ->count();

        $pendingOrdersCount = Order::where('seller_id', $sellerId)
            ->where('status', 'paid')
            ->whereNull('tracking_number')
            ->count();

        $completedOrdersCount = Order::where('seller_id', $sellerId)
            ->where('status', 'completed')
            ->count();

        return view('seller.listings', compact(
            'categories',
            'products',
            'totalSales',
            'activeListingsCount',
            'pendingOrdersCount',
            'completedOrdersCount',
            'search'
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

            // location
            'location'    => 'required|string|max:255',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',

            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = $request->file('image')->store('products', 'public');

        // CREATE PRODUCT
        $product = Product::create([
            'user_id'     => auth()->id(),
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'location'    => $request->location,
            'image'       => '/storage/' . $imagePath,
            'status'      => 'active',
        ]);

        // CREATE LOCATION
        $product->locationPoint()->create([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('success', 'Product added successfully!');
    }

    /**
     * Update listing
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',

            // product display location
            'location'    => 'required|string|max:255',

            // geo location
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',

            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Image update
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = '/storage/' . $imagePath;
        }

        // UPDATE PRODUCT
        $product->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price'       => $request->price,
            'location'    => $request->location,
        ]);

        // UPDATE / CREATE LOCATION
        if ($request->filled(['latitude', 'longitude'])) {
            $product->locationPoint()->updateOrCreate(
                ['product_id' => $product->id],
                [
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                ]
            );
        }

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

        // Optional: also delete location
        $product->locationPoint()?->delete();

        $product->delete();

        return redirect()->back()->with('success', 'Listing deleted successfully!');
    }
}
