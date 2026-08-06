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
     <?php $__env->slot('title', null, []); ?> Submission History - OJT Evaluation <?php $__env->endSlot(); ?>

    <!-- Page Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Submission History</h1>
            <p class="text-xs text-slate-500 mt-1">Complete chronological audit trail of all OJT logbook submissions and trainer verification logs.</p>
        </div>
    </div>

    <!-- Timeline & Audit Logs Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Columns: Submissions Table with Revision Counter & Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Daftar Pengajuan Logbook</h2>
                    <span class="text-xs font-semibold text-slate-500">Chronological Order</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">
                                <th class="py-3.5 px-5">Submission Date</th>
                                <th class="py-3.5 px-4">Logbook & Equipment</th>
                                <th class="py-3.5 px-4">Trainer</th>
                                <th class="py-3.5 px-4 text-center">Revision Count</th>
                                <th class="py-3.5 px-4">Current Status</th>
                                <th class="py-3.5 px-4">Last Updated</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            <?php $__empty_1 = true; $__currentLoopData = $logbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/80 transition">
                                    <!-- Submission Date -->
                                    <td class="py-4 px-5">
                                        <span class="font-bold text-slate-800 block">
                                            <?php echo e($log->submitted_at ? $log->submitted_at->format('d M Y') : \Carbon\Carbon::parse($log->date)->format('d M Y')); ?>

                                        </span>
                                        <span class="text-[10px] text-slate-400 block mt-0.5">Shift <?php echo e(ucfirst($log->shift)); ?></span>
                                    </td>

                                    <!-- Equipment & Logbook Number -->
                                    <td class="py-4 px-4">
                                        <a href="<?php echo e(route('ojt.logbooks.show', $log->id)); ?>" class="font-bold text-slate-900 hover:text-[#00A859] block">
                                            <?php echo e($log->logbook_number); ?>

                                        </a>
                                        <span class="text-[10px] text-[#003829] font-semibold block mt-0.5"><?php echo e($log->equipment->unit_code ?? '-'); ?> (<?php echo e($log->equipment->model_name ?? '-'); ?>)</span>
                                    </td>

                                    <!-- Trainer -->
                                    <td class="py-4 px-4 text-slate-700 font-medium">
                                        <?php echo e($log->trainer->name ?? 'Belum Ditunjuk'); ?>

                                    </td>

                                    <!-- Revision Count -->
                                    <td class="py-4 px-4 text-center">
                                        <?php if($log->revision_count > 0): ?>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300">
                                                <?php echo e($log->revision_count); ?> Revision(s)
                                            </span>
                                        <?php else: ?>
                                            <span class="text-[10px] text-slate-400">0</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Current Status -->
                                    <td class="py-4 px-4">
                                        <?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2ddbc40e602c342e508ac696e52f8719 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.badge','data' => ['status' => $log->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($log->status)]); ?>
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
                                    </td>

                                    <!-- Last Updated -->
                                    <td class="py-4 px-4 text-slate-500 text-[11px]">
                                        <?php echo e($log->updated_at->diffForHumans()); ?>

                                    </td>

                                    <!-- Actions (View Detail, Print, Download PDF) -->
                                    <td class="py-4 px-5 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="<?php echo e(route('ojt.logbooks.show', $log->id)); ?>" class="p-1.5 text-slate-600 hover:text-[#00A859] hover:bg-emerald-50 rounded-lg transition" title="View Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                            <a href="<?php echo e(route('ojt.logbooks.print', $log->id)); ?>" target="_blank" class="p-1.5 text-slate-500 hover:text-[#003829] hover:bg-slate-100 rounded-lg transition" title="Print Logbook">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            </a>
                                            <a href="<?php echo e(route('ojt.logbooks.print', $log->id)); ?>" target="_blank" class="p-1.5 text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition" title="Download PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400">
                                        Belum ada riwayat pengajuan logbook.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    <?php echo e($logbooks->links()); ?>

                </div>
            </div>

        </div>

        <!-- Right Column: Audit Trail Timeline Visual -->
        <div class="space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-6">Aktivitas Sistem Real-Time</h2>

                <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    <?php $__currentLoopData = $timelineHistories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $th): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative">
                            <div class="absolute -left-6 top-0.5 w-4 h-4 rounded-full bg-[#00A859] border-2 border-white ring-2 ring-emerald-100"></div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 block"><?php echo e($th->action); ?></span>
                                <span class="text-[10px] text-[#003829] font-mono block mt-0.5"><?php echo e($th->logbook->logbook_number ?? '-'); ?> (<?php echo e($th->logbook->equipment->unit_code ?? '-'); ?>)</span>
                                <span class="text-[10px] text-slate-400 block mt-0.5"><?php echo e($th->created_at->diffForHumans()); ?></span>
                                <?php if($th->comment): ?>
                                    <p class="text-[11px] text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-200 mt-2 italic">
                                        "<?php echo e($th->comment); ?>"
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

        </div>

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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views\ojt\history.blade.php ENDPATH**/ ?>