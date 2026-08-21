<x-app-layout>
    <x-slot name="title">Approval Pengawas</x-slot>

    <div class="mb-4 sm:mb-6">
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Approval Pengawas</h1>
        <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Review dan setujui logbook OJT yang ditugaskan kepada Anda.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-5 mb-4 sm:mb-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Menunggu Review</p>
            <p class="text-2xl font-black text-[#f59e0b] mt-1">{{ $counts['pending'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Disetujui</p>
            <p class="text-2xl font-black text-[#2563eb] mt-1">{{ $counts['approved'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ditolak</p>
            <p class="text-2xl font-black text-rose-600 mt-1">{{ $counts['rejected'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-200">
            <h2 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wide">Daftar Logbook</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4 text-left">Logbook</th>
                        <th class="px-5 py-4 text-left">Trainee</th>
                        <th class="px-5 py-4 text-left">Unit</th>
                        <th class="px-5 py-4 text-left">Tanggal</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logbooks as $logbook)
                        <tr class="hover:bg-blue-50/30">
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-800">{{ $logbook->logbook_number }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">{{ $logbook->trainee->name ?? '-' }} · {{ $logbook->trainee->sid ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4 text-xs">
                                <p class="font-semibold text-slate-700">{{ $logbook->unit_code }}</p>
                                <p class="text-[11px] text-slate-500 mt-1">{{ ucfirst($logbook->shift) }} · {{ $logbook->total_hm }} HM</p>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600">{{ optional($logbook->date)->format('d M Y') ?? '-' }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('supervisor.approvals.show', $logbook->id) }}" class="inline-flex px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold">Review</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-xs text-slate-400">Tidak ada logbook yang menunggu review.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logbooks->hasPages())
            <div class="px-5 py-4 border-t border-slate-200">
                {{ $logbooks->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

