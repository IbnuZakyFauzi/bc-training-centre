<x-app-layout>
    <x-slot name="title">Trainer Review Queue & Monitoring</x-slot>

    <!-- Header Section -->
    <div class="mb-6 bg-gradient-to-r from-[#1e3a8a] via-[#1d4ed8] to-[#2563eb] p-5 sm:p-7 rounded-3xl shadow-lg text-white border border-blue-900/50 relative overflow-hidden">
        <div class="absolute right-0 top-0 -mt-10 -mr-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-400/20 text-blue-200 border border-blue-300/30 tracking-wider uppercase">
                        Trainer Module
                    </span>
                    <span class="text-blue-200 text-xs">·</span>
                    <span class="text-blue-100 text-xs font-semibold">Review & Monitoring</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Review & Evaluasi Kompetensi OJT</h1>
                <p class="text-blue-100/90 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                    Verifikasi logbook harian, evaluasi SOP teknis, dan monitor kesiapan evaluasi multi-fase peserta OJT.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <div class="rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2.5 text-xs">
                    <p class="text-blue-200 text-[10px] font-medium uppercase">Trainer Aktif</p>
                    <p class="font-bold text-white mt-0.5 text-sm">{{ $trainer->name }}</p>
                    <p class="text-[10px] text-blue-200/80">{{ $trainer->sid }} · {{ ucfirst($trainer->trainer_type ?? 'Trainer') }}</p>
                </div>
                <a href="{{ route('trainer.monitoring') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-[#f59e0b] hover:bg-amber-500 text-slate-900 text-xs font-extrabold shadow-md transition-all min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Monitoring Multi-Fase
                </a>
            </div>
        </div>
    </div>

    <!-- Status Cards: Logbook Reviews -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('trainer.reviews.index', ['status' => 'submitted']) }}" class="group block bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden {{ ($activeStatus ?? 'submitted') === 'submitted' ? 'ring-2 ring-[#2563eb] border-[#2563eb] bg-blue-50/20' : 'border-slate-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-blue-600">Menunggu Review</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">📝</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['submitted'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Form OJT masuk dari trainee</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-600 {{ ($activeStatus ?? 'submitted') === 'submitted' ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }} transition"></div>
        </a>

        <a href="{{ route('trainer.reviews.index', ['status' => 'verified']) }}" class="group block bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden {{ ($activeStatus ?? 'submitted') === 'verified' ? 'ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50/20' : 'border-slate-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-600">Sudah Diverifikasi</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">✓</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['verified'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Telah diverifikasi & dikirim ke Admin TC</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500 {{ ($activeStatus ?? 'submitted') === 'verified' ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }} transition"></div>
        </a>

        <a href="{{ route('trainer.reviews.index', ['status' => 'revision']) }}" class="group block bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden {{ ($activeStatus ?? 'submitted') === 'revision' ? 'ring-2 ring-amber-500 border-amber-500 bg-amber-50/20' : 'border-slate-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-amber-600">Dikembalikan Revisi</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">⚠️</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['revision'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Perlu perbaikan data / checklist</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-500 {{ ($activeStatus ?? 'submitted') === 'revision' ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }} transition"></div>
        </a>
    </div>

    <!-- Visual Analytics: Review & Evaluation Pipeline -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
        <!-- Chart 1: Status Antrean Review Logbook -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-1.5">
                        <span>🍩</span> Antrean Verifikasi Form OJT
                    </h3>
                    <span class="text-[10px] font-bold bg-blue-50 text-[#1e3a8a] px-2 py-0.5 rounded">Logbook Harian</span>
                </div>
                <p class="text-[11px] text-slate-400 mb-2">Sebaran status form OJT bimbingan Anda.</p>
                <div id="chart-trainer-queue" class="min-h-[170px]"></div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Total Form Tercatat:</span>
                <span class="font-extrabold text-[#1e3a8a]">{{ $counts['submitted'] + $counts['verified'] + $counts['revision'] }} Form</span>
            </div>
        </div>

        <!-- Chart 2: Pipeline Antrean Form Evaluasi A2B -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-1.5">
                        <span>📊</span> Status Persetujuan Form Evaluasi
                    </h3>
                    <span class="text-[10px] font-bold bg-purple-50 text-purple-700 px-2 py-0.5 rounded">Evaluasi A2B</span>
                </div>
                <p class="text-[11px] text-slate-400 mb-2">Status evaluasi multi-tahap yang Anda ajukan.</p>
                <div id="chart-trainer-eval-stages" class="min-h-[170px]"></div>
            </div>
            <div class="pt-3 border-t border-slate-100 grid grid-cols-4 gap-1 text-center text-[10px]">
                <div><span class="text-blue-600 font-bold block">{{ $evaluationCounts['submitted'] }}</span><span class="text-slate-400">Ke TC</span></div>
                <div><span class="text-purple-600 font-bold block">{{ $evaluationCounts['tc_approved'] }}</span><span class="text-slate-400">Ke PJO</span></div>
                <div><span class="text-amber-600 font-bold block">{{ $evaluationCounts['pjo_approved'] }}</span><span class="text-slate-400">Ke HSE</span></div>
                <div><span class="text-rose-600 font-bold block">{{ $evaluationCounts['rejected'] }}</span><span class="text-slate-400">Revisi</span></div>
            </div>
        </div>
    </div>

    <!-- Eligible Trainees for Final Evaluation (HM requirement met) -->
    @if(isset($eligibleTrainees) && $eligibleTrainees->isNotEmpty())
        <div class="bg-gradient-to-br from-emerald-500/10 via-white to-emerald-500/5 rounded-3xl border border-emerald-200 shadow-sm overflow-hidden mb-6 p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-emerald-100">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                        <h2 class="font-black text-emerald-950 text-base">Trainee Siap Evaluasi Akhir A2B (Eligible)</h2>
                    </div>
                    <p class="text-xs text-emerald-800 mt-0.5">Syarat akumulasi HM pada fase saat ini telah lengkap. Silakan lakukan evaluasi lapangan.</p>
                </div>
                <a href="{{ route('trainer.final-evaluations.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition inline-flex items-center justify-center gap-1.5 self-start sm:self-auto">
                    <span>✍️</span> Buat Formulir Evaluasi
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 pt-4">
                @foreach($eligibleTrainees as $t)
                    <div class="bg-white rounded-2xl border border-emerald-200/80 p-4 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <p class="text-xs font-extrabold text-slate-800 truncate">{{ $t['name'] }}</p>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $t['progress']['total'] }}% HM
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500">
                                {{ $t['certification'] === 'Green' ? 'Green' : ($t['certification'] === 'Skill-up' ? 'Skill-up' : ($t['certification'] === 'Experience_internal' ? 'Experience Internal' : ($t['certification'] === 'Experience_external' ? 'Experience External' : $t['certification']))) }} · <strong class="text-[#1e3a8a]">{{ $t['phase_label'] }}</strong>
                            </p>
                            @if(!empty($t['department']))
                                <p class="text-[10px] text-slate-400 mt-0.5">🏢 {{ $t['department'] }}</p>
                            @endif
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] text-emerald-700 font-bold">✓ Syarat HM Terpenuhi</span>
                            <a href="{{ route('trainer.final-evaluations.create', ['trainee' => $t['id']]) }}" class="px-3 py-1.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-[11px] font-bold shadow-sm transition">
                                Evaluasi →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Evaluation Queue for Trainer -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div>
                <h2 class="font-black text-slate-800 text-sm sm:text-base">Antrean Form Evaluasi OJT A2B</h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-0.5">Pantau status approval evaluasi akhir yang telah Anda buat.</p>
            </div>
            <!-- Quick Status Filter Pills -->
            <div class="flex flex-wrap gap-2">
                @foreach([
                    ['submitted', 'Menunggu TC', $evaluationCounts['submitted'] ?? 0, 'blue'],
                    ['tc_approved', 'Disetujui TC', $evaluationCounts['tc_approved'] ?? 0, 'purple'],
                    ['pjo_approved', 'Disetujui PJO', $evaluationCounts['pjo_approved'] ?? 0, 'amber'],
                    ['rejected', 'Perlu Revisi', $evaluationCounts['rejected'] ?? 0, 'rose'],
                ] as [$key, $label, $value, $color])
                    <a href="{{ route('trainer.reviews.index', array_merge(request()->query(), ['eval_status' => $key])) }}" class="px-3 py-1.5 rounded-xl border text-xs font-bold transition inline-flex items-center gap-1.5 {{ ($evaluationStatus ?? 'submitted') === $key ? 'bg-slate-800 text-white border-slate-800 shadow-sm' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                        <span>{{ $label }}</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ ($evaluationStatus ?? 'submitted') === $key ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $value }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Nama Operator & Perusahaan</th>
                        <th class="px-5 py-3">Fase Evaluasi</th>
                        <th class="px-5 py-3">Status Persetujuan</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($evaluationQueue as $evaluation)
                        <tr class="hover:bg-blue-50/30">
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-800">{{ $evaluation->nama_operator }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $evaluation->perusahaan }}</p>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600">
                                <span class="font-bold text-[#1e3a8a]">{{ format_phase_label($evaluation->phase, $evaluation->jenis_sertifikasi) }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($evaluation->status === 'submitted')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        ⏳ Menunggu Admin TC
                                    </span>
                                @elseif($evaluation->status === 'tc_approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                        ✓ Disetujui TC (Menunggu PJO)
                                    </span>
                                @elseif($evaluation->status === 'pjo_approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        ✓ Disetujui PJO (Menunggu HSE CT)
                                    </span>
                                @elseif($evaluation->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        ⚠️ Dikembalikan untuk Revisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700">
                                        {{ str_replace('_', ' ', ucfirst($evaluation->status)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    @if($evaluation->status === 'rejected')
                                        <a href="{{ route('trainer.final-evaluations.edit', $evaluation->id) }}" class="inline-flex px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold border border-amber-200 transition">
                                            Edit Revisi
                                        </a>
                                    @endif
                                    <a href="{{ route('trainer.final-evaluations.show', $evaluation->id) }}" class="inline-flex px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold transition">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-xs text-slate-400">Tidak ada form evaluasi pada kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($evaluationQueue->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $evaluationQueue->links() }}
            </div>
        @endif
    </div>

    <!-- Main Logbook Queue Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="font-black text-slate-800 text-sm sm:text-base">
                    {{ ($activeStatus ?? 'submitted') === 'verified' ? 'Form OJT yang Sudah Diverifikasi' : (($activeStatus ?? 'submitted') === 'revision' ? 'Form OJT yang Dikembalikan Revisi' : 'Antrean Form OJT Masuk') }}
                </h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">
                    {{ ($activeStatus ?? 'submitted') === 'verified' ? 'Riwayat form OJT yang sudah Anda verifikasi dan diteruskan ke Admin TC.' : (($activeStatus ?? 'submitted') === 'revision' ? 'Form OJT yang dikembalikan untuk revisi.' : 'Periksa kesesuaian jam operasi dan checklist SOP sebelum verifikasi.') }}
                </p>
            </div>
            <form class="flex flex-col sm:flex-row gap-2 flex-wrap items-stretch sm:items-center" method="GET">
                <input type="hidden" name="status" value="{{ $activeStatus ?? 'submitted' }}">
                <input name="search" value="{{ request('search') }}" placeholder="Cari SID atau no. form..." class="text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]">
                <select name="department" class="text-xs rounded-xl border-slate-300 min-h-[44px]">
                    <option value="">Semua Departemen</option>
                    @php
                        $trainerDeptOptions = collect(['CHCPP', 'RIM', 'HRGS'])->merge($departments ?? [])->filter()->unique()->values();
                    @endphp
                    @foreach($trainerDeptOptions as $d)
                        <option value="{{ $d }}" {{ request('department') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
                <button class="px-4 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold min-h-[44px] transition">Filter</button>
                @if(request()->hasAny(['search', 'department']))
                    <a href="{{ route('trainer.reviews.index', ['status' => $activeStatus ?? 'submitted']) }}" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold min-h-[44px] inline-flex items-center justify-center transition">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Form OJT / Trainee</th>
                        <th class="px-5 py-3">Departemen</th>
                        <th class="px-5 py-3">Unit & Shift</th>
                        <th class="px-5 py-3">Dikirim</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logbooks as $logbook)
                        <tr class="hover:bg-blue-50/30">
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-800">{{ $logbook->logbook_number }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $logbook->trainee->name ?? '-' }} · {{ $logbook->trainee->sid ?? '-' }}</p>
                                @if($logbook->is_locked ?? false)
                                    <p class="text-[10px] text-rose-600 font-bold mt-1 flex items-center gap-1">
                                        <span>🔒</span> Di-lock oleh logbook {{ $logbook->blocking_logbook->logbook_number ?? '' }} ({{ $logbook->blocking_logbook->date ? $logbook->blocking_logbook->date->format('d-m-Y') : '' }})
                                    </p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($logbook->trainee->department ?? false)
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                                        🏢 {{ $logbook->trainee->department }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs">
                                <p class="font-bold text-slate-700">{{ $logbook->unit_code }}</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ ucfirst($logbook->shift) }} · <span class="font-bold text-[#1e3a8a]">{{ $logbook->total_hm }} HM</span></p>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600">
                                {{ optional($logbook->submitted_at)->format('d M Y') ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                <x-badge :status="$logbook->status" />
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    @if($logbook->status === 'revision')
                                        <a href="{{ route('trainer.reviews.edit', $logbook->id) }}" class="inline-flex px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold border border-amber-200 transition">
                                            Edit Revisi
                                        </a>
                                    @endif
                                    <a href="{{ route('trainer.reviews.show', $logbook->id) }}" class="inline-flex px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold transition">
                                        {{ in_array($logbook->status, ['verified', 'final_approved']) ? 'Lihat Evaluasi' : ($logbook->status === 'revision' ? 'Lihat Detail' : 'Review & Nilai') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-xs text-slate-400">Tidak ada form OJT ditemukan pada kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $logbooks->links() }}
        </div>
    </div>

    <!-- Chart Script (ApexCharts) -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Apex = window.ApexCharts || ApexCharts;
            if (!Apex) return;

            // 1. Chart Donut Status Antrean Review Trainer
            const queueChart = new Apex(document.querySelector("#chart-trainer-queue"), {
                series: [{{ $counts['submitted'] }}, {{ $counts['verified'] }}, {{ $counts['revision'] }}],
                labels: ['Menunggu Review', 'Sudah Diverifikasi', 'Revisi'],
                chart: { type: 'donut', height: 160, fontFamily: 'inherit' },
                colors: ['#2563eb', '#10b981', '#f59e0b'],
                legend: { position: 'bottom', fontSize: '10px' },
                dataLabels: { enabled: true, formatter: (val) => Math.round(val) + '%' },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '60%',
                            labels: {
                                show: true,
                                total: { show: true, label: 'Total', fontSize: '11px', fontWeight: 700 }
                            }
                        }
                    }
                }
            });
            queueChart.render();

            // 2. Chart Bar Pipeline Status Evaluasi
            const evalChart = new Apex(document.querySelector("#chart-trainer-eval-stages"), {
                series: [{
                    name: 'Jumlah Form',
                    data: [
                        {{ $evaluationCounts['submitted'] }},
                        {{ $evaluationCounts['tc_approved'] }},
                        {{ $evaluationCounts['pjo_approved'] }},
                        {{ $evaluationCounts['rejected'] }}
                    ]
                }],
                chart: { type: 'bar', height: 160, toolbar: { show: false }, fontFamily: 'inherit' },
                colors: ['#2563eb', '#9333ea', '#f59e0b', '#ef4444'],
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 6,
                        columnWidth: '55%',
                        dataLabels: { position: 'top' }
                    }
                },
                xaxis: {
                    categories: ['1. TC', '2. PJO', '3. HSE CT', '⚠️ Revisi'],
                    labels: { style: { fontSize: '10px', fontWeight: 700 } }
                },
                legend: { show: false },
                dataLabels: { enabled: true, offsetY: -16, style: { fontSize: '11px', fontWeight: 700 } },
                grid: { strokeDashArray: 3 }
            });
            evalChart.render();
        });
    </script>
</x-app-layout>
