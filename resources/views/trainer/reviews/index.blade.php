<x-app-layout>
    <x-slot name="title">Trainer Review Queue</x-slot>
    <div class="mb-4 sm:mb-8 bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] p-4 sm:p-6 rounded-2xl shadow-md text-white border border-blue-900 flex flex-col md:flex-row md:items-center md:justify-between gap-3 sm:gap-4">
        <div>
            <p class="text-blue-300 text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-1">Trainer Module</p>
            <h1 class="text-xl sm:text-2xl font-bold">Review & Evaluasi Kompetensi</h1>
            <p class="text-blue-100 text-[10px] sm:text-xs mt-1">Verifikasi Digital Form OJT, isi penilaian SOP, dan teruskan hasil ke Final Approval.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
            <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-2.5 sm:py-3 text-xs">
                <p class="text-blue-200">Trainer aktif</p><p class="font-bold mt-0.5">{{ $trainer->name }} · {{ $trainer->sid }}</p>
            </div>
            <a href="{{ route('trainer.monitoring') }}" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#f59e0b] hover:bg-amber-500 text-gray-900 text-xs font-bold shadow-sm transition-all min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Monitoring Evaluasi
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-5 mb-4 sm:mb-7">
        @foreach([
            ['submitted', 'Menunggu Review', $counts['submitted'], 'blue'],
            ['verified', 'Sudah Diverifikasi', $counts['verified'], 'blue'],
            ['revision', 'Dikembalikan Revisi', $counts['revision'], 'amber']
        ] as [$key, $label, $value, $color])
            <a href="{{ route('trainer.reviews.index', ['status' => $key]) }}" class="block bg-white rounded-2xl border transition-all p-5 shadow-sm hover:shadow-md {{ ($activeStatus ?? 'submitted') === $key ? 'ring-2 ring-[#2563eb] border-[#2563eb]' : 'border-slate-200' }}">
                <p class="text-xs font-bold uppercase tracking-wide text-{{ $color }}-600">{{ $label }}</p>
                <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $value }}</p>
            </a>
        @endforeach
    </div>

    <!-- Eligible Trainees for Final Evaluation (HM requirement met) -->
    @if(isset($eligibleTrainees) && $eligibleTrainees->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-4 sm:mb-7">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="font-bold text-slate-800 text-sm sm:text-base">Trainee Eligible Evaluasi Akhir A2B</h2>
                    <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Syarat jam operasional fase saat ini sudah terpenuhi. Silakan isi form evaluasi.</p>
                </div>
                <a href="{{ route('trainer.final-evaluations.create') }}" class="px-4 py-3 bg-[#2563eb] text-white text-xs font-bold rounded-xl min-h-[44px] inline-flex items-center justify-center">Isi Evaluasi</a>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($eligibleTrainees as $t)
                    <div class="flex items-center justify-between px-5 py-3">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $t['name'] }}</p>
                            <p class="text-[11px] text-slate-500">{{ $t['certification'] === 'Green' ? 'Green' : ($t['certification'] === 'Skill-up' ? 'Skill-up' : ($t['certification'] === 'Experience_internal' ? 'Experience Internal' : ($t['certification'] === 'Experience_external' ? 'Experience External' : '-'))) }} · {{ $t['phase_label'] }}</p>
                        </div>
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <span class="text-[10px] sm:text-[11px] font-semibold text-[#2563eb]">{{ $t['progress']['total'] }}% HM</span>
                        <a href="{{ route('trainer.final-evaluations.create', ['trainee' => $t['id']]) }}" class="px-3 py-2.5 rounded-lg bg-[#1e3a8a] text-white text-[10px] sm:text-[11px] font-bold min-h-[44px] inline-flex items-center justify-center">Evaluasi</a>
                    </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Evaluation Queue for Trainer -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-4 sm:mb-7">
        <div class="p-4 sm:p-5 border-b border-slate-100">
            <h2 class="font-bold text-slate-800 text-sm sm:text-base">Antrean Form Evaluasi OJT A2B</h2>
            <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Evaluasi yang sedang menunggu persetujuan atau memerlukan revisi.</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            @foreach([
                ['submitted', 'Menunggu TC', $evaluationCounts['submitted'] ?? 0, 'blue'],
                ['tc_approved', 'Sudah TC', $evaluationCounts['tc_approved'] ?? 0, 'purple'],
                ['pjo_approved', 'Sudah PJO', $evaluationCounts['pjo_approved'] ?? 0, 'amber'],
                ['rejected', 'Revisi', $evaluationCounts['rejected'] ?? 0, 'red'],
            ] as [$key, $label, $value, $color])
                <a href="{{ route('trainer.reviews.index', array_merge(request()->query(), ['eval_status' => $key])) }}" class="block bg-slate-50 rounded-xl border transition-all p-3 sm:p-4 hover:shadow-md {{ ($evaluationStatus ?? 'submitted') === $key ? 'ring-2 ring-[#2563eb] border-[#2563eb]' : 'border-slate-200' }}">
                    <p class="text-[10px] sm:text-xs font-bold uppercase tracking-wide text-{{ $color }}-600">{{ $label }}</p>
                    <p class="mt-1 text-xl sm:text-2xl font-extrabold text-slate-800">{{ $value }}</p>
                </a>
            @endforeach
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Nama Operator</th>
                        <th class="px-5 py-3">Evaluasi Ke-</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($evaluationQueue as $evaluation)
                        <tr class="hover:bg-blue-50/30">
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-800">{{ $evaluation->nama_operator }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">{{ $evaluation->perusahaan }}</p>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600">
                                {{ format_phase_label($evaluation->phase, $evaluation->jenis_sertifikasi) }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold
                                    {{ $evaluation->status === 'submitted' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                    {{ $evaluation->status === 'tc_approved' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                                    {{ $evaluation->status === 'pjo_approved' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                    {{ $evaluation->status === 'rejected' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}">
                                    {{ $evaluation->status === 'submitted' ? 'Menunggu TC' : ($evaluation->status === 'tc_approved' ? 'Disetujui TC' : ($evaluation->status === 'pjo_approved' ? 'Disetujui PJO' : ($evaluation->status === 'rejected' ? 'Revisi' : str_replace('_', ' ', ucfirst($evaluation->status))))) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="inline-flex items-center space-x-2">
                                    @if($evaluation->status === 'rejected')
                                        <a href="{{ route('trainer.final-evaluations.edit', $evaluation->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold">
                                            Edit Revisi
                                        </a>
                                    @endif
                                    <a href="{{ route('trainer.final-evaluations.show', $evaluation->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada form evaluasi pada kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($evaluationQueue->hasPages())
            <div class="p-5 border-t border-slate-100">
                {{ $evaluationQueue->links() }}
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="font-bold text-slate-800 text-sm sm:text-base">
                    {{ ($activeStatus ?? 'submitted') === 'verified' ? 'Form OJT yang Sudah Diverifikasi' : (($activeStatus ?? 'submitted') === 'revision' ? 'Form OJT yang Dikembalikan Revisi' : 'Antrean Form OJT') }}
                </h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">
                    {{ ($activeStatus ?? 'submitted') === 'verified' ? 'Riwayat form OJT yang sudah Anda verifikasi dan dikirim ke Admin TC.' : (($activeStatus ?? 'submitted') === 'revision' ? 'Form OJT yang sudah Anda kembalikan untuk revisi.' : 'Prioritaskan pengajuan terbaru untuk diedit dan disetujui.') }}
                </p>
            </div>
            <form class="flex flex-col sm:flex-row gap-2" method="GET">
                <input type="hidden" name="status" value="{{ $activeStatus ?? 'submitted' }}">
                <input name="search" value="{{ request('search') }}" placeholder="Cari SID atau form OJT..." class="text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]">
                <button class="px-4 py-2.5 rounded-xl bg-[#1e3a8a] text-white text-xs font-bold min-h-[44px]">Cari</button>
            </form>
        </div>
        <div class="overflow-x-auto"><table class="w-full text-left"><thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr>                        <th class="px-5 py-3">Form OJT / Trainee</th><th class="px-5 py-3">Unit & Shift</th><th class="px-5 py-3">Dikirim</th><th class="px-5 py-3">Status</th><th class="px-5 py-3"></th></tr></thead><tbody class="divide-y divide-slate-100">
        @forelse($logbooks as $logbook)
                        <tr class="hover:bg-blue-50/30">
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-800">{{ $logbook->logbook_number }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">{{ $logbook->trainee->name }} · {{ $logbook->trainee->sid }}</p>
                                @if($logbook->is_locked ?? false)
                                    <p class="text-[10px] text-red-600 font-bold mt-1">🔒 Di-lock oleh logbook {{ $logbook->blocking_logbook->logbook_number ?? '' }} ({{ $logbook->blocking_logbook->date->format('d-m-Y') ?? '' }})</p>
                                @endif
                            </td><td class="px-5 py-4 text-xs"><p class="font-semibold text-slate-700">{{ $logbook->unit_code }}</p><p class="text-[11px] text-slate-500 mt-1">{{ ucfirst($logbook->shift) }} · {{ $logbook->total_hm }} HM</p></td><td class="px-5 py-4 text-xs text-slate-600">{{ optional($logbook->submitted_at)->format('d M Y') ?? '-' }}</td><td class="px-5 py-4"><x-badge :status="$logbook->status" /></td>                    <td class="px-5 py-4 text-right">
                        <div class="inline-flex items-center space-x-2">
                            @if($logbook->status === 'revision')
                                <a href="{{ route('trainer.reviews.edit', $logbook->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold">
                                    Edit Revisi
                                </a>
                            @endif
                            <a href="{{ route('trainer.reviews.show', $logbook->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold">{{ in_array($logbook->status, ['verified', 'final_approved']) ? 'Lihat Evaluasi' : ( $logbook->status === 'revision' ? 'Lihat Detail' : 'Review' ) }}</a>
                        </div>
                    </td></tr>
        @empty                         <tr>                            <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada form OJT ditemukan pada kategori ini.</td></tr>@endforelse
        </tbody></table></div><div class="p-5 border-t border-slate-100">{{ $logbooks->links() }}</div>
    </div>
</x-app-layout>


