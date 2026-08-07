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
     <?php $__env->slot('title', null, []); ?> Dashboard Pengawas <?php $__env->endSlot(); ?>
    <div class="mb-8 bg-gradient-to-r from-[#003829] to-[#00593E] p-6 rounded-2xl shadow-md text-white border border-emerald-900 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div><p class="text-emerald-300 text-xs font-bold uppercase tracking-widest mb-1">Department Operation · Pengawas</p><h1 class="text-2xl font-bold">Dashboard Approval Pengawas</h1><p class="text-emerald-100 text-xs mt-1">Final review atas logbook dan evaluasi kompetensi yang telah diverifikasi Trainer.</p></div>
        <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-xs"><p class="text-emerald-200">Pengawas Operasional</p><p class="font-bold mt-0.5"><?php echo e($operator->name); ?> · <?php echo e($operator->nrp); ?></p></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-7">
        <?php $__currentLoopData = [['Menunggu Persetujuan', $kpi['pending'], 'blue'], ['Disetujui Bulan Ini', $kpi['approved_this_month'], 'emerald'], ['Dikembalikan Bulan Ini', $kpi['returned_this_month'], 'amber']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm"><p class="text-xs font-bold uppercase tracking-wide text-<?php echo e($color); ?>-600"><?php echo e($label); ?></p><p class="mt-2 text-3xl font-extrabold text-slate-800"><?php echo e($value); ?></p></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"><div class="p-5 border-b border-slate-100 flex justify-between items-center"><div><h2 class="font-bold text-slate-800">Antrean Prioritas</h2><p class="text-xs text-slate-500 mt-1">Hasil evaluasi Trainer yang siap disetujui.</p></div><a href="<?php echo e(route('department-operation.approvals.pending')); ?>" class="text-xs font-bold text-[#00A859]">Lihat semua</a></div><div class="divide-y divide-slate-100"><?php $__empty_1 = true; $__currentLoopData = $pendingLogbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logbook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a href="<?php echo e(route('department-operation.approvals.show', $logbook->id)); ?>" class="block px-5 py-4 hover:bg-emerald-50/40"><div class="flex justify-between gap-3"><div><p class="text-xs font-bold text-slate-800"><?php echo e($logbook->logbook_number); ?></p><p class="text-[11px] text-slate-500 mt-1"><?php echo e($logbook->trainee->name); ?> · <?php echo e($logbook->unit_code); ?></p></div><span class="text-[11px] text-slate-500"><?php echo e(optional($logbook->verified_at)->format('d M Y')); ?></span></div></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <p class="px-5 py-10 text-center text-sm text-slate-400">Tidak ada antrean persetujuan.</p><?php endif; ?></div></section>
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"><div class="p-5 border-b border-slate-100 flex justify-between items-center"><div><h2 class="font-bold text-slate-800">Keputusan Terbaru</h2><p class="text-xs text-slate-500 mt-1">Riwayat keputusan Pengawas.</p></div><a href="<?php echo e(route('department-operation.approvals.history')); ?>" class="text-xs font-bold text-[#00A859]">Lihat riwayat</a></div><div class="divide-y divide-slate-100"><?php $__empty_1 = true; $__currentLoopData = $recentApprovals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logbook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a href="<?php echo e(route('department-operation.approvals.show', $logbook->id)); ?>" class="block px-5 py-4 hover:bg-emerald-50/40"><div class="flex justify-between gap-3"><div><p class="text-xs font-bold text-slate-800"><?php echo e($logbook->logbook_number); ?></p><p class="text-[11px] text-slate-500 mt-1"><?php echo e($logbook->trainee->name); ?> · <?php echo e(optional($logbook->pjo_decided_at)->format('d M Y, H:i')); ?></p></div><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></div></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <p class="px-5 py-10 text-center text-sm text-slate-400">Belum ada keputusan yang dibuat.</p><?php endif; ?></div></section>
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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/department-operation/dashboard.blade.php ENDPATH**/ ?>