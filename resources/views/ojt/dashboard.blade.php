<x-app-layout>
    <x-slot name="title">Dashboard Trainee OJT</x-slot>

    <!-- Page Header & Welcome Banner -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between bg-gradient-to-r from-[#003829] to-[#00593E] p-6 rounded-2xl shadow-md text-white border border-emerald-900">
        <div>
            <div class="flex items-center space-x-2 text-emerald-300 text-xs font-semibold uppercase tracking-wider mb-1">
                <span>DIGITAL OJT LOGBOOK</span>
                <span>•</span>
                <span>OPERATOR COMPETENCY MONITORING</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Selamat Datang, {{ $user->name }}</h1>
            <p class="text-emerald-100 text-xs mt-1">NRP: {{ $user->nrp }} | Departemen: {{ $user->department->name ?? 'Mining Operations' }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            @if($latestDraft)
                <a href="{{ route('ojt.logbooks.edit', $latestDraft->id) }}" class="inline-flex items-center px-4 py-2.5 bg-[#F5A623] hover:bg-amber-500 text-gray-900 font-bold text-xs rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Continue Draft ({{ $latestDraft->logbook_number }})
                </a>
            @endif
            <a href="{{ route('ojt.logbooks.create') }}" class="inline-flex items-center px-4 py-2.5 bg-[#00A859] hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Digital Logbook
            </a>
        </div>
    </div>

    <!-- Notification Panel: Recent Revision Messages -->
    @if($kpi['revision'] > 0)
        <div class="mb-8 bg-amber-50 border-l-4 border-[#F5A623] p-5 rounded-2xl shadow-xs">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-3">
                    <div class="p-2 bg-amber-100 rounded-xl text-amber-800 flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-extrabold text-amber-900 uppercase tracking-wider">Pemberitahuan Revisi Logbook ({{ $kpi['revision'] }} Logbook Perlu Tindakan)</h3>
                        <p class="text-xs text-amber-800 mt-1">Trainer telah mengirimkan catatan perbaikan untuk logbook Anda. Silakan diperbaiki sebelum pengajuan ulang.</p>
                    </div>
                </div>
                <a href="{{ route('ojt.logbooks.index', ['status' => 'revision']) }}" class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-gray-900 text-xs font-bold rounded-xl shadow-xs transition flex-shrink-0">
                    Buka Logbook Revisi
                </a>
            </div>
        </div>
    @endif

    <!-- KPI Grid (5 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
        
        <!-- Draft KPI -->
        <a href="{{ route('ojt.logbooks.index', ['status' => 'draft']) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-slate-400 transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Draft</span>
                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-slate-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-slate-800">{{ $kpi['draft'] }}</span>
                <span class="text-[11px] font-medium text-slate-400">Belum Dikirim</span>
            </div>
        </a>

        <!-- Submitted KPI -->
        <a href="{{ route('ojt.logbooks.index', ['status' => 'submitted']) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Submitted</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-blue-700">{{ $kpi['submitted'] }}</span>
                <span class="text-[11px] font-medium text-blue-500">Menunggu Review</span>
            </div>
        </a>

        <!-- Revision KPI -->
        <a href="{{ route('ojt.logbooks.index', ['status' => 'revision']) }}" class="bg-white p-5 rounded-2xl shadow-sm border {{ $kpi['revision'] > 0 ? 'border-amber-300 ring-2 ring-amber-100' : 'border-slate-200' }} hover:border-amber-400 transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Returned Revision</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-amber-700">{{ $kpi['revision'] }}</span>
                <span class="text-[11px] font-bold text-amber-600">Catatan Trainer</span>
            </div>
        </a>

        <!-- Approved KPI -->
        <a href="{{ route('ojt.logbooks.index', ['status' => 'approved']) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-emerald-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-[#00A859] uppercase tracking-wider">Approved Logbooks</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-[#00A859] group-hover:bg-emerald-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-extrabold text-[#003829]">{{ $kpi['approved'] }}</span>
                <span class="text-[11px] font-medium text-emerald-600">Verifikasi Sukses</span>
            </div>
        </a>

        <!-- Progress KPI Card -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">OJT Progress</span>
                <div class="w-9 h-9 rounded-xl bg-[#003829] flex items-center justify-center text-[#F5A623]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline justify-between">
                    <span class="text-2xl font-extrabold text-slate-800">{{ number_format($kpi['total_hm'], 1) }} <span class="text-xs text-slate-500 font-normal">HM</span></span>
                    <span class="text-xs font-bold text-[#00A859]">{{ $kpi['progress_percentage'] }}%</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2 overflow-hidden">
                    <div class="bg-[#00A859] h-2 rounded-full transition-all duration-500" style="width: {{ $kpi['progress_percentage'] }}%"></div>
                </div>
                <span class="text-[10px] text-slate-400 mt-1 block">Target OJT: {{ $kpi['target_hm'] }} HM</span>
            </div>
        </div>

    </div>

    <!-- Main Grid Content: Analytics & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Columns: Hour Meter Trend Chart & Quick Actions -->
        <div class="lg:col-span-2 flex flex-col gap-8">
            
            <!-- Weekly HM Chart Widget -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Tren Jam Kerja OJT (Weekly Hour Meter)</h2>
                        <p class="text-xs text-slate-500">Aktivitas jam pengoperasian alat berat per hari minggu ini</p>
                    </div>
                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">Minggu Ini</span>
                </div>

                <div id="weeklyHmChart" class="h-64"></div>
            </div>

            <!-- Quick Action Cards Grid -->
            <div class="grid flex-1 grid-cols-1 gap-5 sm:grid-cols-2">
                <a href="{{ route('ojt.logbooks.create') }}" class="h-full p-7 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:border-[#00A859] transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold tracking-widest text-emerald-600 uppercase">Aksi Cepat</span>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-[#00A859] flex items-center justify-center group-hover:bg-[#00A859] group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                    </div>
                    <div class="py-6">
                        <h3 class="text-lg font-bold text-slate-800 group-hover:text-[#003829] transition">Create Digital Logbook</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">Catat HM awal, HM akhir, lokasi pit, dan unggah foto bukti P2H harian.</p>
                    </div>
                    <span class="inline-flex items-center border-t border-slate-100 pt-5 text-sm font-bold text-[#00A859]">Input Logbook<svg class="w-5 h-5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                </a>

                <a href="{{ route('ojt.logbooks.index') }}" class="h-full p-7 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:border-blue-400 transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold tracking-widest text-blue-600 uppercase">Manajemen Data</span>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                    <div class="py-6">
                        <h3 class="text-lg font-bold text-slate-800 group-hover:text-blue-700 transition">My Logbook Directory</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed">Kelola daftar seluruh riwayat logbook, status verifikasi, dan cetak PDF.</p>
                    </div>
                    <span class="inline-flex items-center border-t border-slate-100 pt-5 text-sm font-bold text-blue-600">Buka Tabel Logbook<svg class="w-5 h-5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                </a>
            </div>

        </div>

        <!-- Right Column: Recent Logbooks & Activity Timeline -->
        <div class="space-y-8">
            
            <!-- Recent Activity Widget -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-base font-bold text-slate-800">Aktivitas Terakhir</h2>
                    <a href="{{ route('ojt.history') }}" class="text-xs font-bold text-[#00A859] hover:underline">Lihat Semua</a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentLogbooks as $log)
                        <div class="py-4">
                            <div class="flex items-center justify-between gap-3">
                                <x-badge :status="$log->status" />
                                <span class="shrink-0 text-[11px] font-medium text-slate-400">{{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}</span>
                            </div>
                            <a href="{{ route('ojt.logbooks.show', $log->id) }}" class="mt-2 block text-sm font-bold text-slate-800 hover:text-[#00A859] break-words">
                                {{ $log->logbook_number }}
                            </a>
                            <p class="mt-1 text-xs text-slate-500">
                                Unit: <span class="font-semibold text-slate-700">{{ $log->equipment->unit_code ?? '-' }}</span><span class="mx-1.5 text-slate-300">|</span>HM: {{ number_format($log->total_hm, 1) }}
                            </p>
                            @if($log->status === 'revision' && $log->revision_notes)
                                <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs leading-relaxed text-amber-800">
                                    <strong>Catatan Trainer:</strong> {{ Str::limit($log->revision_notes, 110) }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada logbook yang dicatat.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Operational Guidelines Card -->
    <section class="mt-8 bg-gradient-to-r from-slate-900 to-[#003829] text-white p-7 rounded-2xl shadow-sm border border-slate-800">
        <div class="flex items-center space-x-2 text-[#F5A623] mb-5 font-bold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>PETUNJUK PENGISIAN OJT</span>
        </div>
        <ul class="grid grid-cols-1 gap-3 text-sm text-slate-200 leading-relaxed md:grid-cols-2 xl:grid-cols-4">
            <li class="rounded-xl border border-white/10 bg-white/5 p-4">Logbook wajib diisi setelah selesai shift kerja harian.</li>
            <li class="rounded-xl border border-white/10 bg-white/5 p-4">HM Awal dan HM Akhir harus sesuai dengan angka pada display unit.</li>
            <li class="rounded-xl border border-white/10 bg-white/5 p-4">Wajib mengunggah bukti fisik checklist P2H yang valid.</li>
            <li class="rounded-xl border border-white/10 bg-white/5 p-4">Pastikan memilih Trainer dan Supervisor sesuai pit lokasi tugas.</li>
        </ul>
    </section>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                series: [{
                    name: 'Jam Kerja (HM)',
                    data: [8.5, 7.0, 8.5, 8.0, 7.5, 0, 8.5]
                }],
                colors: ['#00A859'],
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '45%',
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#64748b', fontSize: '11px' } }
                },
                yaxis: {
                    title: { text: 'Total HM', style: { color: '#64748b', fontSize: '11px' } },
                    labels: { style: { colors: '#64748b', fontSize: '11px' } }
                },
                grid: { borderColor: '#f1f5f9' }
            };

            var chart = new ApexCharts(document.querySelector("#weeklyHmChart"), options);
            chart.render();
        });
    </script>
    @endpush

</x-app-layout>
