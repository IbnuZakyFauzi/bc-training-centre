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
     <?php $__env->slot('title', null, []); ?> Trainer Review Queue <?php $__env->endSlot(); ?>
    <div class="mb-8 bg-gradient-to-r from-[#003829] to-[#00593E] p-6 rounded-2xl shadow-md text-white border border-emerald-900 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-emerald-300 text-xs font-bold uppercase tracking-widest mb-1">Trainer Module</p>
            <h1 class="text-2xl font-bold">Review & Evaluasi Kompetensi</h1>
            <p class="text-emerald-100 text-xs mt-1">Verifikasi Digital Logbook, isi penilaian SOP, dan teruskan hasil ke PJO.</p>
        </div>
        <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-xs">
            <p class="text-emerald-200">Trainer aktif</p><p class="font-bold mt-0.5"><?php echo e($trainer->name); ?> · <?php echo e($trainer->nrp); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-7">
        <?php $__currentLoopData = [['Menunggu Review', $counts['submitted'], 'blue'], ['Perlu Tindak Lanjut', $counts['revision'], 'amber'], ['Sudah Diverifikasi', $counts['verified'], 'emerald']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm"><p class="text-xs font-bold uppercase tracking-wide text-<?php echo e($color); ?>-600"><?php echo e($label); ?></p><p class="mt-2 text-3xl font-extrabold text-slate-800"><?php echo e($value); ?></p></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div><h2 class="font-bold text-slate-800">Antrean Logbook</h2><p class="text-xs text-slate-500 mt-1">Prioritaskan pengajuan terbaru dan revisi yang masuk kembali.</p></div>
            <form class="flex gap-2" method="GET"><input name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari NRP atau logbook..." class="text-xs rounded-xl border-slate-300 focus:border-[#00A859] focus:ring-[#00A859]"><select name="status" class="text-xs rounded-xl border-slate-300"><option value="all">Semua status</option><option value="submitted" <?php if(request('status') === 'submitted'): echo 'selected'; endif; ?>>Menunggu review</option><option value="revision" <?php if(request('status') === 'revision'): echo 'selected'; endif; ?>>Perlu revisi</option><option value="verified" <?php if(request('status') === 'verified'): echo 'selected'; endif; ?>>Terverifikasi</option></select><button class="px-4 rounded-xl bg-[#003829] text-white text-xs font-bold">Filter</button></form>
        </div>
        <div class="overflow-x-auto"><table class="w-full text-left"><thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-3">Logbook / Trainee</th><th class="px-5 py-3">Unit & Shift</th><th class="px-5 py-3">Dikirim</th><th class="px-5 py-3">Status</th><th class="px-5 py-3"></th></tr></thead><tbody class="divide-y divide-slate-100">
        <?php $__empty_1 = true; $__currentLoopData = $logbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logbook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-emerald-50/30"><td class="px-5 py-4"><p class="text-xs font-bold text-slate-800"><?php echo e($logbook->logbook_number); ?></p><p class="text-[11px] text-slate-500 mt-1"><?php echo e($logbook->trainee->name); ?> · <?php echo e($logbook->trainee->nrp); ?></p></td><td class="px-5 py-4 text-xs"><p class="font-semibold text-slate-700"><?php echo e($logbook->equipment->unit_code ?? '-'); ?></p><p class="text-[11px] text-slate-500 mt-1"><?php echo e(ucfirst($logbook->shift)); ?> · <?php echo e($logbook->total_hm); ?> HM</p></td><td class="px-5 py-4 text-xs text-slate-600"><?php echo e(optional($logbook->submitted_at)->format('d M Y, H:i') ?? '-'); ?></td><td class="px-5 py-4"><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></td><td class="px-5 py-4 text-right"><a href="<?php echo e(route('trainer.reviews.show', $logbook->id)); ?>" class="inline-flex px-3 py-2 rounded-lg bg-emerald-50 text-[#00593E] hover:bg-emerald-100 text-xs font-bold"><?php echo e($logbook->status === 'verified' ? 'Lihat Evaluasi' : 'Review'); ?></a></td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada logbook pada antrean ini.</td></tr><?php endif; ?>
        </tbody></table></div><div class="p-5 border-t border-slate-100"><?php echo e($logbooks->links()); ?></div>
    </div>
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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views\trainer\reviews\index.blade.php ENDPATH**/ ?>