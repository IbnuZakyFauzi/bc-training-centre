<x-app-layout>
    <x-slot name="title">Detail Evaluasi Form OJT - {{ $evaluation->logbook->logbook_number ?? $evaluation->id }}</x-slot>

    @php
        $isTrainingCentre = auth()->user()?->isTrainingCentre();
        $isPjo = auth()->user()?->isPjo();
        $isHse = auth()->user()?->isHseCt();
        $canApprove = $evaluation->isPendingTc() || $evaluation->isPendingPjo() || $evaluation->isPendingHse();

        $operatorPendamping = $evaluation->operator_pendamping;
        if (empty($operatorPendamping) && $evaluation->logbook) {
            $operatorIds = $evaluation->logbook->selected_operator_pendamping_ids ?? [];
            $operatorPendamping = collect($operatorIds)
                ->map(fn ($id) => \App\Models\User::find($id)?->name)
                ->filter()
                ->join(', ');
        }
    @endphp

    @if(session('success'))
        <div class="mb-4 sm:mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-xs sm:text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 sm:mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-xs sm:text-sm font-medium">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Page Header & Action Bar -->
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-[#2563eb] mb-1">
                <a href="{{ url()->previous() }}" class="hover:underline">Form Evaluasi OJT</a>
                <span>/</span>
                <span class="text-slate-500">{{ $evaluation->nama_operator }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <h1 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight">Form Evaluasi OJT ({{ $evaluation->nama_operator }})</h1>
                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border {{ $evaluation->status === 'hse_approved' ? 'bg-green-100 text-green-800 border-green-200' : ($evaluation->status === 'rejected' ? 'bg-red-100 text-red-800 border-red-200' : 'bg-amber-100 text-amber-800 border-amber-200') }}">
                    {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $evaluation->status)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Revision Callout -->
    @if($evaluation->status === 'rejected' && ($evaluation->tc_notes || $evaluation->pjo_notes || $evaluation->hse_notes))
        <div class="mb-4 sm:mb-8 bg-amber-50 border-l-4 border-amber-500 p-4 sm:p-6 rounded-2xl shadow-sm">
            <div class="flex items-start space-x-3">
                <div class="p-2 bg-amber-100 rounded-xl text-amber-800 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-amber-900">Catatan Revisi</h3>
                    <p class="text-[10px] sm:text-xs text-amber-800 mt-1 leading-relaxed">
                        {{ $evaluation->hse_notes ?: ($evaluation->pjo_notes ?: $evaluation->tc_notes) }}
                    </p>
                    @if($isTrainingCentre || $isPjo || $isHse)
                    <p class="text-[10px] text-amber-700 mt-2 font-medium">Revisi ini akan ditangani oleh Trainer. Silakan tunggu hingga form evaluasi dikirim kembali.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Identity Section (full width, above main layout) -->
    <div class="bg-white rounded-2xl border-2 border-slate-900 shadow-sm overflow-hidden mb-4 sm:mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r">
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NAMA OPERATOR</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $evaluation->nama_operator }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">PERUSAHAAN</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $evaluation->perusahaan }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">LOKASI KERJA</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $evaluation->lokasi_kerja }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">JENIS UNIT A2B</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $evaluation->jenis_unit_a2b }}</div>
                </div>
            </div>

            <div>
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">JENIS SERTIFIKASI</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $evaluation->jenis_sertifikasi }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">INSTRUKTUR</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $evaluation->instruktur }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">OPERATOR PENDAMPING</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $operatorPendamping ?: '-' }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">TANGGAL PENILAIAN</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ \Carbon\Carbon::parse($evaluation->tanggal_penilaian)->format('d M Y') }}</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 border-t border-slate-900">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r p-2 sm:p-3 text-[10px] sm:text-[11px] text-slate-900">
                <div class="font-semibold mb-1 sm:mb-2">Keterangan:</div>
                <ol class="space-y-1 pl-4 list-decimal">
                    <li>K = KOMPETEN, BK = BELUM KOMPETEN</li>
                    <li>Semua aspek penilaian harus diisi</li>
                    <li>Kesimpulan otomatis: jika ada BK maka Belum Kompeten, jika semua K maka Kompeten</li>
                </ol>
            </div>
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r p-2 sm:p-3 text-[10px] sm:text-[11px]">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <div class="font-semibold mb-2">Tahap Penilaian OJT</div>
                        <div class="font-medium">
                            {{ $evaluation->tahap_penilaian === 'pendampingan' ? 'Pendampingan' : ($evaluation->tahap_penilaian === 'tanpa_pendampingan' ? 'Tanpa Pendampingan' : '-') }}
                        </div>
                    </div>
                    <div>
                        <div class="font-semibold mb-2">Tahap Tanpa Pendampingan Lanjutan</div>
                        <div class="font-medium">
                            {{ $evaluation->sub_tahap === 'bulanan' ? 'Bulanan' : ($evaluation->sub_tahap === '3_bulan_pertama' ? '3 Bulan Pertama' : ($evaluation->sub_tahap === '3_bulan_kedua' ? '3 Bulan Kedua' : '-')) }}
                        </div>
                    </div>
                    <div>
                        <div class="font-semibold mb-2">Keterangan</div>
                        <div class="font-medium">
                            {{ $evaluation->sub_tahap_keterangan === 'bulan_1' ? 'Bulan ke-1' : ($evaluation->sub_tahap_keterangan === 'bulan_2' ? 'Bulan ke-2' : ($evaluation->sub_tahap_keterangan === 'bulan_3' ? 'Bulan ke-3' : ($evaluation->sub_tahap_keterangan === 'bulan_4' ? 'Bulan ke-4' : ($evaluation->sub_tahap_keterangan === 'bulan_5' ? 'Bulan ke-5' : ($evaluation->sub_tahap_keterangan === 'bulan_6' ? 'Bulan ke-6' : '-'))))) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-2 sm:p-3 text-[10px] sm:text-[11px]">
                <div class="font-semibold mb-2">Fase & Sertifikasi</div>
                    <div class="font-medium">
                        {{ format_phase_label($evaluation->phase, $evaluation->jenis_sertifikasi) }}
                    </div>
                <div class="font-medium mt-1">{{ $evaluation->jenis_sertifikasi }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8">

        <!-- Left 2 Columns: Evaluation Details -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-8">

            <!-- Section C: Tabel Evaluasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#1e3a8a]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rangkuman Penilaian Pasca Pelatihan</h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="w-full min-w-[760px] table-fixed text-xs">
                            <colgroup>
                                <col class="w-14">
                                <col>
                                <col class="w-24">
                                <col class="w-24">
                            </colgroup>
                            <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                <tr>
                                    <th class="px-3 py-2 text-left w-12">No</th>
                                    <th class="px-3 py-2 text-left">Aspek Penilaian</th>
                                    <th class="px-3 py-2 text-center w-24">K</th>
                                    <th class="px-3 py-2 text-center w-24">BK</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr class="align-top">
                                    <td class="px-3 py-3 font-bold text-slate-700">1</td>
                                    <td class="px-3 py-3">
                                        <div class="font-bold">P2H</div>
                                        <div class="text-[11px] text-slate-500 mt-1">Peserta Melakukan P2H setiap hari secara teratur dan lengkap sesuai dengan standar.</div>
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold {{ $evaluation->p2h_status === 'K' ? 'text-green-600' : 'text-slate-300' }}">
                                        @if($evaluation->p2h_status === 'K')✓@endif
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold {{ $evaluation->p2h_status === 'BK' ? 'text-red-600' : 'text-slate-300' }}">
                                        @if($evaluation->p2h_status === 'BK')✓@endif
                                    </td>
                                </tr>
                                <tr class="align-top">
                                    <td class="px-3 py-3 font-bold text-slate-700">2</td>
                                    <td class="px-3 py-3">
                                        <div class="font-bold">Teknik Pengoperasian Unit</div>
                                        <div class="text-[11px] text-slate-500 mt-1">Peserta mengoperasikan unit dengan benar dan tidak menimbulkan kerusakan baik minor maupun major serta mencapai target kerja yang diharapkan.</div>
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold {{ $evaluation->teknik_pengoperasian_status === 'K' ? 'text-green-600' : 'text-slate-300' }}">
                                        @if($evaluation->teknik_pengoperasian_status === 'K')✓@endif
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold {{ $evaluation->teknik_pengoperasian_status === 'BK' ? 'text-red-600' : 'text-slate-300' }}">
                                        @if($evaluation->teknik_pengoperasian_status === 'BK')✓@endif
                                    </td>
                                </tr>
                                <tr class="align-top">
                                    <td class="px-3 py-3 font-bold text-slate-700">3</td>
                                    <td class="px-3 py-3">
                                        <div class="font-bold">Kepatuhan Terhadap Peraturan Kerja</div>
                                        <div class="text-[11px] text-slate-500 mt-1">Peserta mematuhi peraturan kerja yang berlaku selama mengoperasikan unit termasuk mematuhi rambu-rambu.</div>
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold {{ $evaluation->kepatuhan_status === 'K' ? 'text-green-600' : 'text-slate-300' }}">
                                        @if($evaluation->kepatuhan_status === 'K')✓@endif
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold {{ $evaluation->kepatuhan_status === 'BK' ? 'text-red-600' : 'text-slate-300' }}">
                                        @if($evaluation->kepatuhan_status === 'BK')✓@endif
                                    </td>
                                </tr>
                                <tr class="align-top">
                                    <td class="px-3 py-3 font-bold text-slate-700">4</td>
                                    <td class="px-3 py-3">
                                        <div class="font-bold">Kedisiplinan dan Komunikasi</div>
                                        <div class="text-[11px] text-slate-500 mt-1">Peserta bersikap disiplin terhadap aturan-aturan, tidak melakukan pelanggaran, kehadiran tepat waktu, dan dapat bekerjasama dengan pengawas dan rekan kerja sesama operator.</div>
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold {{ $evaluation->kedisiplinan_status === 'K' ? 'text-green-600' : 'text-slate-300' }}">
                                        @if($evaluation->kedisiplinan_status === 'K')✓@endif
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold {{ $evaluation->kedisiplinan_status === 'BK' ? 'text-red-600' : 'text-slate-300' }}">
                                        @if($evaluation->kedisiplinan_status === 'BK')✓@endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Section D: Kesimpulan & Rekomendasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Kesimpulan & Rekomendasi</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-slate-800">Keterangan:</h3>
                            <p class="text-xs text-slate-500 mt-1">K = KOMPETEN &nbsp;&nbsp; BK = BELUM KOMPETEN</p>
                        </div>
                        <span class="px-4 py-2 rounded-lg font-bold text-sm {{ $evaluation->kesimpulan === 'kompeten' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ strtoupper(str_replace('_', ' ', $evaluation->kesimpulan)) }}
                        </span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-sm font-bold text-slate-800">Berdasarkan rangkuman penilaian di atas, kinerja operator ini dinyatakan:</p>
                        <p class="text-lg font-extrabold text-slate-900 mt-1 {{ $evaluation->kesimpulan === 'kompeten' ? 'text-green-700' : 'text-red-700' }}">
                            {{ strtoupper(str_replace('_', ' ', $evaluation->kesimpulan)) }}
                        </p>
                    </div>
                    @if($evaluation->catatan)
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Rekomendasi:</label>
                            <p class="text-sm text-slate-600 whitespace-pre-line bg-slate-50 rounded-xl p-4 border border-slate-200">{{ $evaluation->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Approval Flow & Actions -->
        <div class="space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Alur Persetujuan</h2>
                </div>
                <div class="p-6 space-y-3 text-xs">
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                        <span class="font-semibold text-slate-700">Trainer</span>
                        <span class="text-amber-600 font-bold">✓ Submit</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                        <span class="font-semibold text-slate-700">Admin TC</span>
                        <span class="{{ $evaluation->tc_approved_at ? 'text-amber-600 font-bold' : 'text-slate-400' }}">{{ $evaluation->tc_approved_at ? '✓ Disetujui' : 'Menunggu' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                        <span class="font-semibold text-slate-700">PJO</span>
                        <span class="{{ $evaluation->pjo_approved_at ? 'text-amber-600 font-bold' : 'text-slate-400' }}">{{ $evaluation->pjo_approved_at ? '✓ Disetujui' : 'Menunggu' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                        <span class="font-semibold text-slate-700">HSE CT</span>
                        <span class="{{ $evaluation->hse_approved_at ? 'text-amber-600 font-bold' : 'text-slate-400' }}">{{ $evaluation->hse_approved_at ? '✓ Disetujui' : 'Menunggu' }}</span>
                    </div>
                </div>
            </div>

            @if($isTrainingCentre && $evaluation->isPendingTc())
                <form method="POST" action="{{ route('training-centre.final-evaluations.tc-approve', $evaluation->id) }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    @csrf
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Persetujuan Admin TC</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <textarea name="tc_notes" placeholder="Catatan (opsional)" class="w-full text-xs rounded-xl border border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]"></textarea>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <button class="flex-1 px-3 py-3 rounded-xl bg-[#2563eb] text-white text-xs font-bold min-h-[44px]">Setujui (TC)</button>
                            <button formaction="{{ route('training-centre.final-evaluations.reject', $evaluation->id) }}" class="px-3 py-3 rounded-xl bg-red-100 text-red-700 text-xs font-bold min-h-[44px]">Revisi</button>
                        </div>
                    </div>
                </form>
            @elseif($isPjo && $evaluation->isPendingPjo())
                <form method="POST" action="{{ route('pjo.final-evaluations.approve', $evaluation->id) }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    @csrf
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Persetujuan PJO</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <textarea name="pjo_notes" placeholder="Catatan (opsional)" class="w-full text-xs rounded-xl border border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]"></textarea>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <button class="flex-1 px-3 py-3 rounded-xl bg-[#2563eb] text-white text-xs font-bold min-h-[44px]">Setujui (PJO)</button>
                            <button formaction="{{ route('pjo.final-evaluations.reject', $evaluation->id) }}" class="px-3 py-3 rounded-xl bg-red-100 text-red-700 text-xs font-bold min-h-[44px]">Revisi</button>
                        </div>
                    </div>
                </form>
            @elseif($isHse && $evaluation->isPendingHse())
                <form method="POST" action="{{ route('hse-ct.final-evaluations.approve', $evaluation->id) }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    @csrf
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Persetujuan HSE CT</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <textarea name="hse_notes" placeholder="Catatan (opsional)" class="w-full text-xs rounded-xl border border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]"></textarea>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <button class="flex-1 px-3 py-3 rounded-xl bg-[#2563eb] text-white text-xs font-bold min-h-[44px]">Setujui Final (HSE CT)</button>
                            <button formaction="{{ route('hse-ct.final-evaluations.reject', $evaluation->id) }}" class="px-3 py-3 rounded-xl bg-red-100 text-red-700 text-xs font-bold min-h-[44px]">Revisi</button>
                        </div>
                        <p class="text-[10px] text-slate-400">Setelah disetujui, trainee otomatis naik ke fase berikutnya.</p>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
