<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginPage()
    {
        try {
            return view('auth');
        } catch (\Throwable $e) {
            Log::error('Show login page error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500);
        }
    }

    public function showRegisterPage()
    {
        try {
            return view('auth');
        } catch (\Throwable $e) {
            Log::error('Show register page error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            abort(500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ], [
                'email.required'    => 'Email is required',
                'password.required' => 'Password is required',
            ]);

            $email = strtolower(trim($request->email));

            if (Auth::attempt([
                'email'    => $email,
                'password' => $request->password,
            ])) {
                $request->session()->regenerate();
                return redirect()->route('dashboard');
            }

            return back()
                ->withErrors(['email' => 'Invalid email or password'])
                ->withInput();

        } catch (\Throwable $e) {
            Log::error('Login error', [
                'email' => $request->email ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['email' => 'Something went wrong']);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email',
                'password' => 'required|min:6|confirmed',
            ], [
                'password.confirmed' => 'Passwords do not match',
            ]);

            $email = strtolower(trim($request->email));

            if (User::where('email', $email)->exists()) {
                return back()->withErrors([
                    'email' => 'This email is already registered',
                ])->withInput();
            }

            $user = User::create([
                'name'     => $request->name,
                'email'    => $email,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);

            return redirect()->route('dashboard');

        } catch (\Throwable $e) {
            Log::error('Register error', [
                'email' => $request->email ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['email' => 'Registration failed']);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('landing');
        } catch (\Throwable $e) {
            Log::error('Logout error', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            abort(500);
        }
    }

    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $email = strtolower(trim($request->email));

            $status = Password::sendResetLink([
                'email' => $email,
            ]);

            return $status === Password::RESET_LINK_SENT
                ? back()->with('status', 'Password reset link sent to your email.')
                : back()->withErrors(['email' => 'Email not found']);

        } catch (\Throwable $e) {
            Log::error('Send reset link error', [
                'email' => $request->email ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['email' => 'Something went wrong']);
        }
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        try {
            return view('auth', [
                'token' => $token,
                'email' => strtolower(trim($request->email)),
            ]);
        } catch (\Throwable $e) {
            Log::error('Show reset password form error', [
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

            $email = strtolower(trim($request->email));

            $status = Password::reset(
                [
                    'email'                 => $email,
                    'password'              => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                    'token'                 => $request->token,
                ],
                function (User $user, string $password) {
                    $user->password = Hash::make($password);
                    $user->setRememberToken(Str::random(60));
                    $user->save();
                }
            );

            return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', 'Password reset successful. Please login.')
                : back()->withErrors(['email' => 'Invalid token or email']);

        } catch (\Throwable $e) {
            Log::error('Reset password error', [
                'email' => $request->email ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['email' => 'Password reset failed']);
        }
    }
}
