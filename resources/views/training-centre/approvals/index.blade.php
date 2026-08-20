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
            <p class="font-bold mt-0.5">{{ $reviewer->name }} · {{ $reviewer->sid }}</p>
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

    <!-- OJT Multi-Phase Monitoring -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-800">Rekap Posisi Fase Evaluasi Trainee</h3>
            <p class="text-xs text-slate-500 mt-1 mb-3">Distribusi fase saat ini seluruh trainee (real-time).</p>
            <div class="space-y-2">
                @forelse($phaseRecap as $label => $count)
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-600">{{ $label }}</span>
                        <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">{{ $count }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">Belum ada trainee.</p>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Antrean Persetujuan Evaluasi Akhir (Trainer → TC)</h3>
                    <p class="text-xs text-slate-500 mt-1">Evaluasi yang menunggu persetujuan Admin TC.</p>
                </div>
                <div class="flex space-x-2 text-[11px] font-bold">
                    <span class="px-2 py-1 rounded bg-blue-100 text-blue-700">TC: {{ $evalCounts['submitted'] }}</span>
                    <span class="px-2 py-1 rounded bg-purple-100 text-purple-700">PJO: {{ $evalCounts['tc_approved'] }}</span>
                    <span class="px-2 py-1 rounded bg-amber-100 text-amber-700">HSE: {{ $evalCounts['pjo_approved'] }}</span>
                    <span class="px-2 py-1 rounded bg-[#00A859]/10 text-[#00A859]">Selesai: {{ $evalCounts['completed'] }}</span>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($pendingEvaluations as $ev)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $ev->nama_operator }}</p>
                            <p class="text-[11px] text-slate-500">{{ $ev->phase }} · Trainer: {{ $ev->trainer->name ?? '-' }}</p>
                        </div>
                        <a href="{{ route('training-centre.final-evaluations.show', $ev->id) }}" class="px-3 py-1.5 rounded-lg bg-[#003829] text-white text-[11px] font-bold">Review</a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 px-5 py-4">Tidak ada evaluasi menunggu persetujuan.</p>
                @endforelse
            </div>
        </div>
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
                <input name="search" value="{{ request('search') }}" placeholder="Cari SID atau logbook..." class="text-xs rounded-xl border-slate-300">
                <button class="px-4 rounded-xl bg-[#003829] text-white text-xs font-bold">Cari</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        @if($activeStatus === 'finalized')
                            <th class="px-5 py-3">Trainee</th>
                            <th class="px-5 py-3">Trainer</th>
                            <th class="px-5 py-3">Total Logbook</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        @else
                            <th class="px-5 py-3">Logbook / Trainee</th>
                            <th class="px-5 py-3">Trainer</th>
                            <th class="px-5 py-3">Status / Tanggal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if($activeStatus === 'finalized' && isset($groupedFinalized))
                        @forelse($groupedFinalized as $traineeId => $group)
                            <tr class="hover:bg-emerald-50/30">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-bold text-slate-800">{{ $group['trainee']->name ?? '-' }}</p>
                                    <p class="text-[11px] text-slate-500 mt-1">{{ $group['trainee']->sid ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    {{ $group['trainer']->name ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">
                                        {{ $group['count'] }} Logbook
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-1">
                                        Terakhir diperbarui: {{ $group['latest_date']->format('d M Y, H:i') }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('ojt.logbooks.print-trainee', $traineeId) }}" target="_blank" class="inline-flex items-center px-4 py-2 rounded-lg bg-[#003829] text-white hover:bg-[#00241A] text-xs font-bold" title="Cetak Semua Logbook Final Approved untuk Trainee Ini">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak Semua
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada dokumen final disahkan.</td>
                            </tr>
                        @endforelse
                    @else
                        @forelse($logbooks as $logbook)
                            <tr class="hover:bg-emerald-50/30">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-bold text-slate-800">{{ $logbook->logbook_number }}</p>
                                    <p class="text-[11px] text-slate-500 mt-1">{{ $logbook->trainee->name }} · {{ $logbook->trainee->sid }}</p>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">{{ $logbook->trainer->name ?? '-' }}                            </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <x-badge :status="$logbook->status" />
                                    <span class="block text-[10px] text-slate-400 mt-1">
                                        {{ optional($logbook->training_centre_decided_at ?? $logbook->verified_at)->format('d M Y, H:i') }}
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
                    @endif
                </tbody>
            </table>
        </div>
        @if($activeStatus !== 'finalized')
            <div class="p-5 border-t border-slate-100">{{ $logbooks->links() }}</div>
        @endif
    </div>
</x-app-layout>
