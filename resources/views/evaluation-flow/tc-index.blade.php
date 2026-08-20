<x-app-layout>
    <x-slot name="title">Admin TC · Evaluasi Akhir</x-slot>

    <div class="mb-8 bg-gradient-to-r from-[#003829] to-[#00593E] p-6 rounded-2xl shadow-md text-white border border-emerald-900">
        <p class="text-emerald-300 text-xs font-bold uppercase tracking-widest mb-1">Admin Training Centre</p>
        <h1 class="text-2xl font-bold">Persetujuan Evaluasi Akhir A2B</h1>
        <p class="text-emerald-100 text-xs mt-1">Tinjau evaluasi dari Trainer, lalu setujui untuk diteruskan ke PJO.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 mb-7">
        @foreach([
            ['Menunggu TC', $counts['pending'], 'blue'],
            ['Sudah TC', $counts['tc_approved'], 'purple'],
            ['Di PJO', $counts['pjo_approved'], 'amber'],
            ['Selesai', $counts['completed'], 'emerald'],
            ['Ditolak', $counts['rejected'], 'red'],
        ] as [$label, $value, $color])
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
                <p class="text-[11px] font-bold uppercase tracking-wide text-{{ $color }}-600">{{ $label }}</p>
                <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Antrean Persetujuan Admin TC</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($evaluations as $ev)
                <div class="flex items-center justify-between px-5 py-3">
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $ev->nama_operator }}</p>
                        <p class="text-[11px] text-slate-500">{{ $ev->phase }} · {{ $ev->jenis_sertifikasi }} · Trainer: {{ $ev->trainer->name ?? '-' }}</p>
                    </div>
                    <a href="{{ route('training-centre.final-evaluations.show', $ev->id) }}" class="px-3 py-1.5 rounded-lg bg-[#003829] text-white text-[11px] font-bold">Review</a>
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
