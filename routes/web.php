<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/{course:slug}/lesson/{lesson}', [LessonController::class, 'show'])->name('lesson.show');


Route::controller(AuthController::class)->group(function () {
    // Authentication
    Route::post('/login', 'login')->name('login');
    Route::post('/signup', 'register')->name('signup');
    Route::post('/logout', 'destroy')->name('logout');

    Route::get('/email/verify/{id}/{hash}', 'verificationVerify')
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('/email/verification-notification', 'resendEmailVerification')
        ->middleware(['throttle:2,1'])
        ->name('verification.send');


    // Password reset
    Route::name('password.')->group(function () {
        Route::post('/password/forgot', 'sendResetLinkEmail')->middleware('guest')->name('email');
        Route::get('/password/reset/{token}', 'showResetPasswordForm')->name('reset');
        Route::post('/password/reset', 'resetPassword')->name('update');
    });
});



Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');

Route::post('/webhook/dodopayments', [PaymentController::class, 'webhook'])->name('webhook.dodopayments');
