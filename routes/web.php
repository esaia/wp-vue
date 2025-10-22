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



Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/signup', [AuthController::class, 'register'])->name('signup');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});
