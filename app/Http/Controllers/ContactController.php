<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        return view('user.contact', [
            'complaints' => Complaint::where('user_id', $request->user()->id)->latest()->take(5)->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $request->user()->complaints()->create($validated);

        return back()->with('success', 'Thanks — your report has been sent to the admin team.');
    }
}
