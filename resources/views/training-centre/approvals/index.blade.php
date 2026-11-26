<x-app-layout>
    <x-slot name="title">Admin Training Centre - Dashboard Approval & Monitoring</x-slot>

    <!-- Header Section -->
    <div class="mb-6 bg-gradient-to-r from-[#1e3a8a] via-[#1d4ed8] to-[#2563eb] p-5 sm:p-7 rounded-3xl shadow-lg text-white border border-blue-900/50 relative overflow-hidden">
        <div class="absolute right-0 top-0 -mt-10 -mr-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-400/20 text-blue-200 border border-blue-300/30 tracking-wider uppercase">
                        Admin Training Centre
                    </span>
                    <span class="text-blue-200 text-xs">·</span>
                    <span class="text-blue-100 text-xs font-semibold">Approval & Monitoring Center</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Final Approval & Cetak Form OJT</h1>
                <p class="text-blue-100/90 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                    Persetujuan akhir dokumen logbook OJT, pemantauan alur evaluasi multi-fase, dan pengelolaan cetak/download resmi.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5">
                <div class="rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 px-4 py-2.5 text-xs">
                    <p class="text-blue-200 text-[10px] font-medium uppercase">Admin Aktif</p>
                    <p class="font-bold text-white mt-0.5 text-sm">{{ $reviewer->name }}</p>
                    <p class="text-[10px] text-blue-200/80">{{ $reviewer->sid }}</p>
                </div>
                <a href="{{ route('training-centre.monitoring') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-[#f59e0b] hover:bg-amber-500 text-slate-900 text-xs font-extrabold shadow-md transition-all min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Monitoring Multi-Fase
                </a>
                <a href="{{ route('training-centre.users.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold border border-white/20 transition min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Kelola Pengguna
                </a>
            </div>
        </div>
    </div>

    <!-- Status Filter Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('training-centre.approvals.index', ['status' => 'pending']) }}" class="group block bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden {{ ($activeStatus ?? 'pending') === 'pending' ? 'ring-2 ring-[#2563eb] border-[#2563eb] bg-blue-50/20' : 'border-slate-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-blue-600">Menunggu Final Approval</span>
                <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm font-bold">⏳</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['pending'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Form OJT siap diverifikasi Kabag TC</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-600 {{ ($activeStatus ?? 'pending') === 'pending' ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }} transition"></div>
        </a>

        <a href="{{ route('training-centre.approvals.index', ['status' => 'finalized']) }}" class="group block bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden {{ ($activeStatus ?? 'pending') === 'finalized' ? 'ring-2 ring-emerald-500 border-emerald-500 bg-emerald-50/20' : 'border-slate-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-600">Dokumen Final (Siap Cetak)</span>
                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm font-bold">🖨️</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['finalized'] }} <span class="text-xs font-normal text-slate-400">trainee</span></p>
            <p class="text-[11px] text-slate-400 mt-1">Telah disahkan & siap diunduh</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500 {{ ($activeStatus ?? 'pending') === 'finalized' ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }} transition"></div>
        </a>

        <a href="{{ route('training-centre.approvals.index', ['status' => 'revision']) }}" class="group block bg-white rounded-2xl border p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden {{ ($activeStatus ?? 'pending') === 'revision' ? 'ring-2 ring-amber-500 border-amber-500 bg-amber-50/20' : 'border-slate-200' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-amber-600">Dikembalikan Revisi</span>
                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm font-bold">⚠️</span>
            </div>
            <p class="mt-3 text-3xl font-black text-slate-800">{{ $counts['revision'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Dikembalikan ke trainer untuk perbaikan</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-500 {{ ($activeStatus ?? 'pending') === 'revision' ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }} transition"></div>
        </a>
    </div>

    <!-- Visual Approval Pipeline & Interactive Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
        <!-- 1. Interactive Pipeline Persetujuan Evaluasi -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-1.5">
                        <span>📊</span> Visualisasi Pipeline Approval
                    </h3>
                    <span class="text-[10px] font-bold bg-blue-50 text-[#1e3a8a] px-2 py-0.5 rounded">3 Tahap Approval</span>
                </div>
                <p class="text-[11px] text-slate-400 mb-2">Sebaran volume berkas pada setiap pos persetujuan.</p>
                
                <!-- Visual Pipeline Bar Chart -->
                <div id="chart-eval-pipeline" class="min-h-[170px]"></div>
            </div>

            <!-- Workflow Step Indicator -->
            <div class="pt-3 border-t border-slate-100 grid grid-cols-4 gap-1 text-center">
                <div class="p-1.5 rounded-xl bg-blue-50/80 border border-blue-100">
                    <span class="text-xs font-black text-blue-700 block">{{ $evalCounts['submitted'] }}</span>
                    <span class="text-[9px] font-bold text-blue-600 uppercase">1. Admin TC</span>
                </div>
                <div class="p-1.5 rounded-xl bg-purple-50/80 border border-purple-100">
                    <span class="text-xs font-black text-purple-700 block">{{ $evalCounts['tc_approved'] }}</span>
                    <span class="text-[9px] font-bold text-purple-600 uppercase">2. PJO</span>
                </div>
                <div class="p-1.5 rounded-xl bg-amber-50/80 border border-amber-100">
                    <span class="text-xs font-black text-amber-700 block">{{ $evalCounts['pjo_approved'] }}</span>
                    <span class="text-[9px] font-bold text-amber-600 uppercase">3. HSE CT</span>
                </div>
                <div class="p-1.5 rounded-xl bg-emerald-50/80 border border-emerald-100">
                    <span class="text-xs font-black text-emerald-700 block">{{ $evalCounts['completed'] }}</span>
                    <span class="text-[9px] font-bold text-emerald-600 uppercase">✓ Selesai</span>
                </div>
            </div>
        </div>

        <!-- 2. Visualisasi Status Antrean Logbook Harian (Donut Chart) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-1.5">
                        <span>🍩</span> Status Antrean Logbook
                    </h3>
                    <span class="text-[10px] font-bold bg-slate-100 text-slate-700 px-2 py-0.5 rounded">Form OJT</span>
                </div>
                <p class="text-[11px] text-slate-400 mb-2">Proporsi antrean verifikasi & dokumen disahkan.</p>
                <div id="chart-logbook-queue" class="min-h-[170px]"></div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Total Menunggu Aksi:</span>
                <span class="font-extrabold text-[#1e3a8a]">{{ $counts['pending'] + $counts['revision'] }} Berkas</span>
            </div>
        </div>

        <!-- 3. Rekap Posisi Fase Trainee (Real-time Progress) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-extrabold text-slate-800 flex items-center gap-1.5">
                        <span>🎯</span> Rekap Fase Trainee
                    </h3>
                    <a href="{{ route('training-centre.monitoring') }}" class="text-[11px] font-bold text-[#1e3a8a] hover:underline">Semua →</a>
                </div>
                <p class="text-[11px] text-slate-400 mb-2">Distribusi posisi fase saat ini seluruh trainee.</p>
                <div class="space-y-1.5 max-h-40 overflow-y-auto pr-1">
                    @forelse($phaseRecap as $label => $count)
                        <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100 text-xs hover:bg-blue-50/40 transition">
                            <span class="text-slate-700 font-medium truncate max-w-[190px]">{{ $label }}</span>
                            <span class="font-black text-[#1e3a8a] bg-blue-100/70 px-2 py-0.5 rounded-lg text-xs">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-6">Belum ada data trainee.</p>
                    @endforelse
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Total Variasi Fase:</span>
                <span class="font-bold text-slate-800">{{ $phaseRecap->count() }} Kategori</span>
            </div>
        </div>
    </div>

    <!-- Antrean Persetujuan Evaluasi Cepat (Live Tracker Card) -->
    @if($pendingEvaluations->isNotEmpty())
        <div class="bg-gradient-to-r from-blue-900/5 via-indigo-900/5 to-white rounded-3xl border border-blue-200/80 p-5 shadow-sm mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-3 border-b border-blue-100">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-600 animate-ping"></span>
                    <h2 class="font-black text-slate-900 text-sm sm:text-base">Antrean Evaluasi Menunggu Persetujuan Anda (Admin TC)</h2>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold bg-blue-600 text-white self-start sm:self-auto">
                    {{ $pendingEvaluations->count() }} Berkas Menunggu
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 pt-3">
                @foreach($pendingEvaluations as $ev)
                    <div class="bg-white rounded-2xl border border-blue-100 p-4 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <p class="text-xs font-black text-slate-800 truncate">{{ $ev->nama_operator }}</p>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    Tahap 1: TC
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500">
                                {{ format_phase_label($ev->phase, $ev->jenis_sertifikasi) }} · Trainer: <span class="font-semibold text-slate-700">{{ $ev->trainer->name ?? '-' }}</span>
                            </p>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] text-blue-600 font-bold">Siap diverifikasi</span>
                            <a href="{{ route('training-centre.final-evaluations.show', $ev->id) }}" class="px-3 py-1.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-[11px] font-bold shadow-sm transition">
                                Review & Approve →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Main Content Container with View Mode Toggle & Evidence Modal -->
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

        <!-- Main Card Section Header -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="font-black text-slate-800 text-sm sm:text-base">
                        {{ ($activeStatus ?? 'pending') === 'finalized' ? 'Dokumen Final Disahkan (Siap Cetak)' : (($activeStatus ?? 'pending') === 'revision' ? 'Form OJT Dikembalikan Revisi' : 'Antrean Form OJT Menunggu Final Approval') }}
                    </h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-[#1e3a8a]">
                        {{ $groupedByTrainee->count() }} Trainee
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

                <!-- Search & Filter Form -->
                <form class="flex flex-col sm:flex-row gap-2 flex-wrap items-stretch sm:items-center" method="GET">
                    <input type="hidden" name="status" value="{{ $activeStatus ?? 'pending' }}">
                    <input name="search" value="{{ request('search') }}" placeholder="Cari SID, nama, unit..." class="text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[38px] px-3 py-1.5">
                    <select name="department" class="text-xs rounded-xl border-slate-300 min-h-[38px] px-3 py-1.5">
                        <option value="">Semua Departemen</option>
                        @php
                            $tcDeptOptions = collect(['CHCPP', 'CHID', 'FPM', 'Logistik', 'RIM', 'HRGS'])->merge($departments ?? [])->filter()->unique()->values();
                        @endphp
                        @foreach($tcDeptOptions as $d)
                            <option value="{{ $d }}" {{ request('department') === $d ? 'selected' : '' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                    <button class="px-3.5 py-1.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold min-h-[38px] transition shadow-xs">Filter</button>
                    @if(request()->hasAny(['search', 'department']))
                        <a href="{{ route('training-centre.approvals.index', ['status' => $activeStatus ?? 'pending']) }}" class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold min-h-[38px] inline-flex items-center justify-center transition">Reset</a>
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
                            
                            <!-- Left: Trainee Info & Avatar -->
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
                                        @if($group['trainer'])
                                            <span>·</span>
                                            <span>Instruktur: <span class="font-semibold text-slate-700">{{ $group['trainer']->name }}</span></span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Badges Notifikasi Logbook Baru & Quick Actions -->
                            <div class="flex flex-wrap items-center gap-2.5 self-start lg:self-auto">
                                @if($group['total_pending'] > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black bg-blue-100 text-[#1e3a8a] border border-blue-300 shadow-xs animate-pulse">
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                        {{ $group['total_pending'] }} Logbook Menunggu Approval
                                    </span>
                                @endif
                                @if($group['revision_count'] > 0)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-black bg-amber-50 text-amber-800 border border-amber-300">
                                        ⚠️ {{ $group['revision_count'] }} Revisi
                                    </span>
                                @endif
                                @if($group['finalized_count'] > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ✓ {{ $group['finalized_count'] }} Disahkan ({{ number_format($group['total_hm'], 1) }} HM)
                                    </span>
                                @endif

                                <a href="{{ route('training-centre.trainee-documents', $group['trainee']->id) }}"
                                   class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition inline-flex items-center gap-1 shadow-xs">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Buka Dokumen
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Logbook Menunggu Approval TC (Jika Ada) -->
                    @if($group['pending_logbooks']->count() > 0)
                        <div class="p-4 sm:p-5 bg-blue-50/20 border-b border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-ping"></span>
                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider">
                                        Form OJT Menunggu Pengesahan Anda ({{ $group['pending_logbooks']->count() }})
                                    </h4>
                                </div>
                                <span class="text-[11px] text-blue-700 font-semibold">Telah disetujui Trainee</span>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-blue-200 bg-white">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-blue-50/60 text-slate-600 font-extrabold uppercase text-[10px] tracking-wider border-b border-blue-100">
                                        <tr>
                                            <th class="py-2.5 px-3.5">Tanggal & Shift</th>
                                            <th class="py-2.5 px-3.5">No. Form & Unit</th>
                                            <th class="py-2.5 px-3.5">Trainer</th>
                                            <th class="py-2.5 px-3.5 text-center">HM Total</th>
                                            <th class="py-2.5 px-3.5 text-center">Bukti / Evidence</th>
                                            <th class="py-2.5 px-3.5 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($group['pending_logbooks'] as $plog)
                                            <tr class="hover:bg-blue-50/40 transition">
                                                <td class="py-3 px-3.5 whitespace-nowrap">
                                                    <span class="font-bold text-slate-800 block">
                                                        {{ \Carbon\Carbon::parse($plog->date)->translatedFormat('d M Y') }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-500 uppercase font-semibold">
                                                        Shift {{ ucfirst($plog->shift) }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3.5">
                                                    <span class="font-black text-slate-900 block">{{ $plog->logbook_number }}</span>
                                                    <span class="text-[11px] text-[#1e3a8a] font-semibold block">
                                                        Unit: {{ $plog->equipment_number ?? ($plog->equipment->unit_code ?? '-') }}
                                                        <span class="text-slate-400 font-normal">({{ $plog->equipmentCategory->name ?? '-' }})</span>
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3.5">
                                                    <span class="font-semibold text-slate-800">{{ $plog->trainer->name ?? '-' }}</span>
                                                </td>
                                                <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                                    <span class="font-mono font-bold text-slate-800 text-xs block">
                                                        {{ number_format($plog->total_hm, 1) }} HM
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 font-mono">
                                                        {{ $plog->hm_start }} - {{ $plog->hm_end }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                                    @php
                                                        $evidence = $plog->evidences->first();
                                                        $evidencePath = $evidence?->file_path ?? data_get($plog->sop_payload, 'meta.evidence_path');
                                                        $evidenceUrl = $evidencePath ? (str_starts_with($evidencePath, 'http') ? $evidencePath : asset('storage/' . $evidencePath)) : null;
                                                    @endphp
                                                    @if($evidenceUrl)
                                                        <button type="button"
                                                                @click="openEvidence('{{ $evidenceUrl }}', 'Bukti OJT {{ $plog->logbook_number }} - {{ $group['trainee']->name }}')"
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#1e3a8a] text-[11px] font-bold border border-blue-200 transition">
                                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            Lihat Bukti
                                                        </button>
                                                    @else
                                                        <span class="text-[11px] text-slate-300 italic">-</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 px-3.5 text-right whitespace-nowrap">
                                                    <a href="{{ route('training-centre.approvals.show', $plog->id) }}"
                                                       class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold transition shadow-xs">
                                                        <span>✍️</span> Review & Sahkan
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Evaluasi Final Menunggu Approval (Jika Ada) -->
                    @if($group['pending_evaluations']->count() > 0)
                        <div class="p-4 sm:p-5 bg-purple-50/20 border-b border-slate-100">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-xs sm:text-sm font-extrabold text-purple-900 uppercase tracking-wider flex items-center gap-2">
                                    <span>📋</span> Evaluasi Akhir A2B Menunggu Persetujuan TC ({{ $group['pending_evaluations']->count() }})
                                </h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($group['pending_evaluations'] as $peval)
                                    <div class="p-3.5 bg-white rounded-xl border border-purple-200 flex items-center justify-between">
                                        <div>
                                            <p class="font-bold text-slate-800 text-xs">{{ format_phase_label($peval->phase, $peval->jenis_sertifikasi) }}</p>
                                            <p class="text-[11px] text-slate-500 mt-0.5">Trainer: {{ $peval->trainer->name ?? '-' }}</p>
                                        </div>
                                        <a href="{{ route('training-centre.final-evaluations.show', $peval->id) }}" class="px-3 py-1.5 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-bold transition">
                                            Review Evaluasi →
                                        </a>
                                    </div>
                                @endforeach
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
                                <span>Rekap Riwayat Sebelumnya: {{ $group['finalized_count'] }} Form Disahkan ({{ number_format($group['total_hm'], 1) }} HM)</span>
                            </button>

                            <span class="text-[11px] text-slate-400">
                                Total: {{ $group['total_all_forms'] }} formulir tercatat
                            </span>
                        </div>

                        <!-- Dropdown Content Riwayat Sebelumnya -->
                        <div x-show="showHistory" x-cloak class="mt-3.5 pt-3.5 border-t border-slate-200/80">
                            @if($group['finalized_logbooks']->count() > 0)
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
                                            @foreach($group['finalized_logbooks'] as $flog)
                                                <tr class="hover:bg-slate-50/60">
                                                    <td class="py-2.5 px-3 whitespace-nowrap font-medium text-slate-700">
                                                        {{ \Carbon\Carbon::parse($flog->date)->format('d M Y') }}
                                                    </td>
                                                    <td class="py-2.5 px-3">
                                                        <span class="font-bold text-slate-800">{{ $flog->logbook_number }}</span>
                                                        <span class="text-[10px] text-slate-400 block">{{ $flog->equipment_number ?? ($flog->equipment->unit_code ?? '-') }}</span>
                                                    </td>
                                                    <td class="py-2.5 px-3 text-[11px] text-slate-600 uppercase">
                                                        {{ ucfirst($flog->shift) }}
                                                    </td>
                                                    <td class="py-2.5 px-3 text-center font-mono font-bold text-slate-700">
                                                        {{ number_format($flog->total_hm, 1) }}
                                                    </td>
                                                    <td class="py-2.5 px-3">
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                            ✓ Disahkan
                                                        </span>
                                                    </td>
                                                    <td class="py-2.5 px-3 text-right whitespace-nowrap">
                                                        <a href="{{ route('ojt.logbooks.print', $flog->id) }}" target="_blank" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-bold transition inline-flex items-center gap-1">
                                                            <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                            Cetak
                                                        </a>
                                                        <a href="{{ route('training-centre.approvals.show', $flog->id) }}" class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#1e3a8a] text-[11px] font-bold transition">
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
                    <p class="text-sm font-bold text-slate-600">Tidak ada data trainee yang sesuai filter.</p>
                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau pilihan departemen.</p>
                </div>
            @endforelse
        </div>

        <!-- 2. MODE TABEL LOGBOOK RINCI (Alternatif) -->
        <div x-show="viewMode === 'table'" x-cloak class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Form OJT / Trainee</th>
                            <th class="px-5 py-3">Departemen</th>
                            <th class="px-5 py-3">Trainer</th>
                            <th class="px-5 py-3">Status / Tanggal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logbooks as $logbook)
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-bold text-slate-800">{{ $logbook->logbook_number }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $logbook->trainee->name ?? '-' }} · {{ $logbook->trainee->sid ?? '-' }}</p>
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
                                <td class="px-5 py-4 text-xs text-slate-600">{{ $logbook->trainer->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <x-badge :status="$logbook->status" />
                                    <span class="block text-[10px] text-slate-400 mt-1">
                                        {{ optional($logbook->training_centre_decided_at ?? $logbook->verified_at)->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('training-centre.approvals.show', $logbook->id) }}" class="inline-flex px-3 py-2 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold transition">
                                            Detail
                                        </a>
                                        @if($logbook->status === 'final_approved')
                                            <a href="{{ route('ojt.logbooks.print', $logbook->id) }}" target="_blank" class="inline-flex items-center px-3 py-2 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold transition" title="Cetak / Download PDF Form OJT">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                Cetak
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada logbook ditemukan pada kategori ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logbooks->hasPages())
                <div class="p-5 border-t border-slate-100">{{ $logbooks->links() }}</div>
            @endif
        </div>

        <!-- Evidence Preview Modal -->
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
                    <span class="text-slate-500 text-[11px]">Foto bukti kegiatan OJT ini disimpan di server dan tidak ikut dicetak ke PDF.</span>
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

            // 1. Chart Pipeline Persetujuan Evaluasi (Bar Column)
            const pipelineChart = new Apex(document.querySelector("#chart-eval-pipeline"), {
                series: [{
                    name: 'Jumlah Form',
                    data: [
                        {{ $evalCounts['submitted'] }},
                        {{ $evalCounts['tc_approved'] }},
                        {{ $evalCounts['pjo_approved'] }},
                        {{ $evalCounts['completed'] }}
                    ]
                }],
                chart: { type: 'bar', height: 160, toolbar: { show: false }, fontFamily: 'inherit' },
                colors: ['#2563eb', '#9333ea', '#f59e0b', '#10b981'],
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 6,
                        columnWidth: '55%',
                        dataLabels: { position: 'top' }
                    }
                },
                xaxis: {
                    categories: ['1. TC', '2. PJO', '3. HSE CT', '✓ Selesai'],
                    labels: { style: { fontSize: '10px', fontWeight: 700 } }
                },
                legend: { show: false },
                dataLabels: { enabled: true, offsetY: -16, style: { fontSize: '11px', fontWeight: 700 } },
                grid: { strokeDashArray: 3 }
            });
            pipelineChart.render();

            // 2. Chart Donut Status Antrean Logbook
            const queueChart = new Apex(document.querySelector("#chart-logbook-queue"), {
                series: [{{ $counts['pending'] }}, {{ $counts['finalized'] }}, {{ $counts['revision'] }}],
                labels: ['Menunggu TC', 'Dokumen Final', 'Revisi'],
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
        });
    </script>
</x-app-layout>
