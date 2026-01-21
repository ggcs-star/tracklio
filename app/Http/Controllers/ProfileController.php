<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);

            $user->update([
                'avatar' => null
            ]);

            // 🔔 Notification
            Notification::create([
                'user_id' => $user->id,
                'type'    => 'profile_update',
                'message' => 'Your profile photo was removed',
                'is_read' => false,
            ]);
        }

        return redirect()
            ->route('profile')
            ->with('success', 'Profile photo removed');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => ['nullable', 'digits:10'],
            'address' => 'nullable|string|max:500',
            'bio'     => 'nullable|string|max:1000',
            'avatar'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'phone.digits' => 'Phone number must be exactly 10 digits',
        ]);

        // 🔁 Replace avatar safely
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')
                                    ->store('avatars', 'public');
        }

        // ✅ Update profile fields (FIXED)
        $user->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
            'bio'     => $request->bio,
        ]);

        // 🔔 Notification
        Notification::create([
            'user_id' => $user->id,
            'type'    => 'profile_update',
            'message' => 'Your profile information was updated',
            'is_read' => false,
        ]);

        return redirect()
            ->route('profile')
            ->with('success', 'Profile updated successfully');
    }
}
