<x-app-layout>
    <x-slot name="title">Dashboard HSE CT - Persetujuan Final Evaluasi A2B</x-slot>

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs sm:text-sm font-bold shadow-xs">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 bg-gradient-to-r from-[#1e3a8a] via-[#1d4ed8] to-[#2563eb] p-5 sm:p-7 rounded-3xl shadow-lg text-white border border-blue-900/50 relative overflow-hidden">
        <div class="absolute right-0 top-0 -mt-10 -mr-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-400/20 text-blue-200 border border-blue-300/30 tracking-wider uppercase">
                        HSE Training Section
                    </span>
                    <span class="text-blue-200 text-xs">·</span>
                    <span class="text-blue-100 text-xs font-semibold">Tingkat Akhir Persetujuan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Persetujuan Final Evaluasi A2B</h1>
                <p class="text-blue-100/90 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                    Berikan persetujuan final (HSE CT) untuk mengesahkan evaluasi dan otomatis memindahkan trainee ke fase berikutnya.
                </p>
            </div>
            <div class="rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2.5 text-xs self-start md:self-auto">
                <p class="text-blue-200 text-[10px] font-medium uppercase">HSE CT Aktif</p>
                <p class="font-bold text-white mt-0.5 text-sm">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-blue-200/80">{{ auth()->user()->sid }}</p>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-blue-600">Menunggu HSE CT</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">⏳</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['pending'] ?? 0 }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Perlu pengesahan final untuk naik fase</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-600">Selesai & Naik Fase</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">🏆</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['completed'] ?? 0 }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Telah disahkan dan naik ke fase berikutnya</p>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-black text-slate-800 text-sm sm:text-base">Antrean Persetujuan Final HSE CT</h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-0.5">Daftar evaluasi yang telah disetujui PJO dan siap disahkan secara final.</p>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                {{ $evaluations->total() }} Total
            </span>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($evaluations as $ev)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between px-5 py-4 gap-3 hover:bg-blue-50/30 transition">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-extrabold text-slate-800">{{ $ev->nama_operator }}</p>
                            @if($ev->perusahaan)
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">{{ $ev->perusahaan }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-1">
                            Fase: <strong class="text-[#1e3a8a]">{{ format_phase_label($ev->phase, $ev->jenis_sertifikasi) }}</strong> · Jalur: {{ $ev->jenis_sertifikasi }} · Trainer: <span class="text-slate-700 font-medium">{{ $ev->trainer->name ?? '-' }}</span>
                        </p>
                    </div>
                    <a href="{{ route('hse-ct.final-evaluations.show', $ev->id) }}" class="px-4 py-2 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold shadow-sm transition inline-flex items-center justify-center gap-1.5 self-start sm:self-auto">
                        <span>🛡️</span> Review Final
                    </a>
                </div>
            @empty
                <div class="px-5 py-12 text-center text-xs text-slate-400">
                    <p class="text-2xl mb-1">🎉</p>
                    Tidak ada evaluasi yang sedang menunggu persetujuan HSE CT saat ini.
                </div>
            @endforelse
        </div>
        @if($evaluations->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $evaluations->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
