<x-app-layout>
    <x-slot name="title">Evaluasi Akhir A2B</x-slot>

    <!-- Page Header & Action Bar -->
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <h1 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight">Evaluasi Akhir A2B</h1>
            <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Kelola dan tinjau formulir evaluasi On the Job Training yang telah Anda isi.</p>
        </div>
        <div>
            <a href="{{ route('trainer.final-evaluations.create') }}" class="inline-flex items-center justify-center px-4 py-3 bg-[#2563eb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow-sm transition-all min-h-[44px]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Isi Evaluasi Baru
            </a>
        </div>
    </div>

    <!-- Status Tabs Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 mb-6 overflow-x-auto">
        <div class="flex items-center space-x-1 min-w-max">
            <a href="{{ route('trainer.final-evaluations.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ !request('status') || request('status') === 'all' ? 'bg-[#1e3a8a] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Semua</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('status') || request('status') === 'all' ? 'bg-blue-800 text-blue-100' : 'bg-slate-100 text-slate-600' }}">{{ $statusCounts['all'] ?? 0 }}</span>
            </a>
            <a href="{{ route('trainer.final-evaluations.index', ['status' => 'kompeten']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ request('status') === 'kompeten' ? 'bg-[#2563eb] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>Kompeten</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'kompeten' ? 'bg-blue-700 text-blue-100' : 'bg-blue-50 text-blue-700' }}">{{ $statusCounts['kompeten'] ?? 0 }}</span>
            </a>
            <a href="{{ route('trainer.final-evaluations.index', ['status' => 'belum_kompeten']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 {{ request('status') === 'belum_kompeten' ? 'bg-amber-500 text-gray-900 shadow-xs' : 'text-amber-700 hover:bg-amber-50' }}">
                <span>Belum Kompeten</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-amber-100 text-amber-900">{{ $statusCounts['belum_kompeten'] ?? 0 }}</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="bg-white p-3 sm:p-4 rounded-2xl shadow-sm border border-slate-200 mb-4 sm:mb-6">
        <form action="{{ route('trainer.final-evaluations.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <!-- Search -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cari Evaluasi</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama operator / unit..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
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
                @if(request()->hasAny(['search', 'status', 'date_from', 'date_to']))
                    <a href="{{ route('trainer.final-evaluations.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition min-h-[44px] inline-flex items-center justify-center">
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
                        <th class="py-3.5 px-5">Nama Operator</th>
                        <th class="py-3.5 px-4">Unit A2B</th>
                        <th class="py-3.5 px-4">Tanggal Penilaian</th>
                        <th class="py-3.5 px-4">Tahap</th>
                        <th class="py-3.5 px-4">Kesimpulan</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($evaluations as $evaluation)
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Operator Name -->
                            <td class="py-4 px-5 font-bold text-slate-900">
                                <a href="{{ route('trainer.final-evaluations.show', $evaluation->id) }}" class="hover:text-[#2563eb] transition">
                                    {{ $evaluation->nama_operator }}
                                </a>
                                <span class="text-[10px] text-slate-400 font-normal block mt-0.5">{{ $evaluation->perusahaan }}</span>
                            </td>

                            <!-- Unit -->
                            <td class="py-4 px-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-bold">
                                    {{ $evaluation->jenis_unit_a2b }}
                                </span>
                            </td>

                            <!-- Tanggal Penilaian -->
                            <td class="py-4 px-4">
                                <span class="font-bold text-slate-700 block">{{ \Carbon\Carbon::parse($evaluation->tanggal_penilaian)->format('d M Y') }}</span>
                            </td>

                            <!-- Tahap Penilaian -->
                            <td class="py-4 px-4 text-slate-600 text-[11px]">
                                {{ ucfirst(str_replace('_', ' ', $evaluation->tahap_penilaian)) }}
                                @if($evaluation->sub_tahap)
                                    <span class="text-[10px] text-slate-400 block">{{ ucfirst(str_replace('_', ' ', $evaluation->sub_tahap)) }}</span>
                                @endif
                            </td>

                            <!-- Kesimpulan -->
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $evaluation->kesimpulan === 'kompeten' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                    {{ strtoupper(str_replace('_', ' ', $evaluation->kesimpulan)) }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-4 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('trainer.final-evaluations.show', $evaluation->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold transition">
                                        Detail
                                    </a>
                                    <a href="{{ route('trainer.final-evaluations.print', $evaluation->id) }}" target="_blank" class="inline-flex items-center px-3 py-2 rounded-lg bg-[#1e3a8a] text-white hover:bg-[#172554] text-xs font-bold transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-sm font-semibold text-slate-600">Tidak ada data evaluasi yang ditemukan.</p>
                                <p class="text-xs text-slate-400 mt-1">Klik tombol "Isi Evaluasi Baru" untuk membuat formulir evaluasi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $evaluations->links() }}
        </div>
    </div>
</x-app-layout>


