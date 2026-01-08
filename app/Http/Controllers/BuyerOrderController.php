<?php

namespace App\Http\Controllers;

use App\Models\Order;

class BuyerOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->latest()
            ->get();

        return view('user.orders', compact('orders'));
    }
}
