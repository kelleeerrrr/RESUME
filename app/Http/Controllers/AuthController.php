<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Resume;

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

        $login = $request->input('login');
        $user = User::where('email', $login)
                    ->orWhere('username', $login)
                    ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_email', $user->email);

            $request->session()->regenerate();

            return redirect()->intended('/home')->with('success', 'Login successful! Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors(['login' => 'Invalid username/email or password.']);
    }

    // ==================== LOGOUT ==================== //
    public function logout(Request $request)
    {

        $request->session()->invalidate();

        $request->session()->regenerateToken();

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

        $status = Password::sendResetLink($request->only('email'));

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

    public function uploadProfilePhoto(Request $request)
    {
        if (! session()->has('user_id')) {
            if ($request->expectsJson()) return response()->json(['error' => 'Unauthenticated'], 401);
            return redirect('/login');
        }

        $userId = session('user_id');

        $request->validate([
            'photo_type' => 'nullable|in:profile,spotlight',
            'photo_data' => 'nullable|string',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'resume_id'  => 'nullable|integer',
        ]);

        $photoType = $request->input('photo_type', 'profile'); 
        $targetColumn = ($photoType === 'spotlight') ? 'spotlight_photo' : 'profile_photo';

        if ($request->filled('resume_id')) {
            $resume = Resume::find($request->input('resume_id'));
            if (! $resume) return back()->with('error', 'Resume not found.');
            if ($resume->user_id !== $userId) abort(403);
        } else {
            if ($photoType === 'spotlight') {
                $resume = Resume::where('user_id', $userId)->first(); 
            } else {
                $resume = Resume::firstOrNew(['user_id' => $userId]);
                if (! $resume->exists) {
                    $defaults = [
                        'fullname' => session('user_name') ?? 'Unnamed User',
                        'email' => session('user_email') ?? '',
                        'specialization' => '',
                        'phone' => '',
                        'address' => '',
                    ];
                    $resume->fill($defaults);
                    $resume->user_id = $userId;
                    $resume->save();
                }
            }
        }

        $saveTemp = function(string $binary, string $ext) use ($userId, $photoType) {
            $dir = 'temp_spotlights';
            if (! Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            $filename = $photoType . '_' . $userId . '_' . Str::random(10) . '.' . $ext;
            $path = $dir . '/' . $filename;
            Storage::disk('public')->put($path, $binary);
            return $path; 
        };

        $saveFinal = function(string $binary, string $ext) use ($userId, $photoType) {
            $dir = 'profile_photos';
            if (! Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            $filename = $photoType . '_' . $userId . '_' . Str::random(10) . '.' . $ext;
            $path = $dir . '/' . $filename;
            Storage::disk('public')->put($path, $binary);
            return $path;
        };

        if ($request->filled('photo_data')) {
            $photoData = $request->input('photo_data');
            if (! preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $photoData, $matches)) {
                return back()->with('error', 'Unsupported image format. Please upload PNG or JPEG.');
            }
            $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $photoData);
            $binary = base64_decode($base64);
            if ($binary === false) return back()->with('error', 'Invalid image data.');
            $ext = (stripos($matches[1], 'png') !== false) ? 'png' : 'jpg';

            if ($photoType === 'spotlight') {
                $tempPath = $saveTemp($binary, $ext);
                session(['pending_spotlight' => $tempPath]);
                return back()->with('success', 'Spotlight photo uploaded and pending. Save your resume to attach it.');
            } else {
                $finalPath = $saveFinal($binary, $ext);
                if (! $resume) {
                    return back()->with('error', 'Resume not found. Create your resume first.');
                }

                if (!empty($resume->profile_photo) && Storage::disk('public')->exists($resume->profile_photo)) {
                    try { Storage::disk('public')->delete($resume->profile_photo); } catch (\Exception $e) {}
                }
                $resume->profile_photo = $finalPath;
                $resume->save(); 
                return back()->with('success', 'Profile photo updated!');
            }
        }

        if ($request->hasFile('photo')) {
            $validator = Validator::make($request->all(), [
                'photo' => 'required|image|mimes:jpg,jpeg,png|max:3072',
            ]);
            if ($validator->fails()) {
                if ($request->expectsJson()) return response()->json(['error' => $validator->errors()->first()], 422);
                return back()->withErrors($validator)->withInput();
            }

            $file = $request->file('photo');
            $ext = $file->getClientOriginalExtension();
            $binary = file_get_contents($file->getRealPath());

            if ($photoType === 'spotlight') {
                $tempPath = $saveTemp($binary, $ext);
                session(['pending_spotlight' => $tempPath]);
                return back()->with('success', 'Spotlight photo uploaded and pending. Save your resume to attach it.');
            } else {
                if (! $resume) return back()->with('error', 'Resume not found. Create your resume first.');
                $finalPath = $saveFinal($binary, $ext);
                if (!empty($resume->profile_photo) && Storage::disk('public')->exists($resume->profile_photo)) {
                    try { Storage::disk('public')->delete($resume->profile_photo); } catch (\Exception $e) {}
                }
                $resume->profile_photo = $finalPath;
                $resume->save();
                return back()->with('success', 'Profile photo updated!');
            }
        }

        if ($request->expectsJson()) return response()->json(['error' => 'No image provided.'], 422);
        return back()->with('error', 'No image provided.');
    }
}

