<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $transactions = Order::with(['buyer', 'seller'])
            ->whereIn('status', Order::SETTLED_STATUSES)
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('bill_code', 'like', "%{$search}%")
                ->orWhere('payment_ref', 'like', "%{$search}%")
                ->orWhereHas('buyer', fn ($b) => $b->where('email', 'like', "%{$search}%"))
                ->orWhereHas('seller', fn ($s) => $s->where('email', 'like', "%{$search}%"))))
            ->latest('paid_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.transactions', compact('transactions', 'search'));
    }
}
