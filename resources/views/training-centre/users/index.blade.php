<x-app-layout>
    <x-slot name="title">Manajemen Pengguna</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-[#2563eb] mb-1">
                <a href="{{ route('training-centre.dashboard') }}" class="hover:underline">Dashboard</a>
                <span>/</span>
                <span class="text-slate-500">Manajemen Pengguna</span>
            </div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Manajemen Pengguna</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar trainee, trainer (instruktur/pengawas/operator pendamping), dan admin yang terdaftar di sistem.</p>
        </div>
        <a href="{{ route('training-centre.users.create') }}" class="px-4 py-2 bg-[#2563eb] hover:bg-blue-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
            + Tambah Pengguna
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-3 sm:p-4">
            <p class="text-[10px] sm:text-[11px] text-slate-500 font-bold uppercase">Total</p>
            <p class="text-xl sm:text-2xl font-black text-slate-800">{{ $counts['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-3 sm:p-4">
            <p class="text-[10px] sm:text-[11px] text-slate-500 font-bold uppercase">Trainee</p>
            <p class="text-xl sm:text-2xl font-black text-[#2563eb]">{{ $counts['trainee'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-3 sm:p-4">
            <p class="text-[10px] sm:text-[11px] text-slate-500 font-bold uppercase">Trainer</p>
            <p class="text-xl sm:text-2xl font-black text-[#1e3a8a]">{{ $counts['trainer'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="font-bold text-slate-800 text-sm sm:text-base">Daftar Pengguna</h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Kelola akun trainee, trainer, dan pengawas.</p>
            </div>
            <form class="flex flex-col sm:flex-row gap-2 flex-wrap items-stretch sm:items-center" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari SID, nama, email, departemen..." class="text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]">
                <select name="role" class="text-xs rounded-xl border-slate-300 min-h-[44px]">
                    <option value="">Semua Role</option>
                    <option value="trainee" {{ request('role') === 'trainee' ? 'selected' : '' }}>Trainee</option>
                    <option value="trainer" {{ request('role') === 'trainer' ? 'selected' : '' }}>Trainer</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin TC</option>
                    <option value="pjo" {{ request('role') === 'pjo' ? 'selected' : '' }}>PJO</option>
                    <option value="hse_ct" {{ request('role') === 'hse_ct' ? 'selected' : '' }}>HSE CT</option>
                </select>
                <select name="department" class="text-xs rounded-xl border-slate-300 min-h-[44px]">
                    <option value="">Semua Departemen</option>
                    @php
                        $deptOptions = collect(['CHCPP', 'RIM', 'HRGS'])->merge($departments ?? [])->filter()->unique()->values();
                    @endphp
                    @foreach($deptOptions as $d)
                        <option value="{{ $d }}" {{ request('department') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
                <button class="px-4 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold min-h-[44px] transition">Filter</button>
                @if(request()->hasAny(['search', 'role', 'department']))
                    <a href="{{ route('training-centre.users.index') }}" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold min-h-[44px] inline-flex items-center justify-center transition">Reset</a>
                @endif
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">SID / Nama</th>
                        <th class="px-5 py-3">Departemen & Perusahaan</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Tipe Trainer</th>
                        <th class="px-5 py-3">Fase Evaluasi</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-blue-50/30">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-[#1e3a8a] font-bold text-xs flex items-center justify-center border border-slate-200">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">{{ $user->sid }}</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $user->name }}</p>
                                        @if($user->email)
                                            <p class="text-[10px] text-slate-400">{{ $user->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @if($user->department || $user->company)
                                    @if($user->department)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200 mb-1">
                                            🏢 {{ $user->department }}
                                        </span>
                                    @endif
                                    @if($user->company)
                                        <p class="text-[10px] text-slate-500 truncate max-w-[180px]" title="{{ $user->company }}">{{ $user->company }}</p>
                                    @endif
                                @else
                                    <span class="text-[11px] text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $roleBadge = match($user->role) {
                                        'trainee' => 'bg-blue-50 text-blue-700',
                                        'trainer' => 'bg-blue-50 text-blue-700',
                                        'admin'   => 'bg-violet-50 text-violet-700',
                                        'pjo'     => 'bg-indigo-50 text-indigo-700',
                                        'hse_ct'  => 'bg-amber-50 text-amber-700',
                                        default   => 'bg-slate-100 text-slate-700',
                                    };
                                    $roleLabel = match($user->role) {
                                        'trainee' => 'Trainee',
                                        'trainer' => 'Trainer',
                                        'admin'   => 'Admin TC',
                                        'pjo'     => 'PJO',
                                        'hse_ct'  => 'HSE CT',
                                        default   => $user->role,
                                    };
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $roleBadge }}">
                                    {{ $roleLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($user->role === 'trainer' && $user->trainer_type)
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700">
                                         {{ $user->trainer_type === 'instruktur' ? 'Instruktur' : ($user->trainer_type === 'pengawas' ? 'Pengawas' : ($user->trainer_type === 'operator_pendamping' ? 'Operator Pendamping' : $user->trainer_type)) }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($user->role === 'trainee')
                                    <form method="POST" action="{{ route('training-centre.users.assign-phase', $user->id) }}" class="flex items-center gap-2">
                                        @csrf
                                        <select name="phase" class="text-[11px] rounded-lg border-slate-300 bg-white px-2 py-1.5 focus:border-brand-500 focus:ring-brand-500 min-h-[36px]">
                                            @foreach(\App\Services\PhaseService::sequence($user->certification ?? 'Green') as $phase)
                                                <option value="{{ $phase['key'] }}" {{ ($user->current_phase ?? \App\Services\PhaseService::firstPhase($user->certification ?? 'Green')) === $phase['key'] ? 'selected' : '' }}>
                                                    {{ $phase['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-[#2563eb] text-white text-[10px] font-bold hover:bg-blue-600 transition min-h-[36px]">Set</button>
                                    </form>
                                @else
                                    <span class="text-[11px] text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                @if($user->isSuperAdmin() && !auth()->user()->isSuperAdmin())
                                    <span class="text-[10px] text-slate-400">Super Admin</span>
                                @else
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('training-centre.users.edit', $user->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold">Edit</a>
                                        @if($user->id !== auth()->id())
                                                 <form method="POST" action="{{ route('training-centre.users.destroy', $user->id) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }} ({{ $user->sid }})? Tindakan ini tidak dapat dibatalkan.')">
                                                 @csrf
                                                 @method('DELETE')
                                                 <button type="submit" class="inline-flex px-3 py-2 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold">Hapus</button>
                                             </form>
                                             <form method="POST" action="{{ route('training-centre.users.reset-password', $user->id) }}" onsubmit="return confirm('Reset password {{ $user->name }} ({{ $user->sid }}) ke default (password)?')">
                                                 @csrf
                                                 <button type="submit" class="inline-flex px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold">Reset Password</button>
                                             </form>
                                         @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>


