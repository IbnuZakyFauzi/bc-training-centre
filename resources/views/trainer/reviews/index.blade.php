<x-app-layout>
    <x-slot name="title">Trainer Review Queue</x-slot>
    <div class="mb-8 bg-gradient-to-r from-[#003829] to-[#00593E] p-6 rounded-2xl shadow-md text-white border border-emerald-900 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-emerald-300 text-xs font-bold uppercase tracking-widest mb-1">Trainer Module</p>
            <h1 class="text-2xl font-bold">Review & Evaluasi Kompetensi</h1>
            <p class="text-emerald-100 text-xs mt-1">Verifikasi Digital Logbook, isi penilaian SOP, dan teruskan hasil ke PJO.</p>
        </div>
        <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-xs">
            <p class="text-emerald-200">Trainer aktif</p><p class="font-bold mt-0.5">{{ $trainer->name }} · {{ $trainer->nrp }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-7">
        @foreach([['Menunggu Review', $counts['submitted'], 'blue'], ['Perlu Tindak Lanjut', $counts['revision'], 'amber'], ['Sudah Diverifikasi', $counts['verified'], 'emerald']] as [$label, $value, $color])
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm"><p class="text-xs font-bold uppercase tracking-wide text-{{ $color }}-600">{{ $label }}</p><p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $value }}</p></div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div><h2 class="font-bold text-slate-800">Antrean Logbook</h2><p class="text-xs text-slate-500 mt-1">Prioritaskan pengajuan terbaru dan revisi yang masuk kembali.</p></div>
            <form class="flex gap-2" method="GET"><input name="search" value="{{ request('search') }}" placeholder="Cari NRP atau logbook..." class="text-xs rounded-xl border-slate-300 focus:border-[#00A859] focus:ring-[#00A859]"><select name="status" class="text-xs rounded-xl border-slate-300"><option value="all">Semua status</option><option value="submitted" @selected(request('status') === 'submitted')>Menunggu review</option><option value="revision" @selected(request('status') === 'revision')>Perlu revisi</option><option value="verified" @selected(request('status') === 'verified')>Terverifikasi</option></select><button class="px-4 rounded-xl bg-[#003829] text-white text-xs font-bold">Filter</button></form>
        </div>
        <div class="overflow-x-auto"><table class="w-full text-left"><thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-3">Logbook / Trainee</th><th class="px-5 py-3">Unit & Shift</th><th class="px-5 py-3">Dikirim</th><th class="px-5 py-3">Status</th><th class="px-5 py-3"></th></tr></thead><tbody class="divide-y divide-slate-100">
        @forelse($logbooks as $logbook)
            <tr class="hover:bg-emerald-50/30"><td class="px-5 py-4"><p class="text-xs font-bold text-slate-800">{{ $logbook->logbook_number }}</p><p class="text-[11px] text-slate-500 mt-1">{{ $logbook->trainee->name }} · {{ $logbook->trainee->nrp }}</p></td><td class="px-5 py-4 text-xs"><p class="font-semibold text-slate-700">{{ $logbook->equipment->unit_code ?? '-' }}</p><p class="text-[11px] text-slate-500 mt-1">{{ ucfirst($logbook->shift) }} · {{ $logbook->total_hm }} HM</p></td><td class="px-5 py-4 text-xs text-slate-600">{{ optional($logbook->submitted_at)->format('d M Y, H:i') ?? '-' }}</td><td class="px-5 py-4"><x-badge :status="$logbook->status" /></td><td class="px-5 py-4 text-right"><a href="{{ route('trainer.reviews.show', $logbook->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-emerald-50 text-[#00593E] hover:bg-emerald-100 text-xs font-bold">{{ $logbook->status === 'verified' ? 'Lihat Evaluasi' : 'Review' }}</a></td></tr>
        @empty <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada logbook pada antrean ini.</td></tr>@endforelse
        </tbody></table></div><div class="p-5 border-t border-slate-100">{{ $logbooks->links() }}</div>
    </div>
</x-app-layout>
