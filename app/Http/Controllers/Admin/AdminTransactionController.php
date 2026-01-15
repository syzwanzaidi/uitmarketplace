<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Complaint;
use Illuminate\Http\Request;

class AdminTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['buyer', 'seller'])
            ->whereIn('status', ['paid', 'completed'])
            ->orderByDesc('updated_at');

        // Search by buyer email, seller email, or bill code
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('bill_code', 'like', '%' . $request->search . '%')
                  ->orWhere('payment_ref', 'like', '%' . $request->search . '%')
                  ->orWhereHas('buyer', function ($qb) use ($request) {
                      $qb->where('email', 'like', '%' . $request->search . '%');
                  })
                  ->orWhereHas('seller', function ($qs) use ($request) {
                      $qs->where('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $transactions = $query->paginate(10)->withQueryString();

        // Dashboard stats (reuse everywhere)
        $totalUsers = User::where('membership_type', '!=', 'admin')->count();
        $totalTransactions = Order::whereIn('status', ['paid', 'completed'])->count();
        $totalSalesValue = Order::whereIn('status', ['paid', 'completed'])->sum('total');
        $totalComplaints = Complaint::where('status', 'open')->count();

        return view('admin.transactions.index', compact(
            'transactions',
            'totalUsers',
            'totalTransactions',
            'totalSalesValue',
            'totalComplaints'
        ));
    }
}
