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

    <div class="mb-4 sm:mb-8 bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] p-4 sm:p-6 rounded-2xl shadow-md text-white border border-blue-900 flex flex-col md:flex-row md:items-center md:justify-between gap-3 sm:gap-4">
        <div>
            <p class="text-blue-300 text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-1">Admin Training Centre</p>
            <h1 class="text-xl sm:text-2xl font-bold">Final Approval & Cetak Form OJT</h1>
            <p class="text-blue-100 text-[10px] sm:text-xs mt-1">Persetujuan akhir dan pengelolaan cetak/download form OJT resmi OJT.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
            <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-2.5 sm:py-3 text-xs">
                <p class="text-blue-200">Admin Training Centre</p>
                <p class="font-bold mt-0.5"><?php echo e($reviewer->name); ?> · <?php echo e($reviewer->sid); ?></p>
            </div>
            <a href="<?php echo e(route('training-centre.monitoring')); ?>" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-[#f59e0b] hover:bg-amber-500 text-gray-900 text-xs font-bold shadow-sm transition-all min-h-[44px]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Monitoring Evaluasi
            </a>
        </div>
    </div>

    <!-- Status Filter Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-5 mb-4 sm:mb-7">
        <?php $__currentLoopData = [
            ['pending', 'Menunggu Final Approval', $counts['pending'], 'blue'],
            ['finalized', 'Dokumen Final (Siap Cetak)', $counts['finalized'], 'blue'],
            ['revision', 'Dikembalikan Revisi', $counts['revision'], 'amber']
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $value, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('training-centre.approvals.index', ['status' => $key])); ?>" class="block bg-white rounded-2xl border transition-all p-5 shadow-sm hover:shadow-md <?php echo e(($activeStatus ?? 'pending') === $key ? 'ring-2 ring-[#2563eb] border-[#2563eb]' : 'border-slate-200'); ?>">
                <p class="text-xs font-bold uppercase tracking-wide text-<?php echo e($color); ?>-600"><?php echo e($label); ?></p>
                <p class="mt-2 text-3xl font-extrabold text-slate-800"><?php echo e($value); ?></p>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Main Data Table (Antrean Approval) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-4 sm:mb-7">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="font-bold text-slate-800 text-sm sm:text-base">
                    <?php echo e(($activeStatus ?? 'pending') === 'finalized' ? 'Dokumen Final Disahkan' : (($activeStatus ?? 'pending') === 'revision' ? 'Form OJT Dikembalikan' : 'Antrean Approval')); ?>

                </h2>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-1">
                    <?php echo e(($activeStatus ?? 'pending') === 'finalized' ? 'Hanya Admin Training Centre yang dapat mengunduh dan mencetak form logbook ini.' : 'Tinjau detail logbook dan berikan keputusan approval.'); ?>

                </p>
            </div>
            <form class="flex flex-col sm:flex-row gap-2" method="GET">
                <input type="hidden" name="status" value="<?php echo e($activeStatus ?? 'pending'); ?>">
                <input name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari SID atau logbook..." class="text-xs rounded-xl border-slate-300 min-h-[44px]">
                <button class="px-4 py-2.5 rounded-xl bg-[#1e3a8a] text-white text-xs font-bold min-h-[44px]">Cari</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <?php if($activeStatus === 'finalized'): ?>
                            <th class="px-5 py-3">Trainee</th>
                            <th class="px-5 py-3">Instruktur</th>
                            <th class="px-5 py-3">Total Form OJT</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        <?php else: ?>
                            <th class="px-5 py-3">Form OJT / Trainee</th>
                            <th class="px-5 py-3">Trainer</th>
                            <th class="px-5 py-3">Status / Tanggal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if($activeStatus === 'finalized' && isset($groupedFinalized)): ?>
                        <?php $__empty_1 = true; $__currentLoopData = $groupedFinalized; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $traineeId => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-bold text-slate-800"><?php echo e($group['trainee']->name ?? '-'); ?></p>
                                    <p class="text-[11px] text-slate-500 mt-1"><?php echo e($group['trainee']->sid ?? '-'); ?></p>
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <?php echo e($group['trainer']->name ?? '-'); ?>

                                </td>
                                <td class="px-5 py-4 text-xs text-slate-600">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 font-bold">
                                        <?php echo e($group['count']); ?> Form OJT
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-1">
                                        Terakhir diperbarui: <?php echo e($group['latest_date']->format('d M Y')); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="<?php echo e(route('training-centre.trainee-documents', $traineeId)); ?>" class="inline-flex items-center px-4 py-2 rounded-lg bg-[#1e3a8a] text-white hover:bg-[#172554] text-xs font-bold" title="Lihat Detail Dokumen Trainee">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
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
                            <tr class="hover:bg-blue-50/30">
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
                                        <?php echo e(optional($logbook->training_centre_decided_at ?? $logbook->verified_at)->format('d M Y')); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="<?php echo e(route('training-centre.approvals.show', $logbook->id)); ?>" class="inline-flex px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold">
                                            Detail
                                        </a>
                                        <?php if($logbook->status === 'final_approved'): ?>
                                            <a href="<?php echo e(route('ojt.logbooks.print', $logbook->id)); ?>" target="_blank" class="inline-flex items-center px-3 py-2 rounded-lg bg-[#1e3a8a] text-white hover:bg-[#172554] text-xs font-bold"                                             title="Cetak / Download PDF Form OJT">
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

    <!-- Rating Trainer -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-4 sm:mb-7">
        <div class="p-4 sm:p-5 border-b border-slate-100">
            <h3 class="text-xs sm:text-sm font-bold text-slate-800">Rating Trainer</h3>
            <p class="text-[10px] sm:text-xs text-slate-500 mt-1">Akumulasi rating bintang dari trainee untuk setiap trainer.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Trainer</th>
                        <th class="px-5 py-3 text-center">Rata-rata Rating</th>
                        <th class="px-5 py-3 text-center">Jumlah Penilaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(!empty($trainerRatings)): ?>
                        <?php $__currentLoopData = $trainerRatings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-blue-50/30">
                                <td class="px-5 py-4 text-xs font-bold text-slate-800"><?php echo e($row['name']); ?></td>
                                <td class="px-5 py-4 text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($row['avg'] >= $i): ?>
                                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.26.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.55-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/></svg>
                                            <?php elseif($row['avg'] >= $i - 0.5): ?>
                                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><defs><linearGradient id="half-<?php echo e($row['name']); ?>-<?php echo e($i); ?>"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#d1d5db"/></linearGradient></defs><path fill="url(#half-<?php echo e($row['name']); ?>-<?php echo e($i); ?>)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.26.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.55-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/></svg>
                                            <?php else: ?>
                                                <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.26.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.957c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.783-.55-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/></svg>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="ml-1 text-[11px] font-bold text-slate-700"><?php echo e($row['avg']); ?></span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center text-xs text-slate-600"><?php echo e($row['count']); ?> penilaian</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-xs text-slate-400">Belum ada penilaian trainer dari trainee.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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
                    <h3 class="text-sm font-bold text-slate-800">Antrean Persetujuan Evaluasi</h3>
                    <p class="text-xs text-slate-500 mt-1">Evaluasi yang menunggu persetujuan Admin TC.</p>
                </div>
                <div class="flex space-x-2 text-[11px] font-bold">
                    <span class="px-2 py-1 rounded bg-blue-100 text-blue-700">TC: <?php echo e($evalCounts['submitted']); ?></span>
                    <span class="px-2 py-1 rounded bg-purple-100 text-purple-700">PJO: <?php echo e($evalCounts['tc_approved']); ?></span>
                    <span class="px-2 py-1 rounded bg-amber-100 text-amber-700">HSE: <?php echo e($evalCounts['pjo_approved']); ?></span>
                    <span class="px-2 py-1 rounded bg-[#2563eb]/10 text-[#2563eb]">Selesai: <?php echo e($evalCounts['completed']); ?></span>
                </div>
            </div>
            <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                <?php $__empty_1 = true; $__currentLoopData = $pendingEvaluations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between px-5 py-3">
                        <div>
                            <p class="text-sm font-bold text-slate-800"><?php echo e($ev->nama_operator); ?></p>
                            <p class="text-[11px] text-slate-500"><?php echo e($ev->phase); ?> · Trainer: <?php echo e($ev->trainer->name ?? '-'); ?></p>
                        </div>
                        <a href="<?php echo e(route('training-centre.final-evaluations.show', $ev->id)); ?>" class="px-3 py-1.5 rounded-lg bg-[#1e3a8a] text-white text-[11px] font-bold">Review</a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-xs text-slate-400 px-5 py-4">Tidak ada evaluasi menunggu persetujuan.</p>
                <?php endif; ?>
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


<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/training-centre/approvals/index.blade.php ENDPATH**/ ?>