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

    <!-- Main Data Table (Antrean Approval & Dokumen Final) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="font-black text-slate-800 text-sm sm:text-base">
                    {{ ($activeStatus ?? 'pending') === 'finalized' ? 'Dokumen Final Disahkan (Siap Cetak)' : (($activeStatus ?? 'pending') === 'revision' ? 'Form OJT Dikembalikan Revisi' : 'Antrean Form OJT Menunggu Final Approval') }}
                </h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">
                    {{ ($activeStatus ?? 'pending') === 'finalized' ? 'Hanya Admin Training Centre yang berwenang mengunduh dan mencetak form logbook resmi ini.' : 'Tinjau detail logbook harian dan berikan keputusan pengesahan.' }}
                </p>
            </div>
            <form class="flex flex-col sm:flex-row gap-2 flex-wrap items-stretch sm:items-center" method="GET">
                <input type="hidden" name="status" value="{{ $activeStatus ?? 'pending' }}">
                <input name="search" value="{{ request('search') }}" placeholder="Cari SID, nama, no. logbook..." class="text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]">
                <select name="department" class="text-xs rounded-xl border-slate-300 min-h-[44px]">
                    <option value="">Semua Departemen</option>
                    @php
                        $tcDeptOptions = collect(['CHCPP', 'RIM', 'HRGS'])->merge($departments ?? [])->filter()->unique()->values();
                    @endphp
                    @foreach($tcDeptOptions as $d)
                        <option value="{{ $d }}" {{ request('department') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
                <button class="px-4 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold min-h-[44px] transition">Cari</button>
                @if(request()->hasAny(['search', 'department']))
                    <a href="{{ route('training-centre.approvals.index', ['status' => $activeStatus ?? 'pending']) }}" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold min-h-[44px] inline-flex items-center justify-center transition">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        @if($activeStatus === 'finalized')
                            <th class="px-5 py-3">Trainee & Departemen</th>
                            <th class="px-5 py-3">Instruktur</th>
                            <th class="px-5 py-3">Total Form OJT Disahkan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        @else
                            <th class="px-5 py-3">Form OJT / Trainee</th>
                            <th class="px-5 py-3">Departemen</th>
                            <th class="px-5 py-3">Trainer</th>
                            <th class="px-5 py-3">Status / Tanggal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if($activeStatus === 'finalized' && isset($groupedFinalized))
                        @forelse($groupedFinalized as $traineeId => $group)
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-bold text-slate-800">{{ $group['trainee']->name ?? '-' }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[11px] text-slate-500">{{ $group['trainee']->sid ?? '-' }}</span>
                                        @if($group['trainee']->department ?? false)
                                            <span class="inline-flex px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                                                🏢 {{ $group['trainee']->department }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    {{ $group['trainer']->name ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">
                                        ✓ {{ $group['count'] }} Form OJT
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-1">
                                        Terakhir: {{ $group['latest_date'] ? $group['latest_date']->format('d M Y') : '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('training-centre.trainee-documents', $traineeId) }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold shadow-sm transition" title="Lihat Detail Dokumen Trainee">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Buka Dokumen
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada dokumen final disahkan yang sesuai filter.</td>
                            </tr>
                        @endforelse
                    @else
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
                    @endif
                </tbody>
            </table>
        </div>
        @if($activeStatus !== 'finalized')
            <div class="p-5 border-t border-slate-100">{{ $logbooks->links() }}</div>
        @endif
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
