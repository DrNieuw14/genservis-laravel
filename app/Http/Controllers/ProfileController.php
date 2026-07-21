<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's own profile page (self-service — reachable by any
     * logged-in account, not permission-gated). Photo upload is scoped to
     * the linked Personnel record; accounts with no Personnel record (e.g.
     * superadmin) simply don't get the upload widget.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'personnel' => $request->user()->personnel,
        ]);
    }

    /**
     * Update the logged-in user's own profile photo, stored on their
     * linked Personnel's EmployeeProfile (the same `photo` column already
     * used by the admin-side Employee Master personal information page —
     * reused, not duplicated). Mirrors MaterialController's image upload
     * pattern (store/delete on disk('public'), optional remove checkbox).
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $personnel = $request->user()->personnel;

        if (!$personnel) {
            return Redirect::route('profile.edit')
                ->with('error', 'No employee record is linked to this account.');
        }

        $validated = $request->validate([
            'photo' => 'nullable|image|max:2048',
            'remove_photo' => 'nullable|boolean',
        ]);

        $photoPath = optional($personnel->profile)->photo;

        if ($request->hasFile('photo')) {

            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            $photoPath = $request->file('photo')->store('profile_photos', 'public');

        } elseif ($request->boolean('remove_photo') && $photoPath) {

            Storage::disk('public')->delete($photoPath);
            $photoPath = null;

        }

        $personnel->profile()->updateOrCreate([], ['photo' => $photoPath]);

        return Redirect::route('profile.edit')->with('status', 'photo-updated');
    }
}
