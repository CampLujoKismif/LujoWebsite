<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Http\Requests\CustomEmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(CustomEmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Check if user implements MustVerifyEmail interface
        if (!($user instanceof MustVerifyEmail)) {
            return redirect()->intended(route('dashboard.home', absolute: false));
        }

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard.home', absolute: false).'?verified=1');
        }

        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            // Set flag to force password change
            $user->update(['must_change_password' => true]);
            event(new Verified($user));
        }

        // Redirect to login with success message
        return redirect()->route('login')->with('message', 'Your email has been verified. Please log in and change your password.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request): RedirectResponse
    {
        // If user is authenticated, use authenticated user
        if (Auth::check()) {
            $user = Auth::user();
        } else {
            // For unauthenticated users, require email address
            $request->validate([
                'email' => ['required', 'string', 'email'],
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                // Don't reveal if email exists or not for security
                return redirect()->route('login')
                    ->with('status', 'verification-link-sent');
            }

            // Only resend if email is not already verified
            if ($user->hasVerifiedEmail()) {
                return redirect()->route('login')
                    ->with('status', 'verification-link-sent');
            }
        }

        // Send the verification notification
        $user->sendEmailVerificationNotification();

        $message = $request->has('email') 
            ? 'A new verification link has been sent to your email address.' 
            : 'verification-link-sent';

        return redirect()->route('login')
            ->with('status', $message);
    }
}
