<x-app-layout>
    <x-slot name="title">Monitoring Evaluasi Multi-Fase</x-slot>

    <div class="mb-8 bg-gradient-to-r from-[#003829] to-[#00593E] p-6 rounded-2xl shadow-md text-white border border-emerald-900 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-emerald-300 text-xs font-bold uppercase tracking-widest mb-1">{{ $user->isTrainingCentre() ? 'Admin Training Centre' : 'Trainer' }}</p>
            <h1 class="text-2xl font-bold">Monitoring Progress Evaluasi Multi-Fase</h1>
            <p class="text-emerald-100 text-xs mt-1">{{ $user->isTrainingCentre() ? 'Seluruh trainee terdaftar.' : 'Hanya trainee yang Anda bimbing (instruktur/pengawas/operator pendamping).' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-7">
        <form method="GET" class="p-4 flex flex-col sm:flex-row gap-3 border-b border-slate-100">
            <input name="search" value="{{ request('search') }}" placeholder="Cari nama / SID..." class="text-xs rounded-xl border-slate-300 focus:border-[#00A859] focus:ring-[#00A859] flex-1">
            <select name="certification" class="text-xs rounded-xl border-slate-300 focus:border-[#00A859] focus:ring-[#00A859]">
                <option value="">Semua Sertifikasi</option>
                @foreach($certifications as $c)
                    <option value="{{ $c }}" {{ request('certification') === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
            <button class="px-5 rounded-xl bg-[#003829] text-white text-xs font-bold">Filter</button>
        </form>
    </div>

    <div class="space-y-5">
        @forelse($trainees as $t)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100">
                    <div class="flex items-center space-x-4">
                        <div class="w-11 h-11 rounded-full bg-[#00A859] flex items-center justify-center text-white font-bold">
                            {{ collect(explode(' ', $t['name']))->take(2)->map(fn($p)=>strtoupper(substr($p,0,1)))->implode('') }}
                        </div>
                        <div>
                            <p class="text-base font-extrabold text-slate-800">{{ $t['name'] }}</p>
                            <p class="text-xs text-slate-500">{{ $t['sid'] }} · {{ $t['certification'] }} · Fase Saat Ini: <span class="font-bold text-[#003829]">{{ $t['current_phase_label'] }}</span></p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if($t['eligible'])
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#00A859] text-white">Siap Dievaluasi</span>
                        @else
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">Akumulasi HM</span>
                        @endif
                        <div class="text-right">
                            <p class="text-xs text-slate-500">Evaluasi Selesai</p>
                            <p class="text-lg font-extrabold text-[#003829]">{{ $t['completed_count'] }} <span class="text-xs font-normal text-slate-400">/ {{ $t['evaluations_count'] }} total</span></p>
                        </div>
                    </div>
                </div>

                @if(($t['progress']['total'] ?? 0) < 100)
                    <div class="px-5 pt-4">
                        <div class="flex justify-between text-xs text-slate-600 mb-1">
                            <span>Progress HM Fase {{ $t['current_phase_label'] }}</span>
                            <span class="font-bold">{{ number_format($t['hm']['total'], 1) }} jam ({{ $t['progress']['total'] }}%)</span>
                        </div>
                        <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#00A859]" style="width: {{ $t['progress']['total'] }}%"></div>
                        </div>
                    </div>
                @endif

                <div class="p-5">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Rincian Evaluasi per Fase</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                        @foreach($t['phases'] as $p)
                            <div class="rounded-xl border p-3 {{ $p['is_current'] ? 'border-[#00A859] bg-[#00A859]/5' : 'border-slate-200' }}">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-700">{{ $p['label'] }}</span>
                                    @if($p['is_current'])<span class="text-[9px] font-extrabold text-[#00A859]">●</span>@endif
                                </div>
                                <p class="text-[10px] text-slate-500 mt-0.5">Selesai: <span class="font-bold text-[#00A859]">{{ $p['completed'] }}</span></p>
                                @if($p['pending'] > 0)
                                    <p class="text-[10px] text-amber-600">Proses: {{ $p['pending'] }}</p>
                                @endif
                                @if($p['rejected'] > 0)
                                    <p class="text-[10px] text-red-600">Ditolak: {{ $p['rejected'] }}</p>
                                @endif
                                @if($p['total'] == 0)
                                    <p class="text-[10px] text-slate-300">Belum ada</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center text-slate-400 text-sm">
                Tidak ada trainee yang sesuai.
            </div>
        @endforelse
    </div>
</x-app-layout>
