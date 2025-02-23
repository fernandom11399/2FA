<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\EmailController;
use App\Http\Controllers\Web\ErrorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Authentication Routes
// These routes handle the user registration, login, email verification, and account activation.
Route::controller(AuthController::class)->middleware('redirect.if.authenticated')->group(function () {
    Route::get('/register', 'showRegisterForm')->name('register.form');  // Show registration form
    Route::post('/register', 'register')->name('register.submit');      // Submit registration
    Route::get('/activate-account', [EmailController::class, 'activate'])->name('activate.account');  // Activate account via email
    Route::get('/login', 'showLoginForm')->name('login.form');          // Show login form
    Route::get('/verification-code', 'showVerificationCode')->name('verification.form');  // Show verification code input form
    Route::get('verify-email', 'showVerifyEmail')->name('verify.email');  // Show email verification prompt
    Route::middleware('limit.login.attempts')->group(function () {
        Route::post('/verification-code', 'verificationCode')->name('verification.code');  // Submit verification code
        Route::post('/login', 'LoginWithPasswordRequest')->name('login.submit');  // Submit login form
    });
});

// Error Routes
// These routes handle the display of error messages, such as too many login attempts or page not found.
Route::controller(ErrorController::class)->prefix('error')->group(function () {
    Route::get('too-many-attempts', 'tooManyAttempts')->name('error.too_many_attempts');  // Show too many attempts error page
    Route::get('not-found', 'notFound')->name('error.404');  // Show 404 not found error page
});

// Authenticated User Routes
// These routes are protected by the 'auth' middleware, meaning only authenticated users can access them.
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard'); // Show the dashboard for the authenticated user
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Handle user logout
});
