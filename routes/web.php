<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LessonController;
use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $course = Course::first();

    $firstLessonRoute = route('lesson.show', [
        'course' => $course->slug,
        'lesson' => $course->chapters[0]->lessons[0]->id
    ]);


    return Inertia::render('Index', compact('firstLessonRoute'));
})->name('home');



Route::get('/{course:slug}/lesson/{lesson}', [LessonController::class, 'show'])
    ->name('lesson.show');


Route::controller(AuthController::class)->group(function () {
    // Authentication
    Route::post('/login', 'login')->name('login');
    Route::post('/signup', 'register')->name('signup');
    Route::post('/logout', 'destroy')->name('logout');

    // Password reset
    Route::name('password.')->group(function () {
        Route::post('/password/forgot', 'sendResetLinkEmail')->name('email');
        Route::get('/password/reset/{token}', 'showResetPasswordForm')->name('reset');
        Route::post('/password/reset', 'resetPassword')->name('update');
    });
});


// Route::get('/email/verify', function () {
//     $course = Course::first();

//     $firstLessonRoute = route('lesson.show', [
//         'course' => $course->slug,
//         'lesson' => $course->chapters[0]->lessons[0]->id
//     ]);

//     return Inertia::render('Index', compact('firstLessonRoute'));
// })->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);

    if ($user->hasVerifiedEmail()) {
        return redirect()->route('home')->with('status', 'Your email is already verified.');
    }

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect()->route('home')->withErrors(['email' => 'Invalid verification link.']);
    }

    $user->markEmailAsVerified();
    event(new Verified($user));

    return redirect()->route('home', ['success' => 'email_verified'])->with('status', 'Email verified successfully!');
})->middleware('signed')->name('verification.verify');
