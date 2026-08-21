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
     <?php $__env->slot('title', null, []); ?> Admin Training Centre <?php $__env->endSlot(); ?>

    <div class="mb-8 bg-gradient-to-r from-[#003829] to-[#00593E] p-6 rounded-2xl shadow-md text-white border border-emerald-900 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <p class="text-emerald-300 text-xs font-bold uppercase tracking-widest mb-1">Admin Training Centre</p>
            <h1 class="text-2xl font-bold">Final Approval & Cetak Logbook</h1>
            <p class="text-emerald-100 text-xs mt-1">Persetujuan akhir dan pengelolaan cetak/download logbook resmi OJT.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-xs">
                <p class="text-emerald-200">Admin Training Centre</p>
                <p class="font-bold mt-0.5"><?php echo e($reviewer->name); ?> · <?php echo e($reviewer->sid); ?></p>
            </div>
            <a href="<?php echo e(route('training-centre.monitoring')); ?>" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-[#F5A623] hover:bg-amber-500 text-gray-900 text-xs font-bold shadow-sm transition-all transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Monitoring Evaluasi
            </a>
        </div>
    </div>

    <!-- Status Filter Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-7">
        <?php $__currentLoopData = [
            ['pending', 'Menunggu Final Approval', $counts['pending'], 'blue'],
            ['finalized', 'Dokumen Final (Siap Cetak)', $counts['finalized'], 'emerald'],
            ['revision', 'Dikembalikan Revisi', $counts['revision'], 'amber']
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $value, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('training-centre.approvals.index', ['status' => $key])); ?>" class="block bg-white rounded-2xl border transition-all p-5 shadow-sm hover:shadow-md <?php echo e(($activeStatus ?? 'pending') === $key ? 'ring-2 ring-[#00A859] border-[#00A859]' : 'border-slate-200'); ?>">
                <p class="text-xs font-bold uppercase tracking-wide text-<?php echo e($color); ?>-600"><?php echo e($label); ?></p>
                <p class="mt-2 text-3xl font-extrabold text-slate-800"><?php echo e($value); ?></p>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- OJT Multi-Phase Monitoring -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-800">Rekap Posisi Fase Evaluasi Trainee</h3>
            <p class="text-xs text-slate-500 mt-1 mb-3">Distribusi fase saat ini seluruh trainee (real-time).</p>
            <div class="space-y-2">
                <?php $__empty_1 = true; $__currentLoopData = $phaseRecap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-600"><?php echo e($label); ?></span>
                        <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded"><?php echo e($count); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-xs text-slate-400">Belum ada trainee.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Antrean Persetujuan Evaluasi Akhir (Trainer → TC)</h3>
                    <p class="text-xs text-slate-500 mt-1">Evaluasi yang menunggu persetujuan Admin TC.</p>
                </div>
                <div class="flex space-x-2 text-[11px] font-bold">
                    <span class="px-2 py-1 rounded bg-blue-100 text-blue-700">TC: <?php echo e($evalCounts['submitted']); ?></span>
                    <span class="px-2 py-1 rounded bg-purple-100 text-purple-700">PJO: <?php echo e($evalCounts['tc_approved']); ?></span>
                    <span class="px-2 py-1 rounded bg-amber-100 text-amber-700">HSE: <?php echo e($evalCounts['pjo_approved']); ?></span>
                    <span class="px-2 py-1 rounded bg-[#00A859]/10 text-[#00A859]">Selesai: <?php echo e($evalCounts['completed']); ?></span>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                <?php $__empty_1 = true; $__currentLoopData = $pendingEvaluations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between px-5 py-3">
                        <div>
                            <p class="text-sm font-bold text-slate-800"><?php echo e($ev->nama_operator); ?></p>
                            <p class="text-[11px] text-slate-500"><?php echo e($ev->phase); ?> · Trainer: <?php echo e($ev->trainer->name ?? '-'); ?></p>
                        </div>
                        <a href="<?php echo e(route('training-centre.final-evaluations.show', $ev->id)); ?>" class="px-3 py-1.5 rounded-lg bg-[#003829] text-white text-[11px] font-bold">Review</a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-xs text-slate-400 px-5 py-4">Tidak ada evaluasi menunggu persetujuan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="font-bold text-slate-800">
                    <?php echo e(($activeStatus ?? 'pending') === 'finalized' ? 'Dokumen Final Disahkan' : (($activeStatus ?? 'pending') === 'revision' ? 'Logbook Dikembalikan' : 'Antrean Final Approval')); ?>

                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    <?php echo e(($activeStatus ?? 'pending') === 'finalized' ? 'Hanya Admin Training Centre yang dapat mengunduh dan mencetak form logbook ini.' : 'Tinjau detail logbook dan berikan keputusan final approval.'); ?>

                </p>
            </div>
            <form class="flex gap-2" method="GET">
                <input type="hidden" name="status" value="<?php echo e($activeStatus ?? 'pending'); ?>">
                <input name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari SID atau logbook..." class="text-xs rounded-xl border-slate-300">
                <button class="px-4 rounded-xl bg-[#003829] text-white text-xs font-bold">Cari</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <?php if($activeStatus === 'finalized'): ?>
                            <th class="px-5 py-3">Trainee</th>
                            <th class="px-5 py-3">Trainer</th>
                            <th class="px-5 py-3">Total Logbook</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        <?php else: ?>
                            <th class="px-5 py-3">Logbook / Trainee</th>
                            <th class="px-5 py-3">Trainer</th>
                            <th class="px-5 py-3">Status / Tanggal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if($activeStatus === 'finalized' && isset($groupedFinalized)): ?>
                        <?php $__empty_1 = true; $__currentLoopData = $groupedFinalized; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $traineeId => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-emerald-50/30">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-bold text-slate-800"><?php echo e($group['trainee']->name ?? '-'); ?></p>
                                    <p class="text-[11px] text-slate-500 mt-1"><?php echo e($group['trainee']->sid ?? '-'); ?></p>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <?php echo e($group['trainer']->name ?? '-'); ?>

                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">
                                        <?php echo e($group['count']); ?> Logbook
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-1">
                                        Terakhir diperbarui: <?php echo e($group['latest_date']->format('d M Y, H:i')); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="<?php echo e(route('ojt.logbooks.print-trainee', $traineeId)); ?>" target="_blank" class="inline-flex items-center px-4 py-2 rounded-lg bg-[#003829] text-white hover:bg-[#00241A] text-xs font-bold" title="Cetak Semua Logbook Final Approved untuk Trainee Ini">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak Semua
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada dokumen final disahkan.</td>
                            </tr>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php $__empty_1 = true; $__currentLoopData = $logbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logbook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-emerald-50/30">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-bold text-slate-800"><?php echo e($logbook->logbook_number); ?></p>
                                    <p class="text-[11px] text-slate-500 mt-1"><?php echo e($logbook->trainee->name); ?> · <?php echo e($logbook->trainee->sid); ?></p>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600"><?php echo e($logbook->trainer->name ?? '-'); ?>                            </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
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
                                    <span class="block text-[10px] text-slate-400 mt-1">
                                        <?php echo e(optional($logbook->training_centre_decided_at ?? $logbook->verified_at)->format('d M Y, H:i')); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="<?php echo e(route('training-centre.approvals.show', $logbook->id)); ?>" class="inline-flex px-3 py-2 rounded-lg bg-emerald-50 text-[#00593E] hover:bg-emerald-100 text-xs font-bold">
                                            Detail
                                        </a>
                                        <?php if($logbook->status === 'final_approved'): ?>
                                            <a href="<?php echo e(route('ojt.logbooks.print', $logbook->id)); ?>" target="_blank" class="inline-flex items-center px-3 py-2 rounded-lg bg-[#003829] text-white hover:bg-[#00241A] text-xs font-bold" title="Cetak / Download PDF Logbook">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                Cetak
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada logbook ditemukan pada kategori ini.</td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($activeStatus !== 'finalized'): ?>
            <div class="p-5 border-t border-slate-100"><?php echo e($logbooks->links()); ?></div>
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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/training-centre/approvals/index.blade.php ENDPATH**/ ?>