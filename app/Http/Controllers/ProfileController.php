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

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['required', 'string', 'max:30'],
            'signature' => ['nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
            'current_password' => ['nullable', 'current_password'],
            'new_password' => ['nullable', 'string', 'min:6', 'confirmed'],
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
            'email' => $data['email'],
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
