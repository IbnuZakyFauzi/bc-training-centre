<?php
    $equipmentMap = $equipments->groupBy('equipment_category_id')
        ->map(fn ($group) => $group->map(fn ($eq) => [
            'id' => $eq->id,
            'label' => $eq->unit_code . ' - ' . $eq->model_name,
        ])->values())
        ->toArray();
?>

<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> Edit Logbook - <?php echo e($logbook->logbook_number); ?> <?php $__env->endSlot(); ?>

    <!-- Page Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-[#00A859] mb-1">
                <a href="<?php echo e(route('ojt.logbooks.index')); ?>" class="hover:underline">My Logbook</a>
                <span>/</span>
                <span class="text-slate-500">Edit Logbook (<?php echo e($logbook->logbook_number); ?>)</span>
            </div>
            <div class="flex items-center space-x-3">
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Edit Logbook <?php echo e($logbook->logbook_number); ?></h1>
                <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $logbook->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($logbook->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $attributes = $__attributesOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__attributesOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2ddbc40e602c342e508ac696e52f8719)): ?>
<?php $component = $__componentOriginal2ddbc40e602c342e508ac696e52f8719; ?>
<?php unset($__componentOriginal2ddbc40e602c342e508ac696e52f8719); ?>
<?php endif; ?>
            </div>
        </div>
        <a href="<?php echo e(route('ojt.logbooks.show', $logbook->id)); ?>" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
            Batal
        </a>
    </div>

    <!-- Trainer Revision Callout (If Status is Revision) -->
    <?php if($logbook->status === 'revision' && $logbook->revision_notes): ?>
        <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 p-6 rounded-2xl shadow-sm">
            <div class="flex items-start space-x-3">
                <div class="p-2 bg-amber-100 rounded-xl text-amber-800 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900">Catatan Revisi dari Trainer Evaluator</h3>
                    <p class="text-xs text-amber-800 mt-1 leading-relaxed"><?php echo e($logbook->revision_notes); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Enterprise Form -->
    <form action="<?php echo e(route('ojt.logbooks.update', $logbook->id)); ?>" method="POST" enctype="multipart/form-data" 
          x-data="{ 
              categoryId: '<?php echo e(old('equipment_category_id', $logbook->equipment_category_id)); ?>',
              equipmentId: '<?php echo e(old('equipment_id', $logbook->equipment_id)); ?>',
              equipmentMap: <?php echo \Illuminate\Support\Js::from($equipmentMap)->toHtml() ?>,
              hmStart: <?php echo e(old('hm_start', $logbook->hm_start)); ?>, 
              hmEnd: <?php echo e(old('hm_end', $logbook->hm_end)); ?>, 
              get filteredEquipments() {
                  return this.equipmentMap[this.categoryId] || [];
              },
              get totalHm() { 
                  let calc = parseFloat(this.hmEnd) - parseFloat(this.hmStart);
                  return isNaN(calc) || calc < 0 ? '0.0' : calc.toFixed(1);
              },
              files: [],
              handleFileSelect(event) {
                  this.files = Array.from(event.target.files);
              }
          }" 
          class="space-y-8 pb-24">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

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

        <!-- SECTION A: GENERAL INFORMATION -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">A</span>
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Informasi Umum Operasional (General Information)</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-medium">* Wajib Diisi</span>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Date -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Operations <span class="text-rose-500">*</span></label>
                    <input type="date" name="date" value="<?php echo e(old('date', $logbook->date->format('Y-m-d'))); ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                </div>

                <!-- Shift -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Shift Kerja <span class="text-rose-500">*</span></label>
                    <select name="shift" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                        <option value="day" <?php echo e(old('shift', $logbook->shift) == 'day' ? 'selected' : ''); ?>>Shift 1 (Siang: 07.00 - 17.00)</option>
                        <option value="night" <?php echo e(old('shift', $logbook->shift) == 'night' ? 'selected' : ''); ?>>Shift 2 (Malam: 19.00 - 05.00)</option>
                    </select>
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Departemen Operasional <span class="text-rose-500">*</span></label>
                    <select name="department_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dept->id); ?>" <?php echo e(old('department_id', $logbook->department_id) == $dept->id ? 'selected' : ''); ?>>
                                <?php echo e($dept->code); ?> - <?php echo e($dept->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Location / Pit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Lokasi Pit / Area Tambang <span class="text-rose-500">*</span></label>
                    <input type="text" name="location" value="<?php echo e(old('location', $logbook->location)); ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                </div>

                <!-- Equipment Category -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kategori Alat Berat <span class="text-rose-500">*</span></label>
                    <select name="equipment_category_id" x-model="categoryId" @change="if (!filteredEquipments.some(item => String(item.id) === String(equipmentId))) equipmentId = ''" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(old('equipment_category_id', $logbook->equipment_category_id) == $cat->id ? 'selected' : ''); ?>>
                                <?php echo e($cat->name); ?> (<?php echo e($cat->code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Equipment Unit -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Unit Alat Berat <span class="text-rose-500">*</span></label>
                    <input type="text" name="equipment_number" value="<?php echo e(old('equipment_number', $logbook->equipment_number ?? $logbook->equipment?->unit_code)); ?>" placeholder="Ketik nomor alat, contoh: DZ-123" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                </div>

                <!-- Trainer Evaluator -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Trainer Pembimbing <span class="text-rose-500">*</span></label>
                    <select name="trainer_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                        <?php $__currentLoopData = $trainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tr->id); ?>" <?php echo e(old('trainer_id', $logbook->trainer_id) == $tr->id ? 'selected' : ''); ?>>
                                <?php echo e($tr->name); ?> (<?php echo e($tr->nrp); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Supervisor -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Supervisor Lapangan</label>
                    <select name="supervisor_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                        <option value="">Pilih Supervisor</option>
                        <?php $__currentLoopData = $supervisors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($spv->id); ?>" <?php echo e(old('supervisor_id', $logbook->supervisor_id) == $spv->id ? 'selected' : ''); ?>>
                                <?php echo e($spv->name); ?> (<?php echo e($spv->nrp); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION B: DAILY ACTIVITIES -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">B</span>
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Rincian Aktivitas Harian (Daily Activities & Task Logs)</h2>
                </div>
            </div>

            <div class="p-6">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Detail Pengoperasian & P2H <span class="text-rose-500">*</span></label>
                <textarea name="daily_activity" rows="6" required class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono leading-relaxed focus:ring-2 focus:ring-[#00A859] focus:bg-white transition"><?php echo e(old('daily_activity', $logbook->daily_activity)); ?></textarea>
            </div>
        </div>

        <!-- SECTION C: WORKING HOURS & HOUR METER -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">C</span>
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Jam Kerja & Hour Meter (HM Logging)</h2>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jam Mulai Shift <span class="text-rose-500">*</span></label>
                    <input type="time" name="start_time" value="<?php echo e(old('start_time', $logbook->start_time)); ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jam Selesai Shift <span class="text-rose-500">*</span></label>
                    <input type="time" name="finish_time" value="<?php echo e(old('finish_time', $logbook->finish_time)); ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">HM Awal Unit <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.1" name="hm_start" x-model="hmStart" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">HM Akhir Unit <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.1" name="hm_end" x-model="hmEnd" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                </div>

                <div class="bg-[#003829] text-white p-3.5 rounded-xl border border-emerald-900 shadow-sm flex flex-col justify-center">
                    <span class="text-[10px] text-emerald-300 font-bold uppercase tracking-wider">Total HM Digunakan</span>
                    <div class="flex items-baseline space-x-1 mt-1">
                        <span class="text-2xl font-black text-[#F5A623]" x-text="totalHm">0.0</span>
                        <span class="text-xs text-emerald-200 font-bold">Jam</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION D: EVIDENCE UPLOAD -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="w-7 h-7 rounded-lg bg-[#003829] text-white font-bold text-xs flex items-center justify-center">D</span>
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Tambah / Perbarui Bukti Lapangan</h2>
                </div>
            </div>

            <div class="p-6">
                <!-- Dropzone -->
                <div class="border-2 border-dashed border-slate-300 hover:border-[#00A859] rounded-2xl p-8 text-center bg-slate-50 hover:bg-emerald-50/30 transition group cursor-pointer relative">
                    <input type="file" name="evidences[]" multiple @change="handleFileSelect" accept="image/*,video/*,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <svg class="w-10 h-10 mx-auto text-slate-400 group-hover:text-[#00A859] transition mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-xs font-bold text-slate-700">Tarik & Lepas Berkas Bukti Baru di Sini, atau <span class="text-[#00A859] underline">Pilih File</span></p>
                </div>
            </div>
        </div>

        <!-- STICKY BOTTOM ACTION BAR -->
        <div class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-md border-t border-slate-200 py-4 px-8 z-40 flex items-center justify-between shadow-2xl">
            <a href="<?php echo e(route('ojt.logbooks.show', $logbook->id)); ?>" class="text-xs font-bold text-slate-500 hover:text-slate-700">Batal</a>

            <div class="flex items-center space-x-4">
                <button type="submit" name="action_type" value="draft" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl shadow-xs transition">
                    Simpan Perubahan Draft
                </button>
                <button type="submit" name="action_type" value="submit" class="px-7 py-2.5 bg-[#00A859] hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Ulang ke Trainer
                </button>
            </div>
        </div>

    </form>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/ojt/logbooks/edit.blade.php ENDPATH**/ ?>