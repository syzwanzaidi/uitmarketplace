<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total users (exclude admin if you want)
        $totalUsers = User::where('membership_type', '!=', 'admin')->count();

        // Total completed transactions
        $totalTransactions = Order::whereNotNull('paid_at')->count();

        // Total sales value
        $totalSalesValue = Order::whereNotNull('paid_at')->sum('total');

        // Complaints (not implemented yet)
        $totalComplaints = 0;

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTransactions',
            'totalSalesValue',
            'totalComplaints'
        ));
    }
}
