<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;

// =================== PUBLIC PAGES =================== //

// Welcome / Landing Page
Route::get('/', fn() => view('welcome'))->name('welcome');

// Public Resume (anyone can view)
Route::get('/public-resume', [ResumeController::class, 'showPublicResume'])->name('resume.public');

// =================== AUTH ROUTES =================== //
// Show signup page (GET) - name it 'register' or 'signup' so Blade can link to it
Route::get('/signup', fn() => view('signup'))->name('register'); // <- named route for signup page

// Handle signup form (POST) - give it a distinct name
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Show login page (GET)
Route::get('/login', fn() => view('login'))->name('login');

// Handle login form (POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout (GET or POST per your preference)
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// =================== PASSWORD RESET =================== //
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// =================== PROTECTED ROUTES (LOGIN REQUIRED) =================== //
Route::middleware(['web'])->group(function () {

    // Home / Dashboard
    Route::get('/home', function () {
        if (!session()->has('user_id')) return redirect('/login');
        $userName = session('user_name', 'Guest');
        return view('home', ['userName' => $userName]);
    });

    // =================== PROTECTED RESUME =================== //
    Route::get('/resume', [ResumeController::class, 'resume'])->name('resume.show');
    Route::get('/resume/edit/{id}', [ResumeController::class, 'editResume'])->name('resume.edit');
    Route::put('/resume/{id}', [ResumeController::class, 'updateResume'])->name('resume.update');
    Route::post('/resume', [ResumeController::class, 'storeResume'])->name('resume.store');
    Route::delete('/resume/{id}', [ResumeController::class, 'deleteResume'])->name('resume.delete');
    Route::get('/resume/download/{id?}', [ResumeController::class, 'downloadResume'])->name('resume.download');

    // =================== OTHER PAGES =================== //
    Route::get('/about', fn() => session()->has('user_id') ? view('about') : redirect('/login'));
    Route::get('/skills', fn() => session()->has('user_id') ? view('skills') : redirect('/login'));
    Route::get('/projects', fn() => session()->has('user_id') ? view('projects') : redirect('/login'));

    // =================== CONTACT PAGE =================== //
    Route::get('/contact', [AuthController::class, 'contact'])->name('contact.show');
    Route::post('/contact', [AuthController::class, 'sendContact'])->name('contact.send');
});
