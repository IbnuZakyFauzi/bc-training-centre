<x-app-layout>
    <x-slot name="title">Submission History - OJT Evaluation</x-slot>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Submission History</h1>
            <p class="text-xs text-slate-500 mt-1">Complete chronological audit trail of all OJT logbook submissions and trainer verification logs.</p>
        </div>
    </div>

    <!-- Timeline & Audit Logs Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Columns: Submissions Table with Revision Counter & Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Daftar Pengajuan Logbook</h2>
                    <span class="text-xs font-semibold text-slate-500">Chronological Order</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                                <th class="py-3.5 px-5">Submission Date</th>
                                <th class="py-3.5 px-4">Logbook & Equipment</th>
                                <th class="py-3.5 px-4">Trainer</th>
                                <th class="py-3.5 px-4 text-center">Revision Count</th>
                                <th class="py-3.5 px-4">Current Status</th>
                                <th class="py-3.5 px-4">Last Updated</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            @forelse($logbooks as $log)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <!-- Submission Date -->
                                    <td class="py-4 px-5">
                                        <span class="font-bold text-slate-800 block">
                                            {{ $log->submitted_at ? $log->submitted_at->format('d M Y') : \Carbon\Carbon::parse($log->date)->format('d M Y') }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Shift {{ ucfirst($log->shift) }}</span>
                                    </td>

                                    <!-- Equipment & Logbook Number -->
                                    <td class="py-4 px-4">
                                        <a href="{{ route('ojt.logbooks.show', $log->id) }}" class="font-bold text-slate-900 hover:text-[#00A859] block">
                                            {{ $log->logbook_number }}
                                        </a>
                                        <span class="text-[10px] text-[#003829] font-semibold block mt-0.5">{{ $log->equipment->unit_code ?? '-' }} ({{ $log->equipment->model_name ?? '-' }})</span>
                                    </td>

                                    <!-- Trainer -->
                                    <td class="py-4 px-4 text-slate-700 font-medium">
                                        {{ $log->trainer->name ?? 'Belum Ditunjuk' }}
                                    </td>

                                    <!-- Revision Count -->
                                    <td class="py-4 px-4 text-center">
                                        @if($log->revision_count > 0)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                                {{ $log->revision_count }} Revision(s)
                                            </span>
                                        @else
                                            <span class="text-[10px] text-slate-400">0</span>
                                        @endif
                                    </td>

                                    <!-- Current Status -->
                                    <td class="py-4 px-4">
                                        <x-badge :status="$log->status" />
                                    </td>

                                    <!-- Last Updated -->
                                    <td class="py-4 px-4 text-slate-500 text-[11px]">
                                        {{ $log->updated_at->diffForHumans() }}
                                    </td>

                                    <!-- Actions (View Detail) -->
                                    <td class="py-4 px-5 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('ojt.logbooks.show', $log->id) }}" class="p-1.5 text-slate-600 hover:text-[#00A859] hover:bg-emerald-50 rounded-lg transition" title="View Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        Belum ada riwayat pengajuan logbook.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $logbooks->links() }}
                </div>
            </div>

        </div>

        <!-- Right Column: Audit Trail Timeline Visual -->
        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-6">Aktivitas Sistem Real-Time</h2>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @foreach($timelineHistories as $th)
                        <div class="relative">
                            <div class="absolute -left-6 top-0.5 w-4 h-4 rounded-full bg-[#00A859] border-2 border-white ring-2 ring-emerald-100"></div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 block">{{ $th->action }}</span>
                                <span class="text-[10px] text-[#003829] font-mono block mt-0.5">{{ $th->logbook->logbook_number ?? '-' }} ({{ $th->logbook->equipment->unit_code ?? '-' }})</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5">{{ $th->created_at->diffForHumans() }}</span>
                                @if($th->comment)
                                    <p class="text-[11px] text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-200 mt-2 italic">
                                        "{{ $th->comment }}"
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
