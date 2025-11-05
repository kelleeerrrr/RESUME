<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Updated to match the session-based AuthController and HomeController logic.
| Protected routes use the 'web' middleware group only; controllers perform
| inline session checks to enforce login/ownership.
|
*/

/* =================== PUBLIC PAGES =================== */

// Welcome / Landing Page (lists community resumes)
Route::get('/', [ResumeController::class, 'welcome'])->name('welcome');

// Public Resume (list) — reuse welcome() which lists users with resumes
Route::get('/public-resume', [ResumeController::class, 'welcome'])->name('resume.public');

// Public Resume Detail (view a specific user's resume)
Route::get('/public-resume/{id}', [ResumeController::class, 'show'])->name('resume.public.show');


/* =================== AUTH ROUTES =================== */

// Show signup page (GET)
Route::get('/signup', fn() => view('signup'))->name('register');

// Handle signup form (POST)
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Show login page (GET)
Route::get('/login', fn() => view('login'))->name('login');

// Handle login form (POST)
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout (use POST for safety)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/* =================== PASSWORD RESET =================== */

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


/* =================== PROTECTED ROUTES (LOGIN REQUIRED) ===================
   NOTE: controllers perform session checks (no custom middleware required).
   Keep routes under 'web' so session & CSRF middleware are applied.
*/
Route::middleware(['web'])->group(function () {

    // Home / Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // =================== PROTECTED RESUME =================== //
    Route::get('/resume', [ResumeController::class, 'resume'])->name('resume.show');

    // Use the same edit blade for both create and edit.
    // Map /resume/create to editResume (no separate create method required).
    Route::get('/resume/create', [ResumeController::class, 'editResume'])->name('resume.create');

    // Edit route (optional id)
    Route::get('/resume/edit/{id?}', [ResumeController::class, 'editResume'])->name('resume.edit');

    // Store (for create)
    Route::post('/resume', [ResumeController::class, 'storeResume'])->name('resume.store');

    // Update (for edit)
    Route::put('/resume/{id}', [ResumeController::class, 'updateResume'])->name('resume.update');

    // Delete (updated to match Blade)
    Route::delete('/resume/{id}', [ResumeController::class, 'deleteResume'])->name('resume.destroy');

    // Download
    Route::get('/resume/download/{id?}', [ResumeController::class, 'downloadResume'])->name('resume.download');

    // =================== PROFILE PHOTO UPLOAD =================== //
    // Accepts base64 'photo_data' or multipart 'photo' — handled in AuthController@uploadProfilePhoto
    Route::post('/resume/photo', [AuthController::class, 'uploadProfilePhoto'])->name('resume.photo.update');

    // =================== OTHER PAGES =================== //
    Route::get('/about', fn() => session()->has('user_id') ? view('about') : redirect('/login'));
    Route::get('/skills', fn() => session()->has('user_id') ? view('skills') : redirect('/login'));
    Route::get('/projects', fn() => session()->has('user_id') ? view('projects') : redirect('/login'));

    // =================== CONTACT PAGE =================== //
    Route::get('/contact', [AuthController::class, 'contact'])->name('contact.show');
    Route::post('/contact', [AuthController::class, 'sendContact'])->name('contact.send');
    Route::post('/resume/spotlight', [AuthController::class, 'uploadProfilePhoto'])->name('resume.spotlight.update');

});
