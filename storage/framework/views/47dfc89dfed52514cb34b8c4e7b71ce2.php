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
            <p class="text-emerald-100 text-xs mt-1">Verifikasi Digital Logbook, isi penilaian SOP, dan teruskan hasil ke Final Approval.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-3 text-xs">
                <p class="text-emerald-200">Trainer aktif</p><p class="font-bold mt-0.5"><?php echo e($trainer->name); ?> · <?php echo e($trainer->sid); ?></p>
            </div>
            <a href="<?php echo e(route('trainer.monitoring')); ?>" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-[#F5A623] hover:bg-amber-500 text-gray-900 text-xs font-bold shadow-sm transition-all transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Monitoring Evaluasi
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-7">
        <?php $__currentLoopData = [
            ['submitted', 'Menunggu Review', $counts['submitted'], 'blue'],
            ['verified', 'Sudah Diverifikasi', $counts['verified'], 'emerald'],
            ['revision', 'Dikembalikan Revisi', $counts['revision'], 'amber']
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $value, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('trainer.reviews.index', ['status' => $key])); ?>" class="block bg-white rounded-2xl border transition-all p-5 shadow-sm hover:shadow-md <?php echo e(($activeStatus ?? 'submitted') === $key ? 'ring-2 ring-[#00A859] border-[#00A859]' : 'border-slate-200'); ?>">
                <p class="text-xs font-bold uppercase tracking-wide text-<?php echo e($color); ?>-600"><?php echo e($label); ?></p>
                <p class="mt-2 text-3xl font-extrabold text-slate-800"><?php echo e($value); ?></p>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Eligible Trainees for Final Evaluation (HM requirement met) -->
    <?php if(isset($eligibleTrainees) && $eligibleTrainees->isNotEmpty()): ?>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-7">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-slate-800">Trainee Eligible Evaluasi Akhir A2B</h2>
                    <p class="text-xs text-slate-500 mt-1">Syarat jam operasional fase saat ini sudah terpenuhi. Silakan isi form evaluasi.</p>
                </div>
                <a href="<?php echo e(route('trainer.final-evaluations.create')); ?>" class="px-4 py-2 rounded-xl bg-[#00A859] text-white text-xs font-bold">Isi Evaluasi</a>
            </div>
            <div class="divide-y divide-slate-100">
                <?php $__currentLoopData = $eligibleTrainees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between px-5 py-3">
                        <div>
                            <p class="text-sm font-bold text-slate-800"><?php echo e($t['name']); ?></p>
                            <p class="text-[11px] text-slate-500"><?php echo e($t['certification']); ?> · <?php echo e($t['phase_label']); ?></p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-[11px] font-semibold text-[#00A859]"><?php echo e($t['progress']['total']); ?>% HM</span>
                            <a href="<?php echo e(route('trainer.final-evaluations.create', ['trainee' => $t['id']])); ?>" class="px-3 py-1.5 rounded-lg bg-[#003829] text-white text-[11px] font-bold">Evaluasi</a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="font-bold text-slate-800">
                    <?php echo e(($activeStatus ?? 'submitted') === 'verified' ? 'Logbook yang Sudah Diverifikasi' : (($activeStatus ?? 'submitted') === 'revision' ? 'Logbook yang Dikembalikan Revisi' : 'Antrean Logbook')); ?>

                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    <?php echo e(($activeStatus ?? 'submitted') === 'verified' ? 'Riwayat logbook yang sudah Anda verifikasi dan dikirim ke Admin TC.' : (($activeStatus ?? 'submitted') === 'revision' ? 'Logbook yang sudah Anda kembalikan untuk revisi.' : 'Prioritaskan pengajuan terbaru untuk diedit dan disetujui.')); ?>

                </p>
            </div>
            <form class="flex gap-2" method="GET">
                <input type="hidden" name="status" value="<?php echo e($activeStatus ?? 'submitted'); ?>">
                <input name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari SID atau logbook..." class="text-xs rounded-xl border-slate-300 focus:border-[#00A859] focus:ring-[#00A859]">
                <button class="px-4 rounded-xl bg-[#003829] text-white text-xs font-bold">Cari</button>
            </form>
        </div>
        <div class="overflow-x-auto"><table class="w-full text-left"><thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500"><tr><th class="px-5 py-3">Logbook / Trainee</th><th class="px-5 py-3">Unit & Shift</th><th class="px-5 py-3">Dikirim</th><th class="px-5 py-3">Status</th><th class="px-5 py-3"></th></tr></thead><tbody class="divide-y divide-slate-100">
        <?php $__empty_1 = true; $__currentLoopData = $logbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logbook): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-emerald-50/30"><td class="px-5 py-4"><p class="text-xs font-bold text-slate-800"><?php echo e($logbook->logbook_number); ?></p><p class="text-[11px] text-slate-500 mt-1"><?php echo e($logbook->trainee->name); ?> · <?php echo e($logbook->trainee->sid); ?></p></td><td class="px-5 py-4 text-xs"><p class="font-semibold text-slate-700"><?php echo e($logbook->unit_code); ?></p><p class="text-[11px] text-slate-500 mt-1"><?php echo e(ucfirst($logbook->shift)); ?> · <?php echo e($logbook->total_hm); ?> HM</p></td><td class="px-5 py-4 text-xs text-slate-600"><?php echo e(optional($logbook->submitted_at)->format('d M Y, H:i') ?? '-'); ?></td><td class="px-5 py-4"><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></td>                    <td class="px-5 py-4 text-right">
                        <div class="inline-flex items-center space-x-2">
                            <?php if($logbook->status === 'revision'): ?>
                                <a href="<?php echo e(route('trainer.reviews.edit', $logbook->id)); ?>" class="inline-flex px-3 py-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold">
                                    Edit Revisi
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('trainer.reviews.show', $logbook->id)); ?>" class="inline-flex px-3 py-2 rounded-lg bg-emerald-50 text-[#00593E] hover:bg-emerald-100 text-xs font-bold"><?php echo e(in_array($logbook->status, ['verified', 'final_approved']) ? 'Lihat Evaluasi' : ( $logbook->status === 'revision' ? 'Lihat Detail' : 'Review' )); ?></a>
                        </div>
                    </td></tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>                         <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Tidak ada logbook ditemukan pada kategori ini.</td></tr><?php endif; ?>
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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/trainer/reviews/index.blade.php ENDPATH**/ ?>