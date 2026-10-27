<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $users = User::where('membership_type', '!=', User::TYPE_ADMIN)
            ->withCount('products')
            ->when($search !== '', fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users', compact('users', 'search'));
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        abort_if($user->isAdmin(), 403);

        $user->update(['status' => $user->isSuspended() ? 'active' : 'suspended']);

        return back()->with('success', $user->isSuspended() ? "{$user->name} has been suspended." : "{$user->name} is active again.");
    }
}
