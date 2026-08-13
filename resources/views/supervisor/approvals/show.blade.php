<x-app-layout>
    <x-slot name="title">Review Logbook - {{ $logbook->logbook_number }}</x-slot>

    <div class="mb-6">
        <div class="flex items-center space-x-2 text-xs text-slate-500 mb-2">
            <a href="{{ route('supervisor.approvals.index') }}" class="hover:underline">Approval Pengawas</a>
            <span>/</span>
            <span class="text-slate-700 font-semibold">{{ $logbook->logbook_number }}</span>
        </div>
        <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Review Logbook OJT</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Informasi Logbook</h2>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <p class="text-slate-400">Trainee</p>
                        <p class="font-semibold text-slate-800 mt-1">{{ $logbook->trainee->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">SID</p>
                        <p class="font-semibold text-slate-800 mt-1">{{ $logbook->trainee->sid ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Tanggal / Shift</p>
                        <p class="font-semibold text-slate-800 mt-1">{{ $logbook->date->format('d M Y') }} · {{ ucfirst($logbook->shift) }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Unit</p>
                        <p class="font-semibold text-slate-800 mt-1">{{ $logbook->unit_code }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Lokasi</p>
                        <p class="font-semibold text-slate-800 mt-1">{{ $logbook->location }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400">Durasi</p>
                        <p class="font-semibold text-slate-800 mt-1">{{ $logbook->total_hm }} HM</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Kegiatan Harian</h2>
                </div>
                <div class="p-6 text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $logbook->daily_activity }}</div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Evaluasi Trainer</h2>
                </div>
                <div class="p-6 space-y-4 text-xs">
                    <div>
                        <p class="text-slate-400">Trainer Evaluator</p>
                        <p class="font-semibold text-slate-800 mt-1">{{ $logbook->trainer->name ?? '-' }}</p>
                    </div>
                    @if($logbook->evaluation)
                        <div>
                            <p class="text-slate-400">Skor</p>
                            <p class="font-semibold text-slate-800 mt-1">{{ $logbook->evaluation->overall_score }} / 100</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Kompetensi</p>
                            <p class="font-semibold text-slate-800 mt-1">{{ $logbook->evaluation->competency_status === 'competent' ? 'Kompeten' : 'Belum Kompeten' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-400">Catatan Trainer</p>
                            <p class="text-slate-700 mt-1">{{ $logbook->evaluation->trainer_comment ?? '-' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Tindakan Pengawas</h2>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('supervisor.approvals.decide', $logbook->id) }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan</label>
                                <textarea name="notes" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tuliskan catatan review Anda...">{{ old('notes') }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="submit" name="action" value="approve" class="py-3 bg-[#00A859] hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition">Setujui</button>
                                <button type="submit" name="action" value="reject" class="py-3 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition">Tolak</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
