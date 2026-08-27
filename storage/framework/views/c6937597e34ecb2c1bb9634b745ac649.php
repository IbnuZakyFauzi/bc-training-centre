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
     <?php $__env->slot('title', null, []); ?> Monitoring Evaluasi Multi-Fase <?php $__env->endSlot(); ?>

    <div class="mb-4 sm:mb-8 bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] p-4 sm:p-6 rounded-2xl shadow-md text-white border border-blue-900 flex flex-col md:flex-row md:items-center md:justify-between gap-3 sm:gap-4">
        <div>
            <p class="text-blue-300 text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-1"><?php echo e($user->isTrainingCentre() ? 'Admin Training Centre' : 'Trainer'); ?></p>
            <h1 class="text-xl sm:text-2xl font-bold">Monitoring Progress Evaluasi Multi-Fase</h1>
            <p class="text-blue-100 text-[10px] sm:text-xs mt-1"><?php echo e($user->isTrainingCentre() ? 'Seluruh trainee terdaftar.' : 'Hanya trainee yang Anda bimbing (instruktur/pengawas/operator pendamping).'); ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-4 sm:mb-7">
        <form method="GET" class="p-3 sm:p-4 flex flex-col sm:flex-row gap-2 sm:gap-3 border-b border-slate-100">
            <input name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama / SID..." class="text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 flex-1 min-h-[44px]">
            <select name="certification" class="text-xs rounded-xl border-slate-300 focus:border-brand-500 focus:ring-brand-500 min-h-[44px]">
                <option value="">Semua Sertifikasi</option>
                <?php $__currentLoopData = $certifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c); ?>" <?php echo e(request('certification') === $c ? 'selected' : ''); ?>><?php echo e($c); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="px-5 py-2.5 rounded-xl bg-[#1e3a8a] text-white text-xs font-bold min-h-[44px]">Filter</button>
        </form>
    </div>

    <div class="space-y-5">
        <?php $__empty_1 = true; $__currentLoopData = $trainees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100">
                    <div class="flex items-center space-x-4">
                        <div class="w-11 h-11 rounded-full bg-[#2563eb] flex items-center justify-center text-white font-bold">
                            <?php echo e(collect(explode(' ', $t['name']))->take(2)->map(fn($p)=>strtoupper(substr($p,0,1)))->implode('')); ?>

                        </div>
                        <div>
                            <p class="text-base font-extrabold text-slate-800"><?php echo e($t['name']); ?></p>
                            <p class="text-xs text-slate-500"><?php echo e($t['sid']); ?> · <?php echo e($t['certification']); ?> · Fase Saat Ini: <span class="font-bold text-[#1e3a8a]"><?php echo e($t['current_phase_label']); ?></span></p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <?php if($t['eligible']): ?>
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#2563eb] text-white">Siap Dievaluasi</span>
                        <?php else: ?>
                            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">Akumulasi HM</span>
                        <?php endif; ?>
                        <div class="text-right">
                            <p class="text-xs text-slate-500">Evaluasi Selesai</p>
                            <p class="text-lg font-extrabold text-[#1e3a8a]"><?php echo e($t['completed_count']); ?> <span class="text-xs font-normal text-slate-400">/ <?php echo e($t['evaluations_count']); ?> total</span></p>
                        </div>
                    </div>
                </div>

                <?php if(($t['progress']['total'] ?? 0) < 100): ?>
                    <div class="px-5 pt-4">
                        <div class="flex justify-between text-xs text-slate-600 mb-1">
                            <span>Progress HM Fase <?php echo e($t['current_phase_label']); ?></span>
                            <span class="font-bold"><?php echo e(number_format($t['hm']['total'], 1)); ?> jam (<?php echo e($t['progress']['total']); ?>%)</span>
                        </div>
                        <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-[#2563eb]" style="width: <?php echo e($t['progress']['total']); ?>%"></div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="p-4 sm:p-5">
                    <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Rincian Evaluasi per Fase</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                        <?php $__currentLoopData = $t['phases']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="rounded-xl border p-3 <?php echo e($p['is_current'] ? 'border-[#2563eb] bg-[#2563eb]/5' : 'border-slate-200'); ?>">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-700"><?php echo e($p['label']); ?></span>
                                    <?php if($p['is_current']): ?><span class="text-[9px] font-extrabold text-[#2563eb]">●</span><?php endif; ?>
                                </div>
                                <p class="text-[10px] text-slate-500 mt-0.5">Selesai: <span class="font-bold text-[#2563eb]"><?php echo e($p['completed']); ?></span></p>
                                <?php if($p['pending'] > 0): ?>
                                    <p class="text-[10px] text-amber-600">Proses: <?php echo e($p['pending']); ?></p>
                                <?php endif; ?>
                                <?php if($p['rejected'] > 0): ?>
                                    <p class="text-[10px] text-red-600">Ditolak: <?php echo e($p['rejected']); ?></p>
                                <?php endif; ?>
                                <?php if($p['total'] == 0): ?>
                                    <p class="text-[10px] text-slate-300">Belum ada</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center text-slate-400 text-sm">
                Tidak ada trainee yang sesuai.
            </div>
        <?php endif; ?>
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


<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/evaluation-flow/monitoring.blade.php ENDPATH**/ ?>