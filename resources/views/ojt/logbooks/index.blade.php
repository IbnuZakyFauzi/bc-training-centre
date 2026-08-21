<x-app-layout>
    <x-slot name="title">Daftar Submission Saya</x-slot>

    <!-- Page Header & Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Daftar Submission Saya</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola, tinjau status verifikasi, dan perbarui submission.</p>
        </div>
        <div>
            <a href="{{ route('ojt.logbooks.create') }}" class="inline-flex items-center px-4 py-2.5 bg-[#00A859] hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Form OJT
            </a>
        </div>
    </div>

    <!-- Status Tabs Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 mb-6 overflow-x-auto">
        <div class="flex items-center space-x-1 min-w-max">
            <a href="{{ route('ojt.logbooks.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ !request('status') || request('status') === 'all' ? 'bg-[#003829] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Semua</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('status') || request('status') === 'all' ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-100 text-slate-600' }}">{{ $statusCounts['all'] }}</span>
            </a>
            <a href="{{ route('ojt.logbooks.index', ['status' => 'draft']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ request('status') === 'draft' ? 'bg-slate-800 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Draft</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'draft' ? 'bg-slate-700 text-slate-100' : 'bg-slate-100 text-slate-600' }}">{{ $statusCounts['draft'] }}</span>
            </a>
            <a href="{{ route('ojt.logbooks.index', ['status' => 'submitted']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ request('status') === 'submitted' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Submitted</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'submitted' ? 'bg-blue-700 text-blue-100' : 'bg-blue-50 text-blue-700' }}">{{ $statusCounts['submitted'] }}</span>
            </a>
            <a href="{{ route('ojt.logbooks.index', ['status' => 'revision']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ request('status') === 'revision' ? 'bg-amber-500 text-gray-900 shadow-xs' : 'text-amber-700 hover:bg-amber-50' }}">
                <span>Returned for Revision</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-900">{{ $statusCounts['revision'] }}</span>
            </a>
            <a href="{{ route('ojt.logbooks.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ request('status') === 'approved' ? 'bg-[#00A859] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Approved</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'approved' ? 'bg-emerald-700 text-emerald-100' : 'bg-emerald-50 text-emerald-700' }}">{{ $statusCounts['approved'] }}</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-6">
        <form action="{{ route('ojt.logbooks.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <!-- Equipment Filter -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Pilih Unit Alat Berat</label>
                <select name="equipment_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
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
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
            </div>

            <!-- Date To / Filter Actions -->
            <div class="flex items-end space-x-2">
                <div class="flex-1">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                </div>
                <button type="submit" class="px-4 py-2 bg-[#003829] hover:bg-[#00241A] text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'equipment_id', 'date_from', 'date_to']))
                    <a href="{{ route('ojt.logbooks.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Responsive Enterprise Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-5">Logbook Number</th>
                        <th class="py-3.5 px-4">Date</th>
                        <th class="py-3.5 px-4">Category</th>
                        <th class="py-3.5 px-4">Instruktur</th>
                        <th class="py-3.5 px-4">Pengawas</th>
                        <th class="py-3.5 px-4">Operator Pendamping</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Last Updated</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($logbooks as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Logbook Number -->
                            <td class="py-4 px-5 font-bold text-slate-900">
                                <a href="{{ route('ojt.logbooks.show', $log->id) }}" class="hover:text-[#00A859] transition flex items-center space-x-2">
                                    <span>{{ $log->logbook_number }}</span>
                                </a>
                                <span class="text-[10px] text-slate-400 font-normal block mt-0.5">{{ $log->location }}</span>
                            </td>

                            <!-- Date -->
                            <td class="py-4 px-4">
                                <span class="font-bold text-slate-700 block">{{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}</span>
                                <span class="inline-flex items-center text-[10px] font-semibold text-slate-500 mt-0.5 uppercase">
                                    Shift {{ ucfirst($log->shift) }}
                                </span>
                            </td>

                            <!-- Equipment Category -->
                            <td class="py-4 px-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold">
                                    {{ $log->equipmentCategory->name ?? 'Heavy Equipment' }}
                                </span>
                            </td>

                            <!-- Instruktur -->
                            <td class="py-4 px-4">
                                <div class="font-semibold text-slate-800">{{ optional($log->trainer)->name ?? 'Belum Ditunjuk' }}</div>
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
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada data logbook yang ditemukan.</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau buat logbook baru.</p>
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
