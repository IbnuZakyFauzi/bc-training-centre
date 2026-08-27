<x-app-layout>
    <x-slot name="title">Detail Evaluasi Akhir A2B</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ url()->previous() }}" class="text-xs text-slate-500 hover:text-[#2563eb]">&larr; Kembali</a>
            <h1 class="text-xl font-extrabold text-slate-800 mt-1">Evaluasi Akhir A2B · {{ $evaluation->nama_operator }}</h1>
            <p class="text-xs text-slate-500 mt-1">Fase: {{ $evaluation->phase }} · Sertifikasi: {{ $evaluation->jenis_sertifikasi }} · Status: {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $evaluation->status)) }}</p>
        </div>
        @if(auth()->user()->isTrainingCentre())
        <a href="{{ route('training-centre.final-evaluations.print', $evaluation->id) }}" target="_blank" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-[#1e3a8a] text-white hover:bg-[#172554] text-xs font-bold transition min-h-[44px]">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 002 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6 space-y-4">
            <div class="grid grid-cols-2 gap-3 text-xs sm:text-sm">
                <div><span class="text-slate-500">Perusahaan</span><p class="font-bold text-slate-800">{{ $evaluation->perusahaan }}</p></div>
                <div><span class="text-slate-500">Lokasi Kerja</span><p class="font-bold text-slate-800">{{ $evaluation->lokasi_kerja }}</p></div>
                <div><span class="text-slate-500">Jenis Unit</span><p class="font-bold text-slate-800">{{ $evaluation->jenis_unit_a2b }}</p></div>
                <div><span class="text-slate-500">Tahap Penilaian</span><p class="font-bold text-slate-800">{{ $evaluation->tahap_penilaian }}</p></div>
                <div><span class="text-slate-500">Instruktur</span><p class="font-bold text-slate-800">{{ $evaluation->instruktur }}</p></div>
                <div><span class="text-slate-500">Operator Pendamping</span><p class="font-bold text-slate-800">{{ $evaluation->operator_pendamping }}</p></div>
            </div>

            <div class="border-t border-slate-100 pt-4">
                <p class="text-xs font-bold text-slate-500 uppercase mb-2">Penilaian Kompetensi</p>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    @foreach([
                        ['P2H', $evaluation->p2h_status],
                        ['Teknik Pengoperasian', $evaluation->teknik_pengoperasian_status],
                        ['Kepatuhan', $evaluation->kepatuhan_status],
                        ['Kedisiplinan', $evaluation->kedisiplinan_status],
                    ] as [$label, $val])
                        <div class="flex items-center justify-between bg-slate-50 rounded-lg px-3 py-2">
                            <span class="text-slate-600">{{ $label }}</span>
                            <span class="font-bold {{ $val === 'K' ? 'text-amber-600' : 'text-red-600' }}">{{ $val }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 rounded-xl p-3 {{ $evaluation->kesimpulan === 'kompeten' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-bold">
                    Kesimpulan: {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $evaluation->kesimpulan)) }}
                </div>
            </div>

            @if($evaluation->catatan)
                <div class="border-t border-slate-100 pt-4">
                    <p class="text-xs font-bold text-slate-500 uppercase mb-1">Catatan / Rekomendasi</p>
                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $evaluation->catatan }}</p>
                </div>
            @endif
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
                <p class="text-xs font-bold text-slate-500 uppercase mb-3">Alur Persetujuan</p>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center justify-between">
                        <span>Trainer ({{ $evaluation->trainer->name ?? '-' }})</span>
                        <span class="text-amber-600 font-bold">✓ Submit</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>Admin TC</span>
                        <span class="{{ $evaluation->tc_approved_at ? 'text-amber-600 font-bold' : 'text-slate-400' }}">{{ $evaluation->tc_approved_at ? '✓ Disetujui' : 'Menunggu' }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>PJO</span>
                        <span class="{{ $evaluation->pjo_approved_at ? 'text-amber-600 font-bold' : 'text-slate-400' }}">{{ $evaluation->pjo_approved_at ? '✓ Disetujui' : 'Menunggu' }}</span>
                    </li>
                    <li class="flex items-center justify-between">
                        <span>HSE CT</span>
                        <span class="{{ $evaluation->hse_approved_at ? 'text-amber-600 font-bold' : 'text-slate-400' }}">{{ $evaluation->hse_approved_at ? '✓ Disetujui' : 'Menunggu' }}</span>
                    </li>
                </ul>
            </div>

            @if(auth()->user()->isTrainingCentre() && $evaluation->isPendingTc())
                <form method="POST" action="{{ route('training-centre.final-evaluations.tc-approve', $evaluation->id) }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 space-y-3">
                    @csrf
                    <textarea name="tc_notes" placeholder="Catatan (opsional)" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]"></textarea>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <button class="flex-1 px-3 py-3 rounded-xl bg-[#2563eb] text-white text-xs font-bold min-h-[44px]">Setujui (TC)</button>
                        <button formaction="{{ route('training-centre.final-evaluations.tc-reject', $evaluation->id) }}" class="px-3 py-3 rounded-xl bg-red-100 text-red-700 text-xs font-bold min-h-[44px]">Revisi</button>
                    </div>
                </form>
            @elseif(auth()->user()->isPjo() && $evaluation->isPendingPjo())
                <form method="POST" action="{{ route('pjo.final-evaluations.approve', $evaluation->id) }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 space-y-3">
                    @csrf
                    <textarea name="pjo_notes" placeholder="Catatan (opsional)" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]"></textarea>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <button class="flex-1 px-3 py-3 rounded-xl bg-[#2563eb] text-white text-xs font-bold min-h-[44px]">Setujui (PJO)</button>
                        <button formaction="{{ route('pjo.final-evaluations.reject', $evaluation->id) }}" class="px-3 py-3 rounded-xl bg-red-100 text-red-700 text-xs font-bold min-h-[44px]">Revisi</button>
                    </div>
                </form>
            @elseif(auth()->user()->isHseCt() && $evaluation->isPendingHse())
                <form method="POST" action="{{ route('hse-ct.final-evaluations.approve', $evaluation->id) }}" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 space-y-3">
                    @csrf
                    <textarea name="hse_notes" placeholder="Catatan (opsional)" class="w-full text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]"></textarea>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <button class="flex-1 px-3 py-3 rounded-xl bg-[#2563eb] text-white text-xs font-bold min-h-[44px]">Setujui Final (HSE CT)</button>
                        <button formaction="{{ route('hse-ct.final-evaluations.reject', $evaluation->id) }}" class="px-3 py-3 rounded-xl bg-red-100 text-red-700 text-xs font-bold min-h-[44px]">Revisi</button>
                    </div>
                    <p class="text-[10px] text-slate-400">Setelah disetujui, trainee otomatis naik ke fase berikutnya.</p>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>


