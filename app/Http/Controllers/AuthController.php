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
    // ==================== USER REGISTRATION ==================== //
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

    // ==================== USER LOGIN ==================== //
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
            Session::put('user_name', $user->name);
            return redirect('/home')->with('success', 'Login successful! Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors(['login' => 'Invalid username/email or password.']);
    }

    // ==================== LOGOUT ==================== //
    public function logout(Request $request)
    {
        Session::flush();
        $request->session()->flash('logout_success', 'You have successfully logged out!');
        return redirect()->route('welcome');
    }

    // ==================== FORGOT PASSWORD ==================== //
    public function showForgotPasswordForm()
    {
        return view('forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->with('error', 'Email not registered!');
        }

        $status = Password::sendResetLink(['email' => $email]);

        if ($status === Password::RESET_LINK_SENT) {
            if ($request->session()->has('password_link_sent_' . $email)) {
                return back()->with('already_sent', 'Reset link already sent. Please check your email.');
            }
            $request->session()->put('password_link_sent_' . $email, true);
            return back()->with('status', 'Reset link sent! Please check your email.');
        }

        return back()->with('error', 'Something went wrong. Please try again.');
    }

    // ==================== RESET PASSWORD ==================== //
    public function showResetPasswordForm($token)
    {
        return view('reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not registered!');
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return back()->with('success', 'Password successfully updated!');
        }

        return back()->with('error', 'Invalid or expired reset token. Please try again.');
    }

    // ==================== AUTH CHECK ==================== //
    public static function checkAuth()
    {
        return session()->has('user_id');
    }

    // ==================== CONTACT ==================== //
    public function contact()
    {
        if (!session()->has('user_id')) return redirect('/login');
        return view('contact');
    }

    public function sendContact(Request $request)
    {
        if (!session()->has('user_id')) return redirect('/login');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
