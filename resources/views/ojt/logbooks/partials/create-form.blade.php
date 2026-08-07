@php
    $categoryMap = $categories->pluck('code', 'id')->all();
    $equipmentMap = $equipments->groupBy('equipment_category_id')
        ->map(fn ($group) => $group->map(fn ($eq) => [
            'id' => $eq->id,
            'label' => $eq->unit_code . ' - ' . $eq->model_name,
        ])->values())
        ->toArray();
    $selectedCategoryId = old('equipment_category_id', $categories->firstWhere('code', 'DZ')?->id ?? $categories->first()?->id);
    $selectedCategoryCode = $selectedCategoryId ? ($categoryMap[$selectedCategoryId] ?? '') : '';

    $trackGroups = [
        [
            'title' => 'Teknik Pengoperasian',
            'subtitle' => 'Dozing & Digging untuk Buldozer / Grading & Digging untuk Motor Grader',
            'items' => [
                ['code' => '1.1', 'label' => 'Cara memposisikan blade pada saat mendorong / grading', 'kind' => 'Skl'],
                ['code' => '1.2', 'label' => 'Penggunaan tilt blade', 'kind' => 'Kwn'],
                ['code' => '1.3', 'label' => 'Cara pengoperasian blade untuk mendorong / ditching', 'kind' => 'Skl'],
                ['code' => '1.4', 'label' => 'Cara pengoperasian blade untuk menggali / sloping', 'kind' => 'Skl'],
                ['code' => '1.5', 'label' => 'Penyesuaian beban dengan RPM / posisi transmisi', 'kind' => 'Skl'],
                ['code' => '1.6', 'label' => 'Teknik dozing / grading / digging', 'kind' => 'Skl'],
            ],
        ],
        [
            'title' => 'Spreading & Leveling',
            'subtitle' => 'Pengoperasian untuk meratakan, memadatkan, dan membentuk area kerja',
            'items' => [
                ['code' => '2.1', 'label' => 'Penggunaan speed / transmisi saat bergerak', 'kind' => 'Kwn'],
                ['code' => '2.2', 'label' => 'Cara leveling menggunakan tilt', 'kind' => 'Kwn'],
                ['code' => '2.3', 'label' => 'Cara menghampar material untuk membuat jalan / menimbun lubang', 'kind' => 'Skl'],
                ['code' => '2.4', 'label' => 'Filling pada saat melewatkan area kerja', 'kind' => 'Skl'],
                ['code' => '2.5', 'label' => 'Penggunaan steering', 'kind' => 'Skl'],
                ['code' => '2.6', 'label' => 'Penggunaan articulated (khusus unit GR)', 'kind' => 'Skl'],
                ['code' => '2.7', 'label' => 'Teknik spreading / leveling', 'kind' => 'Skl'],
            ],
        ],
        [
            'title' => 'Ripping',
            'subtitle' => 'Khusus untuk pekerjaan ripping dan pembukaan material keras',
            'items' => [
                ['code' => '3.1', 'label' => 'Cara memposisikan ripper', 'kind' => 'Skl'],
                ['code' => '3.2', 'label' => 'Teknik penetrasi ripping', 'kind' => 'Kwn'],
                ['code' => '3.3', 'label' => 'Penyesuaian posisi ripper dengan kekerasan material', 'kind' => 'Skl'],
            ],
        ],
        [
            'title' => 'Finishing',
            'subtitle' => 'Finishing grading dan koreksi permukaan kerja',
            'items' => [
                ['code' => '4.1', 'label' => 'Kesesuaian penggunaan speed', 'kind' => 'Skl'],
                ['code' => '4.2', 'label' => 'Hasil akurasi / kualitas pekerjaan', 'kind' => 'Skl'],
            ],
        ],
    ];

    $disciplineItems = [
        ['code' => '1', 'label' => 'Mempedulikan pemakaian fuel/ bahan bakar', 'kind' => 'Atd'],
        ['code' => '2', 'label' => 'Mempedulikan pemakaian tyre/ undercarriage', 'kind' => 'Atd'],
        ['code' => '3', 'label' => 'Mempedulikan akan ketidaknormalan unit', 'kind' => 'Atd'],
        ['code' => '4', 'label' => 'Mempedulikan untuk bekerja dengan efektif dan efisien', 'kind' => 'Atd'],
        ['code' => '5', 'label' => 'Mempedulikan untuk meniadakan pemborosan dimanapun', 'kind' => 'Atd'],
        ['code' => '6', 'label' => 'Melaksanakan aktivitas sesuai instruksi', 'kind' => 'Atd'],
        ['code' => '7', 'label' => 'Berusaha untuk melakukan yang terbaik', 'kind' => 'Atd'],
        ['code' => '8', 'label' => 'Selalu siap menerima tugas yang diberikan', 'kind' => 'Atd'],
        ['code' => '9', 'label' => 'Berani mengingatkan jika ada yang berbuat kesalahan', 'kind' => 'Atd'],
        ['code' => '10', 'label' => 'Disiplin waktu saat pelaksanaan pelatihan', 'kind' => 'Atd'],
        ['code' => '11', 'label' => 'Mematuhi semua aturan yang berlaku', 'kind' => 'Atd'],
        ['code' => '12', 'label' => 'Tidak pernah mangkir', 'kind' => 'Atd'],
        ['code' => '13', 'label' => 'Melaksanakan tugas kelompok bersama-sama', 'kind' => 'Atd'],
        ['code' => '14', 'label' => 'Berinisiatif untuk membantu', 'kind' => 'Atd'],
        ['code' => '15', 'label' => 'Selalu antusias jika diberi tugas', 'kind' => 'Atd'],
        ['code' => '16', 'label' => 'Melaporkan setiap kejadian diluar wewenangnya', 'kind' => 'Atd'],
        ['code' => '17', 'label' => 'Tidak ragu-ragu jika diberi instruksi', 'kind' => 'Atd'],
        ['code' => '18', 'label' => 'Mengoperasikan unit dengan penuh keyakinan', 'kind' => 'Atd'],
        ['code' => '19', 'label' => 'Bersikap proaktif di setiap kegiatan', 'kind' => 'Atd'],
        ['code' => '20', 'label' => 'Tidak malu untuk bertanya jika ada kesulitan', 'kind' => 'Atd'],
    ];

    $excavatorGroups = [
        [
            'title' => 'Positioning',
            'subtitle' => 'Positioning unit di front loading dan sudut kerja bucket',
            'items' => [
                ['code' => '1.1', 'label' => 'Posisi unit di front loading', 'kind' => 'Kwn'],
                ['code' => '1.2', 'label' => 'Sudut kerja bucket ke material', 'kind' => 'Skl'],
                ['code' => '1.3', 'label' => 'Cara menempatkan bucket dengan aman', 'kind' => 'Skl'],
                ['code' => '1.4', 'label' => 'Posisi travel dan swing saat bekerja', 'kind' => 'Skl'],
            ],
        ],
        [
            'title' => 'Loading & Dumping',
            'subtitle' => 'Cara swing, memuat, dan dumping yang aman',
            'items' => [
                ['code' => '2.1', 'label' => 'Cara swing menuju truck', 'kind' => 'Kwn'],
                ['code' => '2.2', 'label' => 'Cara loading material ke dump truck', 'kind' => 'Kwn'],
                ['code' => '2.3', 'label' => 'Kontrol bucket saat dumping', 'kind' => 'Kwn'],
                ['code' => '2.4', 'label' => 'Cara dumping dan kerapian muatan', 'kind' => 'Skl'],
                ['code' => '2.5', 'label' => 'Cycle time', 'kind' => 'Kwn'],
            ],
        ],
        [
            'title' => 'Digging',
            'subtitle' => 'Teknik gali dan pengaturan kerja bucket',
            'items' => [
                ['code' => '3.1', 'label' => 'Self positioning dan penempatan bucket', 'kind' => 'Skl'],
                ['code' => '3.2', 'label' => 'Teknik kombinasi maju pada saat digging', 'kind' => 'Skl'],
                ['code' => '3.3', 'label' => 'Cara pengambilan material pada saat digging', 'kind' => 'Skl'],
                ['code' => '3.4', 'label' => 'Volume bucket', 'kind' => 'Kwn'],
            ],
        ],
        [
            'title' => 'Sloping',
            'subtitle' => 'Kontur slope dan kerja finishing area lereng',
            'items' => [
                ['code' => '4.1', 'label' => 'Keterampilan saat sloping', 'kind' => 'Skl'],
                ['code' => '4.2', 'label' => 'Ketinggian / sudut penempatan slope', 'kind' => 'Skl'],
                ['code' => '4.3', 'label' => 'Kecepatan kerja ketika sloping', 'kind' => 'Skl'],
                ['code' => '4.4', 'label' => 'Hasil akhir permukaan slope', 'kind' => 'Skl'],
            ],
        ],
    ];
@endphp

<div class="mb-6 flex items-center justify-between">
    <div>
        <div class="flex items-center space-x-2 text-xs font-semibold text-[#00A859] mb-1">
            <a href="{{ route('ojt.logbooks.index') }}" class="hover:underline">My Logbook</a>
            <span>/</span>
            <span class="text-slate-500">Create Digital Logbook</span>
        </div>
        <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Formulir Logbook Harian Trainee OJT</h1>
        <p class="text-xs text-slate-500 mt-1">Form ini menampilkan checklist harian per unit: track unit di panel kiri dan excavator di panel kanan.</p>
    </div>
    <a href="{{ route('ojt.logbooks.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">Kembali</a>
</div>

<form action="{{ route('ojt.logbooks.store') }}" method="POST" enctype="multipart/form-data"
      x-data="{
          categoryId: '{{ old('equipment_category_id', $selectedCategoryId) }}',
          equipmentId: '{{ old('equipment_id', '') }}',
          categoryMap: @js($categoryMap),
          equipmentMap: @js($equipmentMap),
          company: '{{ old('sop_payload.meta.company', 'PT BERAU COAL / PT MTL') }}',
          certification: '{{ old('sop_payload.meta.certification', 'Green') }}',
          stickerExpiredAt: '{{ old('sop_payload.meta.sticker_expired_at', '') }}',
          assessmentMode: '{{ old('sop_payload.meta.assessment_mode', 'pendampingan') }}',
          assessmentStage: '{{ old('sop_payload.meta.assessment_stage', 'bulanan') }}',
          hmStart: '{{ old('hm_start', '') }}',
          hmEnd: '{{ old('hm_end', '') }}',
          files: [],
          get selectedCategoryCode() {
              return this.categoryMap[this.categoryId] || '';
          },
          get filteredEquipments() {
              return this.equipmentMap[this.categoryId] || [];
          },
          get unitFamily() {
              if (['DZ', 'MG'].includes(this.selectedCategoryCode)) return 'track';
              if (this.selectedCategoryCode === 'EXC') return 'excavator';
              return '';
          },
          get totalHm() {
              let calc = parseFloat(this.hmEnd) - parseFloat(this.hmStart);
              return isNaN(calc) || calc < 0 ? '0.0' : calc.toFixed(1);
          },
          handleFileSelect(event) {
              this.files = Array.from(event.target.files);
          }
      }"
      class="space-y-8 pb-28">
    @csrf

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

    <input type="hidden" name="sop_payload[meta][category_code]" :value="selectedCategoryCode">
    <input type="hidden" name="sop_payload[meta][unit_family]" :value="unitFamily">

    <div class="rounded-2xl border-2 border-slate-900 bg-white overflow-hidden shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r">
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NAMA</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="sop_payload[meta][trainee_name]" value="{{ old('sop_payload.meta.trainee_name', $user->name) }}" readonly class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HARI/ TANGGAL</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">SHIFT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <select name="shift" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                            <option value="day" {{ old('shift') == 'day' ? 'selected' : '' }}>Shift 1 (Siang: 07.00 - 17.00)</option>
                            <option value="night" {{ old('shift') == 'night' ? 'selected' : '' }}>Shift 2 (Malam: 19.00 - 05.00)</option>
                        </select>
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">LOKASI (OJT)</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Pit H1 East - Bench 45" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold">SERTIFIKASI</div>
                    <div class="px-3 py-1.5">
                        <select name="sop_payload[meta][certification]" x-model="certification" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                            <option value="Green">Green</option>
                            <option value="Skill-up">Skill-up</option>
                            <option value="Experience">Experience</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">PERUSAHAAN</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="sop_payload[meta][company]" x-model="company" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">TIPE ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <select name="equipment_category_id" x-model="categoryId" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                            <option value="">Pilih kategori alat</option>
                            <optgroup label="Track Equipment">
                                @foreach($categories->filter(fn($c) => in_array($c->code, ['DZ', 'EXC'])) as $cat)
                                    <option value="{{ $cat->id }}" {{ old('equipment_category_id', $selectedCategoryId) == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->code }})</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Wheel Equipment">
                                @foreach($categories->filter(fn($c) => $c->code === 'MG') as $cat)
                                    <option value="{{ $cat->id }}" {{ old('equipment_category_id', $selectedCategoryId) == $cat->id ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->code }})</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NO ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="equipment_number" value="{{ old('equipment_number') }}" placeholder="Ketik nomor alat, contoh: DZ-123" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HM / KM AWAL</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="number" step="0.1" name="hm_start" x-model="hmStart" placeholder="Contoh: 4520.5" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold">HM / KM AKHIR</div>
                    <div class="px-3 py-1.5">
                        <input type="number" step="0.1" name="hm_end" x-model="hmEnd" placeholder="Contoh: 4529.0" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-t border-slate-900">EXPIRED DATE STIKER (SKO)</div>
                    <div class="px-3 py-1.5 border-t border-slate-900">
                        <input type="date" name="sop_payload[meta][sticker_expired_at]" x-model="stickerExpiredAt" class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 border-t border-slate-900">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r p-3 text-[11px]">
                <div class="font-semibold mb-2">Keterangan:</div>
                <ol class="space-y-1 pl-4 list-decimal">
                    <li>Beri tanda "&#10003;" pada kolom yang sesuai</li>
                    <li>Kolom "Catatan Penguji" memuat penjelasan item evaluasi terkait</li>
                    <li>(K) Kompeten, (BK) Belum Kompeten</li>
                    <li>Knw: Knowledge, Skl: Skill, Atd: Attitude</li>
                </ol>
            </div>
            <div class="p-3 text-[11px]">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <div class="font-semibold mb-2">Tahap Penilaian OJT</div>
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="radio" name="sop_payload[meta][assessment_mode]" value="pendampingan" x-model="assessmentMode" class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>Pendampingan</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="sop_payload[meta][assessment_mode]" value="tanpa_pendampingan" x-model="assessmentMode" class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>Tanpa Pendampingan</span>
                        </label>
                    </div>
                    <div>
                        <div class="font-semibold mb-2">Tahap Tanpa Pendampingan Lanjutan</div>
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="radio" name="sop_payload[meta][assessment_stage]" value="bulanan" x-model="assessmentStage" class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>Bulanan</span>
                        </label>
                        <label class="flex items-center gap-2 mb-2 cursor-pointer">
                            <input type="radio" name="sop_payload[meta][assessment_stage]" value="3_bulan_pertama" x-model="assessmentStage" class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>3 Bulan Pertama</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="sop_payload[meta][assessment_stage]" value="3_bulan_kedua" x-model="assessmentStage" class="h-3.5 w-3.5 border-slate-400 text-[#003829] focus:ring-[#00A859]">
                            <span>3 Bulan Kedua</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">A</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Penanggung Jawab OJT</h2>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">Data pendamping dan persetujuan</span>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Departemen Operasional <span class="text-rose-500">*</span></label>
                <select name="department_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->code }} - {{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Trainer Pembimbing <span class="text-rose-500">*</span></label>
                <select name="trainer_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                    <option value="">Pilih trainer</option>
                    @foreach($trainers as $tr)
                        <option value="{{ $tr->id }}" {{ old('trainer_id') == $tr->id ? 'selected' : '' }}>{{ $tr->name }} ({{ $tr->nrp }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Supervisor Lapangan</label>
                <select name="supervisor_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                    <option value="">Pilih supervisor</option>
                    @foreach($supervisors as $spv)
                        <option value="{{ $spv->id }}" {{ old('supervisor_id') == $spv->id ? 'selected' : '' }}>{{ $spv->name }} ({{ $spv->nrp }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div x-show="unitFamily === 'track'" x-cloak class="w-full space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-semibold text-[#00A859] uppercase tracking-wider"><span>Unit Track</span><span>•</span><span>Buldozer / Motor Grader</span></div>
                        <h2 class="mt-1 text-base font-bold text-slate-800">Checklist SOP Harian</h2>
                        <p class="text-[11px] text-slate-500 mt-1">Isi K / BK dan catatan penguji pada setiap item evaluasi.</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-2 text-[11px] font-bold text-emerald-700">DZ / MG</div>
                </div>

                <div class="p-6 space-y-6">
                    @foreach($trackGroups as $groupIndex => $group)
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide">{{ $group['title'] }}</div>
                                <div class="text-[11px] text-emerald-100 mt-1">{{ $group['subtitle'] }}</div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] table-fixed text-xs">
                                    <colgroup>
                                        <col class="w-14">
                                        <col class="w-20">
                                        <col>
                                        <col class="w-14">
                                        <col class="w-14">
                                        <col class="w-64">
                                    </colgroup>
                                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-3 py-2 text-left w-12">No</th>
                                            <th class="px-3 py-2 text-left">Aspek</th>
                                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                            <th class="px-3 py-2 text-center w-14">K</th>
                                            <th class="px-3 py-2 text-center w-14">BK</th>
                                            <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($group['items'] as $itemIndex => $item)
                                            <tr class="align-top">
                                                <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                                <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                                <td class="px-3 py-3 text-slate-700 leading-relaxed break-words">{{ $item['label'] }}</td>
                                                <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[track][groups][{{ $groupIndex }}][title]" value="{{ $group['title'] }}"><input type="hidden" name="sop_payload[track][groups][{{ $groupIndex }}][subtitle]" value="{{ $group['subtitle'] }}"><input type="hidden" name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600" name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
                                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
                                                <td class="px-3 py-3"><textarea name="sop_payload[track][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis catatan penguji">{{ old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.note') }}</textarea></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">Kedisiplinan dan Komunikasi</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Evaluasi perilaku kerja, komunikasi, dan kepatuhan SOP lapangan</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Aspek</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($disciplineItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[track][behavior][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[track][behavior][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[track][behavior][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600" name="sop_payload[track][behavior][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.track.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="sop_payload[track][behavior][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.track.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[track][behavior][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.track.behavior.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="unitFamily === 'excavator'" x-cloak class="w-full space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-semibold text-[#00A859] uppercase tracking-wider"><span>Unit Excavator</span><span>•</span><span>Digging & Loading</span></div>
                        <h2 class="mt-1 text-base font-bold text-slate-800">Checklist SOP Harian</h2>
                        <p class="text-[11px] text-slate-500 mt-1">Isi evaluasi penguji untuk item positioning, loading, digging, dan sloping.</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-3 py-2 text-[11px] font-bold text-emerald-700">EXC</div>
                </div>

                <div class="p-6 space-y-6">
                    @foreach($excavatorGroups as $groupIndex => $group)
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide">{{ $group['title'] }}</div>
                                <div class="text-[11px] text-emerald-100 mt-1">{{ $group['subtitle'] }}</div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-[760px] table-fixed text-xs">
                                    <colgroup>
                                        <col class="w-14">
                                        <col class="w-20">
                                        <col>
                                        <col class="w-14">
                                        <col class="w-14">
                                        <col class="w-64">
                                    </colgroup>
                                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                        <tr>
                                            <th class="px-3 py-2 text-left w-12">No</th>
                                            <th class="px-3 py-2 text-left w-20">Aspek</th>
                                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                            <th class="px-3 py-2 text-center w-14">K</th>
                                            <th class="px-3 py-2 text-center w-14">BK</th>
                                            <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($group['items'] as $itemIndex => $item)
                                            <tr class="align-top">
                                                <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                                <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                                <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
                                                <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[excavator][groups][{{ $groupIndex }}][title]" value="{{ $group['title'] }}"><input type="hidden" name="sop_payload[excavator][groups][{{ $groupIndex }}][subtitle]" value="{{ $group['subtitle'] }}"><input type="hidden" name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600" name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
                                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
                                                <td class="px-3 py-3"><textarea name="sop_payload[excavator][groups][{{ $groupIndex }}][items][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis catatan penguji">{{ old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.note') }}</textarea></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach

                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                        <div class="bg-[#003829] px-4 py-3 text-white">
                            <div class="text-xs font-bold uppercase tracking-wide">Kedisiplinan dan Komunikasi</div>
                            <div class="text-[11px] text-emerald-100 mt-1">Evaluasi perilaku kerja, komunikasi, dan kepatuhan SOP lapangan</div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[760px] table-fixed text-xs">
                                <colgroup>
                                    <col class="w-14">
                                    <col class="w-20">
                                    <col>
                                    <col class="w-14">
                                    <col class="w-14">
                                    <col class="w-64">
                                </colgroup>
                                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-3 py-2 text-left w-12">No</th>
                                        <th class="px-3 py-2 text-left w-20">Aspek</th>
                                        <th class="px-3 py-2 text-left">Item Evaluasi</th>
                                        <th class="px-3 py-2 text-center w-14">K</th>
                                        <th class="px-3 py-2 text-center w-14">BK</th>
                                        <th class="px-3 py-2 text-left w-56">Catatan Penguji</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($disciplineItems as $itemIndex => $item)
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $item['code'] }}</td>
                                            <td class="px-3 py-3 text-slate-500 font-medium">{{ $item['kind'] }}</td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] }}</td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[excavator][behavior][{{ $itemIndex }}][code]" value="{{ $item['code'] }}"><input type="hidden" name="sop_payload[excavator][behavior][{{ $itemIndex }}][label]" value="{{ $item['label'] }}"><input type="hidden" name="sop_payload[excavator][behavior][{{ $itemIndex }}][kind]" value="{{ $item['kind'] }}"><input type="radio" class="accent-emerald-600" name="sop_payload[excavator][behavior][{{ $itemIndex }}][status]" value="K" {{ old('sop_payload.excavator.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="sop_payload[excavator][behavior][{{ $itemIndex }}][status]" value="BK" {{ old('sop_payload.excavator.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : '' }}></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[excavator][behavior][{{ $itemIndex }}][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji">{{ old('sop_payload.excavator.behavior.'.$itemIndex.'.note') }}</textarea></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="!unitFamily" x-cloak class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">
            Pilih kategori alat terlebih dahulu untuk menampilkan checklist SOP yang sesuai unit.
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">B</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rincian Narasi Harian</h2>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">Ringkasan aktivitas shift</span>
        </div>

        <div class="p-6">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan kegiatan / pekerjaan harian <span class="text-rose-500">*</span></label>
            <textarea name="daily_activity" rows="6" required placeholder="Tuliskan ringkasan aktivitas harian, kondisi unit, dan poin penting pekerjaan shift ini..." class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">{{ old('daily_activity') }}</textarea>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">C</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Jam Kerja & Hour Meter</h2>
            </div>
            <span class="text-xs font-bold text-[#00A859] bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">Auto Calculate</span>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Start Time <span class="text-rose-500">*</span></label>
                <input type="time" name="start_time" value="{{ old('start_time', '07:00') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Finish Time <span class="text-rose-500">*</span></label>
                <input type="time" name="finish_time" value="{{ old('finish_time', '17:00') }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">HM Start <span class="text-rose-500">*</span></label>
                <input type="number" step="0.1" name="hm_start" x-model="hmStart" placeholder="Contoh: 4520.5" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">HM End <span class="text-rose-500">*</span></label>
                <input type="number" step="0.1" name="hm_end" x-model="hmEnd" placeholder="Contoh: 4529.0" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
            </div>
            <div class="bg-[#003829] text-white p-3.5 rounded-xl border border-emerald-900 shadow-sm flex flex-col justify-center">
                <span class="text-[10px] text-emerald-300 font-bold uppercase tracking-wider">Total HM</span>
                <div class="flex items-baseline space-x-1 mt-1">
                    <span class="text-2xl font-black text-[#F5A623]" x-text="totalHm">0.0</span>
                    <span class="text-xs text-emerald-200 font-bold">Hours</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">D</span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Evidence Upload</h2>
            </div>
            <span class="text-[11px] text-slate-400 font-medium">Photos, Videos, Documents</span>
        </div>

        <div class="p-6">
            <div class="border-2 border-dashed border-slate-300 hover:border-[#00A859] rounded-2xl p-8 text-center bg-slate-50 hover:bg-emerald-50/30 transition group cursor-pointer relative">
                <input type="file" name="evidences[]" multiple @change="handleFileSelect" accept="image/*,video/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                <svg class="w-10 h-10 mx-auto text-slate-400 group-hover:text-[#00A859] transition mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="text-xs font-bold text-slate-700">Tarik file bukti di sini, atau <span class="text-[#00A859] underline">Browse</span></p>
                <p class="text-[11px] text-slate-400 mt-1">PNG, JPG, MP4, PDF, DOC, DOCX. Maks 20MB per file.</p>
            </div>

            <div x-show="files.length > 0" class="mt-4 space-y-2" x-cloak>
                <p class="text-xs font-bold text-slate-700">Preview file (<span x-text="files.length"></span>)</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <template x-for="(file, index) in files" :key="index">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-[#00A859] flex items-center justify-center font-bold text-xs">FILE</div>
                            <div class="overflow-hidden min-w-0 flex-1">
                                <p class="text-xs font-bold text-slate-800 truncate" x-text="file.name"></p>
                                <p class="text-[10px] text-slate-400" x-text="(file.size / 1024).toFixed(1) + ' KB'"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 py-4 px-6 md:px-8 z-40 flex items-center justify-between shadow-2xl">
        <div class="hidden lg:flex items-center space-x-2 text-xs text-slate-500">
            <svg class="w-4 h-4 text-[#00A859]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Checklist K / BK dan catatan penguji harus sesuai SOP unit yang dipilih sebelum submit.</span>
        </div>

        <div class="flex items-center space-x-3 w-full lg:w-auto justify-end">
            <button type="submit" name="action_type" value="draft" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl shadow-xs transition">Save Draft</button>
            <button type="submit" name="action_type" value="submit" class="px-7 py-2.5 bg-[#00A859] hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Submit Logbook
            </button>
        </div>
    </div>
</form>