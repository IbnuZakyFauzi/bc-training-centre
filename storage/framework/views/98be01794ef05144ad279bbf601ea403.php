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
     <?php $__env->slot('title', null, []); ?> <?php echo e(($trainingCentreApproval ?? false) ? 'Final Approval Training Centre' : 'Detail Form OJT'); ?> - <?php echo e($logbook->logbook_number); ?> <?php $__env->endSlot(); ?>
    <?php
        $trainerReview = $trainerReview ?? false;
        $trainingCentreApproval = $trainingCentreApproval ?? false;
    ?>

    <!-- Page Header & Action Bar -->
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-[#2563eb] mb-1">
                <a href="<?php echo e($trainingCentreApproval ? route('training-centre.approvals.index') : ($trainerReview ? route('trainer.reviews.index') : route('ojt.logbooks.index'))); ?>" class="hover:underline"><?php echo e($trainingCentreApproval ? 'Final Approval Training Centre' : ($trainerReview ? 'Trainer Review Queue' : 'My Form OJT')); ?></a>
                <span>/</span>
                <span class="text-slate-500"><?php echo e($logbook->logbook_number); ?></span>
            </div>
            <div class="flex items-center space-x-3">
                <h1 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight"><?php echo e($logbook->logbook_number); ?></h1>
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
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
            <?php if($trainerReview && $logbook->status === 'submitted'): ?>
                <a href="<?php echo e(route('trainer.reviews.edit', $logbook->id)); ?>" class="inline-flex items-center justify-center px-4 py-3 bg-amber-500 hover:bg-amber-600 text-gray-900 font-bold text-xs rounded-xl shadow-xs transition min-h-[44px]">Edit Form OJT</a>
            <?php endif; ?>
            <?php if($trainerReview && $logbook->status === 'verified'): ?>
                <a href="<?php echo e(route('trainer.reviews.index')); ?>" class="inline-flex items-center justify-center px-4 py-3 bg-[#2563eb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow-xs transition min-h-[44px]">Approval Pengawas</a>
            <?php endif; ?>
            <?php if(!$trainerReview && !$trainingCentreApproval && in_array($logbook->status, ['draft']) || (!$trainerReview && !$trainingCentreApproval && $logbook->status === 'revision' && empty($logbook->training_centre_decided_at))): ?>
                <a href="<?php echo e(route('ojt.logbooks.edit', $logbook->id)); ?>" class="inline-flex items-center justify-center px-4 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-xs transition min-h-[44px]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Form OJT
                </a>
            <?php endif; ?>

            <?php if(auth()->user()?->isTrainingCentre() && $logbook->status === 'final_approved'): ?>
                <a href="<?php echo e(route('ojt.logbooks.print', $logbook->id)); ?>" target="_blank" class="inline-flex items-center justify-center px-4 py-3 bg-[#1e3a8a] hover:bg-[#172554] text-white font-bold text-xs rounded-xl shadow-xs transition min-h-[44px]">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak / Download PDF Form OJT
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Trainer Revision Callout (If Revision status) -->
    <?php if($logbook->status === 'revision' && $logbook->revision_notes): ?>
        <?php
            $revisionFromTc = !empty($logbook->training_centre_decided_at);
        ?>
        <div class="mb-4 sm:mb-8 bg-amber-50 border-l-4 border-amber-500 p-4 sm:p-6 rounded-2xl shadow-sm">
            <div class="flex items-start space-x-3">
                <div class="p-2 bg-amber-100 rounded-xl text-amber-800 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-amber-900">
                        <?php if($revisionFromTc): ?>
                            Catatan Revisi dari Admin Training Centre
                        <?php else: ?>
                            Catatan Revisi dari Trainer Evaluator
                        <?php endif; ?>
                    </h3>
                    <p class="text-[10px] sm:text-xs text-amber-800 mt-1 leading-relaxed"><?php echo e($logbook->revision_notes); ?></p>
                    <?php if(!$trainerReview && !$revisionFromTc): ?><div class="mt-3">
                        <a href="<?php echo e(route('ojt.logbooks.edit', $logbook->id)); ?>" class="inline-flex items-center text-xs font-extrabold text-amber-900 bg-amber-200 hover:bg-amber-300 px-3 py-2.5 rounded-lg transition min-h-[44px]">
                            Perbaiki Form OJT Sekarang
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div><?php endif; ?>
                    <?php if($revisionFromTc && !$trainerReview): ?>
                        <p class="text-[10px] text-amber-700 mt-2 font-medium">Revisi ini akan ditangani oleh Trainer. Silakan tunggu hingga form OJT dikirim kembali.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($trainerReview || $trainingCentreApproval): ?>
        <?php echo $__env->make('trainer.reviews.partials.submitted-checklist', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8">

        <!-- Left 2 Columns: Form OJT Details -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-8">
            
            <?php if($assignedPengawas->count() > 0 || $assignedOperators->count() > 0): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Penugasan Personil</h2>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                        <?php if($assignedPengawas->count() > 0): ?>
                            <div>
                                <span class="text-slate-400 font-medium block">Pengawas</span>
                                <div class="mt-2 space-y-1">
                                    <?php $__currentLoopData = $assignedPengawas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 border border-blue-200 font-semibold"><?php echo e($p->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($assignedOperators->count() > 0): ?>
                            <div>
                                <span class="text-slate-400 font-medium block">Operator Pendamping</span>
                                <div class="mt-2 space-y-1">
                                    <?php $__currentLoopData = $assignedOperators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 border border-blue-200 font-semibold"><?php echo e($o->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Hour Meter Summary Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-200">
                    <h2 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wide">Hour Meter & Jam Pengoperasian</h2>
                </div>
                <div class="p-4 sm:p-6 grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-6 text-center">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">HM Awal</span>
                        <span class="text-xl font-extrabold text-slate-800 mt-1 block"><?php echo e(number_format($logbook->hm_start, 1)); ?></span>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">HM Akhir</span>
                        <span class="text-xl font-extrabold text-slate-800 mt-1 block"><?php echo e(number_format($logbook->hm_end, 1)); ?></span>
                    </div>

                    <div class="p-4 bg-[#1e3a8a] text-white rounded-xl border border-blue-900 shadow-sm">
                        <span class="text-[10px] font-bold text-blue-300 uppercase tracking-wider block">Total HM</span>
                        <span class="text-2xl font-black text-[#f59e0b] mt-1 block"><?php echo e(number_format($logbook->total_hm, 1)); ?> <span class="text-xs text-white">Jam</span></span>
                    </div>
                </div>
                <?php
                    $initialHmDay = (float) ($logbook->trainee->initial_hm_day ?? 0);
                    $initialHmNight = (float) ($logbook->trainee->initial_hm_night ?? 0);
                ?>
                <?php if($initialHmDay > 0 || $initialHmNight > 0): ?>
                <div class="px-4 sm:px-6 pb-4 sm:pb-6">
                    <div class="rounded-xl bg-blue-50 border border-blue-200 p-3 flex flex-wrap items-center gap-4 text-xs">
                        <?php if($initialHmDay > 0): ?>
                        <span class="font-bold text-blue-800">HM Siang Awal: <span class="text-blue-600"><?php echo e(number_format($initialHmDay, 1)); ?> jam</span></span>
                        <?php endif; ?>
                        <?php if($initialHmNight > 0): ?>
                        <span class="font-bold text-blue-800">HM Malam Awal: <span class="text-blue-600"><?php echo e(number_format($initialHmNight, 1)); ?> jam</span></span>
                        <?php endif; ?>
                        <span class="font-bold text-blue-800">Total Kumulatif: <span class="text-amber-600"><?php echo e(number_format($initialHmDay + $initialHmNight + $logbook->total_hm, 1)); ?> jam</span></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Right Column: Audit Timeline & Signatures -->
        <div class="flex flex-col gap-8">
            <!-- Audit Submission Timeline Widget -->
            <div class="flex flex-1 flex-col bg-white p-7 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-base font-bold text-slate-800 uppercase tracking-wide mb-7">Timeline Pengajuan Form OJT</h2>

                <div class="relative flex-1 pl-7 space-y-7 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    <?php $__currentLoopData = $logbook->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative">
                            <!-- Bullet -->
                            <div class="absolute -left-7 top-0.5 w-5 h-5 rounded-full bg-[#2563eb] border-2 border-white ring-2 ring-blue-100 flex items-center justify-center"></div>
                            
                            <div>
                                <span class="text-sm font-bold text-slate-800 block"><?php echo e($h->action); ?></span>
                                <span class="text-xs text-slate-400 font-medium block mt-1">Oleh: <?php echo e($h->user->name ?? 'System Trainee'); ?></span>
                                <span class="text-xs text-slate-400 block mt-0.5"><?php echo e($h->created_at->format('d M Y H:i:s')); ?> WITA</span>
                                
                                <?php if($h->comment): ?>
                                    <p class="text-xs text-slate-600 bg-slate-50 p-3.5 rounded-lg border border-slate-200 mt-3 italic leading-relaxed">
                                        "<?php echo e($h->comment); ?>"
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Verification Signature Status Card -->
            <div class="flex flex-1 flex-col bg-white p-7 rounded-2xl shadow-sm border border-slate-200 gap-5">
                <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Status Lembar Pengesahan</h2>

                <!-- Trainee -->
                <div class="flex-1 p-5 bg-amber-50 rounded-xl border border-amber-200 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-amber-700 font-bold uppercase block">Trainee Operator</span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block"><?php echo e($logbook->trainee->name ?? 'Belum Ditunjuk'); ?></span>
                    </div>
                    <span class="px-3 py-1 bg-amber-600 text-white text-xs font-bold rounded">Tersimpan</span>
                </div>

                <!-- Trainer -->
                    <div class="flex-1 p-5 <?php echo e(in_array($logbook->status, ['verified', 'final_approved']) ? 'bg-amber-50 border-amber-200' : 'bg-slate-50 border-slate-200'); ?> rounded-xl border flex items-center justify-between">
                    <div>
                        <?php
                            $trainerApproverTitle = 'Instruktur/ Pengawas/ Operator Pendamping';
                            $trainerApproverName = 'Belum Ditunjuk';
                            $trainerApproverSid = '-';

                            if ($logbook->evaluation?->trainer_signature_path && $logbook->evaluation->trainer) {
                                $trainerApprover = $logbook->evaluation->trainer;
                                $trainerApproverTitle = match($trainerApprover->trainer_type) {
                                    'pengawas' => 'Pengawas',
                                    'operator_pendamping' => 'Operator Pendamping',
                                    default => 'Instruktur',
                                };
                                $trainerApproverName = $trainerApprover->name;
                                $trainerApproverSid = $trainerApprover->sid;
                            } elseif ($logbook->trainer) {
                                $trainerApproverName = $logbook->trainer->name;
                                $trainerApproverSid = $logbook->trainer->sid;
                            } elseif (!empty($logbook->selected_pengawas_ids)) {
                                $pengawas = \App\Models\User::find($logbook->selected_pengawas_ids[0]);
                                if ($pengawas) {
                                    $trainerApproverTitle = 'Pengawas';
                                    $trainerApproverName = $pengawas->name;
                                    $trainerApproverSid = $pengawas->sid;
                                }
                            } elseif (!empty($logbook->selected_operator_pendamping_ids)) {
                                $operator = \App\Models\User::find($logbook->selected_operator_pendamping_ids[0]);
                                if ($operator) {
                                    $trainerApproverTitle = 'Operator Pendamping';
                                    $trainerApproverName = $operator->name;
                                    $trainerApproverSid = $operator->sid;
                                }
                            }
                        ?>
                        <span class="text-xs text-slate-500 font-bold uppercase block"><?php echo e($trainerApproverTitle); ?></span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block"><?php echo e($trainerApproverName); ?></span>
                        <?php if($logbook->evaluation?->trainer_signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->evaluation->trainer_signature_path)); ?>" alt="Trainer signature" style="max-height: 48px; max-width: 100%; height: auto; width: auto; object-fit: contain; display: block; margin: 8px auto 0;">
                        <?php endif; ?>
                    </div>
                    <?php if(in_array($logbook->status, ['verified', 'final_approved'])): ?>
                        <span class="px-3 py-1 bg-amber-600 text-white text-xs font-bold rounded">Verified</span>
                    <?php else: ?>
                        <span class="px-3 py-1 bg-slate-200 text-slate-600 text-xs font-bold rounded">Pending</span>
                    <?php endif; ?>
                </div>

                <?php if($logbook->pjo_decided_at || $trainingCentreApproval): ?>
                <div class="flex-1 p-5 <?php echo e(in_array($logbook->status, ['verified', 'final_approved']) ? 'bg-amber-50 border-amber-200' : 'bg-slate-50 border-slate-200'); ?> rounded-xl border flex items-center justify-between">
                    <div>
                        <span class="text-xs text-slate-500 font-bold uppercase block">Pengawas Trainer</span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block"><?php echo e($logbook->pengawasTrainer->name ?? 'Menunggu Approval Pengawas'); ?></span>
                        <?php if($logbook->pjo_signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->pjo_signature_path)); ?>" alt="Pengawas signature" style="max-height: 48px; max-width: 100%; height: auto; width: auto; object-fit: contain; display: block; margin: 8px auto 0;">
                        <?php endif; ?>
                    </div>
                        <span class="px-3 py-1 <?php echo e(in_array($logbook->status, ['verified', 'final_approved']) ? 'bg-amber-600 text-white' : 'bg-slate-200 text-slate-600'); ?> text-xs font-bold rounded"><?php echo e(in_array($logbook->status, ['verified', 'final_approved']) ? 'Approved' : 'Pending'); ?></span>
                    </div>
                <?php endif; ?>

                <?php if($trainingCentreApproval): ?>
                    <div class="flex-1 p-5 <?php echo e($logbook->training_centre_decided_at ? 'bg-amber-50 border-amber-200' : 'bg-slate-50 border-slate-200'); ?> rounded-xl border flex items-center justify-between">
                        <div><span class="text-xs text-slate-500 font-bold uppercase block">Kabag Training Centre</span><span class="text-sm font-bold text-slate-800 mt-1 block"><?php echo e($logbook->trainingCentre->name ?? 'Menunggu Final Approval'); ?></span></div>
                        <?php if($logbook->training_centre_signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->training_centre_signature_path)); ?>" alt="Training centre signature" style="max-height: 48px; max-width: 100%; height: auto; width: auto; object-fit: contain; display: block; margin: 8px auto 0;">
                        <?php endif; ?>
                        <span class="px-3 py-1 <?php echo e($logbook->training_centre_decided_at ? 'bg-amber-600 text-white' : 'bg-slate-200 text-slate-600'); ?> text-xs font-bold rounded"><?php echo e($logbook->training_centre_decided_at ? 'Approved' : 'Pending'); ?></span>
                    </div>
                <?php endif; ?>

            </div>

            </div>

        </div>

    <?php if($trainerReview && $logbook->status === 'submitted'): ?>
        <?php if($locked ?? false): ?>
            <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 sm:p-6">
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-amber-100 rounded-xl text-amber-700 flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-900">Approval Verifikasi Dikunci</h3>
                        <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                            Anda harus menyelesaikan approval logbook tanggal <b><?php echo e($blockingLogbook->date->format('d-m-Y')); ?></b> terlebih dahulu sebelum dapat <b>mengapprove</b> logbook ini. Anda masih dapat meminta revisi dari trainee.
                        </p>
                        <a href="<?php echo e(route('trainer.reviews.show', $blockingLogbook->id)); ?>" class="inline-flex items-center mt-3 px-4 py-2.5 rounded-xl bg-amber-600 text-white text-xs font-bold hover:bg-amber-700 transition">
                            Review Logbook <?php echo e($blockingLogbook->logbook_number); ?>

                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php echo $__env->make('trainer.reviews.partials.decision-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if($trainerReview && $logbook->status === 'revision'): ?>
        <div class="mt-6">
            <a href="<?php echo e(route('trainer.reviews.edit', $logbook->id)); ?>" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-[#1e3a8a] text-white hover:bg-[#172554] text-xs font-bold shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit & Revisi Form OJT
            </a>
        </div>
    <?php endif; ?>

    <?php if($trainingCentreApproval && ($isPending ?? false)): ?>
        <?php echo $__env->make('training-centre.approvals.partials.decision-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <?php if($trainingCentreApproval && !($isPending ?? false)): ?>
        <section class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-xs text-slate-600">
            Form OJT ini hanya dapat dilihat oleh role Anda. Persetujuan dilakukan oleh Trainer.
        </section>
    <?php endif; ?>

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

<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/ojt/logbooks/show.blade.php ENDPATH**/ ?>