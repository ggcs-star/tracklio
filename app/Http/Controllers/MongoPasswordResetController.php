<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;
use App\Models\PasswordReset;

class MongoPasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->withErrors(['email' => 'Email not found']);
            }

            PasswordReset::where('email', $request->email)->delete();

            $token = Str::random(64);

            PasswordReset::create([
                'email' => $request->email,
                'token' => $token,
                'expires_at' => Carbon::now()->addMinutes(30),
            ]);

            $link = url("/reset-password/{$token}?email={$request->email}");

            Mail::raw(
                "Reset your password using this link:\n\n{$link}\n\nThis link expires in 30 minutes.",
                function ($message) use ($request) {
                    $message->to($request->email)
                            ->subject('Reset Your Password');
                }
            );

            return back()->with('status', 'Password reset link sent to your email.');
        } catch (\Throwable $e) {
            Log::error('Mongo password reset link error', [
                'email' => $request->email ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['email' => 'Something went wrong']);
        }
    }

    public function showResetForm(Request $request, $token)
    {
        try {
            return view('auth', [
                'token' => $token,
                'email' => $request->email,
            ]);
        } catch (\Throwable $e) {
            Log::error('Show reset form error', [
                'token' => $token,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|min:6|confirmed',
            ]);

            $reset = PasswordReset::where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (!$reset || Carbon::now()->gt($reset->expires_at)) {
                return back()->withErrors(['email' => 'Reset link expired or invalid']);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->withErrors(['email' => 'User not found']);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            $reset->delete();

            return redirect()->route('login')
                ->with('status', 'Password reset successful. Please login.');
        } catch (\Throwable $e) {
            Log::error('Mongo password reset error', [
                'email' => $request->email ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['email' => 'Password reset failed']);
        }
    }
}
