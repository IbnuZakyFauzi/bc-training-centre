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
     <?php $__env->slot('title', null, []); ?> <?php echo e(($trainingCentreApproval ?? false) ? 'Final Approval Training Centre' : 'Detail Logbook'); ?> - <?php echo e($logbook->logbook_number); ?> <?php $__env->endSlot(); ?>
    <?php
        $trainerReview = $trainerReview ?? false;
        $trainingCentreApproval = $trainingCentreApproval ?? false;
    ?>

    <!-- Page Header & Action Bar -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-[#00A859] mb-1">
                <a href="<?php echo e($trainingCentreApproval ? route('training-centre.approvals.index') : ($trainerReview ? route('trainer.reviews.index') : route('ojt.logbooks.index'))); ?>" class="hover:underline"><?php echo e($trainingCentreApproval ? 'Final Approval Training Centre' : ($trainerReview ? 'Trainer Review Queue' : 'My Logbook')); ?></a>
                <span>/</span>
                <span class="text-slate-500"><?php echo e($logbook->logbook_number); ?></span>
            </div>
            <div class="flex items-center space-x-3">
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight"><?php echo e($logbook->logbook_number); ?></h1>
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

        <div class="flex items-center space-x-3">
            <?php if($trainerReview && $logbook->status === 'submitted'): ?>
                <a href="<?php echo e(route('trainer.reviews.edit', $logbook->id)); ?>" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-gray-900 font-bold text-xs rounded-xl shadow-xs transition">Edit Logbook</a>
            <?php endif; ?>
            <?php if($trainerReview && $logbook->status === 'verified'): ?>
                <a href="<?php echo e(route('trainer.reviews.index')); ?>" class="inline-flex items-center px-4 py-2 bg-[#00A859] hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-xs transition">Approval Pengawas</a>
            <?php endif; ?>
            <?php if(!$trainerReview && !$trainingCentreApproval && in_array($logbook->status, ['draft']) || (!$trainerReview && !$trainingCentreApproval && $logbook->status === 'revision' && empty($logbook->training_centre_decided_at))): ?>
                <a href="<?php echo e(route('ojt.logbooks.edit', $logbook->id)); ?>" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-gray-900 font-bold text-xs rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 mr-2 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Logbook
                </a>
            <?php endif; ?>

            <?php if(auth()->user()?->isTrainingCentre() && $logbook->status === 'final_approved'): ?>
                <a href="<?php echo e(route('ojt.logbooks.print', $logbook->id)); ?>" target="_blank" class="inline-flex items-center px-4 py-2 bg-[#003829] hover:bg-[#00241A] text-white font-bold text-xs rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak / Download PDF Logbook
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Trainer Revision Callout (If Revision status) -->
    <?php if($logbook->status === 'revision' && $logbook->revision_notes): ?>
        <?php
            $revisionFromTc = !empty($logbook->training_centre_decided_at);
        ?>
        <div class="mb-8 bg-amber-50 border-l-4 border-amber-500 p-6 rounded-2xl shadow-sm">
            <div class="flex items-start space-x-3">
                <div class="p-2 bg-amber-100 rounded-xl text-amber-800 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900">
                        <?php if($revisionFromTc): ?>
                            Catatan Revisi dari Admin Training Centre
                        <?php else: ?>
                            Catatan Revisi dari Trainer Evaluator
                        <?php endif; ?>
                    </h3>
                    <p class="text-xs text-amber-800 mt-1 leading-relaxed"><?php echo e($logbook->revision_notes); ?></p>
                    <?php if(!$trainerReview && !$revisionFromTc): ?><div class="mt-3">
                        <a href="<?php echo e(route('ojt.logbooks.edit', $logbook->id)); ?>" class="inline-flex items-center text-xs font-extrabold text-amber-900 bg-amber-200 hover:bg-amber-300 px-3 py-1.5 rounded-lg transition">
                            Perbaiki Logbook Sekarang
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div><?php endif; ?>
                    <?php if($revisionFromTc && !$trainerReview): ?>
                        <p class="text-[10px] text-amber-700 mt-2 font-medium">Revisi ini akan ditangani oleh Trainer. Silakan tunggu hingga logbook dikirim kembali.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if($trainerReview || $trainingCentreApproval): ?>
        <?php echo $__env->make('trainer.reviews.partials.submitted-checklist', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 <?php echo e($trainerReview ? 'mt-8' : ''); ?>">

        <!-- Left 2 Columns: Logbook Details -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Section A & General Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Informasi General OJT Operations</h2>
                </div>
                <div class="p-6 grid grid-cols-2 sm:grid-cols-4 gap-6 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium block">Tanggal</span>
                        <span class="font-bold text-slate-800 mt-1 block"><?php echo e(\Carbon\Carbon::parse($logbook->date)->format('d F Y')); ?></span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block">Shift Kerja</span>
                        <span class="font-bold text-slate-800 mt-1 block uppercase">Shift <?php echo e($logbook->shift); ?></span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block">Departemen</span>
                        <span class="font-bold text-slate-800 mt-1 block"><?php echo e($logbook->department->name ?? 'Mining Operations'); ?></span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block">Lokasi Pit / Area</span>
                        <span class="font-bold text-[#003829] mt-1 block"><?php echo e($logbook->location); ?></span>
                    </div>

                    <div class="col-span-2">
                        <span class="text-slate-400 font-medium block">Unit Alat Berat</span>
                        <span class="font-extrabold text-[#003829] text-sm mt-1 block">
                            <?php echo e($logbook->equipment_number ?? $logbook->equipment?->unit_code ?? '-'); ?><?php echo e($logbook->equipment?->model_name ? ' - '.$logbook->equipment->model_name : ''); ?>

                        </span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block">Trainer Evaluator</span>
                        <span class="font-bold text-slate-800 mt-1 block"><?php echo e($logbook->trainer->name ?? '-'); ?></span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-medium block">Supervisor Lapangan</span>
                        <span class="font-bold text-slate-800 mt-1 block"><?php echo e($logbook->supervisor->name ?? '-'); ?></span>
                    </div>
                </div>
            </div>

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
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold"><?php echo e($p->name); ?></span>
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
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Hour Meter & Jam Pengoperasian</h2>
                </div>
                <div class="p-6 grid grid-cols-2 sm:grid-cols-3 gap-6 text-center">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">HM Awal</span>
                        <span class="text-xl font-extrabold text-slate-800 mt-1 block"><?php echo e(number_format($logbook->hm_start, 1)); ?></span>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">HM Akhir</span>
                        <span class="text-xl font-extrabold text-slate-800 mt-1 block"><?php echo e(number_format($logbook->hm_end, 1)); ?></span>
                    </div>

                    <div class="p-4 bg-[#003829] text-white rounded-xl border border-emerald-900 shadow-sm">
                        <span class="text-[10px] font-bold text-emerald-300 uppercase tracking-wider block">Total HM</span>
                        <span class="text-2xl font-black text-[#F5A623] mt-1 block"><?php echo e(number_format($logbook->total_hm, 1)); ?> <span class="text-xs text-white">Jam</span></span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Audit Timeline & Signatures -->
        <div class="flex flex-col gap-8">
            <!-- Audit Submission Timeline Widget -->
            <div class="flex flex-1 flex-col bg-white p-7 rounded-2xl shadow-sm border border-slate-200">
                <h2 class="text-base font-bold text-slate-800 uppercase tracking-wide mb-7">Timeline Pengajuan Logbook</h2>

                <div class="relative flex-1 pl-7 space-y-7 before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    <?php $__currentLoopData = $logbook->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative">
                            <!-- Bullet -->
                            <div class="absolute -left-7 top-0.5 w-5 h-5 rounded-full bg-[#00A859] border-2 border-white ring-2 ring-emerald-100 flex items-center justify-center"></div>
                            
                            <div>
                                <span class="text-sm font-bold text-slate-800 block"><?php echo e($h->action); ?></span>
                                <span class="text-xs text-slate-400 font-medium block mt-1">Oleh: <?php echo e($h->user->name ?? 'System Trainee'); ?></span>
                                <span class="text-xs text-slate-400 block mt-0.5"><?php echo e($h->created_at->format('d M Y - H:i')); ?> WITA</span>
                                
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
                <div class="flex-1 p-5 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-emerald-700 font-bold uppercase block">Trainee Operator</span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block"><?php echo e($logbook->trainee->name ?? 'Ahmad Rian Syahputra'); ?></span>
                    </div>
                    <span class="px-3 py-1 bg-[#00A859] text-white text-xs font-bold rounded">Tersimpan</span>
                </div>

                <!-- Trainer -->
                <div class="flex-1 p-5 <?php echo e(in_array($logbook->status, ['verified', 'approved', 'supervisor_approved', 'final_approved']) ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-200'); ?> rounded-xl border flex items-center justify-between">
                    <div>
                        <?php
                            $trainerApproverTitle = 'Instruktur/ Pengawas/ Operator Pendamping';
                            $trainerApproverName = $logbook->trainer->name ?? 'Bambang Hermawan';
                            $trainerApproverSid = $logbook->trainer->sid ?? '-';

                            if ($logbook->evaluation?->trainer_signature_path && $logbook->evaluation->trainer) {
                                $trainerApprover = $logbook->evaluation->trainer;
                                $trainerApproverTitle = match($trainerApprover->trainer_type) {
                                    'pengawas' => 'Pengawas',
                                    'operator_pendamping' => 'Operator Pendamping',
                                    default => 'Instruktur',
                                };
                                $trainerApproverName = $trainerApprover->name;
                                $trainerApproverSid = $trainerApprover->sid;
                            }
                        ?>
                        <span class="text-xs text-slate-500 font-bold uppercase block"><?php echo e($trainerApproverTitle); ?></span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block"><?php echo e($trainerApproverName); ?></span>
                        <?php if($logbook->evaluation?->trainer_signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->evaluation->trainer_signature_path)); ?>" alt="Trainer signature" class="mt-2 max-h-12 w-auto object-contain bg-white rounded-lg border border-emerald-200 p-1">
                        <?php endif; ?>
                    </div>
                    <?php if(in_array($logbook->status, ['verified', 'approved', 'supervisor_approved', 'final_approved'])): ?>
                        <span class="px-3 py-1 bg-[#00A859] text-white text-xs font-bold rounded">Verified</span>
                    <?php else: ?>
                        <span class="px-3 py-1 bg-slate-200 text-slate-600 text-xs font-bold rounded">Pending</span>
                    <?php endif; ?>
                </div>

                <?php if($logbook->pjo_decided_at || $trainingCentreApproval): ?>
                    <div class="flex-1 p-5 <?php echo e(in_array($logbook->status, ['verified', 'approved', 'supervisor_approved', 'final_approved']) ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-200'); ?> rounded-xl border flex items-center justify-between">
                    <div>
                        <span class="text-xs text-slate-500 font-bold uppercase block">Pengawas Trainer</span>
                        <span class="text-sm font-bold text-slate-800 mt-1 block"><?php echo e($logbook->pengawasTrainer->name ?? 'Menunggu Approval Pengawas'); ?></span>
                        <?php if($logbook->pjo_signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->pjo_signature_path)); ?>" alt="Pengawas signature" class="mt-2 max-h-12 w-auto object-contain bg-white rounded-lg border border-emerald-200 p-1">
                        <?php endif; ?>
                    </div>
                        <span class="px-3 py-1 <?php echo e(in_array($logbook->status, ['verified', 'approved', 'supervisor_approved', 'final_approved']) ? 'bg-[#00A859] text-white' : 'bg-slate-200 text-slate-600'); ?> text-xs font-bold rounded"><?php echo e(in_array($logbook->status, ['verified', 'approved', 'supervisor_approved', 'final_approved']) ? 'Approved' : 'Pending'); ?></span>
                    </div>
                <?php endif; ?>

                <?php if($trainingCentreApproval): ?>
                    <div class="flex-1 p-5 <?php echo e($logbook->training_centre_decided_at ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50 border-slate-200'); ?> rounded-xl border flex items-center justify-between">
                        <div><span class="text-xs text-slate-500 font-bold uppercase block">Kabag Training Centre</span><span class="text-sm font-bold text-slate-800 mt-1 block"><?php echo e($logbook->trainingCentre->name ?? 'Menunggu Final Approval'); ?></span></div>
                        <?php if($logbook->training_centre_signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->training_centre_signature_path)); ?>" alt="Training centre signature" class="mt-2 max-h-12 w-auto object-contain bg-white rounded-lg border border-emerald-200 p-1">
                        <?php endif; ?>
                        <span class="px-3 py-1 <?php echo e($logbook->training_centre_decided_at ? 'bg-[#00A859] text-white' : 'bg-slate-200 text-slate-600'); ?> text-xs font-bold rounded"><?php echo e($logbook->training_centre_decided_at ? 'Approved' : 'Pending'); ?></span>
                    </div>
                <?php endif; ?>

            </div>

            </div>

            <?php if($trainingCentreApproval && !empty($logbook->trainer_ratings)): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Penilaian Trainer oleh Trainee</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <?php
                            $ratings = is_array($logbook->trainer_ratings) ? $logbook->trainer_ratings : [];
                            $instrukturRatings = array_filter($ratings, fn($r) => ($r['role_type'] ?? '') === 'instruktur');
                            $pengawasRatings = array_filter($ratings, fn($r) => ($r['role_type'] ?? '') === 'pengawas');
                            $operatorRatings = array_filter($ratings, fn($r) => ($r['role_type'] ?? '') === 'operator_pendamping');
                        ?>

                        <?php if(!empty($instrukturRatings)): ?>
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Instruktur</p>
                                <div class="space-y-2">
                                    <?php $__currentLoopData = $instrukturRatings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $user = \App\Models\User::find($rating['user_id'] ?? null); ?>
                                        <?php if($user): ?>
                                            <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                                                <span class="text-xs font-semibold text-slate-700"><?php echo e($user->name); ?></span>
                                                <div class="flex items-center gap-0.5">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <svg class="w-4 h-4 <?php echo e(($rating['rating'] ?? 0) >= $i ? 'text-amber-400' : 'text-slate-200'); ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($pengawasRatings)): ?>
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pengawas</p>
                                <div class="space-y-2">
                                    <?php $__currentLoopData = $pengawasRatings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $user = \App\Models\User::find($rating['user_id'] ?? null); ?>
                                        <?php if($user): ?>
                                            <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                                                <span class="text-xs font-semibold text-slate-700"><?php echo e($user->name); ?></span>
                                                <div class="flex items-center gap-0.5">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <svg class="w-4 h-4 <?php echo e(($rating['rating'] ?? 0) >= $i ? 'text-amber-400' : 'text-slate-200'); ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if(!empty($operatorRatings)): ?>
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Operator Pendamping</p>
                                <div class="space-y-2">
                                    <?php $__currentLoopData = $operatorRatings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $user = \App\Models\User::find($rating['user_id'] ?? null); ?>
                                        <?php if($user): ?>
                                            <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                                                <span class="text-xs font-semibold text-slate-700"><?php echo e($user->name); ?></span>
                                                <div class="flex items-center gap-0.5">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <svg class="w-4 h-4 <?php echo e(($rating['rating'] ?? 0) >= $i ? 'text-amber-400' : 'text-slate-200'); ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    <?php if($trainerReview && $logbook->status === 'submitted'): ?>
        <?php echo $__env->make('trainer.reviews.partials.decision-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <?php if($trainerReview && $logbook->status === 'revision'): ?>
        <div class="mt-6">
            <a href="<?php echo e(route('trainer.reviews.edit', $logbook->id)); ?>" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-[#003829] text-white hover:bg-[#00241A] text-xs font-bold shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit & Revisi Logbook
            </a>
        </div>
    <?php endif; ?>

    <?php if($trainingCentreApproval && ($isPending ?? false)): ?>
        <?php echo $__env->make('training-centre.approvals.partials.decision-form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <?php if($trainingCentreApproval && !($isPending ?? false)): ?>
        <section class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-xs text-slate-600">
            Logbook ini hanya dapat dilihat oleh role Anda. Persetujuan dilakukan oleh Trainer.
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