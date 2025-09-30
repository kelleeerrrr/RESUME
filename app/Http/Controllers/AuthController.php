<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    // User Registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Account created successfully! You can now login.');
    }

    // User Login (username or email)
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)
                    ->orWhere('username', $request->login)
                    ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user_id', $user->id);
            // Flash success message to session
            return redirect('/resume')->with('success', 'Login successful! Welcome to your Resume.');
        }

        return back()->withErrors(['login' => 'Invalid username/email or password.']);
    }

    // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('forgot-password');
    }

    // Send Reset Password Link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'We have emailed your password reset link!');
        } else {
            return back()->withErrors(['email' => 'Your email is not registered.']);
        }
    }

    // Show Reset Password Form
    public function showResetPasswordForm($token)
    {
        return view('reset-password', ['token' => $token]);
    }

    // Handle Password Reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('success', 'Your password has been reset! You can now login.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
