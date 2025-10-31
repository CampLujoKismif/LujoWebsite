<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Http\Requests\CustomEmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Auth\MustVerifyEmail;

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
}
