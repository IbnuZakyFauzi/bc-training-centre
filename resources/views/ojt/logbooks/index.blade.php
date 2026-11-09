<x-app-layout>
    <x-slot name="title">Daftar Submission Saya</x-slot>

    <!-- Page Header & Action Bar -->
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight">
                Daftar Submission Saya
                @if($selectedPhase && $phaseMeta)
                    <span class="text-[#2563eb] font-bold">— {{ $phaseMeta['label'] }}</span>
                @endif
            </h1>
            <p class="text-[10px] sm:text-xs text-slate-500 mt-1">
                @if($selectedPhase && $phaseMeta)
                    Menampilkan riwayat form OJT khusus periode {{ $phaseMeta['label'] }}.
                @else
                    Kelola, tinjau status verifikasi, dan perbarui seluruh submission OJT Anda.
                @endif
            </p>
        </div>
        <div>
            <a href="{{ route('ojt.logbooks.create') }}" class="inline-flex items-center justify-center px-4 py-3 bg-[#2563eb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow-sm transition-all min-h-[44px]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Form OJT
            </a>
        </div>
    </div>

    <!-- Phase Navigation Switcher Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2.5 mb-4">
        <div class="flex items-center justify-between px-2 pb-2 mb-2 border-b border-slate-100">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4 text-[#2563eb]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Pilih Fase Evaluasi:</span>
            </div>
            @if($selectedPhase && $selectedPhase !== 'all')
                <div class="flex items-center space-x-2">
                    <span class="text-[11px] font-semibold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-200">
                        Filter Aktif: {{ $phaseMeta['label'] ?? \App\Services\PhaseService::phaseLabel($selectedPhase) }}
                    </span>
                    <a href="{{ route('ojt.logbooks.index', request()->except('phase', 'page')) }}" class="text-[11px] font-bold text-slate-500 hover:text-slate-800 underline">
                        Reset Fase
                    </a>
                </div>
            @else
                <span class="text-[11px] font-semibold text-slate-500 bg-slate-100 px-2.5 py-0.5 rounded-full">
                    Menampilkan Semua Fase
                </span>
            @endif
        </div>
        <div class="flex items-center space-x-1.5 overflow-x-auto pb-1 pt-0.5 min-w-max">
            @php
                $isAllPhases = empty($selectedPhase) || $selectedPhase === 'all';
                $basePhaseParams = request()->only(['status', 'equipment_id', 'date_from', 'date_to', 'search']);
            @endphp
            <a href="{{ route('ojt.logbooks.index', $basePhaseParams) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $isAllPhases ? 'bg-[#1e3a8a] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Semua Fase</span>
                <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $isAllPhases ? 'bg-blue-800 text-blue-100' : 'bg-slate-100 text-slate-600' }}">{{ $totalAllPhases ?? $statusCounts['all'] }}</span>
            </a>

            @foreach($availablePhases as $ph)
                @php
                    $isThisPhase = ($selectedPhase === $ph['key']);
                    $countThisPhase = $phaseCounts[$ph['key']] ?? 0;
                @endphp
                <a href="{{ route('ojt.logbooks.index', array_merge($basePhaseParams, ['phase' => $ph['key']])) }}"
                   class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 {{ $isThisPhase ? 'bg-[#2563eb] text-white shadow-sm ring-2 ring-blue-300' : 'text-slate-600 hover:bg-slate-100' }}">
                    <span>{{ $ph['label'] }}</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $isThisPhase ? 'bg-blue-800 text-white' : ($countThisPhase > 0 ? 'bg-blue-50 text-blue-700 font-semibold' : 'bg-slate-100 text-slate-400') }}">
                        {{ $countThisPhase }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    @if($selectedPhase && $selectedPhase !== 'all' && $phaseMeta)
        <!-- Phase Highlight Banner -->
        <div class="bg-gradient-to-r from-[#1e3a8a] to-[#2563eb] text-white rounded-2xl p-4 sm:p-5 mb-4 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-400 text-gray-900">
                        {{ $phaseMeta['label'] }}
                    </span>
                    <span class="text-xs text-blue-100 font-medium">
                        {{ $phaseCounts[$selectedPhase] ?? 0 }} Form Disubmit Pada Periode Ini
                    </span>
                </div>
                <p class="text-xs text-blue-100 mt-1.5 font-medium">
                    @if(($phaseMeta['type'] ?? '') === 'evaluasi')
                        Persyaratan HM Fase: <strong class="text-amber-300 font-bold">{{ $phaseMeta['total_hm'] ?? '-' }} HM Total</strong> (Siang: {{ $phaseMeta['day_hm'] ?? '-' }} HM | Malam: {{ $phaseMeta['night_hm'] ?? '-' }} HM)
                    @elseif(($phaseMeta['type'] ?? '') === 'bulanan')
                        Persyaratan Fase: <strong class="text-amber-300 font-bold">Minimal 4 Form OJT Bulanan</strong>
                    @endif
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('ojt.logbooks.index', request()->except('phase', 'page')) }}" class="px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-semibold backdrop-blur transition inline-flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tampilkan Semua Fase
                </a>
            </div>
        </div>
    @endif

    <!-- Status Tabs Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 mb-6 overflow-x-auto">
        <div class="flex items-center space-x-1 min-w-max">
            @php
                $baseStatusQuery = request()->only(['phase', 'equipment_id', 'date_from', 'date_to', 'search']);
            @endphp
            <a href="{{ route('ojt.logbooks.index', array_merge($baseStatusQuery, ['status' => 'all'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ !request('status') || request('status') === 'all' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Semua</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('status') || request('status') === 'all' ? 'bg-blue-800 text-blue-100' : 'bg-slate-100 text-slate-600' }}">{{ $statusCounts['all'] }}</span>
            </a>
            <a href="{{ route('ojt.logbooks.index', array_merge($baseStatusQuery, ['status' => 'draft'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ request('status') === 'draft' ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Draft</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'draft' ? 'bg-slate-700 text-slate-100' : 'bg-slate-100 text-slate-600' }}">{{ $statusCounts['draft'] }}</span>
            </a>
            <a href="{{ route('ojt.logbooks.index', array_merge($baseStatusQuery, ['status' => 'submitted'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ request('status') === 'submitted' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Submitted</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'submitted' ? 'bg-blue-700 text-blue-100' : 'bg-blue-50 text-blue-700' }}">{{ $statusCounts['submitted'] }}</span>
            </a>
            <a href="{{ route('ojt.logbooks.index', array_merge($baseStatusQuery, ['status' => 'revision'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ request('status') === 'revision' ? 'bg-amber-500 text-gray-900 shadow-xs' : 'text-amber-700 hover:bg-amber-50' }}">
                <span>Returned for Revision</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-900">{{ $statusCounts['revision'] }}</span>
            </a>
            <a href="{{ route('ojt.logbooks.index', array_merge($baseStatusQuery, ['status' => 'approved'])) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ request('status') === 'approved' ? 'bg-[#2563eb] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Approved</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'approved' ? 'bg-blue-700 text-blue-100' : 'bg-amber-50 text-amber-700' }}">{{ $statusCounts['approved'] }}</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white p-3 sm:p-4 rounded-2xl shadow-sm border border-slate-200 mb-4 sm:mb-6">
        <form action="{{ route('ojt.logbooks.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            @if(request('phase'))
                <input type="hidden" name="phase" value="{{ request('phase') }}">
            @endif
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif

            <!-- Equipment Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Unit Alat Berat</label>
                <select name="equipment_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                    <option value="">Semua Alat Berat</option>
                    @foreach($equipments as $eq)
                        <option value="{{ $eq->id }}" {{ request('equipment_id') == $eq->id ? 'selected' : '' }}>
                            {{ $eq->unit_code }} - {{ $eq->model_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
            </div>

            <!-- Date To / Filter Actions -->
            <div class="flex items-end space-x-2">
                <div class="flex-1">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white transition min-h-[44px]">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-[#1e3a8a] hover:bg-[#172554] text-white font-bold text-xs rounded-xl shadow-xs transition min-h-[44px]">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'equipment_id', 'date_from', 'date_to', 'sort_by', 'sort_dir']))
                    <a href="{{ route('ojt.logbooks.index', array_filter(['status' => request('status'), 'phase' => request('phase')])) }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition min-h-[44px] inline-flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </div>

        </form>

        <!-- Sort Controls -->
        <div class="mt-3 pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2">
            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Urutkan:</label>
            <div class="inline-flex rounded-xl border border-slate-200 overflow-hidden">
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'date', 'sort_dir' => request('sort_by') === 'date' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="px-3 py-1.5 text-xs font-bold {{ request('sort_by') !== 'hm_start' && request('sort_by') !== 'status' ? 'bg-[#1e3a8a] text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    Tanggal @if(request('sort_by') === 'date')<span>{{ request('sort_dir') === 'asc' ? '↑' : '↓' }}</span>@endif
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'hm_start', 'sort_dir' => request('sort_by') === 'hm_start' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="px-3 py-1.5 text-xs font-bold {{ request('sort_by') === 'hm_start' ? 'bg-[#1e3a8a] text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    HM Awal @if(request('sort_by') === 'hm_start')<span>{{ request('sort_dir') === 'asc' ? '↑' : '↓' }}</span>@endif
                </a>
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_dir' => request('sort_by') === 'status' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="px-3 py-1.5 text-xs font-bold {{ request('sort_by') === 'status' ? 'bg-[#1e3a8a] text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}">
                    Status @if(request('sort_by') === 'status')<span>{{ request('sort_dir') === 'asc' ? '↑' : '↓' }}</span>@endif
                </a>
            </div>
        </div>
    </div>

    <!-- Responsive Enterprise Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-5">
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'logbook_number', 'sort_dir' => request('sort_by') === 'logbook_number' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-slate-700 transition">
                        Form OJT Number @if(request('sort_by') === 'logbook_number')<span>{{ request('sort_dir') === 'asc' ? '↑' : '↓' }}</span>@endif
                    </a>
                </th>
                <th class="py-3.5 px-4">
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'date', 'sort_dir' => request('sort_by') === 'date' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-slate-700 transition">
                        Date @if(request('sort_by') === 'date')<span>{{ request('sort_dir') === 'asc' ? '↑' : '↓' }}</span>@endif
                    </a>
                </th>
                <th class="py-3.5 px-4">Nomor Alat</th>
                <th class="py-3.5 px-4">Category</th>
                <th class="py-3.5 px-4">Instruktur</th>
                <th class="py-3.5 px-4">Pengawas</th>
                <th class="py-3.5 px-4">Operator Pendamping</th>
                <th class="py-3.5 px-4 text-center">
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'hm_start', 'sort_dir' => request('sort_by') === 'hm_start' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-slate-700 transition">
                        HM Awal @if(request('sort_by') === 'hm_start')<span>{{ request('sort_dir') === 'asc' ? '↑' : '↓' }}</span>@endif
                    </a>
                </th>
                <th class="py-3.5 px-4 text-center">HM Akhir</th>
                <th class="py-3.5 px-4">
                    <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort_dir' => request('sort_by') === 'status' && request('sort_dir') === 'asc' ? 'desc' : 'asc']) }}" class="hover:text-slate-700 transition">
                        Status @if(request('sort_by') === 'status')<span>{{ request('sort_dir') === 'asc' ? '↑' : '↓' }}</span>@endif
                    </a>
                </th>
                        <th class="py-3.5 px-4">Last Updated</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($logbooks as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Form OJT Number -->
                            <td class="py-4 px-5 font-bold text-slate-900">
                                @php
                                    $rowPhaseKey = \App\Services\PhaseService::resolveLogbookPhase($log);
                                    $rowPhaseLabel = \App\Services\PhaseService::phaseLabel($rowPhaseKey);
                                @endphp
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('ojt.logbooks.show', $log->id) }}" class="hover:text-[#2563eb] transition">
                                        <span>{{ $log->logbook_number }}</span>
                                    </a>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $rowPhaseLabel }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-normal block mt-0.5">{{ $log->location }}</span>
                            </td>

                            <!-- Date -->
                            <td class="py-4 px-4">
                                <span class="font-bold text-slate-700 block">{{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}</span>
                                <span class="inline-flex items-center text-[10px] font-semibold text-slate-500 mt-0.5 uppercase">
                                    Shift {{ ucfirst($log->shift) }}
                                </span>
                            </td>

                            <!-- Nomor Alat -->
                            <td class="py-4 px-4">
                                <span class="font-mono text-xs font-bold text-slate-700">{{ $log->equipment_number ?? '-' }}</span>
                            </td>

                            <!-- Equipment Category -->
                            <td class="py-4 px-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold">
                                    {{ $log->equipmentCategory->name ?? 'Heavy Equipment' }}
                                </span>
                            </td>

                            <!-- Instruktur -->
                            <td class="py-4 px-4">
                                <div class="font-semibold text-slate-800">{{ optional($log->trainer)->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ optional($log->trainer)->sid ?? '-' }}</div>
                            </td>

                            <!-- Pengawas -->
                            <td class="py-4 px-4">
                                @php
                                    $pengawasList = collect($log->selected_pengawas_ids ?? [])->map(fn($id) => $usersMap[$id] ?? null)->filter();
                                @endphp
                                @if($pengawasList->count() > 0)
                                    @foreach($pengawasList as $pengawas)
                                        <div class="font-semibold text-slate-800">{{ $pengawas->name }}</div>
                                    @endforeach
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- Operator Pendamping -->
                            <td class="py-4 px-4">
                                @php
                                    $operatorList = collect($log->selected_operator_pendamping_ids ?? [])->map(fn($id) => $usersMap[$id] ?? null)->filter();
                                @endphp
                                @if($operatorList->count() > 0)
                                    @foreach($operatorList as $operator)
                                        <div class="font-semibold text-slate-800">{{ $operator->name }}</div>
                                    @endforeach
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <!-- HM Awal -->
                            <td class="py-4 px-4 text-center font-mono text-xs text-slate-700">
                                {{ $log->hm_start ?? '-' }}
                            </td>

                            <!-- HM Akhir -->
                            <td class="py-4 px-4 text-center font-mono text-xs text-slate-700">
                                {{ $log->hm_end ?? '-' }}
                            </td>

                            <!-- Status Badge -->
                            <td class="py-4 px-4">
                                <x-badge :status="$log->status" />
                                @if($log->status === 'revision' && $log->revision_notes)
                                    <p class="text-[10px] text-amber-800 font-medium mt-1 truncate max-w-xs" title="{{ $log->revision_notes }}">
                                        Notes: {{ $log->revision_notes }}
                                    </p>
                                @endif
                            </td>

                        <!-- Last Updated -->
                        <td class="py-4 px-4 text-slate-500 text-[11px]">
                            {{ $log->updated_at->diffForHumans() }}
                        </td>
                        <td class="py-4 px-4 text-right">
                            @if(in_array($log->status, ['draft', 'revision']))
                                <a href="{{ route('ojt.logbooks.edit', $log->id) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold transition">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                            @else
                                <span class="text-[10px] text-slate-400">-</span>
                            @endif
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                @if(!empty($selectedPhase) && $selectedPhase !== 'all')
                                    <p class="text-sm font-semibold text-slate-700">Tidak ada submission form OJT pada {{ $phaseMeta['label'] ?? \App\Services\PhaseService::phaseLabel($selectedPhase) }}.</p>
                                    <p class="text-xs text-slate-400 mt-1">Gunakan tombol "Create Form OJT" untuk menambahkan submission baru pada fase ini, atau pilih tab fase lainnya.</p>
                                @else
                                    <p class="text-sm font-semibold text-slate-600">Tidak ada data form OJT yang ditemukan.</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau buat form OJT baru.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $logbooks->links() }}
        </div>
    </div>

</x-app-layout>

