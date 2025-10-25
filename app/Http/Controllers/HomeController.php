<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function home()
    {
        $user = Auth::user();
        $wpVueCourse = Course::first();


        $firstLessonRoute = route('lesson.show', [
            'course' => $wpVueCourse->slug,
            'lesson' => $wpVueCourse->chapters[0]->lessons[0]->id
        ]);


        $hasCourseAccess = $user ? $user->containsCourse($wpVueCourse->product_id) : false;


        return Inertia::render('Index', compact('firstLessonRoute', 'hasCourseAccess'));
    }
}
