<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Complaint;
use App\Models\Message;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Services\ToyyibPay;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ToyyibPay::class, fn () => ToyyibPay::fromConfig());
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Only UiTM members may list items for sale.
        Gate::define('sell', fn (User $user) => $user->canSell());

        // Product grids need to know which items the viewer has saved.
        View::composer(['user.home', 'user.category', 'user.product-details'], function ($view) {
            $view->with('savedIds', Wishlist::where('user_id', auth()->id())->pluck('product_id'));
        });

        $this->shareHeaderCounts();
        $this->shareSellerStats();
        $this->shareAdminStats();
    }

    private function shareHeaderCounts(): void
    {
        View::composer('partials.header', function ($view) {
            $user = auth()->user();

            if (! $user || $user->isAdmin()) {
                return;
            }

            $view->with([
                'cartCount' => Cart::where('user_id', $user->id)->count(),
                'unreadCount' => Message::whereNull('read_at')
                    ->where('sender_id', '!=', $user->id)
                    ->whereHas('conversation', fn ($q) => $q->involving($user->id))
                    ->count(),
            ]);
        });
    }

    private function shareSellerStats(): void
    {
        View::composer('seller.partials.stats', function ($view) {
            $sellerId = auth()->id();
            $orders = Order::where('seller_id', $sellerId);

            $view->with('stats', [
                'sales' => (clone $orders)->whereIn('status', Order::SETTLED_STATUSES)->sum('total'),
                'active' => Product::where('user_id', $sellerId)->active()->count(),
                'toShip' => (clone $orders)->where('status', Order::STATUS_PAID)->count(),
                'completed' => (clone $orders)->where('status', Order::STATUS_COMPLETED)->count(),
            ]);
        });
    }

    private function shareAdminStats(): void
    {
        View::composer('admin.partials.stats', function ($view) {
            $settled = Order::whereIn('status', Order::SETTLED_STATUSES);

            $view->with('stats', [
                'users' => User::where('membership_type', '!=', User::TYPE_ADMIN)->count(),
                'transactions' => (clone $settled)->count(),
                'sales' => (clone $settled)->sum('total'),
                'openComplaints' => Complaint::where('status', 'open')->count(),
            ]);
        });
    }
}
