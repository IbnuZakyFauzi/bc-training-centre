<x-app-layout>
    <x-slot name="title">Formulir Evaluasi Akhir A2B</x-slot>

    @php
        $isEditing = isset($evaluation);
    @endphp

    <!-- Page Header & Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-[#00A859] mb-1">
                <a href="{{ route('trainer.final-evaluations.index') }}" class="hover:underline">Evaluasi Akhir A2B</a>
                <span>/</span>
                <span class="text-slate-500">{{ $isEditing ? 'Edit Evaluasi' : 'Create Evaluasi Baru' }}</span>
            </div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Formulir Evaluasi On the Job Training A2B</h1>
            <p class="text-xs text-slate-500 mt-1">F-HCT-02.02, Revisi 2 · Isi formulir evaluasi pasca pelatihan.</p>
        </div>
        <a href="{{ route('trainer.final-evaluations.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">Kembali</a>
    </div>

    <form method="POST" action="{{ $isEditing ? route('trainer.final-evaluations.update', $evaluation->id) : route('trainer.final-evaluations.store') }}" class="space-y-3 pb-10">
        @csrf
        @if($isEditing)
            @method('PUT')
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-xs text-rose-900">
                <p class="font-bold">Submit gagal. Periksa field berikut:</p>
                <ul class="mt-2 list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Section A: Informasi Umum -->
        <div class="rounded-2xl border-2 border-slate-900 bg-white overflow-hidden shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="border-b border-slate-900 lg:border-b-0 lg:border-r">
                    <div class="grid grid-cols-[160px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                        <div class="px-3 py-2 font-semibold border-b border-slate-900">NAMA OPERATOR</div>
                        <div class="px-3 py-1.5 border-b border-slate-900">
                            @if(!$isEditing)
                                <select name="nama_operator" id="nama_operator" data-trainees='@json($trainees)' onchange="handleOperatorChange(this)" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                                    <option value="">Pilih operator...</option>
                                    @foreach($trainees as $trainee)
                                        <option value="{{ $trainee['name'] }}" {{ old('nama_operator') == $trainee['name'] ? 'selected' : '' }}>
                                            {{ $trainee['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" name="nama_operator" value="{{ old('nama_operator', $evaluation->nama_operator ?? '') }}" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0" readonly>
                            @endif
                        </div>

                        <div class="px-3 py-2 font-semibold border-b border-slate-900">PERUSAHAAN</div>
                        <div class="px-3 py-1.5 border-b border-slate-900">
                            <input type="text" name="perusahaan" id="perusahaan" value="{{ old('perusahaan', $isEditing ? $evaluation->perusahaan : '') }}" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0" readonly>
                        </div>

                        <div class="px-3 py-2 font-semibold border-b border-slate-900">LOKASI KERJA</div>
                        <div class="px-3 py-1.5 border-b border-slate-900">
                            <select name="lokasi_kerja" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                                <option value="">Pilih lokasi</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location }}" {{ old('lokasi_kerja', $isEditing ? $evaluation->lokasi_kerja : '') == $location ? 'selected' : '' }}>{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="px-3 py-2 font-semibold border-b border-slate-900">JENIS UNIT A2B</div>
                        <div class="px-3 py-1.5 border-b border-slate-900">
                            <input type="text" name="jenis_unit_a2b" id="jenis_unit_a2b" value="{{ old('jenis_unit_a2b', $isEditing ? $evaluation->jenis_unit_a2b : '') }}" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0" readonly>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="grid grid-cols-[160px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                        <div class="px-3 py-2 font-semibold border-b border-slate-900">JENIS SERTIFIKASI</div>
                        <div class="px-3 py-1.5 border-b border-slate-900">
                            <input type="text" name="jenis_sertifikasi" id="jenis_sertifikasi" value="{{ old('jenis_sertifikasi', $isEditing ? $evaluation->jenis_sertifikasi : '') }}" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0" readonly>
                        </div>

                        <div class="px-3 py-2 font-semibold border-b border-slate-900">INSTRUKTUR</div>
                        <div class="px-3 py-1.5 border-b border-slate-900">
                            <input type="text" name="instruktur" id="instruktur" value="{{ old('instruktur', $isEditing ? $evaluation->instruktur : (Auth::user()->name ?? '')) }}" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0" readonly>
                        </div>

                        <div class="px-3 py-2 font-semibold border-b border-slate-900">OPERATOR PENDAMPING</div>
                        <div class="px-3 py-1.5 border-b border-slate-900">
                            <input type="text" name="operator_pendamping" id="operator_pendamping" value="{{ old('operator_pendamping', $isEditing ? $evaluation->operator_pendamping : '') }}" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0" readonly>
                        </div>

                        <div class="px-3 py-2 font-semibold border-b border-slate-900">TANGGAL PENILAIAN</div>
                        <div class="px-3 py-1.5 border-b border-slate-900">
                            <input type="date" name="tanggal_penilaian" value="{{ old('tanggal_penilaian', $isEditing ? $evaluation->tanggal_penilaian : date('Y-m-d')) }}" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section B: Penilaian Pasca Pelatihan -->
        <div class="rounded-2xl border-2 border-slate-900 bg-white overflow-hidden shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-2 border-t border-slate-900">
                <div class="border-b border-slate-900 lg:border-b-0 lg:border-r p-3 text-[11px]">
                    <div class="font-semibold mb-2">Penilaian Pasca Pelatihan (Beri tanda “√” untuk yang sesuai)</div>
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tahap_penilaian" value="pendampingan" {{ old('tahap_penilaian', $isEditing ? $evaluation->tahap_penilaian : '') === 'pendampingan' ? 'checked' : '' }} class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>Tahap Pendampingan</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tahap_penilaian" value="tanpa_pendampingan" {{ old('tahap_penilaian', $isEditing ? $evaluation->tahap_penilaian : '') === 'tanpa_pendampingan' ? 'checked' : '' }} class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>Tahap Tanpa Pendampingan</span>
                        </label>
                    </div>
                </div>
                <div class="p-3 text-[11px]">
                    <div class="font-semibold mb-2">Tahap Tanpa Pendampingan Lanjutan</div>
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="sub_tahap" value="bulanan" {{ old('sub_tahap', $isEditing ? $evaluation->sub_tahap : '') === 'bulanan' ? 'checked' : '' }} class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>Bulanan</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="sub_tahap" value="3_bulan_pertama" {{ old('sub_tahap', $isEditing ? $evaluation->sub_tahap : '') === '3_bulan_pertama' ? 'checked' : '' }} class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>3 Bulan Pertama</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="sub_tahap" value="3_bulan_kedua" {{ old('sub_tahap', $isEditing ? $evaluation->sub_tahap : '') === '3_bulan_kedua' ? 'checked' : '' }} class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>3 Bulan Kedua</span>
                        </label>
                    </div>
                    <div class="mt-3">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan</label>
                        <select name="sub_tahap_keterangan" class="w-full border border-slate-200 rounded-lg bg-white px-2 py-1.5 text-[11px] font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                            <option value="">Pilih bulan</option>
                            <option value="-" {{ old('sub_tahap_keterangan', $isEditing ? ($evaluation->sub_tahap ?? '') : '') === '-' ? 'selected' : '' }}>-</option>
                            <option value="bulan_1" {{ old('sub_tahap_keterangan', $isEditing ? ($evaluation->sub_tahap ?? '') : '') === 'bulan_1' ? 'selected' : '' }}>Bulan ke-1</option>
                            <option value="bulan_2" {{ old('sub_tahap_keterangan', $isEditing ? ($evaluation->sub_tahap ?? '') : '') === 'bulan_2' ? 'selected' : '' }}>Bulan ke-2</option>
                            <option value="bulan_3" {{ old('sub_tahap_keterangan', $isEditing ? ($evaluation->sub_tahap ?? '') : '') === 'bulan_3' ? 'selected' : '' }}>Bulan ke-3</option>
                            <option value="bulan_4" {{ old('sub_tahap_keterangan', $isEditing ? ($evaluation->sub_tahap ?? '') : '') === 'bulan_4' ? 'selected' : '' }}>Bulan ke-4</option>
                            <option value="bulan_5" {{ old('sub_tahap_keterangan', $isEditing ? ($evaluation->sub_tahap ?? '') : '') === 'bulan_5' ? 'selected' : '' }}>Bulan ke-5</option>
                            <option value="bulan_6" {{ old('sub_tahap_keterangan', $isEditing ? ($evaluation->sub_tahap ?? '') : '') === 'bulan_6' ? 'selected' : '' }}>Bulan ke-6</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section C: Tabel Evaluasi -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#003829]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rangkuman penilaian pasca pelatihan untuk operator ybs:</h2>
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
                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-emerald-600" name="p2h_status" value="K" {{ old('p2h_status', $isEditing ? $evaluation->p2h_status : '') === 'K' ? 'checked' : '' }}></td>
                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="p2h_status" value="BK" {{ old('p2h_status', $isEditing ? $evaluation->p2h_status : '') === 'BK' ? 'checked' : '' }}></td>
                            </tr>
                            <tr class="align-top">
                                <td class="px-3 py-3 font-bold text-slate-700">2</td>
                                <td class="px-3 py-3">
                                    <div class="font-bold">Teknik Pengoperasian Unit</div>
                                    <div class="text-[11px] text-slate-500 mt-1">Peserta mengoperasikan unit dengan benar dan tidak menimbulkan kerusakan baik minor maupun major serta mencapai target kerja yang diharapkan.</div>
                                </td>
                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-emerald-600" name="teknik_pengoperasian_status" value="K" {{ old('teknik_pengoperasian_status', $isEditing ? $evaluation->teknik_pengoperasian_status : '') === 'K' ? 'checked' : '' }}></td>
                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="teknik_pengoperasian_status" value="BK" {{ old('teknik_pengoperasian_status', $isEditing ? $evaluation->teknik_pengoperasian_status : '') === 'BK' ? 'checked' : '' }}></td>
                            </tr>
                            <tr class="align-top">
                                <td class="px-3 py-3 font-bold text-slate-700">3</td>
                                <td class="px-3 py-3">
                                    <div class="font-bold">Kepatuhan Terhadap Peraturan Kerja</div>
                                    <div class="text-[11px] text-slate-500 mt-1">Peserta mematuhi peraturan kerja yang berlaku selama mengoperasikan unit termasuk mematuhi rambu-rambu.</div>
                                </td>
                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-emerald-600" name="kepatuhan_status" value="K" {{ old('kepatuhan_status', $isEditing ? $evaluation->kepatuhan_status : '') === 'K' ? 'checked' : '' }}></td>
                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="kepatuhan_status" value="BK" {{ old('kepatuhan_status', $isEditing ? $evaluation->kepatuhan_status : '') === 'BK' ? 'checked' : '' }}></td>
                            </tr>
                            <tr class="align-top">
                                <td class="px-3 py-3 font-bold text-slate-700">4</td>
                                <td class="px-3 py-3">
                                    <div class="font-bold">Kedisiplinan dan Komunikasi</div>
                                    <div class="text-[11px] text-slate-500 mt-1">Peserta bersikap disiplin terhadap aturan-aturan, tidak melakukan pelanggaran, kehadiran tepat waktu, dan dapat bekerjasama dengan pengawas dan rekan kerja sesama operator.</div>
                                </td>
                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-emerald-600" name="kedisiplinan_status" value="K" {{ old('kedisiplinan_status', $isEditing ? $evaluation->kedisiplinan_status : '') === 'K' ? 'checked' : '' }}></td>
                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="kedisiplinan_status" value="BK" {{ old('kedisiplinan_status', $isEditing ? $evaluation->kedisiplinan_status : '') === 'BK' ? 'checked' : '' }}></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section D: Kesimpulan & Rekomendasi -->
        <div class="rounded-2xl border-2 border-slate-900 bg-white overflow-hidden shadow-sm">
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Keterangan:</h3>
                        <p class="text-xs text-slate-500 mt-1">K : KOMPETEN &nbsp;&nbsp; BK : BELUM KOMPETEN</p>
                    </div>
                    <span id="kesimpulanBadge" class="px-4 py-2 rounded-lg font-bold text-sm bg-slate-100 text-slate-600">Belum Dinilai</span>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-sm font-bold text-slate-800">Berdasarkan rangkuman penilaian di atas, kinerja operator ini dinyatakan:</p>
                    <p id="kesimpulanText" class="text-lg font-extrabold text-slate-900 mt-1 text-slate-500">Belum Dinilai</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Rekomendasi:</label>
                    <textarea name="catatan" rows="3" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Isi rekomendasi untuk operator di kolom berikut">{{ old('catatan', $isEditing ? $evaluation->catatan : '') }}</textarea>
                    <p class="text-[10px] text-slate-500 mt-1">Catatan: Lampirkan bukti dari pelaksanaan P2H, Teknik Pengoprasian, Kepatuhan Terhadap Peraturan Kerja, Kedisiplinan dan Komunikasi</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('trainer.final-evaluations.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#00A859] hover:bg-emerald-600 text-white text-xs font-bold transition">
                Simpan Evaluasi
            </button>
        </div>
    </form>

    <script>
        function handleOperatorChange(selectElement) {
            const selectedName = selectElement.value;
            const trainees = JSON.parse(selectElement.dataset.trainees || '[]');
            const trainee = trainees.find(t => t.name === selectedName);

            const perusahaanInput = document.getElementById('perusahaan');
            const jenisUnitA2BInput = document.getElementById('jenis_unit_a2b');
            const jenisSertifikasiInput = document.getElementById('jenis_sertifikasi');
            const instrukturInput = document.getElementById('instruktur');
            const operatorPendampingInput = document.getElementById('operator_pendamping');

            if (trainee) {
                perusahaanInput.value = trainee.company || '';
                jenisUnitA2BInput.value = trainee.equipment_category_name || '';
                jenisSertifikasiInput.value = trainee.certification || '';
                instrukturInput.value = @json(Auth::user()->name ?? '');
                operatorPendampingInput.value = trainee.operator_pendamping || '';
            } else {
                perusahaanInput.value = '';
                jenisUnitA2BInput.value = '';
                jenisSertifikasiInput.value = '';
                instrukturInput.value = @json(Auth::user()->name ?? '');
                operatorPendampingInput.value = '';
            }
        }

        function updateKesimpulan() {
            const p2h = document.querySelector('input[name="p2h_status"]:checked')?.value;
            const teknik = document.querySelector('input[name="teknik_pengoperasian_status"]:checked')?.value;
            const kepatuhan = document.querySelector('input[name="kepatuhan_status"]:checked')?.value;
            const kedisiplinan = document.querySelector('input[name="kedisiplinan_status"]:checked')?.value;
            const badge = document.getElementById('kesimpulanBadge');
            const kesimpulanText = document.getElementById('kesimpulanText');

            const hasBK = p2h === 'BK' || teknik === 'BK' || kepatuhan === 'BK' || kedisiplinan === 'BK';
            const allK = p2h === 'K' && teknik === 'K' && kepatuhan === 'K' && kedisiplinan === 'K';
            const incomplete = !p2h || !teknik || !kepatuhan || !kedisiplinan;

            if (incomplete) {
                badge.textContent = 'Belum Dinilai';
                badge.className = 'px-4 py-2 rounded-lg font-bold text-sm bg-slate-100 text-slate-600';
                if (kesimpulanText) {
                    kesimpulanText.textContent = 'Belum Dinilai';
                    kesimpulanText.className = 'text-lg font-extrabold text-slate-900 mt-1 text-slate-500';
                }
            } else if (hasBK) {
                badge.textContent = 'BELUM KOMPETEN';
                badge.className = 'px-4 py-2 rounded-lg font-bold text-sm bg-red-100 text-red-800';
                if (kesimpulanText) {
                    kesimpulanText.textContent = 'BELUM KOMPETEN';
                    kesimpulanText.className = 'text-lg font-extrabold text-slate-900 mt-1 text-red-700';
                }
            } else if (allK) {
                badge.textContent = 'KOMPETEN';
                badge.className = 'px-4 py-2 rounded-lg font-bold text-sm bg-green-100 text-green-800';
                if (kesimpulanText) {
                    kesimpulanText.textContent = 'KOMPETEN';
                    kesimpulanText.className = 'text-lg font-extrabold text-slate-900 mt-1 text-green-700';
                }
            } else {
                badge.textContent = 'Belum Dinilai';
                badge.className = 'px-4 py-2 rounded-lg font-bold text-sm bg-slate-100 text-slate-600';
                if (kesimpulanText) {
                    kesimpulanText.textContent = 'Belum Dinilai';
                    kesimpulanText.className = 'text-lg font-extrabold text-slate-900 mt-1 text-slate-500';
                }
            }
        }

        document.addEventListener('change', function(e) {
            if (e.target.name === 'p2h_status' || e.target.name === 'teknik_pengoperasian_status' || e.target.name === 'kepatuhan_status' || e.target.name === 'kedisiplinan_status') {
                updateKesimpulan();
            }
        });

        updateKesimpulan();
    </script>
</x-app-layout>
