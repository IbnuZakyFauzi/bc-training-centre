<x-app-layout>
    <x-slot name="title">Dashboard HSE CT</x-slot>

    <div class="mb-8 bg-gradient-to-r from-[#003829] to-[#00593E] p-6 rounded-2xl shadow-md text-white border border-emerald-900">
        <p class="text-emerald-300 text-xs font-bold uppercase tracking-widest mb-1">HSE Training Section</p>
        <h1 class="text-2xl font-bold">Persetujuan Final Evaluasi A2B</h1>
        <p class="text-emerald-100 text-xs mt-1">Setujui evaluasi final untuk memutakhirkan fase trainee ke tahap berikutnya.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-7">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-blue-600">Menunggu HSE CT</p>
            <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $counts['pending'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-bold uppercase tracking-wide text-[#00A859]">Selesai & Naik Fase</p>
            <p class="mt-2 text-3xl font-extrabold text-slate-800">{{ $counts['completed'] ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Antrean Persetujuan HSE CT</h2>
            <p class="text-xs text-slate-500 mt-1">Evaluasi yang menunggu persetujuan final Anda.</p>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($evaluations as $ev)
                <div class="flex items-center justify-between px-5 py-3">
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $ev->nama_operator }}</p>
                        <p class="text-[11px] text-slate-500">{{ $ev->phase }} · {{ $ev->jenis_sertifikasi }} · Trainer: {{ $ev->trainer->name ?? '-' }}</p>
                    </div>
                    <a href="{{ route('hse-ct.final-evaluations.show', $ev->id) }}" class="px-3 py-1.5 rounded-lg bg-[#003829] text-white text-[11px] font-bold">Review</a>
                </div>
            @empty
                <p class="text-xs text-slate-400 px-5 py-4">Tidak ada evaluasi menunggu persetujuan.</p>
            @endforelse
        </div>
        <div class="p-4">
            {{ $evaluations->links() }}
        </div>
    </div>
</x-app-layout>
