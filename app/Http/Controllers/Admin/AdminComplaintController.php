<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with('user');

        if ($request->filled('search')) {
            $query->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) use ($request) {
                      $q->where('email', 'like', '%' . $request->search . '%');
                  });
        }

        $complaints = $query->latest()->paginate(10)->withQueryString();

        // Dashboard cards (required)
        $totalUsers = User::where('membership_type', '!=', 'admin')->count();
        $totalTransactions = Order::whereIn('status', ['paid', 'completed'])->count();
        $totalSalesValue = Order::whereIn('status', ['paid', 'completed'])->sum('total');
        $totalComplaints = Complaint::where('status', 'open')->count();

        return view('admin.complaints.index', compact(
            'complaints',
            'totalUsers',
            'totalTransactions',
            'totalSalesValue',
            'totalComplaints'
        ));
    }

    public function toggleStatus(Complaint $complaint)
    {
        $complaint->status = $complaint->status === 'open'
            ? 'resolved'
            : 'open';

        $complaint->save();

        return back()->with('success', 'Complaint status updated.');
    }
}
