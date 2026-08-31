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
     <?php $__env->slot('title', null, []); ?> Detail Dokumen - <?php echo e($trainee->name); ?> <?php $__env->endSlot(); ?>

    <div class="mb-4 sm:mb-6 bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] p-4 sm:p-6 rounded-2xl shadow-md text-white border border-blue-900 flex flex-col md:flex-row md:items-center md:justify-between gap-3 sm:gap-4">
        <div>
            <p class="text-blue-300 text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-1">Admin Training Centre</p>
            <h1 class="text-xl sm:text-2xl font-bold">Detail Dokumen <?php echo e($trainee->name); ?></h1>
            <p class="text-blue-100 text-[10px] sm:text-xs mt-1"><?php echo e($trainee->sid); ?> · <?php echo e($trainee->certification ?? '-'); ?> · <?php echo e($trainee->currentPhaseMeta()['label'] ?? $trainee->current_phase); ?></p>
        </div>
        <a href="<?php echo e(route('training-centre.approvals.index')); ?>" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white/10 border border-white/15 text-white text-xs font-bold hover:bg-white/20 transition min-h-[44px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    <div class="space-y-6">
        <!-- Form OJT Harian -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Form OJT Harian</h2>
                <p class="text-xs text-slate-500 mt-1">Daftar form OJT harian yang telah disahkan (Final Approved).</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3">No. Form OJT</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3">Unit / Alat</th>
                            <th class="px-5 py-3">Shift</th>
                            <th class="px-5 py-3">Total HM</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $logbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logbook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-5 py-4 text-xs font-bold text-slate-800"><?php echo e($logbook->logbook_number); ?></td>
                                <td class="px-5 py-4 text-xs text-slate-600"><?php echo e($logbook->date?->format('d M Y') ?? '-'); ?></td>
                                <td class="px-5 py-4 text-xs text-slate-600"><?php echo e($logbook->unit_code); ?> · <?php echo e($logbook->equipmentCategory->name ?? '-'); ?></td>
                                <td class="px-5 py-4 text-xs text-slate-600"><?php echo e(ucfirst($logbook->shift ?? '-')); ?></td>
                                <td class="px-5 py-4 text-xs text-slate-600"><?php echo e($logbook->total_hm ?? 0); ?></td>
                                <td class="px-5 py-4 text-right">
                                    <a href="<?php echo e(route('ojt.logbooks.print', $logbook->id)); ?>" target="_blank" class="inline-flex items-center px-3 py-2 rounded-lg bg-[#1e3a8a] text-white hover:bg-[#172554] text-[11px] font-bold" title="Cetak Form OJT Ini">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-xs text-slate-400">Belum ada form OJT harian yang disahkan untuk trainee ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Form Evaluasi -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Form Evaluasi</h2>
                <p class="text-xs text-slate-500 mt-1">Daftar formulir evaluasi akhir yang telah disubmit.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Tanggal Penilaian</th>
                            <th class="px-5 py-3">Jenis Unit A2B</th>
                            <th class="px-5 py-3">Fase</th>
                            <th class="px-5 py-3">Trainer</th>
                            <th class="px-5 py-3">Kesimpulan</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $evaluations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-5 py-4 text-xs text-slate-600"><?php echo e(\Carbon\Carbon::parse($ev->tanggal_penilaian)->format('d M Y') ?? '-'); ?></td>
                                <td class="px-5 py-4 text-xs text-slate-600"><?php echo e($ev->jenis_unit_a2b ?? '-'); ?></td>
                                <td class="px-5 py-4 text-xs text-slate-600"><?php echo e(format_phase_label($ev->phase, $ev->jenis_sertifikasi ?? null)); ?></td>
                                <td class="px-5 py-4 text-xs text-slate-600"><?php echo e($ev->trainer->name ?? '-'); ?></td>
                                <td class="px-5 py-4 text-xs">
                                    <?php if($ev->kesimpulan === 'kompeten'): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-100 text-amber-700 font-bold">Kompeten</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-100 text-amber-700 font-bold">Belum Kompeten</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="<?php echo e(route('training-centre.final-evaluations.print', $ev->id)); ?>" target="_blank" class="inline-flex items-center px-3 py-2 rounded-lg bg-[#1e3a8a] text-white hover:bg-[#172554] text-[11px] font-bold" title="Cetak Form Evaluasi Ini">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        Cetak
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-xs text-slate-400">Belum ada form evaluasi untuk trainee ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cetak Semua -->
        <div class="flex justify-center pb-4 sm:pb-6">
            <a href="<?php echo e(route('ojt.logbooks.print-trainee', $trainee->id)); ?>" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3 rounded-xl bg-[#f59e0b] hover:bg-amber-500 text-gray-900 text-sm font-bold shadow-sm transition-all min-h-[44px]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Semua Dokumen
            </a>
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


<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/training-centre/trainee-documents.blade.php ENDPATH**/ ?>