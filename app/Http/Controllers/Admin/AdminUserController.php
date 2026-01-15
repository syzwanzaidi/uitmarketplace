<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Complaint;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('membership_type', '!=', 'admin');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10);

        // Dashboard stats (reuse)
        $totalUsers = User::where('membership_type', '!=', 'admin')->count();
        $totalTransactions = Order::whereIn('status', ['paid', 'completed'])->count();
        $totalSalesValue = Order::whereIn('status', ['paid', 'completed'])->sum('total');
        $totalComplaints = Complaint::where('status', 'open')->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'totalTransactions',
            'totalSalesValue',
            'totalComplaints'
        ));
    }

    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active'
            ? 'suspended'
            : 'active';

        $user->save();

        return back()->with('success', 'User status updated.');
    }
}
