<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        $course->load('chapters');
        $lesson->load('chapter');

        $user = Auth::user();

        $hasCourseAccess = $user ? $user->containsCourse($course['product_id']) : false;

        return Inertia::render('Lesson/Show', [
            'course' => $course,
            'currentLesson' => $lesson,
            'hasCourseAccess' => $hasCourseAccess
        ]);
    }
}
