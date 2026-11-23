<x-app-layout>
    <x-slot name="title">Riwayat Pengajuan Form OJT Per Evaluasi</x-slot>

    <!-- Top Banner -->
    <div class="mb-4 sm:mb-6 bg-gradient-to-r from-[#1e3a8a] to-[#2563eb] p-4 sm:p-6 rounded-2xl shadow-md text-white border border-blue-900">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 text-blue-200 text-[10px] sm:text-xs font-semibold uppercase tracking-wider mb-1">
                    <span>DIGITAL OJT FORM</span>
                    <span>•</span>
                    <span>MY SUBMISSION HISTORY</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-white">
                    Riwayat Form OJT: {{ $user->name }}
                </h1>
                <div class="flex flex-wrap items-center gap-2 mt-2 text-xs text-blue-100">
                    <span class="px-2 py-0.5 rounded bg-white/10 text-white font-mono font-semibold">SID: {{ $user->sid }}</span>
                    <span>•</span>
                    <span>Sertifikasi: <strong class="text-white">{{ $user->certification === 'Green' ? 'Green' : ($user->certification === 'Skill-up' ? 'Skill-up' : ($user->certification === 'Experience_internal' ? 'Experience Internal' : ($user->certification === 'Experience_external' ? 'Experience External' : ($user->certification ?? '-')))) }}</strong></span>
                    <span>•</span>
                    <span>Fase Saat Ini: <strong class="bg-amber-400 text-slate-900 px-2 py-0.5 rounded font-extrabold">{{ ucwords(str_replace('_', ' ', $currentPhaseKey)) }}</strong></span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('ojt.logbooks.index') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-bold transition shadow-xs">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    Direktori Lengkap
                </a>
                <a href="{{ route('ojt.dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white text-[#1e3a8a] hover:bg-blue-50 text-xs font-bold transition shadow-xs">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-6">
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl shadow-sm border border-slate-200">
            <span class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Form OJT</span>
            <span class="text-xl sm:text-2xl font-black text-slate-800 mt-1 block">{{ $stats['total_form'] }} <span class="text-xs font-normal text-slate-400">form</span></span>
        </div>
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl shadow-sm border border-slate-200">
            <span class="text-[10px] sm:text-xs font-bold text-blue-600 uppercase tracking-wider block">Total Jam Operasional</span>
            <span class="text-xl sm:text-2xl font-black text-blue-800 mt-1 block">{{ number_format($stats['total_hm'], 1) }} <span class="text-xs font-normal text-slate-400">HM</span></span>
        </div>
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl shadow-sm border border-blue-200 bg-blue-50/30">
            <span class="text-[10px] sm:text-xs font-bold text-blue-700 uppercase tracking-wider block">Menunggu Persetujuan</span>
            <span class="text-xl sm:text-2xl font-black text-blue-700 mt-1 block">{{ $stats['submitted'] }} <span class="text-xs font-normal text-blue-500">form</span></span>
        </div>
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl shadow-sm border {{ $stats['revision'] > 0 ? 'border-amber-300 ring-2 ring-amber-100' : 'border-slate-200' }}">
            <span class="text-[10px] sm:text-xs font-bold text-amber-600 uppercase tracking-wider block">Catatan / Revisi</span>
            <span class="text-xl sm:text-2xl font-black text-amber-700 mt-1 block">{{ $stats['revision'] }} <span class="text-xs font-normal text-amber-500">form</span></span>
        </div>
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl shadow-sm border border-slate-200">
            <span class="text-[10px] sm:text-xs font-bold text-emerald-600 uppercase tracking-wider block">Disetujui</span>
            <span class="text-xl sm:text-2xl font-black text-emerald-700 mt-1 block">{{ $stats['approved'] }} <span class="text-xs font-normal text-emerald-500">form</span></span>
        </div>
    </div>

    <!-- Main Container with Alpine State for Phase Filter & Modal -->
    <div x-data="{
        activePhase: 'all',
        evidenceModalOpen: false,
        evidenceModalUrl: '',
        evidenceModalTitle: '',
        openEvidence(url, title) {
            this.evidenceModalUrl = url;
            this.evidenceModalTitle = title;
            this.evidenceModalOpen = true;
        }
    }">

        <!-- Filter & Search Controls -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4 pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider">
                        Rangkaian Fase Evaluasi OJT
                    </h2>
                    <p class="text-[11px] text-slate-400 mt-0.5">Pilih salah satu fase evaluasi untuk memfilter riwayat logbook, atau lihat semua rangkaian.</p>
                </div>
                <form action="{{ route('ojt.history') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, unit, trainer..." class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:bg-white w-48 sm:w-64">
                    <button type="submit" class="px-3 py-1.5 bg-[#1e3a8a] hover:bg-blue-900 text-white font-bold text-xs rounded-xl shadow-xs transition">
                        Cari
                    </button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('ojt.history') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- Phase Tabs / Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                <button type="button"
                        @click="activePhase = 'all'"
                        :class="activePhase === 'all' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap flex items-center gap-2">
                    <span>Semua Evaluasi</span>
                    <span :class="activePhase === 'all' ? 'bg-blue-800 text-blue-100' : 'bg-white text-slate-700'" class="px-2 py-0.5 rounded-full text-[10px]">
                        {{ $allLogbooks->count() }}
                    </span>
                </button>

                @foreach($phaseGroups as $grp)
                    <button type="button"
                            @click="activePhase = '{{ $grp['key'] }}'"
                            :class="activePhase === '{{ $grp['key'] }}' ? 'bg-[#2563eb] text-white shadow-xs ring-2 ring-blue-300' : 'bg-slate-50 text-slate-700 border border-slate-200 hover:bg-slate-100'"
                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap flex items-center gap-2">
                        <span>{{ $grp['label'] }}</span>
                        @if($grp['is_current'])
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        @endif
                        <span :class="activePhase === '{{ $grp['key'] }}' ? 'bg-blue-800 text-blue-100' : 'bg-slate-200/80 text-slate-700'" class="px-2 py-0.5 rounded-full text-[10px] font-mono">
                            {{ $grp['count'] }} form
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Layout Grid: Left (Grouped Form History), Right (Audit Trail) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left 2 Columns: Grouped Phase Containers -->
            <div class="lg:col-span-2 space-y-6">

                @forelse($phaseGroups as $grp)
                    <div x-show="activePhase === 'all' || activePhase === '{{ $grp['key'] }}'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

                        <!-- Phase Header Card -->
                        <div class="p-4 sm:p-5 bg-gradient-to-r {{ $grp['is_current'] ? 'from-blue-50/80 via-white to-slate-50' : 'from-slate-50 via-white to-slate-50/60' }} border-b border-slate-200">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl {{ $grp['is_current'] ? 'bg-[#1e3a8a] text-white' : 'bg-slate-200 text-slate-700' }} flex items-center justify-center font-black text-sm shadow-xs flex-shrink-0">
                                        {{ substr(str_replace('Evaluasi ', 'E', $grp['label']), 0, 3) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-base font-extrabold text-slate-800">{{ $grp['label'] }}</h3>
                                            @if($grp['is_current'])
                                                <span class="px-2.5 py-0.5 rounded-md bg-blue-100 text-[#1e3a8a] text-[10px] font-black uppercase tracking-wider border border-blue-200">
                                                    Sedang Berjalan
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-[11px] text-slate-500 mt-0.5">
                                            @if($grp['type'] === 'bulanan')
                                                Target: Minimal 4 Form OJT (1 form/minggu) · Terkirim: <strong class="text-slate-800">{{ $grp['count'] }} Form</strong>
                                            @else
                                                Target: {{ $grp['target_total_hm'] ?? '-' }} Jam HM · Akumulasi: <strong class="text-slate-800">{{ number_format($grp['total_hm'], 1) }} HM</strong> (Siang: {{ number_format($grp['day_hm'], 1) }}h | Malam: {{ number_format($grp['night_hm'], 1) }}h)
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-start sm:self-auto">
                                    <div class="text-right">
                                        <span class="text-xs font-black text-[#1e3a8a]">{{ $grp['percent'] }}% Terpenuhi</span>
                                        <div class="w-24 sm:w-32 h-2 bg-slate-200 rounded-full overflow-hidden mt-1">
                                            <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full" style="width: {{ $grp['percent'] }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table of Logbooks for this Phase -->
                        @if($grp['logbooks']->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                                            <th class="py-3 px-4">Tanggal & Shift</th>
                                            <th class="py-3 px-4">No. Form & Unit</th>
                                            <th class="py-3 px-4">Trainer</th>
                                            <th class="py-3 px-4 text-center">HM Total</th>
                                            <th class="py-3 px-4">Status</th>
                                            <th class="py-3 px-4 text-center">Bukti</th>
                                            <th class="py-3 px-4 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-xs">
                                        @foreach($grp['logbooks'] as $log)
                                            <tr class="hover:bg-slate-50/80 transition">
                                                <!-- Tanggal & Shift -->
                                                <td class="py-3.5 px-4 whitespace-nowrap">
                                                    <span class="font-bold text-slate-800 block">
                                                        {{ \Carbon\Carbon::parse($log->date)->translatedFormat('d M Y') }}
                                                    </span>
                                                    <span class="inline-flex items-center text-[10px] font-semibold text-slate-500 uppercase mt-0.5">
                                                        Shift {{ ucfirst($log->shift) }}
                                                    </span>
                                                </td>

                                                <!-- No. Form & Unit Alat -->
                                                <td class="py-3.5 px-4">
                                                    <a href="{{ route('ojt.logbooks.show', $log->id) }}" class="font-extrabold text-slate-900 hover:text-blue-600 transition block">
                                                        {{ $log->logbook_number }}
                                                    </a>
                                                    <span class="text-[11px] text-[#1e3a8a] font-semibold block mt-0.5">
                                                        Unit: {{ $log->equipment_number ?? ($log->equipment->unit_code ?? '-') }}
                                                        <span class="text-slate-400 font-normal">({{ $log->equipmentCategory->name ?? '-' }})</span>
                                                    </span>
                                                </td>

                                                <!-- Trainer -->
                                                <td class="py-3.5 px-4">
                                                    <span class="font-semibold text-slate-800 block">{{ $log->trainer->name ?? '-' }}</span>
                                                    <span class="text-[10px] text-slate-400 block">{{ $log->trainer->sid ?? 'Trainer' }}</span>
                                                </td>

                                                <!-- HM Total -->
                                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                                    <span class="font-mono font-bold text-slate-800 text-xs block">
                                                        {{ number_format($log->total_hm, 1) }} HM
                                                    </span>
                                                    <span class="text-[10px] text-slate-400 block font-mono">
                                                        {{ $log->hm_start }} - {{ $log->hm_end }}
                                                    </span>
                                                </td>

                                                <!-- Status -->
                                                <td class="py-3.5 px-4 whitespace-nowrap">
                                                    <x-badge :status="$log->status" />
                                                    @if($log->status === 'revision' && $log->revision_notes)
                                                        <p class="text-[10px] text-amber-700 font-semibold mt-1 max-w-[150px] truncate" title="{{ $log->revision_notes }}">
                                                            Revisi: {{ $log->revision_notes }}
                                                        </p>
                                                    @endif
                                                </td>

                                                <!-- Evidence / Bukti Thumbnail -->
                                                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                                    @php
                                                        $evidence = $log->evidences->first();
                                                        $evidencePath = $evidence?->file_path ?? data_get($log->sop_payload, 'meta.evidence_path');
                                                        $evidenceUrl = $evidencePath ? (str_starts_with($evidencePath, 'http') ? $evidencePath : asset('storage/' . $evidencePath)) : null;
                                                    @endphp
                                                    @if($evidenceUrl)
                                                        <button type="button"
                                                                @click="openEvidence('{{ $evidenceUrl }}', 'Bukti OJT {{ $log->logbook_number }} ({{ \Carbon\Carbon::parse($log->date)->format('d M Y') }})')"
                                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#1e3a8a] text-[11px] font-bold border border-blue-200 transition">
                                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                            Lihat
                                                        </button>
                                                    @else
                                                        <span class="text-[10px] text-slate-300 italic">-</span>
                                                    @endif
                                                </td>

                                                <!-- Aksi: Buka Logbook -->
                                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                                    <div class="inline-flex items-center gap-2">
                                                        @if($log->status === 'submitted')
                                                            <a href="{{ route('ojt.logbooks.show', $log->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-xs">
                                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                                Review & Setujui
                                                            </a>
                                                        @else
                                                            <a href="{{ route('ojt.logbooks.show', $log->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                                                                <svg class="w-3.5 h-3.5 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                                Buka Logbook
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="py-8 px-4 text-center text-slate-400 bg-slate-50/40">
                                <p class="text-xs font-semibold text-slate-500">Belum ada form OJT yang tercatat pada {{ $grp['label'] }}.</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Trainer pembimbing akan menginputkan form logbook harian untuk fase ini.</p>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center text-slate-400">
                        Belum ada riwayat form OJT.
                    </div>
                @endforelse

            </div>

            <!-- Right Column: Audit Trail Timeline -->
            <div class="space-y-6">

                <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-4 flex items-center justify-between">
                        <span>Aktivitas Sistem Real-Time</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    </h3>

                    <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                        @forelse($timelineHistories as $th)
                            <div class="relative">
                                <div class="absolute -left-6 top-0.5 w-4 h-4 rounded-full bg-[#2563eb] border-2 border-white ring-2 ring-blue-100"></div>
                                <div>
                                    <span class="text-xs font-bold text-slate-800 block">{{ $th->action }}</span>
                                    <span class="text-[10px] text-[#1e3a8a] font-mono block mt-0.5">
                                        {{ $th->logbook->logbook_number ?? '-' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">
                                        {{ $th->created_at->diffForHumans() }} · oleh {{ $th->user->name ?? 'Sistem' }}
                                    </span>
                                    @if($th->comment)
                                        <p class="text-[11px] text-slate-600 bg-slate-50 p-2 rounded-lg border border-slate-200 mt-1.5 italic">
                                            "{{ $th->comment }}"
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">Belum ada riwayat aktivitas sistem.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Info Help Card -->
                <div class="bg-gradient-to-br from-blue-50/70 to-indigo-50/50 p-4 sm:p-5 rounded-2xl border border-blue-100 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0 font-bold text-sm">
                            ℹ️
                        </div>
                        <div class="text-xs text-blue-900 leading-relaxed">
                            <h4 class="font-bold mb-1">Informasi Pengelompokan</h4>
                            <p class="text-[11px] text-blue-700">Setiap form OJT secara otomatis dipetakan ke fase evaluasi berdasarkan tahapan berjalan dan HM operasional. Form yang sudah diajukan dapat dibuka kembali untuk diperiksa atau disetujui.</p>
                        </div>
                    </div>
                </div>

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
                    <h3 class="text-xs font-bold text-slate-800" x-text="evidenceModalTitle"></h3>
                    <button type="button" @click="evidenceModalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg leading-none">
                        ✕
                    </button>
                </div>
                <div class="p-4 bg-slate-900 flex items-center justify-center max-h-[75vh] overflow-hidden">
                    <img :src="evidenceModalUrl" alt="Foto Evidence OJT" class="max-h-[70vh] max-w-full object-contain rounded-lg">
                </div>
                <div class="px-5 py-3 bg-white border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 text-[11px]">Bukti pelaksanaan OJT ini tersimpan dalam sistem dan tidak ikut tercetak ke PDF.</span>
                    <a :href="evidenceModalUrl" target="_blank" download class="px-3 py-1.5 rounded-lg bg-[#1e3a8a] text-white font-bold hover:bg-blue-900 transition">
                        Unduh Foto
                    </a>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
