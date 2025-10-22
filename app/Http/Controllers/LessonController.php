<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson)
    {
        // Ensure the lesson belongs to the course
        if ($lesson->course_id !== $course->id) {
            abort(404, 'Lesson not found in this course.');
        }

        // Load related data (e.g., chapter) if needed
        $lesson->load('chapter');

        return Inertia::render('Lesson/Show', [
            'course' => [
                'id' => $course->id,
                'slug' => $course->slug,
                'title' => $course->title,
            ],
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'content' => $lesson->content,
                'sort_order' => $lesson->sort_order,
                'chapter' => [
                    'id' => $lesson->chapter->id,
                    'title' => $lesson->chapter->title,
                ],
            ],
        ]);
    }
}
