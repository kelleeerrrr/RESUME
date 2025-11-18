<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\HomeController;

/* =================== PUBLIC PAGES =================== */

Route::get('/', [ResumeController::class, 'welcome'])->name('welcome');
Route::get('/public-resume', [ResumeController::class, 'welcome'])->name('resume.public');
Route::get('/public-resume/{id}', [ResumeController::class, 'show'])->name('resume.public.show');


/* =================== AUTH ROUTES =================== */

Route::get('/signup', fn() => view('signup'))->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/login', fn() => view('login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/* =================== PASSWORD RESET =================== */

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');


/* =================== PROTECTED ROUTES (LOGIN REQUIRED) =================== */

Route::middleware(['web'])->group(function () {

    // Home / Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // =================== RESUME =================== //
    Route::get('/resume', [ResumeController::class, 'resume'])->name('resume.show');
    Route::get('/resume/create', [ResumeController::class, 'editResume'])->name('resume.create');
    Route::get('/resume/edit/{id?}', [ResumeController::class, 'editResume'])->name('resume.edit');
    Route::post('/resume', [ResumeController::class, 'storeResume'])->name('resume.store');
    Route::put('/resume/{id}', [ResumeController::class, 'updateResume'])->name('resume.update');
    Route::delete('/resume/{id}', [ResumeController::class, 'deleteResume'])->name('resume.destroy');
    Route::get('/resume/download/{id?}', [ResumeController::class, 'downloadResume'])->name('resume.download');

    // Profile photo upload
    Route::post('/resume/photo', [AuthController::class, 'uploadProfilePhoto'])->name('resume.photo.update');
    Route::post('/resume/spotlight', [AuthController::class, 'uploadProfilePhoto'])->name('resume.spotlight.update');

});
