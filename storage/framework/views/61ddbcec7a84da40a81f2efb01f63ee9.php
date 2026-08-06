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
     <?php $__env->slot('title', null, []); ?> Review <?php echo e($logbook->logbook_number); ?> <?php $__env->endSlot(); ?>
    <div class="mb-6 flex items-center justify-between"><div><a href="<?php echo e(route('trainer.reviews.index')); ?>" class="text-xs font-bold text-[#00A859] hover:underline">← Kembali ke antrean</a><h1 class="text-2xl font-bold text-slate-800 mt-2">Review <?php echo e($logbook->logbook_number); ?></h1><p class="text-xs text-slate-500 mt-1">Trainee: <?php echo e($logbook->trainee->name); ?> · <?php echo e($logbook->trainee->nrp); ?></p></div><?php if (isset($component)) { $__componentOriginal2ddbc40e602c342e508ac696e52f8719 = $component; } ?>
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
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6" x-data="{ action: 'verify' }">
        <div class="xl:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5"><h2 class="text-sm font-bold text-slate-800">Ringkasan Operasional</h2><dl class="mt-4 grid grid-cols-2 gap-4 text-xs"><div><dt class="text-slate-400">Tanggal / Shift</dt><dd class="font-semibold mt-1"><?php echo e($logbook->date->format('d M Y')); ?> · <?php echo e(ucfirst($logbook->shift)); ?></dd></div><div><dt class="text-slate-400">Unit</dt><dd class="font-semibold mt-1"><?php echo e($logbook->equipment->unit_code ?? '-'); ?></dd></div><div><dt class="text-slate-400">Lokasi</dt><dd class="font-semibold mt-1"><?php echo e($logbook->location); ?></dd></div><div><dt class="text-slate-400">Durasi</dt><dd class="font-semibold mt-1"><?php echo e($logbook->total_hm); ?> HM</dd></div></dl></div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5"><h2 class="text-sm font-bold text-slate-800">Aktivitas Harian</h2><p class="mt-3 whitespace-pre-line text-xs text-slate-600 leading-relaxed"><?php echo e($logbook->daily_activity); ?></p></div>
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5"><h2 class="text-sm font-bold text-slate-800">Bukti Pendukung</h2><div class="mt-3 space-y-2"><?php $__empty_1 = true; $__currentLoopData = $logbook->evidences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evidence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><a href="<?php echo e(asset('storage/'.$evidence->file_path)); ?>" target="_blank" class="block text-xs text-[#00593E] font-semibold p-3 bg-emerald-50 rounded-lg"><?php echo e($evidence->file_name); ?> <span class="text-slate-400 font-normal">· <?php echo e($evidence->file_size); ?></span></a><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> <p class="text-xs text-amber-700 bg-amber-50 p-3 rounded-lg">Tidak ada bukti pendukung yang dilampirkan.</p><?php endif; ?></div></div>
        </div>
        <div class="xl:col-span-3"><form method="POST" action="<?php echo e(route('trainer.reviews.evaluate', $logbook->id)); ?>" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden"><?php echo csrf_field(); ?>
            <div class="p-5 bg-[#003829] text-white"><p class="text-emerald-300 text-[10px] uppercase font-bold tracking-widest">Form Evaluasi Kompetensi · SOP OJT</p><h2 class="font-bold mt-1">Penilaian Trainer</h2><p class="text-xs text-emerald-100 mt-1">Skala 1 = perlu bimbingan hingga 4 = mandiri dan konsisten.</p></div>
            <div class="p-5 space-y-5">
                <?php $criteria = ['safety' => ['Penerapan K3 & Golden Rules', 'P2H, APD, hazard awareness, dan disiplin area kerja'], 'operation' => ['Pengoperasian Unit', 'Kontrol unit, produktivitas, dan teknik kerja'], 'procedure' => ['Kepatuhan SOP', 'Urutan kerja, inspeksi, dan dokumentasi'], 'communication' => ['Komunikasi Operasional', 'Koordinasi dengan pengawas, dispatcher, dan tim']]; $payload = $logbook->evaluation->assessment_payload ?? []; ?>
                <?php $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name => [$label, $help]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="border border-slate-200 rounded-xl p-4"><div class="flex justify-between gap-3"><div><p class="text-xs font-bold text-slate-800"><?php echo e($label); ?></p><p class="text-[11px] text-slate-500 mt-1"><?php echo e($help); ?></p></div><select name="<?php echo e($name); ?>" class="text-xs rounded-lg border-slate-300" <?php if($logbook->status === 'verified'): echo 'disabled'; endif; ?>><?php for($i=1;$i<=4;$i++): ?><option value="<?php echo e($i); ?>" <?php if(old($name, $payload[$name] ?? 3) == $i): echo 'selected'; endif; ?>><?php echo e($i); ?> — <?php echo e(['Perlu bimbingan','Cukup','Baik','Sangat baik'][$i-1]); ?></option><?php endfor; ?></select></div></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div><label class="text-xs font-bold text-slate-700">Komentar Trainer</label><textarea name="trainer_comment" rows="3" class="mt-2 w-full text-xs rounded-xl border-slate-300" <?php if($logbook->status === 'verified'): echo 'disabled'; endif; ?> placeholder="Umpan balik spesifik atas performa trainee..."><?php echo e(old('trainer_comment', $logbook->evaluation->trainer_comment ?? '')); ?></textarea></div>
                <?php if($logbook->status !== 'verified'): ?><div class="grid md:grid-cols-2 gap-4"><div><label class="text-xs font-bold text-slate-700">Keputusan Kompetensi</label><select name="competency_status" class="mt-2 w-full text-xs rounded-xl border-slate-300"><option value="competent">Kompeten</option><option value="not_yet_competent">Belum Kompeten</option></select></div><div class="flex items-end pb-2"><label class="flex gap-2 items-start text-xs text-slate-600"><input type="checkbox" name="send_to_pjo" value="1" class="rounded text-[#00A859] mt-0.5"><span><b>Kirim hasil ke PJO</b><br><span class="text-slate-400">Hanya untuk evaluasi yang diverifikasi.</span></span></label></div></div>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4"><label class="flex items-center gap-2 text-xs font-bold text-amber-900"><input type="radio" name="action" value="revision" x-model="action"> Minta Revisi dari Trainee</label><textarea name="revision_instruction" x-show="action === 'revision'" x-cloak rows="3" class="mt-3 w-full text-xs rounded-lg border-amber-300" placeholder="Jelaskan revisi yang wajib dilakukan..."><?php echo e(old('revision_instruction')); ?></textarea></div>
                <label class="flex items-center gap-2 text-xs font-bold text-[#00593E]"><input type="radio" name="action" value="verify" x-model="action" checked> Verifikasi & finalisasi evaluasi</label>
                <div class="flex justify-end"><button class="px-5 py-3 rounded-xl bg-[#00A859] hover:bg-emerald-600 text-white text-xs font-bold">Simpan Keputusan</button></div><?php else: ?> <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-xs text-emerald-900"><b>Evaluasi final:</b> <?php echo e($logbook->evaluation->competency_status === 'competent' ? 'Kompeten' : 'Belum Kompeten'); ?> · Skor <?php echo e($logbook->evaluation->overall_score); ?>/100 <?php if($logbook->evaluation->sent_to_pjo_at): ?><br><span class="text-emerald-700">Hasil sudah dikirim ke PJO pada <?php echo e($logbook->evaluation->sent_to_pjo_at->format('d M Y H:i')); ?>.</span><?php endif; ?></div><?php endif; ?>
                <?php if($errors->any()): ?><p class="text-xs text-red-600"><?php echo e($errors->first()); ?></p><?php endif; ?>
            </div>
        </form></div>
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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views\trainer\reviews\show.blade.php ENDPATH**/ ?>