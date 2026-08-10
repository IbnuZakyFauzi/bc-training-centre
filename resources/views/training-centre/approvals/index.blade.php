<x-app-layout>
    <x-slot name="title">Admin Training Centre</x-slot>

    <div class="mb-8 bg-gradient-to-r from-[#003829] to-[#00593E] p-6 rounded-2xl shadow-md text-white border border-emerald-900 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-emerald-300 text-xs font-bold uppercase tracking-widest mb-1">Admin Training Centre</p>
            <h1 class="text-2xl font-bold">Final Approval & Cetak Logbook</h1>
            <p class="text-emerald-100 text-xs mt-1">Persetujuan akhir dan pengelolaan cetak/download logbook resmi OJT.</p>
        </div>
        <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-xs">
            <p class="text-emerald-200">Admin Training Centre</p>
            <p class="font-bold mt-0.5">{{ $reviewer->name }} · {{ $reviewer->nrp }}</p>
        </div>
    </div>

    <!-- Status Filter Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-7">
        @foreach([
            ['pending', 'Menunggu Final Approval', $counts['pending'], 'blue'],
            ['finalized', 'Dokumen Final (Siap Cetak)', $counts['finalized'], 'emerald'],
            ['revision', 'Dikembalikan Revisi', $counts['revision'], 'amber']
        ] as [$key, $label, $value, $color])
            <a href="{{ route('training-centre.approvals.index', ['status' => $key]) }}" class="block bg-white rounded-2xl border transition-all p-5 shadow-sm hover:shadow-md {{ ($activeStatus ?? 'pending') === $key ? 'ring-2 ring-[#00A859] border-[#00A859]' : 'border-slate-200' }}">
                <p class="text-xs font-bold uppercase tracking-wide text-{{ $color }}-600">{{ $label }}</p>
                <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $value }}</p>
            </a>
        @endforeach
    </div>

    <!-- Main Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="font-bold text-slate-800">
                    {{ ($activeStatus ?? 'pending') === 'finalized' ? 'Dokumen Final Disahkan' : (($activeStatus ?? 'pending') === 'revision' ? 'Logbook Dikembalikan' : 'Antrean Final Approval') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    {{ ($activeStatus ?? 'pending') === 'finalized' ? 'Hanya Admin Training Centre yang dapat mengunduh dan mencetak form logbook ini.' : 'Tinjau detail logbook dan berikan keputusan final approval.' }}
                </p>
            </div>
            <form class="flex gap-2" method="GET">
                <input type="hidden" name="status" value="{{ $activeStatus ?? 'pending' }}">
                <input name="search" value="{{ request('search') }}" placeholder="Cari NRP atau logbook..." class="text-xs rounded-xl border-slate-300">
                <button class="px-4 rounded-xl bg-[#003829] text-white text-xs font-bold">Cari</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Logbook / Trainee</th>
                        <th class="px-5 py-3">Trainer</th>
                        <th class="px-5 py-3">Pengawas</th>
                        <th class="px-5 py-3">Status / Tanggal</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logbooks as $logbook)
                        <tr class="hover:bg-emerald-50/30">
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-800">{{ $logbook->logbook_number }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">{{ $logbook->trainee->name }} · {{ $logbook->trainee->nrp }}</p>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600">{{ $logbook->trainer->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-xs text-slate-600">{{ $logbook->departmentOperation->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-xs text-slate-600">
                                <x-badge :status="$logbook->status" />
                                <span class="block text-[10px] text-slate-400 mt-1">
                                    {{ optional($logbook->training_centre_decided_at ?? $logbook->pjo_decided_at)->format('d M Y, H:i') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('training-centre.approvals.show', $logbook->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-emerald-50 text-[#00593E] hover:bg-emerald-100 text-xs font-bold">
                                        Detail
                                    </a>
                                    @if($logbook->status === 'final_approved')
                                        <a href="{{ route('ojt.logbooks.print', $logbook->id) }}" target="_blank" class="inline-flex items-center px-3 py-2 rounded-lg bg-[#003829] text-white hover:bg-[#00241A] text-xs font-bold" title="Cetak / Download PDF Logbook">
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
        <div class="p-5 border-t border-slate-100">{{ $logbooks->links() }}</div>
    </div>
</x-app-layout>
