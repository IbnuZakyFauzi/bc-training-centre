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
     <?php $__env->slot('title', null, []); ?> Approval Pengawas <?php $__env->endSlot(); ?>

    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Approval Pengawas</h1>
        <p class="text-xs text-slate-500 mt-1">Review dan setujui logbook OJT yang ditugaskan kepada Anda.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Menunggu Review</p>
            <p class="text-2xl font-black text-[#F5A623] mt-1"><?php echo e($counts['pending']); ?></p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Disetujui</p>
            <p class="text-2xl font-black text-[#00A859] mt-1"><?php echo e($counts['approved']); ?></p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Ditolak</p>
            <p class="text-2xl font-black text-rose-600 mt-1"><?php echo e($counts['rejected']); ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Daftar Logbook</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4 text-left">Logbook</th>
                        <th class="px-5 py-4 text-left">Trainee</th>
                        <th class="px-5 py-4 text-left">Unit</th>
                        <th class="px-5 py-4 text-left">Tanggal</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $logbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logbook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-emerald-50/30">
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-800"><?php echo e($logbook->logbook_number); ?></p>
                                <p class="text-[11px] text-slate-500 mt-1"><?php echo e($logbook->trainee->name ?? '-'); ?> · <?php echo e($logbook->trainee->sid ?? '-'); ?></p>
                            </td>
                            <td class="px-5 py-4 text-xs">
                                <p class="font-semibold text-slate-700"><?php echo e($logbook->unit_code); ?></p>
                                <p class="text-[11px] text-slate-500 mt-1"><?php echo e(ucfirst($logbook->shift)); ?> · <?php echo e($logbook->total_hm); ?> HM</p>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600"><?php echo e(optional($logbook->date)->format('d M Y') ?? '-'); ?></td>
                            <td class="px-5 py-4 text-right">
                                <a href="<?php echo e(route('supervisor.approvals.show', $logbook->id)); ?>" class="inline-flex px-3 py-2 rounded-lg bg-emerald-50 text-[#00593E] hover:bg-emerald-100 text-xs font-bold">Review</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-xs text-slate-400">Tidak ada logbook yang menunggu review.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($logbooks->hasPages()): ?>
            <div class="px-5 py-4 border-t border-slate-200">
                <?php echo e($logbooks->links()); ?>

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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/supervisor/approvals/index.blade.php ENDPATH**/ ?>