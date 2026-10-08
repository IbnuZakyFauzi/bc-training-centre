<x-app-layout>
    <x-slot name="title">Dashboard HSE CT - Persetujuan Final & Riwayat Evaluasi A2B</x-slot>

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs sm:text-sm font-bold shadow-xs flex items-center justify-between">
            <span class="flex items-center gap-2">✓ {{ session('success') }}</span>
        </div>
    @endif

    <!-- Header Hero Banner -->
    <div class="mb-6 bg-gradient-to-r from-[#1e3a8a] via-[#1d4ed8] to-[#2563eb] p-5 sm:p-7 rounded-3xl shadow-lg text-white border border-blue-900/50 relative overflow-hidden">
        <div class="absolute right-0 top-0 -mt-10 -mr-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-400/20 text-blue-200 border border-blue-300/30 tracking-wider uppercase">
                        HSE Training Section (HSE CT)
                    </span>
                    <span class="text-blue-200 text-xs">·</span>
                    <span class="text-blue-100 text-xs font-semibold">Tingkat Akhir Persetujuan</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Dashboard Finalisasi Evaluasi A2B</h1>
                <p class="text-blue-100/90 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                    Berikan persetujuan final (HSE CT) untuk mengesahkan kelayakan trainee, otomatis memindahkan ke fase berikutnya, dan memantau rekap performa evaluasi per trainer.
                </p>
            </div>
            <div class="rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2.5 text-xs self-start md:self-auto">
                <p class="text-blue-200 text-[10px] font-medium uppercase">HSE CT Aktif</p>
                <p class="font-bold text-white mt-0.5 text-sm">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-blue-200/80">{{ auth()->user()->sid }}</p>
            </div>
        </div>
    </div>

    <!-- Visual Approval Flow Timeline & Live Metrics -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-6 p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
            <div>
                <h3 class="text-sm sm:text-base font-black text-slate-800 flex items-center gap-2">
                    <span>🔄</span> Alur Persetujuan & Posisi HSE CT
                </h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Posisi peran HSE CT berada pada Tahap ke-4 (Tahap Final Pengesahan Kompetensi Trainee OJT A2B).</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-xl bg-emerald-50 text-emerald-800 text-xs font-black border border-emerald-200">
                    Total Disahkan Final: {{ $counts['total_history'] ?? 0 }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 relative">
            <!-- Step 1 -->
            <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Tahap 1</span>
                        <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 text-xs font-black flex items-center justify-center">1</span>
                    </div>
                    <p class="text-xs font-black text-slate-800">Trainer / Instruktur</p>
                    <p class="text-[11px] text-slate-500 mt-1">Pembuatan form & penilaian OJT.</p>
                </div>
                <div class="mt-3 pt-2.5 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">Inisiasi</span>
                    <span class="font-bold text-slate-700">Formulir OJT</span>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Tahap 2</span>
                        <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 text-xs font-black flex items-center justify-center">2</span>
                    </div>
                    <p class="text-xs font-black text-slate-800">Admin Training Centre</p>
                    <p class="text-[11px] text-slate-500 mt-1">Verifikasi administrasi & HM.</p>
                </div>
                <div class="mt-3 pt-2.5 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">Verifikasi</span>
                    <span class="font-bold text-slate-700">Tahap 1 TC</span>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="p-4 rounded-2xl bg-slate-50/80 border border-slate-200/80 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Tahap 3</span>
                        <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 text-xs font-black flex items-center justify-center">3</span>
                    </div>
                    <p class="text-xs font-black text-slate-800">PJO (Operasional)</p>
                    <p class="text-[11px] text-slate-500 mt-1">Persetujuan operasional lapangan.</p>
                </div>
                <div class="mt-3 pt-2.5 border-t border-slate-200/60 flex items-center justify-between text-[11px]">
                    <span class="text-slate-400">Persetujuan</span>
                    <span class="font-bold text-slate-700">Tahap 2 PJO</span>
                </div>
            </div>

            <!-- Step 4 (Active HSE CT) -->
            <div class="p-4 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50/70 border-2 border-emerald-600 flex flex-col justify-between shadow-sm relative">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-black text-emerald-800 uppercase tracking-wider">Tahap 4 · Final Aktif</span>
                        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-black flex items-center justify-center animate-pulse">4</span>
                    </div>
                    <p class="text-xs font-black text-emerald-900">HSE CT (Final)</p>
                    <p class="text-[11px] text-emerald-800/90 mt-1">Pengesahan resmi & perpindahan fase.</p>
                </div>
                <div class="mt-3 pt-2.5 border-t border-emerald-200 flex items-center justify-between text-[11px]">
                    <span class="font-extrabold text-emerald-800">Antrean Final:</span>
                    <span class="px-2 py-0.5 rounded-md bg-emerald-700 text-white font-black text-xs">{{ $counts['pending'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                    <span>🔍</span> Filter & Pencarian Evaluasi
                </h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Saring antrean ataupun history berdasarkan kriteria tertentu.</p>
            </div>
            @if(request()->hasAny(['search', 'trainer_id', 'department', 'certification']))
                <a href="{{ route('hse-ct.dashboard', ['tab' => $activeTab]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold transition self-start md:self-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset Filter
                </a>
            @endif
        </div>
        <form method="GET" action="{{ route('hse-ct.dashboard') }}" class="p-4 bg-slate-50/50 flex flex-col sm:flex-row gap-2.5 flex-wrap items-stretch sm:items-center">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <div class="flex-1 min-w-[200px]">
                <input name="search" value="{{ request('search') }}" placeholder="Cari nama trainee, SID, perusahaan..." class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px] bg-white">
            </div>
            <div class="w-full sm:w-auto min-w-[170px]">
                <select name="trainer_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px] bg-white">
                    <option value="">Semua Trainer</option>
                    @foreach($trainers as $tr)
                        <option value="{{ $tr->id }}" {{ request('trainer_id') == $tr->id ? 'selected' : '' }}>
                            {{ $tr->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto min-w-[150px]">
                <select name="department" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px] bg-white">
                    <option value="">Semua Departemen</option>
                    @php
                        $hseDeptOptions = collect(['CHCPP', 'RIM', 'HRGS'])->merge($departments ?? [])->filter()->unique()->values();
                    @endphp
                    @foreach($hseDeptOptions as $d)
                        <option value="{{ $d }}" {{ request('department') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto min-w-[160px]">
                <select name="certification" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px] bg-white">
                    <option value="">Semua Sertifikasi</option>
                    @foreach($certifications as $c)
                        <option value="{{ $c }}" {{ request('certification') === $c ? 'selected' : '' }}>
                            {{ $c === 'Green' ? 'Green' : ($c === 'Skill-up' ? 'Skill-up' : ($c === 'Experience_internal' ? 'Experience Internal' : ($c === 'Experience_external' ? 'Experience External' : $c))) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold min-h-[44px] transition shadow-sm inline-flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filter
            </button>
        </form>
    </div>

    <!-- Tab Switcher Navigation -->
    <div class="flex items-center gap-2 mb-4 border-b border-slate-200 pb-2">
        <a href="{{ route('hse-ct.dashboard', array_merge(request()->query(), ['tab' => 'pending'])) }}" class="px-4 py-2 rounded-xl text-xs font-black transition inline-flex items-center gap-2 {{ $activeTab !== 'history' ? 'bg-[#1e3a8a] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            <span>⏳ Antrean Menunggu HSE CT</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeTab !== 'history' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $evaluations->total() }}</span>
        </a>
        <a href="{{ route('hse-ct.dashboard', array_merge(request()->query(), ['tab' => 'history'])) }}" class="px-4 py-2 rounded-xl text-xs font-black transition inline-flex items-center gap-2 {{ $activeTab === 'history' ? 'bg-[#1e3a8a] text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
            <span>📜 Riwayat Evaluasi yang Telah Disahkan Final</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ $activeTab === 'history' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $historyEvaluations->total() }}</span>
        </a>
    </div>

    @if($activeTab !== 'history')
        <!-- Tab 1: Antrean Persetujuan Final HSE CT -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-black text-slate-800 text-sm sm:text-base">Antrean Persetujuan Final HSE CT</h2>
                    <p class="text-[10px] sm:text-xs text-slate-500 mt-0.5">Daftar evaluasi yang telah disetujui PJO dan siap disahkan secara final.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                    {{ $evaluations->total() }} Berkas
                </span>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($evaluations as $ev)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between px-5 py-4 gap-3 hover:bg-blue-50/30 transition">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-extrabold text-slate-800">{{ $ev->nama_operator }}</p>
                                @if($ev->trainee && $ev->trainee->department)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                                        🏢 {{ $ev->trainee->department }}
                                    </span>
                                @endif
                                @if($ev->perusahaan)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-600">{{ $ev->perusahaan }}</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                Fase: <strong class="text-[#1e3a8a]">{{ format_phase_label($ev->phase, $ev->jenis_sertifikasi) }}</strong> · Jalur: {{ $ev->jenis_sertifikasi }} · Trainer: <span class="text-slate-800 font-bold">{{ $ev->trainer->name ?? '-' }}</span>
                            </p>
                            @if($ev->pjo_approved_at)
                                <p class="text-[10px] text-slate-400 mt-0.5">Disetujui PJO: {{ \Carbon\Carbon::parse($ev->pjo_approved_at)->translatedFormat('d M Y, H:i') }}</p>
                            @endif
                        </div>
                        <a href="{{ route('hse-ct.final-evaluations.show', $ev->id) }}" class="px-4 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold shadow-sm transition inline-flex items-center justify-center gap-1.5 self-start sm:self-auto">
                            <span>🛡️</span> Review & Sahkan Final
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
                    {{ $evaluations->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Tab 2: Tabel History Persetujuan Final HSE CT -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-black text-slate-800 text-sm sm:text-base">Riwayat Evaluasi yang Telah Disahkan Final</h2>
                    <p class="text-[10px] sm:text-xs text-slate-500 mt-0.5">Daftar evaluasi kompetensi yang telah resmi disahkan oleh HSE CT (telah otomatis naik fase).</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    {{ $historyEvaluations->total() }} Selesai
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3.5">Nama Operator / Trainee</th>
                            <th class="px-5 py-3.5">Departemen & Perusahaan</th>
                            <th class="px-5 py-3.5">Fase & Sertifikasi</th>
                            <th class="px-5 py-3.5">Trainer Pembuat</th>
                            <th class="px-5 py-3.5">Tgl Disahkan HSE CT</th>
                            <th class="px-5 py-3.5">Status</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse($historyEvaluations as $he)
                            <tr class="hover:bg-blue-50/20 transition">
                                <td class="px-5 py-3.5">
                                    <p class="font-black text-slate-800">{{ $he->nama_operator }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $he->trainee->sid ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($he->trainee && $he->trainee->department)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200 mb-1">
                                            🏢 {{ $he->trainee->department }}
                                        </span>
                                    @endif
                                    <p class="text-[10px] text-slate-500">{{ $he->perusahaan ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-bold text-[#1e3a8a]">{{ format_phase_label($he->phase, $he->jenis_sertifikasi) }}</p>
                                    <span class="text-[10px] text-slate-400">{{ $he->jenis_sertifikasi }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-6 h-6 rounded-full bg-slate-100 text-[#1e3a8a] font-black text-[10px] flex items-center justify-center border border-slate-200">
                                            {{ strtoupper(substr($he->trainer->name ?? 'T', 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-slate-700">{{ $he->trainer->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">
                                    {{ $he->hse_approved_at ? \Carbon\Carbon::parse($he->hse_approved_at)->translatedFormat('d M Y, H:i') : '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span>✓</span> Disahkan Final
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('hse-ct.final-evaluations.show', $he->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                                            Detail
                                        </a>
                                        <a href="{{ route('final-evaluations.print', $he->id) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#1e3a8a] font-bold text-xs transition inline-flex items-center gap-1">
                                            <span>🖨️</span> Cetak
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-xs text-slate-400">
                                    Tidak ada data riwayat evaluasi yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($historyEvaluations->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $historyEvaluations->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    @endif
</x-app-layout>
