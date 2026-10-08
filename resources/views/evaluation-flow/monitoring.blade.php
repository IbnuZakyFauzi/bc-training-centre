<x-app-layout>
    <x-slot name="title">Monitoring Evaluasi Multi-Fase</x-slot>

    <!-- Header Section -->
    <div class="mb-6 bg-gradient-to-r from-[#1e3a8a] via-[#1d4ed8] to-[#2563eb] p-5 sm:p-7 rounded-3xl shadow-lg text-white border border-blue-900/50 relative overflow-hidden">
        <div class="absolute right-0 top-0 -mt-10 -mr-10 w-64 h-64 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-400/20 text-blue-200 border border-blue-300/30 tracking-wider uppercase">
                        {{ $user->isTrainingCentre() ? 'Admin Training Centre' : 'Trainer Evaluator' }}
                    </span>
                    <span class="text-blue-200 text-xs">·</span>
                    <span class="text-blue-100 text-xs font-semibold">Live Timeline Monitoring</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Monitoring Progress Evaluasi Multi-Fase</h1>
                <p class="text-blue-100/90 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                    {{ $user->isTrainingCentre() ? 'Pantau visualisasi roadmap timeline, akumulasi jam terbang (HM), dan status kelayakan evaluasi seluruh trainee secara interaktif.' : 'Pantau visualisasi roadmap timeline progress jam terbang dan kelayakan evaluasi multi-fase seluruh trainee bimbingan Anda.' }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 px-4 py-3 text-xs shadow-inner">
                    <p class="text-blue-200 text-[11px] font-medium">User Login</p>
                    <p class="font-bold text-white mt-0.5 text-sm">{{ $user->name }}</p>
                    <p class="text-[10px] text-blue-200/80">{{ $user->sid }}</p>
                </div>
                @if($user->isTrainingCentre())
                    <a href="{{ route('training-centre.dashboard') }}" class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl text-xs font-bold transition border border-white/20 text-center min-h-[44px] inline-flex items-center justify-center">
                        Dashboard Logbook
                    </a>
                @else
                    <a href="{{ route('trainer.dashboard') }}" class="px-4 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl text-xs font-bold transition border border-white/20 text-center min-h-[44px] inline-flex items-center justify-center">
                        Review Logbook
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Analytics KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Trainee</p>
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-[#1e3a8a] flex items-center justify-center font-bold">
                    👥
                </div>
            </div>
            <p class="text-3xl font-black text-slate-800 mt-2">{{ $analytics['total_trainees'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Peserta OJT aktif</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Siap Dievaluasi</p>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    ✓
                </div>
            </div>
            <p class="text-3xl font-black text-emerald-600 mt-2">{{ $analytics['eligible_count'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Syarat HM / Form terpenuhi</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Akumulasi HM / Form</p>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                    ⏱
                </div>
            </div>
            <p class="text-3xl font-black text-amber-600 mt-2">{{ $analytics['in_progress_count'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Sedang proses pengisian OJT</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-[#1e3a8a] uppercase tracking-wider">Evaluasi Selesai</p>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-[#1e3a8a] flex items-center justify-center font-bold">
                    📊
                </div>
            </div>
            <p class="text-3xl font-black text-[#1e3a8a] mt-2">{{ $analytics['completed_evaluations'] }}</p>
            <p class="text-[11px] text-slate-400 mt-1">Disetujui final HSE CT</p>
        </div>
    </div>

    <!-- Interactive Charts Section -->
    @if($trainees->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
            <!-- Chart 1: Komposisi Sertifikasi -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">Distribusi Sertifikasi</h3>
                        <p class="text-[11px] text-slate-400">Klasifikasi jalur masuk trainee</p>
                    </div>
                    <span class="text-xs font-bold text-[#1e3a8a] bg-blue-50 px-2 py-1 rounded-lg">Realtime</span>
                </div>
                <div id="chart-certification" class="min-h-[220px]"></div>
            </div>

            <!-- Chart 2: Distribusi Departemen -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">Distribusi Departemen</h3>
                        <p class="text-[11px] text-slate-400">Sebaran departemen trainee</p>
                    </div>
                    <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 rounded-lg">Departemen</span>
                </div>
                <div id="chart-department" class="min-h-[220px]"></div>
            </div>

            <!-- Chart 3: Posisi Fase & Kelayakan -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-800">Status Kesiapan Evaluasi</h3>
                        <p class="text-[11px] text-slate-400">Rasio eligible vs dalam akumulasi</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg">Kesiapan</span>
                </div>
                <div id="chart-readiness" class="min-h-[220px]"></div>
            </div>
        </div>
    @endif

    <!-- Search & Filter Toolbar -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-sm font-extrabold text-slate-800 flex items-center gap-2">
                    <span>🔍</span> Filter & Pencarian Trainee
                </h2>
                <p class="text-[11px] text-slate-500 mt-0.5">Saring data trainee berdasarkan kriteria di bawah ini.</p>
            </div>
            @if(request()->hasAny(['search', 'certification', 'department']))
                <a href="{{ url()->current() }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold transition self-start md:self-auto">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Reset Filter
                </a>
            @endif
        </div>
        <form method="GET" class="p-4 bg-slate-50/50 flex flex-col sm:flex-row gap-2.5 flex-wrap items-stretch sm:items-center">
            <div class="flex-1 min-w-[220px]">
                <input name="search" value="{{ request('search') }}" placeholder="Cari nama, SID, perusahaan, departemen..." class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px] bg-white">
            </div>
            <div class="w-full sm:w-auto min-w-[180px]">
                <select name="certification" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px] bg-white">
                    <option value="">Semua Sertifikasi</option>
                    @foreach($certifications as $c)
                        <option value="{{ $c }}" {{ request('certification') === $c ? 'selected' : '' }}>
                            {{ $c === 'Green' ? 'Green' : ($c === 'Skill-up' ? 'Skill-up' : ($c === 'Experience_internal' ? 'Experience Internal' : ($c === 'Experience_external' ? 'Experience External' : $c))) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-auto min-w-[180px]">
                <select name="department" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px] bg-white">
                    <option value="">Semua Departemen</option>
                    @php
                        $monitoringDeptOptions = collect(['CHCPP', 'RIM', 'HRGS'])->merge($departments ?? [])->filter()->unique()->values();
                    @endphp
                    @foreach($monitoringDeptOptions as $d)
                        <option value="{{ $d }}" {{ request('department') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold min-h-[44px] transition shadow-sm inline-flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Terapkan Filter
            </button>
        </form>
    </div>

    <!-- Interactive View Mode Selector -->
    <div x-data="{ viewMode: 'timeline' }" class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h2 class="text-sm font-black text-slate-800">Visualisasi Progress Trainee OJT</h2>
                <span class="text-xs text-slate-400">({{ $trainees->count() }} Trainee Terdaftar)</span>
            </div>
            <div class="inline-flex p-1 bg-slate-200/70 rounded-xl text-xs font-bold">
                <button @click="viewMode = 'timeline'" :class="viewMode === 'timeline' ? 'bg-white text-[#1e3a8a] shadow-xs' : 'text-slate-600 hover:text-slate-800'" class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5">
                    <span>📊</span> Timeline Roadmap
                </button>
                <button @click="viewMode = 'cards'" :class="viewMode === 'cards' ? 'bg-white text-[#1e3a8a] shadow-xs' : 'text-slate-600 hover:text-slate-800'" class="px-3 py-1.5 rounded-lg transition inline-flex items-center gap-1.5">
                    <span>🗂️</span> Tampilan Kartu
                </button>
            </div>
        </div>

        <!-- ================= VIEW 1: TIMELINE ROADMAP MATRIX (DEFAULT) ================= -->
        <div x-show="viewMode === 'timeline'" class="space-y-3">
            @forelse($trainees as $t)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition p-4 sm:p-5">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                        <!-- Left: Trainee Info & Live Status -->
                        <div class="min-w-[280px] max-w-sm flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#1e3a8a] to-[#2563eb] text-white font-black text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
                                {{ collect(explode(' ', $t['name']))->take(2)->map(fn($p)=>strtoupper(substr($p,0,1)))->implode('') }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-xs sm:text-sm font-black text-slate-800 truncate" title="{{ $t['name'] }}">{{ $t['name'] }}</h3>
                                    @if($t['department'])
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-extrabold bg-blue-50 text-[#1e3a8a] border border-blue-200">
                                            🏢 {{ $t['department'] }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    SID: <strong class="text-slate-700">{{ $t['sid'] }}</strong> · {{ $t['equipment_category_name'] }}
                                </p>
                                <div class="flex items-center gap-2 mt-2">
                                    @if($t['eligible'])
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span>✓</span> Siap Dievaluasi
                                        </span>
                                        @if($user->isTrainer())
                                            <a href="{{ route('trainer.final-evaluations.create', ['trainee' => $t['id']]) }}" class="px-2.5 py-0.5 rounded-md bg-[#1e3a8a] hover:bg-blue-900 text-white text-[10px] font-bold shadow-xs transition">
                                                Evaluasi →
                                            </a>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            @if(($t['progress']['type'] ?? '') === 'bulanan')
                                                Isi Form OJT ({{ $t['progress']['fraction'] ?? ($t['progress']['count'] ?? 0) . '/4' }})
                                            @else
                                                Akumulasi HM ({{ $t['progress']['total'] }}%)
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right: Visual Connected Multi-Phase Stepper Timeline -->
                        <div class="flex-1 overflow-x-auto py-2 pr-2">
                            <div class="flex items-center gap-1 min-w-[550px] relative">
                                @foreach($t['phases'] as $idx => $p)
                                    @php
                                        $isCurrent = $p['is_current'];
                                        $isCompleted = $p['completed'] > 0;
                                        $isPending = $p['pending'] > 0;
                                    @endphp

                                    <!-- Step Node -->
                                    <div class="flex-1 flex flex-col items-center text-center relative group">
                                        <!-- Step Milestone Badge / Circle -->
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-[11px] transition-all relative z-10 
                                            {{ $isCompleted ? 'bg-emerald-500 text-white shadow-sm ring-2 ring-emerald-200' : ($isCurrent ? 'bg-[#1e3a8a] text-white shadow-md ring-4 ring-blue-100 animate-pulse' : ($isPending ? 'bg-amber-500 text-white ring-2 ring-amber-200' : 'bg-slate-100 text-slate-400 border border-slate-200')) }}">
                                            @if($isCompleted)
                                                ✓
                                            @elseif($isPending)
                                                ⏳
                                            @elseif($isCurrent)
                                                ★
                                            @else
                                                {{ $idx + 1 }}
                                            @endif
                                        </div>

                                        <!-- Phase Label & State -->
                                        <p class="text-[10px] font-extrabold mt-1.5 max-w-[80px] leading-tight truncate {{ $isCurrent ? 'text-[#1e3a8a]' : ($isCompleted ? 'text-emerald-800' : 'text-slate-500') }}" title="{{ $p['label'] }}">
                                            {{ str_replace('Evaluasi ', 'Eval ', str_replace('Evaluasi Bulanan ', 'Bulan ', $p['label'])) }}
                                        </p>

                                        <!-- Sub Status / HM / Form Details -->
                                        @if($isCurrent)
                                            <span class="mt-0.5 px-1.5 py-0.2 rounded text-[9px] font-black bg-blue-100 text-blue-800">
                                                @if(($t['progress']['type'] ?? '') === 'bulanan')
                                                    {{ $t['progress']['fraction'] ?? ($t['progress']['count'] ?? 0) . '/4' }}
                                                @else
                                                    {{ $t['progress']['total'] }}%
                                                @endif
                                            </span>
                                        @elseif($isCompleted)
                                            <span class="mt-0.5 text-[9px] font-bold text-emerald-600">Lulus</span>
                                        @elseif($isPending)
                                            <span class="mt-0.5 text-[9px] font-bold text-amber-600">Review</span>
                                        @else
                                            <span class="mt-0.5 text-[9px] text-slate-300">—</span>
                                        @endif
                                    </div>

                                    <!-- Connector Line Between Nodes -->
                                    @if(!$loop->last)
                                        <div class="flex-1 h-1.5 rounded-full -mt-7 -mx-1 {{ $isCompleted ? 'bg-emerald-400' : 'bg-slate-200' }}"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                        🔍
                    </div>
                    <h3 class="text-base font-bold text-slate-700">Tidak ada trainee yang sesuai filter</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Coba ubah kata kunci pencarian, pilihan sertifikasi, atau filter departemen.</p>
                    <a href="{{ url()->current() }}" class="inline-flex mt-4 px-4 py-2 rounded-xl bg-[#1e3a8a] text-white text-xs font-bold hover:bg-blue-900 transition">
                        Reset Semua Filter
                    </a>
                </div>
            @endforelse
        </div>

        <!-- ================= VIEW 2: DETAILED CARDS VIEW ================= -->
        <div x-show="viewMode === 'cards'" class="space-y-4 sm:space-y-5">
            @forelse($trainees as $t)
                <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm hover:shadow-md transition-all overflow-hidden">
                    <!-- Card Header -->
                    <div class="p-4 sm:p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-gradient-to-r from-slate-50/50 to-white border-b border-slate-100">
                        <div class="flex items-start sm:items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#1e3a8a] to-[#2563eb] flex items-center justify-center text-white font-extrabold text-sm shadow-md flex-shrink-0">
                                {{ collect(explode(' ', $t['name']))->take(2)->map(fn($p)=>strtoupper(substr($p,0,1)))->implode('') }}
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-base font-extrabold text-slate-800">{{ $t['name'] }}</h2>
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold border border-slate-200">
                                        SID: {{ $t['sid'] }}
                                    </span>
                                    @if($t['department'])
                                        <span class="px-2 py-0.5 rounded-md bg-blue-50 text-[#1e3a8a] text-[10px] font-extrabold border border-blue-200 flex items-center gap-1">
                                            🏢 {{ $t['department'] }}
                                        </span>
                                    @endif
                                    @if($t['company'])
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-semibold">
                                            {{ $t['company'] }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 mt-1.5">
                                    <span class="inline-flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                        Sertifikasi: <strong class="text-slate-700">{{ $t['certification'] === 'Green' ? 'Green' : ($t['certification'] === 'Skill-up' ? 'Skill-up' : ($t['certification'] === 'Experience_internal' ? 'Experience Internal' : ($t['certification'] === 'Experience_external' ? 'Experience External' : $t['certification']))) }}</strong>
                                    </span>
                                    <span>·</span>
                                    <span>Tipe Alat: <strong class="text-slate-700">{{ $t['equipment_category_name'] }}</strong></span>
                                    <span>·</span>
                                    <span>Fase Saat Ini: <span class="font-extrabold text-[#1e3a8a] bg-blue-50 px-2 py-0.5 rounded">{{ $t['current_phase_label'] }}</span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Status Badges & Action -->
                        <div class="flex flex-wrap items-center gap-3 self-start lg:self-auto">
                            @if($t['eligible'])
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-500 text-white shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Siap Dievaluasi
                                </span>
                                @if($user->isTrainer())
                                    <a href="{{ route('trainer.final-evaluations.create', ['trainee' => $t['id']]) }}" class="px-3 py-1.5 rounded-xl bg-[#1e3a8a] hover:bg-blue-900 text-white text-xs font-bold transition shadow-sm inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Isi Evaluasi
                                    </a>
                                @endif
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    @if(($t['progress']['type'] ?? '') === 'bulanan')
                                        Isi Form OJT ({{ $t['progress']['fraction'] ?? ($t['progress']['count'] ?? 0) . '/4' }})
                                    @else
                                        Akumulasi HM
                                    @endif
                                </span>
                            @endif

                            <div class="text-right pl-3 border-l border-slate-200">
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Evaluasi Selesai</p>
                                <p class="text-base font-black text-[#1e3a8a]">
                                    {{ $t['completed_count'] }} <span class="text-xs font-normal text-slate-400">/ {{ $t['evaluations_count'] }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar Section (HM / Form OJT Bulanan) -->
                    <div class="px-5 pt-4 pb-2 bg-slate-50/40">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 text-xs text-slate-600 mb-1.5">
                            <div class="flex items-center gap-2">
                                @if(($t['progress']['type'] ?? '') === 'bulanan')
                                    <span class="font-bold text-slate-700">Progress Form OJT Mingguan - Fase {{ $t['current_phase_label'] }}</span>
                                    <span class="text-[11px] text-slate-400">({{ $t['progress']['count'] ?? 0 }}/4 Form OJT Terkirim)</span>
                                @else
                                    <span class="font-bold text-slate-700">Progress Jam Terbang (HM) - Fase {{ $t['current_phase_label'] }}</span>
                                    <span class="text-[11px] text-slate-400">(Siang: {{ number_format($t['hm']['day'], 1) }}h | Malam: {{ number_format($t['hm']['night'], 1) }}h)</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 font-black text-[#1e3a8a]">
                                @if(($t['progress']['type'] ?? '') === 'bulanan')
                                    <span>{{ $t['progress']['fraction'] ?? ($t['progress']['count'] ?? 0) . '/4' }} Form</span>
                                @else
                                    <span>{{ number_format($t['hm']['total'], 1) }} HM</span>
                                @endif
                                <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-800 text-[10px] font-extrabold">{{ $t['progress']['total'] }}%</span>
                            </div>
                        </div>
                        <div class="h-3 bg-slate-200/80 rounded-full overflow-hidden p-0.5 flex">
                            <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full transition-all duration-500" style="width: {{ min(100, $t['progress']['total']) }}%"></div>
                        </div>
                    </div>

                    <!-- Multi-Phase Grid Breakdown -->
                    <div class="p-4 sm:p-5">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Rangkaian Fase Evaluasi OJT</p>
                            <span class="text-[10px] text-slate-400">Titik biru menandakan fase aktif saat ini</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-2.5">
                            @foreach($t['phases'] as $p)
                                @php
                                    $isCurrent = $p['is_current'];
                                    $isCompleted = $p['completed'] > 0;
                                    $isPending = $p['pending'] > 0;
                                    $isRejected = $p['rejected'] > 0;
                                @endphp
                                <div class="rounded-xl border p-3 transition-all relative {{ $isCurrent ? 'border-[#2563eb] bg-blue-50/40 shadow-sm ring-1 ring-blue-400/50' : ($isCompleted ? 'border-emerald-200 bg-emerald-50/20' : 'border-slate-200 bg-white') }}">
                                    <div class="flex items-center justify-between gap-1 mb-1.5">
                                        <span class="text-xs font-bold {{ $isCurrent ? 'text-[#1e3a8a]' : ($isCompleted ? 'text-emerald-800' : 'text-slate-700') }} truncate">
                                            {{ $p['label'] }}
                                        </span>
                                        @if($isCompleted)
                                            <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-[10px] font-bold">✓</span>
                                        @elseif($isPending)
                                            <span class="w-4 h-4 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center text-[10px] font-bold">⏳</span>
                                        @elseif($isCurrent)
                                            <span class="w-2 h-2 rounded-full bg-blue-600 animate-ping"></span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-500 space-y-0.5">
                                        @if($isCompleted)
                                            <p class="text-emerald-700 font-bold">Disetujui HSE CT</p>
                                        @elseif($isPending)
                                            <p class="text-amber-700 font-semibold">Proses Persetujuan</p>
                                        @elseif($isRejected)
                                            <p class="text-rose-600 font-semibold">Perlu Revisi</p>
                                        @elseif(!$isCurrent)
                                            <p class="text-slate-400">Belum dimulai</p>
                                        @else
                                            <p class="text-blue-600 font-semibold">Sedang berjalan</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                        🔍
                    </div>
                    <h3 class="text-base font-bold text-slate-700">Tidak ada trainee yang sesuai filter</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Coba ubah kata kunci pencarian, pilihan sertifikasi, atau filter departemen.</p>
                    <a href="{{ url()->current() }}" class="inline-flex mt-4 px-4 py-2 rounded-xl bg-[#1e3a8a] text-white text-xs font-bold hover:bg-blue-900 transition">
                        Reset Semua Filter
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Chart Script (ApexCharts) -->
    @if($trainees->isNotEmpty())
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const Apex = window.ApexCharts || ApexCharts;
                if (!Apex) return;

                // 1. Chart Sertifikasi
                const certData = @json($analytics['cert_distribution']);
                const certChart = new Apex(document.querySelector("#chart-certification"), {
                    series: certData.data,
                    labels: certData.labels,
                    chart: { type: 'donut', height: 220, fontFamily: 'inherit' },
                    colors: ['#22c55e', '#6366f1', '#a855f7', '#3b82f6'],
                    legend: { position: 'bottom', fontSize: '11px' },
                    dataLabels: { enabled: true, formatter: (val) => Math.round(val) + '%' },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '65%',
                                labels: {
                                    show: true,
                                    total: { show: true, label: 'Total', fontSize: '12px', fontWeight: 700 }
                                }
                            }
                        }
                    }
                });
                certChart.render();

                // 2. Chart Departemen
                const deptMap = @json($analytics['dept_distribution']);
                const deptLabels = Object.keys(deptMap);
                const deptValues = Object.values(deptMap);
                const deptChart = new Apex(document.querySelector("#chart-department"), {
                    series: [{ name: 'Trainee', data: deptValues }],
                    chart: { type: 'bar', height: 220, toolbar: { show: false }, fontFamily: 'inherit' },
                    colors: ['#1e3a8a'],
                    plotOptions: { bar: { borderRadius: 6, horizontal: true, distributed: false, dataLabels: { position: 'top' } } },
                    xaxis: { categories: deptLabels, labels: { style: { fontSize: '10px' } } },
                    yaxis: { labels: { style: { fontSize: '11px', fontWeight: 600 } } },
                    dataLabels: { enabled: true, offsetX: 10, style: { fontSize: '11px', colors: ['#1e3a8a'] } },
                    grid: { strokeDashArray: 3 }
                });
                deptChart.render();

                // 3. Chart Kesiapan Evaluasi
                const readyChart = new Apex(document.querySelector("#chart-readiness"), {
                    series: [{{ $analytics['eligible_count'] }}, {{ $analytics['in_progress_count'] }}],
                    labels: ['Siap Dievaluasi', 'Akumulasi HM'],
                    chart: { type: 'pie', height: 220, fontFamily: 'inherit' },
                    colors: ['#10b981', '#f59e0b'],
                    legend: { position: 'bottom', fontSize: '11px' },
                    dataLabels: { enabled: true }
                });
                readyChart.render();
            });
        </script>
    @endif
</x-app-layout>
