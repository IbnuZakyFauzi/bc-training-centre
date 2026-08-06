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
     <?php $__env->slot('title', null, []); ?> Approval Pengawas <?php echo e($logbook->logbook_number); ?> <?php $__env->endSlot(); ?>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"><div><a href="<?php echo e($isPending ? route('department-operation.approvals.pending') : route('department-operation.approvals.history')); ?>" class="text-xs font-bold text-[#00A859] hover:underline">← Kembali</a><h1 class="text-2xl font-bold text-slate-800 mt-2">Approval Pengawas · <?php echo e($logbook->logbook_number); ?></h1><p class="text-xs text-slate-500 mt-1">Trainee: <?php echo e($logbook->trainee->name); ?> · <?php echo e($logbook->trainee->nrp); ?></p></div><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
<?php endif; ?></div>
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6" x-data="{ action: 'approve' }">
        <div class="xl:col-span-2 space-y-5">
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5"><div class="flex items-center justify-between"><h2 class="text-sm font-bold text-slate-800">Submitted Logbook</h2><span class="text-[10px] font-bold text-slate-400 uppercase">Read only</span></div><dl class="mt-4 grid grid-cols-2 gap-4 text-xs"><div><dt class="text-slate-400">Tanggal / Shift</dt><dd class="font-semibold mt-1"><?php echo e($logbook->date->format('d M Y')); ?> · <?php echo e(ucfirst($logbook->shift)); ?></dd></div><div><dt class="text-slate-400">Unit</dt><dd class="font-semibold mt-1"><?php echo e($logbook->equipment->unit_code ?? '-'); ?></dd></div><div><dt class="text-slate-400">Lokasi</dt><dd class="font-semibold mt-1"><?php echo e($logbook->location); ?></dd></div><div><dt class="text-slate-400">Durasi</dt><dd class="font-semibold mt-1"><?php echo e($logbook->total_hm); ?> HM</dd></div><div><dt class="text-slate-400">Trainer</dt><dd class="font-semibold mt-1"><?php echo e($logbook->trainer->name ?? '-'); ?></dd></div><div><dt class="text-slate-400">Dikirim Trainee</dt><dd class="font-semibold mt-1"><?php echo e(optional($logbook->submitted_at)->format('d M Y H:i') ?? '-'); ?></dd></div></dl></section>
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5"><h2 class="text-sm font-bold text-slate-800">Aktivitas Harian</h2><p class="mt-3 whitespace-pre-line text-xs text-slate-600 leading-relaxed"><?php echo e($logbook->daily_activity); ?></p></section>
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5"><h2 class="text-sm font-bold text-slate-800">Bukti Pendukung</h2><div class="mt-3 space-y-2"><?php $__empty_1 = true; $__currentLoopData = $logbook->evidences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evidence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a href="<?php echo e(asset('storage/'.$evidence->file_path)); ?>" target="_blank" class="block text-xs text-[#00593E] font-semibold p-3 bg-emerald-50 rounded-lg"><?php echo e($evidence->file_name); ?> <span class="text-slate-400 font-normal">· <?php echo e($evidence->file_size); ?></span></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <p class="text-xs text-amber-700 bg-amber-50 p-3 rounded-lg">Tidak ada bukti pendukung yang dilampirkan.</p><?php endif; ?></div></section>
        </div>
        <div class="xl:col-span-3 space-y-5">
            <?php $evaluation = $logbook->evaluation; $criteria = ['safety' => 'Penerapan K3 & Golden Rules', 'operation' => 'Pengoperasian Unit', 'procedure' => 'Kepatuhan SOP', 'communication' => 'Komunikasi Operasional']; $scores = $evaluation?->assessment_payload ?? []; ?>
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"><div class="p-5 bg-[#003829] text-white"><p class="text-emerald-300 text-[10px] uppercase font-bold tracking-widest">Read-only · Trainer Evaluation</p><h2 class="font-bold mt-1">Evaluasi Kompetensi Trainer</h2><p class="text-xs text-emerald-100 mt-1">Diverifikasi <?php echo e(optional($logbook->verified_at)->format('d M Y, H:i') ?? '-'); ?>.</p></div><div class="p-5"><div class="grid sm:grid-cols-2 gap-3"><?php $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="rounded-xl border border-slate-200 p-3"><p class="text-[11px] text-slate-500"><?php echo e($label); ?></p><p class="mt-1 text-sm font-bold text-slate-800"><?php echo e($scores[$key] ?? '-'); ?> / 4</p></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div><div class="mt-4 grid sm:grid-cols-2 gap-4 text-xs"><div class="rounded-xl bg-emerald-50 p-4"><p class="text-emerald-700 font-bold">Status Kompetensi</p><p class="mt-1 text-emerald-950"><?php echo e($evaluation?->competency_status === 'competent' ? 'Kompeten' : 'Belum Kompeten'); ?> · Skor <?php echo e($evaluation?->overall_score ?? '-'); ?>/100</p></div><div class="rounded-xl bg-slate-50 p-4"><p class="text-slate-500 font-bold">Komentar Trainer</p><p class="mt-1 text-slate-700 whitespace-pre-line"><?php echo e($evaluation?->trainer_comment ?: 'Tidak ada komentar.'); ?></p></div></div></div></section>
            <?php if($isPending): ?><form method="POST" action="<?php echo e(route('department-operation.approvals.decide', $logbook->id)); ?>" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"><?php echo csrf_field(); ?><div class="p-5 border-b border-slate-100"><h2 class="font-bold text-slate-800">Keputusan Pengawas</h2><p class="text-xs text-slate-500 mt-1">Logbook dan evaluasi tidak dapat diubah dari halaman ini.</p></div><div class="p-5 space-y-4"><div class="grid sm:grid-cols-2 gap-3"><label :class="action === 'approve' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200'" class="cursor-pointer rounded-xl border p-4"><input type="radio" name="action" value="approve" x-model="action" class="text-[#00A859]"> <span class="ml-1 text-xs font-bold text-emerald-900">Setujui Logbook</span><p class="ml-5 mt-1 text-[11px] text-emerald-700">Finalisasi persetujuan Pengawas.</p></label><label :class="action === 'revision' ? 'border-amber-400 bg-amber-50' : 'border-slate-200'" class="cursor-pointer rounded-xl border p-4"><input type="radio" name="action" value="revision" x-model="action" class="text-amber-500"> <span class="ml-1 text-xs font-bold text-amber-900">Kembalikan Revisi</span><p class="ml-5 mt-1 text-[11px] text-amber-700">Trainee dapat memperbaiki dan mengirim ulang.</p></label></div><div><label class="text-xs font-bold text-slate-700">Catatan Pengawas <span class="font-normal text-slate-400">(opsional untuk persetujuan)</span></label><textarea name="approval_notes" rows="4" class="mt-2 w-full text-xs rounded-xl border-slate-300 focus:border-[#00A859] focus:ring-[#00A859]" placeholder="Tambahkan catatan persetujuan atau instruksi revisi..."><?php echo e(old('approval_notes')); ?></textarea><?php $__errorArgs = ['approval_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div><div class="flex justify-end"><button class="px-5 py-3 rounded-xl bg-[#00A859] hover:bg-emerald-600 text-white text-xs font-bold">Simpan Keputusan</button></div></div></form><?php else: ?> <section class="rounded-2xl border <?php echo e(in_array($logbook->status, ['approved', 'supervisor_approved', 'final_approved'], true) ? 'border-emerald-200 bg-emerald-50' : 'border-amber-200 bg-amber-50'); ?> p-5"><p class="text-xs font-bold <?php echo e(in_array($logbook->status, ['approved', 'supervisor_approved', 'final_approved'], true) ? 'text-emerald-900' : 'text-amber-900'); ?>">Keputusan Pengawas · <?php echo e(optional($logbook->pjo_decided_at)->format('d M Y, H:i')); ?></p><p class="mt-2 text-xs text-slate-700 whitespace-pre-line"><?php echo e($logbook->pjo_notes ?: 'Tidak ada catatan keputusan.'); ?></p><p class="mt-3 text-[11px] text-slate-500">Diputuskan oleh <?php echo e($logbook->departmentOperation->name ?? 'Pengawas'); ?></p></section><?php endif; ?>
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



<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views\department-operation\approvals\show.blade.php ENDPATH**/ ?>