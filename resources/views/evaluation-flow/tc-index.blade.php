<x-app-layout>
    <x-slot name="title">Admin TC · Evaluasi Form OJT</x-slot>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-xs sm:text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8 bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] p-6 rounded-2xl shadow-md text-white border border-blue-900">
        <p class="text-blue-300 text-xs font-bold uppercase tracking-widest mb-1">Admin Training Centre</p>
        <h1 class="text-2xl font-bold">Persetujuan Evaluasi Form OJT</h1>
        <p class="text-blue-100 text-xs mt-1">Tinjau evaluasi dari Trainer, lalu setujui untuk diteruskan ke PJO.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-4 sm:mb-7">
        @foreach([
            ['Menunggu TC', $counts['pending'], 'blue'],
            ['Sudah TC', $counts['tc_approved'], 'purple'],
            ['Di PJO', $counts['pjo_approved'], 'amber'],
            ['Selesai', $counts['completed'], 'blue'],
            ['Revisi', $counts['rejected'], 'amber'],
        ] as [$label, $value, $color])
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-3 sm:p-4">
                <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wide text-{{ $color }}-600">{{ $label }}</p>
                <p class="mt-1 text-xl sm:text-2xl font-extrabold text-slate-800">{{ $value }}</p>
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
                        <p class="text-[11px] text-slate-500">{{ format_phase_label($ev->phase, $ev->jenis_sertifikasi) }} · {{ $ev->jenis_sertifikasi }} · Trainer: {{ $ev->trainer->name ?? '-' }}</p>
                    </div>
                    <a href="{{ route('training-centre.final-evaluations.show', $ev->id) }}" class="px-3 py-1.5 rounded-lg bg-[#1e3a8a] text-white text-[11px] font-bold">Review</a>
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

