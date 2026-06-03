<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Ambil data yang sudah divalidasi
        $validatedData = $request->validated();

        // --- MULAI SUNTIKAN KODE PEMAKSAAN KAPITAL ---
        if (isset($validatedData['name'])) {
            // Nama user dipaksa jadi HURUF BESAR
            $validatedData['name'] = strtoupper($validatedData['name']);
        }
        if (isset($validatedData['email'])) {
            // Khusus Email, dipaksa jadi huruf kecil semua (standar keamanan)
            $validatedData['email'] = strtolower($validatedData['email']); 
        }
        // --- AKHIR SUNTIKAN KODE ---

        // Masukkan data yang sudah disulap ke database
        $request->user()->fill($validatedData);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
