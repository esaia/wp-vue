<?php

use App\Http\Controllers\LessonController;
use App\Models\Course;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $course = Course::first();

    return Inertia::render('Index', [
        'course' => $course
    ]);
})->name('home');

Route::get('/{course:slug}/lesson/{lesson}', [LessonController::class, 'show'])
    ->name('lesson.show');
