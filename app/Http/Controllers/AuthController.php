<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create($validated);

        event(new Registered($user));

        return redirect()->back();
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
            $user = Auth::user();

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'You must verify your email address before logging in.',
                    'email_verify' => true
                ])->onlyInput('email');
            }

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


    public function resendEmailVerification(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        $user->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    }

    // Send the password reset link email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }


    public function showResetPasswordForm(Request $request, $token)
    {
        return redirect()->route('home', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed:confirmPassword',
        ]);


        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                    // 'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }


    public function verificationVerify($id, $hash)
    {
        $user = User::findOrFail($id);

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('status', 'Your email is already verified.');
        }

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('home')->withErrors(['email' => 'Invalid verification link.']);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return redirect()->route('home', ['success' => 'email_verified'])->with('status', 'Email verified successfully!');
    }
}
