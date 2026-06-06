<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Jika user sebenarnya sudah terverifikasi sebelumnya, langsung lempar ke dashboard
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        // Tandai email sebagai terverifikasi di database
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Lempar ke dashboard dengan membawa pesan sukses
        return redirect()->route('dashboard')->with('success', 'Email Anda berhasil diverifikasi!');
    }
}