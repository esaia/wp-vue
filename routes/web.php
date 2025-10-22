<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LessonController;
use App\Models\Course;
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
