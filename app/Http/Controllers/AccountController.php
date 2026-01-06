<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AccountController extends Controller
{
    public function index()
    {
        return view('user.account');
    }

    //show user's orders
    public function orders()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->latest()
            ->get();

        return view('user.orders', compact('orders'));
    }

}
