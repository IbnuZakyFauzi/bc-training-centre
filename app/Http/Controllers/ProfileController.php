<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['required', 'string', 'max:30'],
            'signature' => ['nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
        ];

        if ($request->filled('new_password')) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['new_password'] = ['required', 'string', 'min:6', 'confirmed'];
        }

        $data = $request->validate($rules, [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $signaturePath = $user->signature_path;

        if ($request->hasFile('signature')) {
            if ($signaturePath) {
                Storage::disk('public')->delete($signaturePath);
            }

            $signaturePath = $request->file('signature')->store('signatures', 'public');
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'] ?: null,
            'phone' => $data['phone'] ?? null,
            'signature_path' => $signaturePath,
            'must_change_password' => false,
        ];

        if (!empty($data['new_password'])) {
            $updateData['password'] = bcrypt($data['new_password']);
        }

        $user->update($updateData);

        return back()->with('success', 'Profil berhasil disimpan.');
    }
}
