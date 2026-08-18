<x-app-layout>
    <x-slot name="title">Trainer Review Queue</x-slot>
    <div class="mb-8 bg-gradient-to-r from-[#003829] to-[#00593E] p-6 rounded-2xl shadow-md text-white border border-emerald-900 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-emerald-300 text-xs font-bold uppercase tracking-widest mb-1">Trainer Module</p>
            <h1 class="text-2xl font-bold">Review & Evaluasi Kompetensi</h1>
            <p class="text-emerald-100 text-xs mt-1">Verifikasi Digital Logbook, isi penilaian SOP, dan teruskan hasil ke Final Approval.</p>
        </div>
        <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-xs">
            <p class="text-emerald-200">Trainer aktif</p><p class="font-bold mt-0.5">{{ $trainer->name }} · {{ $trainer->sid }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-7">
        @foreach([
            ['submitted', 'Menunggu Review', $counts['submitted'], 'blue'],
            ['verified', 'Sudah Diverifikasi', $counts['verified'], 'emerald'],
            ['revision', 'Dikembalikan Revisi', $counts['revision'], 'amber']
        ] as [$key, $label, $value, $color])
            <a href="{{ route('trainer.reviews.index', ['status' => $key]) }}" class="block bg-white rounded-2xl border transition-all p-5 shadow-sm hover:shadow-md {{ ($activeStatus ?? 'submitted') === $key ? 'ring-2 ring-[#00A859] border-[#00A859]' : 'border-slate-200' }}">
                <p class="text-xs font-bold uppercase tracking-wide text-{{ $color }}-600">{{ $label }}</p>
                <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $value }}</p>
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="font-bold text-slate-800">
                    {{ ($activeStatus ?? 'submitted') === 'verified' ? 'Logbook yang Sudah Diverifikasi' : (($activeStatus ?? 'submitted') === 'revision' ? 'Logbook yang Dikembalikan Revisi' : 'Antrean Logbook') }}
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    {{ ($activeStatus ?? 'submitted') === 'verified' ? 'Riwayat logbook yang sudah Anda verifikasi dan dikirim ke Admin TC.' : (($activeStatus ?? 'submitted') === 'revision' ? 'Logbook yang sudah Anda kembalikan untuk revisi.' : 'Prioritaskan pengajuan terbaru untuk diedit dan disetujui.') }}
                </p>
            </div>
            <form class="flex gap-2" method="GET">
                <input type="hidden" name="status" value="{{ $activeStatus ?? 'submitted' }}">
                <input name="search" value="{{ request('search') }}" placeholder="Cari SID atau logbook..." class="text-xs rounded-xl border-slate-300 focus:border-[#00A859] focus:ring-[#00A859]">
                <button class="px-4 rounded-xl bg-[#003829] text-white text-xs font-bold">Cari</button>
            </form>
        </div>
        <div class="overflow-x-auto"><table class="w-full text-left"><thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-3">Logbook / Trainee</th><th class="px-5 py-3">Unit & Shift</th><th class="px-5 py-3">Dikirim</th><th class="px-5 py-3">Status</th><th class="px-5 py-3"></th></tr></thead><tbody class="divide-y divide-slate-100">
        @forelse($logbooks as $logbook)
                        <tr class="hover:bg-emerald-50/30"><td class="px-5 py-4"><p class="text-xs font-bold text-slate-800">{{ $logbook->logbook_number }}</p><p class="text-[11px] text-slate-500 mt-1">{{ $logbook->trainee->name }} · {{ $logbook->trainee->sid }}</p></td><td class="px-5 py-4 text-xs"><p class="font-semibold text-slate-700">{{ $logbook->unit_code }}</p><p class="text-[11px] text-slate-500 mt-1">{{ ucfirst($logbook->shift) }} · {{ $logbook->total_hm }} HM</p></td><td class="px-5 py-4 text-xs text-slate-600">{{ optional($logbook->submitted_at)->format('d M Y, H:i') ?? '-' }}</td><td class="px-5 py-4"><x-badge :status="$logbook->status" /></td>                    <td class="px-5 py-4 text-right"><a href="{{ route('trainer.reviews.show', $logbook->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-emerald-50 text-[#00593E] hover:bg-emerald-100 text-xs font-bold">{{ in_array($logbook->status, ['verified', 'final_approved']) ? 'Lihat Evaluasi' : ( $logbook->status === 'revision' ? 'Lihat Revisi' : 'Review' ) }}</a></td></tr>
        @empty                         <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada logbook ditemukan pada kategori ini.</td></tr>@endforelse
        </tbody></table></div><div class="p-5 border-t border-slate-100">{{ $logbooks->links() }}</div>
    </div>
</x-app-layout>
