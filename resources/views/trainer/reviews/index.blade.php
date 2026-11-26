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
                <a href="{{ route('trainer.logbooks.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-extrabold shadow-md transition-all min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Input Logbook OJT
                </a>
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
                <span class="text-xs font-extrabold uppercase tracking-wider text-blue-600">Menunggu Persetujuan Trainee</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">📝</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['submitted'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Form diinput Trainer, menunggu approve Trainee</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-600 {{ ($activeStatus ?? 'submitted') === 'submitted' ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }} transition"></div>
        </a>

        <a href="{{ route('trainer.reviews.index', ['status' => 'verified']) }}" class="group block bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden {{ ($activeStatus ?? 'submitted') === 'verified' ? 'ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50/20' : 'border-slate-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-600">Disetujui Trainee / Antrean TC</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">✓</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['verified'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Telah disetujui Trainee & dalam antrean Admin TC</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500 {{ ($activeStatus ?? 'submitted') === 'verified' ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }} transition"></div>
        </a>

        <a href="{{ route('trainer.reviews.index', ['status' => 'revision']) }}" class="group block bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden {{ ($activeStatus ?? 'submitted') === 'revision' ? 'ring-2 ring-amber-500 border-amber-500 bg-amber-50/20' : 'border-slate-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-amber-600">Dikembalikan Revisi TC</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">⚠️</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['revision'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Perlu perbaikan catatan dari Admin TC</p>
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
                                    @if(($t['progress']['type'] ?? '') === 'bulanan')
                                        {{ $t['progress']['fraction'] ?? '4/4' }} Form ({{ $t['progress']['total'] }}%)
                                    @else
                                        {{ $t['progress']['total'] }}% HM
                                    @endif
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
                            <span class="text-[10px] text-emerald-700 font-bold">
                                @if(($t['progress']['type'] ?? '') === 'bulanan')
                                    ✓ Syarat 4 Form OJT Terpenuhi
                                @else
                                    ✓ Syarat HM Terpenuhi
                                @endif
                            </span>
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

    <!-- Main Logbook Queue Container with View Mode Toggle & Evidence Modal -->
    <div x-data="{
        viewMode: 'trainee_cards',
        evidenceModalOpen: false,
        modalUrl: '',
        modalTitle: '',
        openEvidence(url, title) {
            this.modalUrl = url;
            this.modalTitle = title;
            this.evidenceModalOpen = true;
        }
    }" class="space-y-6 mb-6">

        <!-- Header Card & View Mode Toggle -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="font-black text-slate-800 text-sm sm:text-base">
                        {{ ($activeStatus ?? 'submitted') === 'verified' ? 'Form OJT Disetujui Trainee / Antrean TC' : (($activeStatus ?? 'submitted') === 'revision' ? 'Form OJT Dikembalikan Revisi TC' : 'Form OJT Menunggu Persetujuan Trainee') }}
                    </h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-[#1e3a8a]">
                        {{ $groupedByTrainee->count() }} Trainee Bimbingan
                    </span>
                </div>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">
                    Profil Trainee beserta logbook yang menunggu persetujuan dan riwayat sebelumnya terekap per nama secara otomatis.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                <!-- View Mode Switcher -->
                <div class="inline-flex rounded-xl bg-slate-100 p-1 border border-slate-200 text-xs font-bold">
                    <button type="button"
                            @click="viewMode = 'trainee_cards'"
                            :class="viewMode === 'trainee_cards' ? 'bg-white text-[#1e3a8a] shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Rekap Profil Trainee
                    </button>
                    <button type="button"
                            @click="viewMode = 'table'"
                            :class="viewMode === 'table' ? 'bg-white text-[#1e3a8a] shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                            class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        Tabel Logbook
                    </button>
                </div>

                <!-- Filter Form -->
                <form class="flex flex-col sm:flex-row gap-2 flex-wrap items-stretch sm:items-center" method="GET">
                    <input type="hidden" name="status" value="{{ $activeStatus ?? 'submitted' }}">
                    <input name="search" value="{{ request('search') }}" placeholder="Cari SID atau nama..." class="text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[38px] px-3 py-1.5">
                    <select name="department" class="text-xs rounded-xl border-slate-300 min-h-[38px] px-3 py-1.5">
                        <option value="">Semua Departemen</option>
                        @php
                            $trainerDeptOptions = collect(['CHCPP', 'CHID', 'FPM', 'Logistik', 'RIM', 'HRGS'])->merge($departments ?? [])->filter()->unique()->values();
                        @endphp
                        @foreach($trainerDeptOptions as $d)
                            <option value="{{ $d }}" {{ request('department') === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                    <button class="px-3.5 py-1.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold min-h-[38px] transition shadow-xs">Filter</button>
                    @if(request()->hasAny(['search', 'department']))
                        <a href="{{ route('trainer.reviews.index', ['status' => $activeStatus ?? 'submitted']) }}" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold min-h-[38px] inline-flex items-center justify-center transition">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <!-- 1. MODE KARTU PROFIL TRAINEE (Dikelompokkan Per Nama Trainee) -->
        <div x-show="viewMode === 'trainee_cards'" class="space-y-5">
            @forelse($groupedByTrainee as $group)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition-all"
                     x-data="{ showHistory: false }">

                    <!-- Trainee Profile Card Header -->
                    <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-50/80 via-white to-blue-50/30 border-b border-slate-100">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            
                            <!-- Left: Trainee Avatar & Info -->
                            <div class="flex items-start sm:items-center gap-3.5">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#1e3a8a] to-[#2563eb] flex items-center justify-center text-white font-black text-sm shadow-md flex-shrink-0">
                                    {{ collect(explode(' ', $group['trainee']->name))->take(2)->map(fn($p)=>strtoupper(substr($p,0,1)))->implode('') }}
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-base font-extrabold text-slate-800">{{ $group['trainee']->name }}</h3>
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold border border-slate-200">
                                            SID: {{ $group['trainee']->sid }}
                                        </span>
                                        @if($group['trainee']->department)
                                            <span class="px-2 py-0.5 rounded-md bg-blue-50 text-[#1e3a8a] text-[10px] font-extrabold border border-blue-200 flex items-center gap-1">
                                                🏢 {{ $group['trainee']->department }}
                                            </span>
                                        @endif
                                        @if($group['trainee']->company)
                                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-semibold">
                                                {{ $group['trainee']->company }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 mt-1.5">
                                        <span>Sertifikasi: <strong class="text-slate-700">{{ $group['trainee']->certification ?? '-' }}</strong></span>
                                        <span>·</span>
                                        <span>Kategori Alat: <strong class="text-slate-700">{{ $group['trainee']->equipmentCategory->name ?? '-' }}</strong></span>
                                        <span>·</span>
                                        <span>Fase Saat Ini: <span class="font-extrabold text-[#1e3a8a] bg-blue-50 px-2 py-0.5 rounded">{{ $group['phase_label'] }}</span></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Badges Notifikasi Logbook Baru & Quick Action -->
                            <div class="flex flex-wrap items-center gap-2.5 self-start lg:self-auto">
                                @if($group['pending_count'] > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black bg-blue-100 text-[#1e3a8a] border border-blue-300 shadow-xs animate-pulse">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        {{ $group['pending_count'] }} Form Menunggu Approve
                                    </span>
                                @endif
                                @if($group['revision_count'] > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black bg-amber-50 text-amber-800 border border-amber-300">
                                        ⚠️ {{ $group['revision_count'] }} Perlu Revisi TC
                                    </span>
                                @endif
                                @if($group['verified_count'] > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ✓ {{ $group['verified_count'] }} Disetujui ({{ number_format($group['total_hm'], 1) }} HM)
                                    </span>
                                @endif

                                <a href="{{ route('trainer.logbooks.create', ['trainee_id' => $group['trainee']->id]) }}"
                                   class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition inline-flex items-center gap-1 shadow-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Input Logbook Baru
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Logbook yang Menunggu Persetujuan Trainee / Perlu Tindakan -->
                    @php
                        $activeLogs = ($activeStatus === 'revision') ? $group['revision_logbooks'] : (($activeStatus === 'verified') ? $group['verified_logbooks'] : $group['submitted_logbooks']);
                    @endphp
                    @if($activeLogs->count() > 0)
                        <div class="p-4 sm:p-5 bg-blue-50/20 border-b border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $activeStatus === 'revision' ? 'bg-amber-500' : 'bg-blue-600' }} animate-ping"></span>
                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider">
                                        {{ $activeStatus === 'revision' ? 'Form OJT Perlu Revisi' : ($activeStatus === 'verified' ? 'Form OJT Disetujui Trainee' : 'Form OJT Menunggu Persetujuan Trainee') }} ({{ $activeLogs->count() }})
                                    </h4>
                                </div>
                                <span class="text-[11px] text-slate-400 font-semibold">Bisa dibuka dan diedit kembali oleh Trainer</span>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-blue-200 bg-white">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-blue-50/60 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider border-b border-blue-100">
                                        <tr>
                                            <th class="py-2.5 px-3.5">Tanggal & Shift</th>
                                            <th class="py-2.5 px-3.5">No. Form & Unit</th>
                                            <th class="py-2.5 px-3.5 text-center">HM Total</th>
                                            <th class="py-2.5 px-3.5">Status</th>
                                            <th class="py-2.5 px-3.5 text-center">Bukti / Evidence</th>
                                            <th class="py-2.5 px-3.5 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($activeLogs as $alog)
                                            <tr class="hover:bg-blue-50/40 transition">
                                                <td class="py-3 px-3.5 whitespace-nowrap">
                                                    <span class="font-bold text-slate-800 block">
                                                        {{ \Carbon\Carbon::parse($alog->date)->translatedFormat('d M Y') }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-500 uppercase font-semibold">
                                                        Shift {{ ucfirst($alog->shift) }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3.5">
                                                    <span class="font-black text-slate-900 block">{{ $alog->logbook_number }}</span>
                                                    <span class="text-[11px] text-[#1e3a8a] font-semibold block">
                                                        Unit: {{ $alog->equipment_number ?? ($alog->equipment->unit_code ?? '-') }}
                                                        <span class="text-slate-400 font-normal">({{ $alog->equipmentCategory->name ?? '-' }})</span>
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                                    <span class="font-mono font-bold text-slate-800 text-xs block">
                                                        {{ number_format($alog->total_hm, 1) }} HM
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 font-mono">
                                                        {{ $alog->hm_start }} - {{ $alog->hm_end }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3.5 whitespace-nowrap">
                                                    <x-badge :status="$alog->status" />
                                                    @if($alog->status === 'revision' && $alog->revision_notes)
                                                        <p class="text-[10px] text-amber-800 font-semibold mt-1 max-w-[180px] truncate" title="{{ $alog->revision_notes }}">
                                                            Revisi: {{ $alog->revision_notes }}
                                                        </p>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                                    @php
                                                        $evidence = $alog->evidences->first();
                                                        $evidencePath = $evidence?->file_path ?? data_get($alog->sop_payload, 'meta.evidence_path');
                                                        $evidenceUrl = $evidencePath ? (str_starts_with($evidencePath, 'http') ? $evidencePath : asset('storage/' . $evidencePath)) : null;
                                                    @endphp
                                                    @if($evidenceUrl)
                                                        <button type="button"
                                                                @click="openEvidence('{{ $evidenceUrl }}', 'Bukti OJT {{ $alog->logbook_number }} - {{ $group['trainee']->name }}')"
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#1e3a8a] text-[11px] font-bold border border-blue-200 transition">
                                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            Lihat Bukti
                                                        </button>
                                                    @else
                                                        <span class="text-[11px] text-slate-300 italic">-</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-3.5 text-right whitespace-nowrap">
                                                    <div class="inline-flex items-center gap-1.5">
                                                        @if(in_array($alog->status, ['submitted', 'revision', 'draft']))
                                                            <a href="{{ route('trainer.reviews.edit', $alog->id) }}"
                                                               class="inline-flex items-center px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold border border-amber-300 transition shadow-xs">
                                                                <span>✏️</span> Buka & Edit
                                                            </a>
                                                        @endif
                                                        <a href="{{ route('trainer.reviews.show', $alog->id) }}"
                                                           class="inline-flex items-center px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-[#1e3a8a] text-xs font-bold transition">
                                                            Detail
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Riwayat Sebelumnya Terekap Per Nama (Collapsible Accordion) -->
                    <div class="p-4 sm:p-5 bg-slate-50/50">
                        <div class="flex items-center justify-between">
                            <button type="button"
                                    @click="showHistory = !showHistory"
                                    class="text-xs font-extrabold text-slate-700 hover:text-[#1e3a8a] transition inline-flex items-center gap-2">
                                <svg class="w-4 h-4 transition-transform duration-200" :class="showHistory ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                <span>Rekap Riwayat Sebelumnya: {{ $group['verified_count'] }} Form Disetujui ({{ number_format($group['total_hm'], 1) }} HM)</span>
                            </button>

                            <span class="text-[11px] text-slate-400">
                                Total: {{ $group['total_forms'] }} formulir bimbingan
                            </span>
                        </div>

                        <!-- Dropdown Riwayat Logbook Sebelumnya -->
                        <div x-show="showHistory" x-cloak class="mt-3.5 pt-3.5 border-t border-slate-200/80">
                            @if($group['verified_logbooks']->count() > 0)
                                <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                                    <table class="w-full text-left text-xs">
                                        <thead class="bg-slate-50 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider border-b border-slate-200">
                                            <tr>
                                                <th class="py-2 px-3">Tanggal</th>
                                                <th class="py-2 px-3">No. Form & Unit</th>
                                                <th class="py-2 px-3">Shift</th>
                                                <th class="py-2 px-3 text-center">HM</th>
                                                <th class="py-2 px-3">Status</th>
                                                <th class="py-2 px-3 text-right">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach($group['verified_logbooks'] as $vlog)
                                                <tr class="hover:bg-slate-50/60">
                                                    <td class="py-2.5 px-3 whitespace-nowrap font-medium text-slate-700">
                                                        {{ \Carbon\Carbon::parse($vlog->date)->format('d M Y') }}
                                                    </td>
                                                    <td class="py-2.5 px-3">
                                                        <span class="font-bold text-slate-800">{{ $vlog->logbook_number }}</span>
                                                        <span class="text-[10px] text-slate-400 block">{{ $vlog->equipment_number ?? ($vlog->equipment->unit_code ?? '-') }}</span>
                                                    </td>
                                                    <td class="py-2.5 px-3 text-[11px] text-slate-600 uppercase">
                                                        {{ ucfirst($vlog->shift) }}
                                                    </td>
                                                    <td class="py-2.5 px-3 text-center font-mono font-bold text-slate-700">
                                                        {{ number_format($vlog->total_hm, 1) }}
                                                    </td>
                                                    <td class="py-2.5 px-3">
                                                        <x-badge :status="$vlog->status" />
                                                    </td>
                                                    <td class="py-2.5 px-3 text-right whitespace-nowrap">
                                                        <a href="{{ route('trainer.reviews.show', $vlog->id) }}" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#1e3a8a] text-[11px] font-bold transition">
                                                            Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-xs text-slate-400 py-3 text-center">Belum ada riwayat dokumen yang disahkan sebelumnya untuk trainee ini.</p>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400">
                    <p class="text-sm font-bold text-slate-600">Tidak ada trainee bimbingan yang sesuai filter.</p>
                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau pilihan departemen.</p>
                </div>
            @endforelse
        </div>

        <!-- 2. MODE TABEL LOGBOOK (Alternatif Flat) -->
        <div x-show="viewMode === 'table'" x-cloak class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
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
                                    <div class="flex items-center gap-2">
                                        <p class="text-xs font-bold text-slate-800">{{ $logbook->logbook_number }}</p>
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                                            {{ $logbook->phase_label }}
                                        </span>
                                    </div>
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
                                        @if(in_array($logbook->status, ['submitted', 'revision', 'draft']))
                                            <a href="{{ route('trainer.reviews.edit', $logbook->id) }}" class="inline-flex px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold border border-amber-200 transition">
                                                {{ $logbook->status === 'submitted' ? 'Buka & Edit' : ($logbook->status === 'draft' ? 'Lanjutkan Draft' : 'Edit Revisi') }}
                                            </a>
                                        @endif
                                        <a href="{{ route('trainer.reviews.show', $logbook->id) }}" class="inline-flex px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold transition">
                                            Lihat Detail
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

        <!-- Evidence Modal -->
        <div x-show="evidenceModalOpen"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-xs flex items-center justify-center p-4"
             @keydown.escape.window="evidenceModalOpen = false">
            <div class="bg-white rounded-2xl max-w-2xl w-full overflow-hidden shadow-2xl border border-slate-200"
                 @click.away="evidenceModalOpen = false">
                <div class="px-5 py-3.5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-slate-800" x-text="modalTitle"></h3>
                    <button type="button" @click="evidenceModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg leading-none">
                        ✕
                    </button>
                </div>
                <div class="p-4 bg-slate-900 flex items-center justify-center max-h-[75vh] overflow-hidden">
                    <img :src="modalUrl" alt="Foto Evidence OJT" class="max-h-[70vh] max-w-full object-contain rounded-lg">
                </div>
                <div class="px-5 py-3 bg-white border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 text-[11px]">Foto bukti pelaksanaan OJT ini disimpan dalam sistem dan tidak ikut dicetak ke PDF.</span>
                    <a :href="modalUrl" target="_blank" download class="px-3 py-1.5 rounded-lg bg-[#1e3a8a] text-white font-bold hover:bg-blue-900 transition">
                        Unduh Foto
                    </a>
                </div>
            </div>
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
