<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);



        $course = Course::create([
            'title' => 'WordPress Plugin Development with Vue.js',
            'description' => 'Learn to build WordPress plugins using Vue.js.',
            'slug' => 'wordpress-plugin-development-vuejs',
            'price' => 49.99,
        ]);

        $chapter = Chapter::create([
            'course_id' => $course->id,
            'title' => 'Introduction to WordPress Plugins',
            'content' => 'Overview of plugin development.',
            'order' => 1,
        ]);

        Lesson::create([
            'chapter_id' => $chapter->id,
            'title' => 'What is a WordPress Plugin?',
            'content' => 'Understanding the basics of plugins.',
            'order' => 1,
        ]);

        Lesson::create([
            'chapter_id' => $chapter->id,
            'title' => 'Setting Up Your Development Environment',
            'content' => 'Tools and setup for plugin development.',
            'order' => 2,
        ]);

        $chapter2 = Chapter::create([
            'course_id' => $course->id,
            'title' => 'Setting Up Vue.js in WordPress',
            'content' => 'Integrating Vue.js with WordPress.',
            'order' => 2,
        ]);

        Lesson::create([
            'chapter_id' => $chapter2->id,
            'title' => 'Introduction to Vue.js',
            'content' => 'Basics of Vue.js for WordPress.',
            'order' => 1,
        ]);
    }
}
