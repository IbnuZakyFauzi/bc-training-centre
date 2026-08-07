<x-app-layout>
    <x-slot name="title">My Profile</x-slot>

    <div class="mb-6">
        <div class="bg-gradient-to-r from-[#003829] to-[#00593E] text-white rounded-2xl p-6 shadow-sm border border-emerald-900">
            <p class="text-emerald-300 text-[10px] font-bold uppercase tracking-widest">Account Settings</p>
            <h1 class="mt-1 text-2xl font-extrabold">My Profile</h1>
            <p class="mt-2 text-xs text-emerald-100">Simpan tanda tangan sekali di sini, lalu dipakai otomatis untuk approval trainer, pengawas, dan kabag.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-900">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="text-xs font-bold text-slate-700">Nama</label>
                <input name="name" value="{{ old('name', $user->name) }}" class="mt-2 w-full rounded-xl border-slate-300 text-sm" required>
                @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-2 w-full rounded-xl border-slate-300 text-sm" required>
                @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700">Phone</label>
                <input name="phone" value="{{ old('phone', $user->phone) }}" class="mt-2 w-full rounded-xl border-slate-300 text-sm" placeholder="Opsional">
                @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700">Upload Tanda Tangan</label>
                <input type="file" name="signature" accept="image/png,image/jpeg" class="mt-2 block w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#003829] file:px-3 file:py-2 file:text-xs file:font-bold file:text-white">
                <p class="mt-2 text-[11px] text-slate-500">Format PNG, JPG, atau JPEG. Maksimal 2 MB. Tanda tangan ini akan dipakai otomatis saat approval.</p>
                @error('signature')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end">
                <button class="rounded-xl bg-[#00A859] px-5 py-3 text-xs font-bold text-white hover:bg-emerald-600">Simpan Profil</button>
            </div>
        </form>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Informasi Akun</p>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500 text-xs">NRP</span>
                        <span class="font-semibold text-slate-800">{{ $user->nrp }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500 text-xs">Role</span>
                        <span class="font-semibold text-slate-800">{{ strtoupper($user->role) }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500 text-xs">Department</span>
                        <span class="font-semibold text-slate-800">{{ $user->department->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Tanda Tangan Tersimpan</p>
                <div class="mt-4 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4">
                    @if($user->signature_path)
                        <img src="{{ asset('storage/'.$user->signature_path) }}" alt="Signature" class="max-h-32 w-auto object-contain">
                        <p class="mt-3 text-[11px] text-slate-500">Dipakai otomatis saat approval.</p>
                    @else
                        <p class="text-sm text-slate-500">Belum ada tanda tangan tersimpan.</p>
                        <p class="mt-2 text-[11px] text-slate-500">Upload sekali dari halaman ini agar approval berikutnya bisa langsung klik approve.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
