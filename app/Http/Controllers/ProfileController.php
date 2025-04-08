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
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'alamat_toko' => ['nullable', 'string', 'max:255'],
            'nomor_whatsapp' => ['nullable', 'string', 'max:20'],
        ]);

        $request->user()->fill([
            'name' => $request->name,
            'email' => $request->email,
            'alamat_toko' => $request->alamat_toko,
            'nomor_whatsapp' => $request->nomor_whatsapp,
        ]);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Profile berhasil diperbarui');
    }
} 