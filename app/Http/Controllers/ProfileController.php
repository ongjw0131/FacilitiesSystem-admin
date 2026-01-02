<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    // Retrieve all user profile information
    public function show()
    {
        $user = User::find(Auth::id());
        return view('user.profile', compact('user'));
    }

    public function settings()
    {
        $user = User::find(Auth::id());
        return view('user.profile_settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:11|min:10',
            'major' => 'nullable|string|max:255',
            'year_of_graduation' => 'nullable|integer|min:1900|max:2100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update basic fields
        $user->update([
            'name' => $validated['name'],
            'contact_number' => $validated['contact_number'],
            'major' => $validated['major'],
            'year_of_graduation' => $validated['year_of_graduation'],
        ]);

        // Handle file upload only if provided
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['profile_picture_file_path' => $path]);
        }

        return back()->with('success', 'Profile updated successfully!');
    }
}
