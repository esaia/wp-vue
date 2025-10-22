<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        User::create($validated);

        return redirect()->route('lesson.show', [
            'course' => 'WordPress-Plugin-Development-with-Vue-js',
            'lesson' => 1,
        ]);
    }



    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }



    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            $course = Course::first();

            return redirect()->route('lesson.show', [
                'course' => $course->slug,
                'lesson' => $course->chapters[0]->lessons[0]->id
            ]);
        }

        // Return error if authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
}
