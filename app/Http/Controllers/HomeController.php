<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function home()
    {
        $course = Course::first();

        $firstLessonRoute = route('lesson.show', [
            'course' => $course->slug,
            'lesson' => $course->chapters[0]->lessons[0]->id
        ]);


        return Inertia::render('Index', compact('firstLessonRoute'));
    }
}
