<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Stores the buyer's chosen area in the session so every listing page
 * can filter by distance without passing coordinates around in URLs.
 */
class LocationController extends Controller
{
    public const SESSION_KEY = 'buyer_location';

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'area' => ['nullable', 'string', 'max:100'],
            'radius' => ['required', 'integer', 'between:1,50'],
        ]);

        $request->session()->put(self::SESSION_KEY, [
            'lat' => (float) $validated['lat'],
            'lng' => (float) $validated['lng'],
            'area' => $validated['area'] ?: 'Selected area',
            'radius' => (int) $validated['radius'],
        ]);

        return back()->with('success', "Showing items within {$validated['radius']} km.");
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget(self::SESSION_KEY);

        return back()->with('success', 'Location filter cleared.');
    }

    /** @return array{lat:float,lng:float,area:string,radius:int}|null */
    public static function current(): ?array
    {
        return session(self::SESSION_KEY);
    }
}
