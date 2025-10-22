<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
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
}
