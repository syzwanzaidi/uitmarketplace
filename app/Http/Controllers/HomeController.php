<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.users');
        }

        $location = LocationController::current();

        $query = Product::query()
            ->with(['category', 'locationPoint'])
            ->active()
            ->where('products.user_id', '!=', auth()->id());

        if ($location) {
            $query->withDistanceFrom($location['lat'], $location['lng'], $location['radius'])->orderBy('distance');
        } else {
            $query->latest('products.created_at');
        }

        return view('user.home', [
            'products' => $query->take(8)->get(),
            'categories' => Category::withCount(['products' => fn ($q) => $q->active()])->orderBy('name')->get(),
            'location' => $location,
        ]);
    }
}
