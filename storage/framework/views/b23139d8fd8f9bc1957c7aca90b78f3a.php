<?php
    $isEditing = isset($logbook);
    $isTrainerEditing = $isTrainerEditing ?? false;
    $formPayload = old('sop_payload', $isEditing ? ($logbook->sop_payload ?? []) : []);
    $categoryMap = $categories->pluck('code', 'id')->all();
    $equipmentMap = $equipments->groupBy('equipment_category_id')
        ->map(fn ($group) => $group->map(fn ($eq) => [
            'id' => $eq->id,
            'label' => $eq->unit_code . ' - ' . $eq->model_name,
        ])->values())
        ->toArray();
    $selectedCategoryId = old('equipment_category_id', $isEditing ? $logbook->equipment_category_id : ($categories->firstWhere('code', 'DZ')?->id ?? $categories->first()?->id));
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
?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <div class="flex items-center space-x-2 text-xs font-semibold text-[#00A859] mb-1">
            <a href="<?php echo e(route('ojt.logbooks.index')); ?>" class="hover:underline">My Logbook</a>
            <span>/</span>
            <span class="text-slate-500"><?php echo e($isTrainerEditing ? 'Edit Logbook Trainer' : ($isEditing ? 'Edit Draft Logbook' : 'Create Digital Logbook')); ?></span>
        </div>
        <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Formulir Logbook Harian Trainee OJT</h1>
        <p class="text-xs text-slate-500 mt-1">Form ini menampilkan checklist harian per unit: track unit di panel kiri dan excavator di panel kanan.</p>
    </div>
    <a href="<?php echo e(route('ojt.logbooks.index')); ?>" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">Kembali</a>
</div>

<form action="<?php echo e($isTrainerEditing ? route('trainer.reviews.update', $logbook->id) : ($isEditing ? route('ojt.logbooks.update', $logbook->id) : route('ojt.logbooks.store'))); ?>" method="POST" enctype="multipart/form-data"
      x-data="{
          categoryId: '<?php echo e(old('equipment_category_id', $selectedCategoryId)); ?>',
          equipmentId: '<?php echo e(old('equipment_id', $isEditing ? $logbook->equipment_id : '')); ?>',
          categoryMap: <?php echo \Illuminate\Support\Js::from($categoryMap)->toHtml() ?>,
          equipmentMap: <?php echo \Illuminate\Support\Js::from($equipmentMap)->toHtml() ?>,
          company: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.company', data_get($formPayload, 'meta.company', 'PT BERAU COAL / PT MTL')))->toHtml() ?>,
          certification: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.certification', data_get($formPayload, 'meta.certification', 'Green')))->toHtml() ?>,
          stickerExpiredAt: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.sticker_expired_at', data_get($formPayload, 'meta.sticker_expired_at', '')))->toHtml() ?>,
          assessmentMode: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.assessment_mode', data_get($formPayload, 'meta.assessment_mode', 'pendampingan')))->toHtml() ?>,
          assessmentStage: <?php echo \Illuminate\Support\Js::from(old('sop_payload.meta.assessment_stage', data_get($formPayload, 'meta.assessment_stage', 'bulanan')))->toHtml() ?>,
          hmStart: <?php echo \Illuminate\Support\Js::from(old('hm_start', $isEditing ? $logbook->hm_start : ''))->toHtml() ?>,
          hmEnd: <?php echo \Illuminate\Support\Js::from(old('hm_end', $isEditing ? $logbook->hm_end : ''))->toHtml() ?>,
          existingSopPayload: <?php echo \Illuminate\Support\Js::from($formPayload)->toHtml() ?>,
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
          },
          fillExistingChecklist() {
              this.$root.querySelectorAll('[name]').forEach((field) => {
                  if (!field.name.startsWith('sop_payload[')) return;
                  const path = Array.from(field.name.matchAll(/\[([^\]]+)\]/g)).map((match) => match[1]);
                  const value = path.reduce((data, key) => data?.[key], this.existingSopPayload);
                  if (value === undefined || value === null || field.type === 'hidden') return;
                  if (field.type === 'radio') field.checked = String(value) === field.value;
                  else if (!field.value) field.value = value;
              });
          }
      }"
      x-init="$nextTick(() => fillExistingChecklist())"
      class="space-y-8 pb-28">
    <?php echo csrf_field(); ?>
    <?php if($isEditing): ?>
        <?php echo method_field('PUT'); ?>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-xs text-rose-900">
            <p class="font-bold">Submit gagal. Periksa field berikut:</p>
            <ul class="mt-2 list-disc pl-5 space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <input type="hidden" name="sop_payload[meta][category_code]" :value="selectedCategoryCode">
    <input type="hidden" name="sop_payload[meta][unit_family]" :value="unitFamily">

    <div class="rounded-2xl border-2 border-slate-900 bg-white overflow-hidden shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r">
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NAMA</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="sop_payload[meta][trainee_name]" value="<?php echo e(old('sop_payload.meta.trainee_name', data_get($formPayload, 'meta.trainee_name', $user->name))); ?>" readonly class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HARI/ TANGGAL</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="date" name="date" value="<?php echo e(old('date', $isEditing ? optional($logbook->date)->format('Y-m-d') : date('Y-m-d'))); ?>" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">SHIFT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <select name="shift" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
                            <option value="day" <?php echo e(old('shift', $isEditing ? $logbook->shift : '') == 'day' ? 'selected' : ''); ?>>Shift 1 (Siang: 07.00 - 17.00)</option>
                            <option value="night" <?php echo e(old('shift', $isEditing ? $logbook->shift : '') == 'night' ? 'selected' : ''); ?>>Shift 2 (Malam: 19.00 - 05.00)</option>
                        </select>
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">LOKASI (OJT)</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="location" value="<?php echo e(old('location', $isEditing ? $logbook->location : '')); ?>" placeholder="Contoh: Pit H1 East - Bench 45" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
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
                                <?php $__currentLoopData = $categories->filter(fn($c) => in_array($c->code, ['DZ', 'EXC'])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php echo e(old('equipment_category_id', $selectedCategoryId) == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?> (<?php echo e($cat->code); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                            <optgroup label="Wheel Equipment">
                                <?php $__currentLoopData = $categories->filter(fn($c) => $c->code === 'MG'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php echo e(old('equipment_category_id', $selectedCategoryId) == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?> (<?php echo e($cat->code); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        </select>
                    </div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NO ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <input type="text" name="equipment_number" value="<?php echo e(old('equipment_number', $isEditing ? $logbook->equipment_number : '')); ?>" placeholder="Ketik nomor alat, contoh: DZ-123" required class="w-full border-0 bg-transparent p-0 text-[11px] font-medium focus:ring-0">
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
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dept->id); ?>" <?php echo e(old('department_id', $isEditing ? $logbook->department_id : $user->department_id) == $dept->id ? 'selected' : ''); ?>><?php echo e($dept->code); ?> - <?php echo e($dept->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Trainer Pembimbing <span class="text-rose-500">*</span></label>
                <select name="trainer_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                    <option value="">Pilih trainer</option>
                    <?php $__currentLoopData = $trainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($tr->id); ?>" <?php echo e(old('trainer_id', $isEditing ? $logbook->trainer_id : '') == $tr->id ? 'selected' : ''); ?>><?php echo e($tr->name); ?> (<?php echo e($tr->nrp); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Supervisor Lapangan</label>
                <select name="supervisor_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                    <option value="">Pilih supervisor</option>
                    <?php $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($spv->id); ?>" <?php echo e(old('supervisor_id', $isEditing ? $logbook->supervisor_id : '') == $spv->id ? 'selected' : ''); ?>><?php echo e($spv->name); ?> (<?php echo e($spv->nrp); ?>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__currentLoopData = $trackGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide"><?php echo e($group['title']); ?></div>
                                <div class="text-[11px] text-emerald-100 mt-1"><?php echo e($group['subtitle']); ?></div>
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
                                        <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="align-top">
                                                <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                                <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                                <td class="px-3 py-3 text-slate-700 leading-relaxed break-words"><?php echo e($item['label']); ?></td>
                                                <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][title]" value="<?php echo e($group['title']); ?>"><input type="hidden" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][subtitle]" value="<?php echo e($group['subtitle']); ?>"><input type="hidden" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
                                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
                                                <td class="px-3 py-3"><textarea name="sop_payload[track][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis catatan penguji"><?php echo e(old('sop_payload.track.groups.'.$groupIndex.'.items.'.$itemIndex.'.note')); ?></textarea></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
                                    <?php $__currentLoopData = $disciplineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600" name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.track.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.track.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[track][behavior][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji"><?php echo e(old('sop_payload.track.behavior.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__currentLoopData = $excavatorGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-2xl border border-slate-200 overflow-hidden">
                            <div class="bg-[#003829] px-4 py-3 text-white">
                                <div class="text-xs font-bold uppercase tracking-wide"><?php echo e($group['title']); ?></div>
                                <div class="text-[11px] text-emerald-100 mt-1"><?php echo e($group['subtitle']); ?></div>
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
                                        <?php $__currentLoopData = $group['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="align-top">
                                                <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                                <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                                <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
                                                <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][title]" value="<?php echo e($group['title']); ?>"><input type="hidden" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][subtitle]" value="<?php echo e($group['subtitle']); ?>"><input type="hidden" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
                                                <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
                                                <td class="px-3 py-3"><textarea name="sop_payload[excavator][groups][<?php echo e($groupIndex); ?>][items][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Tulis catatan penguji"><?php echo e(old('sop_payload.excavator.groups.'.$groupIndex.'.items.'.$itemIndex.'.note')); ?></textarea></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
                                    <?php $__currentLoopData = $disciplineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="align-top">
                                            <td class="px-3 py-3 font-bold text-slate-700"><?php echo e($item['code']); ?></td>
                                            <td class="px-3 py-3 text-slate-500 font-medium"><?php echo e($item['kind']); ?></td>
                                            <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label']); ?></td>
                                            <td class="px-3 py-3 text-center"><input type="hidden" name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][code]" value="<?php echo e($item['code']); ?>"><input type="hidden" name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][label]" value="<?php echo e($item['label']); ?>"><input type="hidden" name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][kind]" value="<?php echo e($item['kind']); ?>"><input type="radio" class="accent-emerald-600" name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][status]" value="K" <?php echo e(old('sop_payload.excavator.behavior.'.$itemIndex.'.status') == 'K' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3 text-center"><input type="radio" class="accent-rose-600" name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][status]" value="BK" <?php echo e(old('sop_payload.excavator.behavior.'.$itemIndex.'.status') == 'BK' ? 'checked' : ''); ?>></td>
                                            <td class="px-3 py-3"><textarea name="sop_payload[excavator][behavior][<?php echo e($itemIndex); ?>][note]" rows="2" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Catatan penguji"><?php echo e(old('sop_payload.excavator.behavior.'.$itemIndex.'.note')); ?></textarea></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            <textarea name="daily_activity" rows="6" required placeholder="Tuliskan ringkasan aktivitas harian, kondisi unit, dan poin penting pekerjaan shift ini..." class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition"><?php echo e(old('daily_activity', $isEditing ? $logbook->daily_activity : '')); ?></textarea>
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
                <input type="time" name="start_time" value="<?php echo e(old('start_time', $isEditing ? $logbook->start_time : '07:00')); ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Finish Time <span class="text-rose-500">*</span></label>
                <input type="time" name="finish_time" value="<?php echo e(old('finish_time', $isEditing ? $logbook->finish_time : '17:00')); ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
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
            <?php if (! ($isTrainerEditing)): ?>
                <button type="submit" name="action_type" value="draft" formnovalidate class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl shadow-xs transition"><?php echo e($isEditing ? 'Simpan Perubahan Draft' : 'Save Draft'); ?></button>
            <?php endif; ?>
            <button type="submit" name="action_type" value="submit" class="px-7 py-2.5 bg-[#00A859] hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                <?php echo e($isTrainerEditing ? 'Simpan Perubahan' : ($isEditing ? 'Kirim Ulang ke Trainer' : 'Submit Logbook')); ?>

            </button>
        </div>
    </div>
</form>
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/ojt/logbooks/partials/create-form.blade.php ENDPATH**/ ?>