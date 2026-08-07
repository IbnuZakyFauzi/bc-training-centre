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
     <?php $__env->slot('title', null, []); ?> Edit Logbook <?php echo e($logbook->logbook_number); ?> <?php $__env->endSlot(); ?>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="<?php echo e(route('trainer.reviews.show', $logbook->id)); ?>" class="text-xs font-bold text-[#00A859] hover:underline">← Kembali ke review</a>
            <h1 class="mt-2 text-xl font-extrabold text-slate-800">Edit Logbook <?php echo e($logbook->logbook_number); ?></h1>
            <p class="mt-1 text-xs text-slate-500">Perubahan disimpan oleh trainer sebelum logbook disetujui.</p>
        </div>
    </div>

    <form method="POST" action="<?php echo e(route('trainer.reviews.update', $logbook->id)); ?>" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <?php if($errors->any()): ?>
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-800"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-5 md:grid-cols-2">
                <label class="text-xs font-bold text-slate-700">Tanggal<input type="date" name="date" value="<?php echo e(old('date', $logbook->date->format('Y-m-d'))); ?>" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">Shift<select name="shift" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"><option value="day" <?php if(old('shift', $logbook->shift) === 'day'): echo 'selected'; endif; ?>>Shift 1</option><option value="night" <?php if(old('shift', $logbook->shift) === 'night'): echo 'selected'; endif; ?>>Shift 2</option></select></label>
                <label class="text-xs font-bold text-slate-700">Nomor alat<input type="text" name="equipment_number" value="<?php echo e(old('equipment_number', $logbook->equipment_number ?? $logbook->equipment?->unit_code)); ?>" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">Lokasi<input type="text" name="location" value="<?php echo e(old('location', $logbook->location)); ?>" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">Jam mulai<input type="time" name="start_time" value="<?php echo e(old('start_time', $logbook->start_time)); ?>" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">Jam selesai<input type="time" name="finish_time" value="<?php echo e(old('finish_time', $logbook->finish_time)); ?>" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">HM awal<input type="number" step="0.1" name="hm_start" value="<?php echo e(old('hm_start', $logbook->hm_start)); ?>" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">HM akhir<input type="number" step="0.1" name="hm_end" value="<?php echo e(old('hm_end', $logbook->hm_end)); ?>" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
            </div>
            <label class="mt-5 block text-xs font-bold text-slate-700">Catatan kegiatan<textarea name="daily_activity" rows="7" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"><?php echo e(old('daily_activity', $logbook->daily_activity)); ?></textarea></label>
        </section>
        <div class="flex justify-end gap-3"><a href="<?php echo e(route('trainer.reviews.show', $logbook->id)); ?>" class="rounded-xl bg-slate-100 px-5 py-3 text-xs font-bold text-slate-700">Batal</a><button class="rounded-xl bg-[#00A859] px-5 py-3 text-xs font-bold text-white">Simpan Perubahan</button></div>
    </form>

    <?php echo $__env->make('trainer.reviews.partials.checklist-editor', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/trainer/reviews/edit.blade.php ENDPATH**/ ?>