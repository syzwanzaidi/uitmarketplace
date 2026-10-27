<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ListingsController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $products = Product::with(['category', 'locationPoint'])
            ->where('user_id', auth()->id())
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when(in_array($status, [Product::STATUS_ACTIVE, Product::STATUS_INACTIVE, Product::STATUS_SOLD], true),
                fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('seller.listings', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateListing($request, imageRequired: true);

        DB::transaction(function () use ($request, $validated) {
            $product = Product::create([
                'user_id' => auth()->id(),
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'location' => $validated['location'],
                'image' => '/storage/' . $request->file('image')->store('products', 'public'),
                'status' => Product::STATUS_ACTIVE,
            ]);

            $product->locationPoint()->create([
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]);
        });

        return back()->with('success', 'Your listing is live!');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $validated = $this->validateListing($request, imageRequired: false);

        if ($request->hasFile('image')) {
            $product->deleteImageFile();
            $product->image = '/storage/' . $request->file('image')->store('products', 'public');
        }

        $product->fill([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'location' => $validated['location'],
        ])->save();

        $product->locationPoint()->updateOrCreate([], [
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return back()->with('success', 'Listing updated.');
    }

    public function updateStatus(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'status' => ['required', Rule::in([Product::STATUS_ACTIVE, Product::STATUS_INACTIVE])],
        ]);

        $product->update($validated);

        return back()->with('success', $validated['status'] === Product::STATUS_ACTIVE
            ? 'Listing is visible to buyers again.'
            : 'Listing hidden from buyers.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->authorize('delete', $product);

        if ($product->hasOrders()) {
            return back()->with('error', 'This listing has orders against it. Hide it instead of deleting it.');
        }

        $product->deleteImageFile();
        $product->delete(); // location, cart and wishlist rows cascade

        return back()->with('success', 'Listing deleted.');
    }

    private function validateListing(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:1', 'max:100000'],
            'location' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'latitude.required' => 'Pick the meetup location on the map.',
            'longitude.required' => 'Pick the meetup location on the map.',
            'location.required' => 'Pick the meetup location on the map.',
        ]);
    }
}
