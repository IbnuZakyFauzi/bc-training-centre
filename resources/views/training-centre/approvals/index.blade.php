<x-app-layout>
    <x-slot name="title">Admin Training Centre</x-slot>

    <div class="mb-4 sm:mb-8 bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] p-4 sm:p-6 rounded-2xl shadow-md text-white border border-blue-900 flex flex-col md:flex-row md:items-center md:justify-between gap-3 sm:gap-4">
        <div>
            <p class="text-blue-300 text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-1">Admin Training Centre</p>
            <h1 class="text-xl sm:text-2xl font-bold">Final Approval & Cetak Form OJT</h1>
            <p class="text-blue-100 text-[10px] sm:text-xs mt-1">Persetujuan akhir dan pengelolaan cetak/download form OJT resmi OJT.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
            <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-2.5 sm:py-3 text-xs">
                <p class="text-blue-200">Admin Training Centre</p>
                <p class="font-bold mt-0.5">{{ $reviewer->name }} · {{ $reviewer->sid }}</p>
            </div>
            <a href="{{ route('training-centre.monitoring') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#f59e0b] hover:bg-amber-500 text-gray-900 text-xs font-bold shadow-sm transition-all min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Monitoring Evaluasi
            </a>
        </div>
    </div>

    <!-- Status Filter Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-5 mb-4 sm:mb-7">
        @foreach([
            ['pending', 'Menunggu Final Approval', $counts['pending'], 'blue'],
            ['finalized', 'Dokumen Final (Siap Cetak)', $counts['finalized'], 'blue'],
            ['revision', 'Dikembalikan Revisi', $counts['revision'], 'amber']
        ] as [$key, $label, $value, $color])
            <a href="{{ route('training-centre.approvals.index', ['status' => $key]) }}" class="block bg-white rounded-2xl border transition-all p-5 shadow-sm hover:shadow-md {{ ($activeStatus ?? 'pending') === $key ? 'ring-2 ring-[#2563eb] border-[#2563eb]' : 'border-slate-200' }}">
                <p class="text-xs font-bold uppercase tracking-wide text-{{ $color }}-600">{{ $label }}</p>
                <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $value }}</p>
            </a>
        @endforeach
    </div>

    <!-- Main Data Table (Antrean Approval) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-4 sm:mb-7">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="font-bold text-slate-800 text-sm sm:text-base">
                    {{ ($activeStatus ?? 'pending') === 'finalized' ? 'Dokumen Final Disahkan' : (($activeStatus ?? 'pending') === 'revision' ? 'Form OJT Dikembalikan' : 'Antrean Approval') }}
                </h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">
                    {{ ($activeStatus ?? 'pending') === 'finalized' ? 'Hanya Admin Training Centre yang dapat mengunduh dan mencetak form logbook ini.' : 'Tinjau detail logbook dan berikan keputusan approval.' }}
                </p>
            </div>
            <form class="flex flex-col sm:flex-row gap-2" method="GET">
                <input type="hidden" name="status" value="{{ $activeStatus ?? 'pending' }}">
                <input name="search" value="{{ request('search') }}" placeholder="Cari SID atau logbook..." class="text-xs rounded-xl border-slate-300 min-h-[44px]">
                <button class="px-4 py-2.5 rounded-xl bg-[#1e3a8a] text-white text-xs font-bold min-h-[44px]">Cari</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        @if($activeStatus === 'finalized')
                            <th class="px-5 py-3">Trainee</th>
                            <th class="px-5 py-3">Instruktur</th>
                            <th class="px-5 py-3">Total Form OJT</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        @else
                            <th class="px-5 py-3">Form OJT / Trainee</th>
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
                                    <p class="text-[11px] text-slate-500 mt-1">{{ $group['trainee']->sid ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    {{ $group['trainer']->name ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 font-bold">
                                        {{ $group['count'] }} Form OJT
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-1">
                                        Terakhir diperbarui: {{ $group['latest_date']->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('training-centre.trainee-documents', $traineeId) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-[#1e3a8a] text-white hover:bg-[#172554] text-xs font-bold" title="Lihat Detail Dokumen Trainee">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
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
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-bold text-slate-800">{{ $logbook->logbook_number }}</p>
                                    <p class="text-[11px] text-slate-500 mt-1">{{ $logbook->trainee->name }} · {{ $logbook->trainee->sid }}</p>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">{{ $logbook->trainer->name ?? '-' }}                            </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <x-badge :status="$logbook->status" />
                                    <span class="block text-[10px] text-slate-400 mt-1">
                                        {{ optional($logbook->training_centre_decided_at ?? $logbook->verified_at)->format('d M Y') }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('training-centre.approvals.show', $logbook->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold">
                                            Detail
                                        </a>
                                        @if($logbook->status === 'final_approved')
                                            <a href="{{ route('ojt.logbooks.print', $logbook->id) }}" target="_blank" class="inline-flex items-center px-3 py-2 rounded-lg bg-[#1e3a8a] text-white hover:bg-[#172554] text-xs font-bold"                                             title="Cetak / Download PDF Form OJT">
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

    <!-- Rating Trainer -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-4 sm:mb-7">
        <div class="p-4 sm:p-5 border-b border-slate-100">
            <h3 class="text-xs sm:text-sm font-bold text-slate-800">Rating Trainer</h3>
            <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Akumulasi rating bintang dari trainee untuk setiap trainer.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Trainer</th>
                        <th class="px-5 py-3 text-center">Rata-rata Rating</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @if(!empty($trainerRatings))
                        @foreach($trainerRatings as $row)
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-5 py-4 text-xs font-bold text-slate-800">{{ $row['name'] }}</td>
                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($row['avg'] >= $i)
                                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.26.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z"/></svg>
                                            @elseif($row['avg'] >= $i - 0.5)
                                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><defs><linearGradient id="half-{{ $row['name'] }}-{{ $i }}"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#d1d5db"/></linearGradient></defs><path fill="url(#half-{{ $row['name'] }}-{{ $i }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.26.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z"/></svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.26.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.951-.69l1.286-3.957z"/></svg>
                                            @endif
                                        @endfor
                                        <span class="ml-1 text-[11px] font-bold text-slate-700">{{ $row['avg'] }}</span>
                                    </div>
                                </td>
                             </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2" class="px-5 py-10 text-center text-xs text-slate-400">Belum ada penilaian trainer dari trainee.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
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
                    <h3 class="text-sm font-bold text-slate-800">Antrean Persetujuan Evaluasi</h3>
                    <p class="text-xs text-slate-500 mt-1">Evaluasi yang menunggu persetujuan Admin TC.</p>
                </div>
                <div class="flex space-x-2 text-[11px] font-bold">
                    <span class="px-2 py-1 rounded bg-blue-100 text-blue-700">TC: {{ $evalCounts['submitted'] }}</span>
                    <span class="px-2 py-1 rounded bg-purple-100 text-purple-700">PJO: {{ $evalCounts['tc_approved'] }}</span>
                    <span class="px-2 py-1 rounded bg-amber-100 text-amber-700">HSE: {{ $evalCounts['pjo_approved'] }}</span>
                    <span class="px-2 py-1 rounded bg-[#2563eb]/10 text-[#2563eb]">Selesai: {{ $evalCounts['completed'] }}</span>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                @forelse($pendingEvaluations as $ev)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $ev->nama_operator }}</p>
                            <p class="text-[11px] text-slate-500">{{ format_phase_label($ev->phase, $ev->jenis_sertifikasi) }} · Trainer: {{ $ev->trainer->name ?? '-' }}</p>
                        </div>
                        <a href="{{ route('training-centre.final-evaluations.show', $ev->id) }}" class="px-3 py-1.5 rounded-lg bg-[#1e3a8a] text-white text-[11px] font-bold">Review</a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 px-5 py-4">Tidak ada evaluasi menunggu persetujuan.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>


