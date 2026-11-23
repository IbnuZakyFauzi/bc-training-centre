<x-app-layout>
    <x-slot name="title">My Profile</x-slot>

    <div class="mb-6">
        <div class="bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] text-white rounded-2xl p-6 shadow-sm border border-blue-900">
            <p class="text-blue-300 text-[10px] font-bold uppercase tracking-widest">Account Settings</p>
            <h1 class="mt-1 text-2xl font-extrabold">My Profile</h1>
            <p class="mt-2 text-xs text-blue-100">Simpan tanda tangan sekali di sini, lalu dipakai otomatis untuk approval trainer, pengawas, dan kabag.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-xs text-blue-900">
            {{ session('success') }}
        </div>
    @endif

    @if($user->must_change_password)
        <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-900">
            <p class="font-bold">Anda harus mengganti password default terlebih dahulu.</p>
            <p class="mt-1 text-[11px] text-amber-700">Gunakan form di bawah ini untuk mengubah password Anda.</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6" x-data="{
        hasSavedSignature: {{ $user->signature_path ? 'true' : 'false' }},
        previewUrl: null,
        newFileName: '',
        handleSignatureChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.newFileName = file.name;
                this.previewUrl = URL.createObjectURL(file);
            } else {
                this.newFileName = '';
                this.previewUrl = null;
            }
        }
    }">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" autocomplete="off" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 space-y-4 sm:space-y-5">
            @csrf
            @method('PATCH')

            <!-- Decoy inputs to prevent browser aggressive credential autofill -->
            <input type="text" name="fakeusernameremembered" style="display:none" tabindex="-1" autocomplete="off" />
            <input type="password" name="fakepasswordremembered" style="display:none" tabindex="-1" autocomplete="off" />

            <div>
                <label class="text-xs font-bold text-slate-700">Nama</label>
                <input name="name" value="{{ old('name', $user->name) }}" autocomplete="off" class="mt-2 w-full rounded-xl border-slate-300 text-sm min-h-[44px]" required>
                @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700">Email (Opsional)</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="off" placeholder="opsional@email.com" class="mt-2 w-full rounded-xl border-slate-300 text-sm min-h-[44px]">
                @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700">No. HP <span class="text-rose-500">*</span></label>
                <input name="phone" value="{{ old('phone', $user->phone) }}" autocomplete="off" class="mt-2 w-full rounded-xl border-slate-300 text-sm min-h-[44px]" required placeholder="Contoh: 08123456789">
                @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label class="text-xs font-bold text-slate-700">Upload Tanda Tangan</label>
                    @if($user->signature_path)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ✓ Sudah Ada Tersimpan
                        </span>
                    @endif
                </div>

                @if($user->signature_path)
                    <div class="mt-2 p-2.5 rounded-xl bg-emerald-50/80 border border-emerald-200 text-xs text-emerald-800 flex items-start gap-2">
                        <svg class="w-4 h-4 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <div>
                            <p class="font-semibold">Tanda tangan Anda sudah tersimpan di sistem.</p>
                            <p class="text-[11px] text-emerald-700 mt-0.5">Pilih file baru di bawah ini hanya jika Anda ingin mengganti tanda tangan tersebut.</p>
                        </div>
                    </div>
                @endif

                <input type="file" name="signature" accept="image/png,image/jpeg" @change="handleSignatureChange($event)" class="mt-2 block w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#1e3a8a] file:px-3 file:py-2 file:text-xs file:font-bold file:text-white min-h-[44px]">
                
                <template x-if="newFileName">
                    <div class="mt-2 p-2 rounded-lg bg-blue-50 border border-blue-200 text-xs text-blue-800 flex items-center gap-2">
                        <span>📁</span>
                        <span>File baru dipilih: <strong x-text="newFileName"></strong></span>
                        <span class="text-[11px] text-blue-600 ml-auto">(Klik Simpan Profil untuk menyimpan)</span>
                    </div>
                </template>

                <p class="mt-2 text-[11px] text-slate-500">Format PNG, JPG, atau JPEG. Maksimal 2 MB. Tanda tangan ini akan dipakai otomatis saat approval.</p>
                @error('signature')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="border-t border-slate-200 pt-4 sm:pt-5">
                <p class="text-xs font-bold text-slate-700 mb-3 sm:mb-4">Ganti Password</p>
                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <label class="text-xs font-bold text-slate-700">Password Saat Ini</label>
                        <input type="password" name="current_password" autocomplete="new-password" class="mt-2 w-full rounded-xl border-slate-300 text-sm min-h-[44px]" placeholder="Wajib diisi jika ingin ganti password">
                        @error('current_password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-700">Password Baru</label>
                        <input type="password" name="new_password" autocomplete="new-password" class="mt-2 w-full rounded-xl border-slate-300 text-sm min-h-[44px]" placeholder="Minimal 6 karakter">
                        @error('new_password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-700">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" autocomplete="new-password" class="mt-2 w-full rounded-xl border-slate-300 text-sm min-h-[44px]" placeholder="Ulangi password baru">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="rounded-xl bg-[#2563eb] px-4 sm:px-5 py-3 text-xs font-bold text-white hover:bg-blue-600 min-h-[44px]">Simpan Profil</button>
            </div>
        </form>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Informasi Akun</p>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500 text-xs">SID</span>
                        <span class="font-semibold text-slate-800">{{ $user->sid }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500 text-xs">Role</span>
                        <span class="font-semibold text-slate-800">{{ strtoupper($user->role) }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500 text-xs">Status Password</span>
                        <span class="font-semibold {{ $user->must_change_password ? 'text-amber-600' : 'text-emerald-600' }}">
                            {{ $user->must_change_password ? 'Harus diganti' : 'Sudah diganti' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Tanda Tangan Tersimpan</p>
                    @if($user->signature_path)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ✓ Aktif
                        </span>
                    @endif
                </div>

                <div class="mt-4 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 flex flex-col items-center justify-center min-h-[140px]">
                    <!-- Live preview of newly selected file -->
                    <template x-if="previewUrl">
                        <div class="text-center w-full">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600 mb-2">Pratinjau File Baru:</p>
                            <img :src="previewUrl" alt="Pratinjau Baru" class="max-h-32 mx-auto w-auto object-contain bg-white p-2 rounded-lg border border-blue-200 shadow-xs">
                            <p class="mt-2 text-[11px] text-blue-700 font-semibold">Tanda tangan baru ini akan disimpan setelah Anda klik tombol "Simpan Profil".</p>
                        </div>
                    </template>

                    <!-- Existing saved signature -->
                    <template x-if="!previewUrl && hasSavedSignature">
                        <div class="text-center w-full">
                            <img src="{{ asset('storage/'.$user->signature_path) }}" onerror="this.onerror=null; this.src='{{ route('storage.local', $user->signature_path ?? '') }}'" alt="Tanda Tangan {{ $user->name }}" class="max-h-32 mx-auto w-auto object-contain bg-white p-2 rounded-lg border border-slate-200 shadow-xs">
                            <p class="mt-3 text-[11px] text-slate-500 font-medium">Dipakai otomatis saat approval berkas.</p>
                        </div>
                    </template>

                    <!-- No signature yet -->
                    <template x-if="!previewUrl && !hasSavedSignature">
                        <div class="text-center py-4">
                            <span class="text-3xl">✍️</span>
                            <p class="text-xs font-bold text-slate-700 mt-2">Belum ada tanda tangan tersimpan</p>
                            <p class="mt-1 text-[11px] text-slate-500 text-center max-w-xs">Upload tanda tangan sekali di samping agar berkas approval dapat langsung disahkan.</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


