<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminComplaintController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = in_array($request->query('status'), ['open', 'resolved'], true) ? $request->query('status') : null;

        $complaints = Complaint::with('user')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('subject', 'like', "%{$search}%")
                ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$search}%"))))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.complaints', compact('complaints', 'search', 'status'));
    }

    public function toggleStatus(Complaint $complaint): RedirectResponse
    {
        $complaint->update(['status' => $complaint->status === 'open' ? 'resolved' : 'open']);

        return back()->with('success', 'Complaint marked as ' . $complaint->status . '.');
    }
}
