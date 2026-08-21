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
     <?php $__env->slot('title', null, []); ?> Dashboard Trainee OJT <?php $__env->endSlot(); ?>

    <!-- Page Header & Welcome Banner -->
    <div class="mb-4 sm:mb-8 flex flex-col md:flex-row md:items-center md:justify-between bg-gradient-to-r from-[#1e3a8a] to-[#1d4ed8] p-4 sm:p-6 rounded-2xl shadow-md text-white border border-blue-900">
        <div>
            <div class="flex items-center space-x-2 text-blue-300 text-[10px] sm:text-xs font-semibold uppercase tracking-wider mb-1">
                <span>DIGITAL OJT LOGBOOK</span>
                <span class="hidden sm:inline">•</span>
                <span class="hidden sm:inline">OPERATOR COMPETENCY MONITORING</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-white">Selamat Datang, <?php echo e($user->name); ?></h1>
            <p class="text-blue-100 text-[10px] sm:text-xs mt-1">SID: <?php echo e($user->sid); ?> | Departemen: <?php echo e($user->department->name ?? 'Mining Operations'); ?></p>
        </div>
        <div class="mt-3 sm:mt-4 flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
            <?php if($latestDraft): ?>
                <a href="<?php echo e(route('ojt.logbooks.edit', $latestDraft->id)); ?>" class="inline-flex items-center justify-center px-4 py-3 bg-[#f59e0b] hover:bg-amber-500 text-gray-900 font-bold text-xs rounded-xl shadow-sm transition-all min-h-[44px]">
                    <svg class="w-4 h-4 mr-2 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Continue Draft (<?php echo e($latestDraft->logbook_number); ?>)
                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('ojt.logbooks.create')); ?>" class="inline-flex items-center justify-center px-4 py-3 bg-[#2563eb] hover:bg-blue-600 text-white font-bold text-xs rounded-xl shadow-sm transition-all min-h-[44px]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Form OJT
            </a>
        </div>
    </div>

    <!-- Notification Panel: Recent Revision Messages -->
    <?php if($kpi['revision'] > 0): ?>
        <div class="mb-4 sm:mb-8 bg-amber-50 border-l-4 border-[#f59e0b] p-4 sm:p-5 rounded-2xl shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                <div class="flex items-start space-x-3">
                    <div class="p-2 bg-amber-100 rounded-xl text-amber-800 flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-amber-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div>
                        <h3 class="text-[10px] sm:text-xs font-extrabold text-amber-900 uppercase tracking-wider">Pemberitahuan Revisi Logbook (<?php echo e($kpi['revision']); ?> Logbook Perlu Tindakan)</h3>
                        <p class="text-[10px] sm:text-xs text-amber-800 mt-1">Trainer telah mengirimkan catatan perbaikan untuk logbook Anda. Silakan diperbaiki sebelum pengajuan ulang.</p>
                    </div>
                </div>
                <a href="<?php echo e(route('ojt.logbooks.index', ['status' => 'revision'])); ?>" class="px-3.5 py-3 bg-amber-500 hover:bg-amber-600 text-gray-900 text-xs font-bold rounded-xl shadow-xs transition flex-shrink-0 min-h-[44px] inline-flex items-center justify-center">
                    Buka Logbook Revisi
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- OJT Multi-Phase Progress Tracker -->
    <div class="mb-4 sm:mb-8 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h3 class="text-xs sm:text-sm font-bold text-slate-800">Progress Evaluasi Multi-Fase (A2B)</h3>
                <p class="text-[10px] sm:text-xs text-slate-500 mt-0.5">Sertifikasi: <span class="font-semibold text-slate-700"><?php echo e($user->certification ?? '-'); ?></span></p>
            </div>
            <?php if($phaseProgress['eligible']): ?>
                <span class="px-3 py-1.5 rounded-lg font-bold text-xs bg-[#2563eb] text-white">Siap Dievaluasi</span>
            <?php else: ?>
                <span class="px-3 py-1.5 rounded-lg font-bold text-xs bg-slate-100 text-slate-600">Akumulasi Jam Operasional</span>
            <?php endif; ?>
        </div>
        <div class="p-5 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Fase Evaluasi Saat Ini</p>
                <p class="text-lg font-extrabold text-[#1e3a8a] mt-1"><?php echo e($phaseMeta['label'] ?? 'Evaluasi 3'); ?></p>
                <?php if(($phaseMeta['type'] ?? '') === 'evaluasi'): ?>
                    <div class="mt-4 space-y-3">
                        <div>
                            <div class="flex justify-between text-xs text-slate-600 mb-1">
                                <span>Total HM</span>
                                <span class="font-bold"><?php echo e(number_format($hmProgress['total'], 1)); ?> / <?php echo e($phaseMeta['total_hm']); ?> jam</span>
                            </div>
                            <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#2563eb]" style="width: <?php echo e($phaseProgress['total']); ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs text-slate-600 mb-1">
                                <span>HM Siang</span>
                                <span class="font-bold"><?php echo e(number_format($hmProgress['day'], 1)); ?> / <?php echo e($phaseMeta['day_hm'] ?? '—'); ?> jam</span>
                            </div>
                            <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400" style="width: <?php echo e($phaseProgress['day']); ?>%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs text-slate-600 mb-1">
                                <span>HM Malam</span>
                                <span class="font-bold"><?php echo e(number_format($hmProgress['night'], 1)); ?> / <?php echo e($phaseMeta['night_hm'] ?? '—'); ?> jam</span>
                            </div>
                            <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-400" style="width: <?php echo e($phaseProgress['night']); ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-slate-500 mt-3 leading-relaxed">Fase bulanan: isi form OJT minimal 1× per minggu selama periode berjalan. Evaluasi dilakukan di akhir periode oleh Trainer.</p>
                <?php endif; ?>
            </div>
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Evaluasi Berjalan</p>
                <?php if($currentEvaluation): ?>
                    <p class="text-lg font-extrabold text-slate-800 mt-1"><?php echo e($currentEvaluation->phase ?? '-'); ?></p>
                    <span class="inline-block mt-2 px-3 py-1 rounded-lg text-xs font-bold
                        <?php if($currentEvaluation->status === 'submitted'): ?> bg-blue-100 text-blue-700
                        <?php elseif($currentEvaluation->status === 'tc_approved'): ?> bg-purple-100 text-purple-700
                        <?php elseif($currentEvaluation->status === 'pjo_approved'): ?> bg-amber-100 text-amber-700
                        <?php elseif($currentEvaluation->status === 'hse_approved'): ?> bg-amber-100 text-amber-700
                        <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                        <?php echo e(\Illuminate\Support\Str::title(str_replace('_', ' ', $currentEvaluation->status))); ?>

                    </span>
                <?php else: ?>
                    <p class="text-sm text-slate-500 mt-2">Belum ada evaluasi untuk fase ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- KPI Grid (5 Cards) -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-5 mb-4 sm:mb-8">
        
        <!-- Draft KPI -->
        <a href="<?php echo e(route('ojt.logbooks.index', ['status' => 'draft'])); ?>" class="bg-white p-3 sm:p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-slate-400 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">Draft</span>
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 group-hover:bg-slate-200 transition">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
            </div>
            <div class="mt-2 sm:mt-3 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-slate-800"><?php echo e($kpi['draft']); ?></span>
                <span class="text-[10px] font-medium text-slate-400 hidden sm:inline">Belum Dikirim</span>
            </div>
        </a>

        <!-- Submitted KPI -->
        <a href="<?php echo e(route('ojt.logbooks.index', ['status' => 'submitted'])); ?>" class="bg-white p-3 sm:p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-bold text-blue-600 uppercase tracking-wider">Submitted</span>
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-100 transition">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </div>
            </div>
            <div class="mt-2 sm:mt-3 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-blue-700"><?php echo e($kpi['submitted']); ?></span>
                <span class="text-[10px] font-medium text-blue-500 hidden sm:inline">Menunggu Review</span>
            </div>
        </a>

        <!-- Revision KPI -->
        <a href="<?php echo e(route('ojt.logbooks.index', ['status' => 'revision'])); ?>" class="bg-white p-3 sm:p-5 rounded-2xl shadow-sm border <?php echo e($kpi['revision'] > 0 ? 'border-amber-300 ring-2 ring-amber-100' : 'border-slate-200'); ?> hover:border-amber-400 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-bold text-amber-600 uppercase tracking-wider">Returned Revision</span>
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-100 transition">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>
            <div class="mt-2 sm:mt-3 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-amber-700"><?php echo e($kpi['revision']); ?></span>
                <span class="text-[10px] font-bold text-amber-600 hidden sm:inline">Catatan Trainer</span>
            </div>
        </a>

        <!-- Approved KPI -->
        <a href="<?php echo e(route('ojt.logbooks.index', ['status' => 'approved'])); ?>" class="bg-white p-3 sm:p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-300 transition group">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-bold text-[#2563eb] uppercase tracking-wider">Approved Logbooks</span>
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-100 transition">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2 sm:mt-3 flex items-baseline justify-between">
                <span class="text-2xl sm:text-3xl font-extrabold text-[#1e3a8a]"><?php echo e($kpi['approved']); ?></span>
                <span class="text-[10px] font-medium text-amber-600 hidden sm:inline">Verifikasi Sukses</span>
            </div>
        </a>

        <!-- Progress KPI Card -->
        <div class="bg-white p-3 sm:p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="flex items-center justify-between">
                <span class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider">OJT Progress</span>
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[#1e3a8a] flex items-center justify-center text-[#f59e0b]">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-2 sm:mt-3">
                <div class="flex items-baseline justify-between">
                    <span class="text-xl sm:text-2xl font-extrabold text-slate-800"><?php echo e(number_format($kpi['total_hm'], 1)); ?> <span class="text-[10px] sm:text-xs text-slate-500 font-normal">HM Total</span></span>
                </div>
                <div class="mt-2 flex items-center gap-3 sm:gap-4 text-[10px] sm:text-[11px] text-slate-500">
                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Siang: <?php echo e(number_format($kpi['hm_day'], 1)); ?> HM</span>
                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-indigo-500"></span> Malam: <?php echo e(number_format($kpi['hm_night'], 1)); ?> HM</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Grid Content: Analytics & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8">
        
        <!-- Left 2 Columns: Quick Actions -->
        <div class="lg:col-span-2 flex flex-col gap-8">
            
            <!-- Quick Action Cards Grid -->
            <div class="grid flex-1 grid-cols-1 gap-3 sm:gap-5 sm:grid-cols-2">
                <a href="<?php echo e(route('ojt.logbooks.create')); ?>" class="h-full p-5 sm:p-7 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:border-[#2563eb] transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold tracking-widest text-blue-600 uppercase">Aksi Cepat</span>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-[#2563eb] group-hover:text-white transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                    </div>
                    <div class="py-4 sm:py-6">
                        <h3 class="text-base sm:text-lg font-bold text-slate-800 group-hover:text-[#1e3a8a] transition">Create Digital Logbook</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">Catat HM awal, HM akhir, lokasi pit, dan isi checklist SOP harian.</p>
                    </div>
                        <span class="inline-flex items-center border-t border-slate-100 pt-4 sm:pt-5 text-xs sm:text-sm font-bold text-[#2563eb]">Input Logbook<svg class="w-4 h-4 sm:w-5 sm:h-5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                </a>

                <a href="<?php echo e(route('ojt.logbooks.index')); ?>" class="h-full p-5 sm:p-7 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:border-blue-400 transition group flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold tracking-widest text-blue-600 uppercase">Manajemen Data</span>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    </div>
                    <div class="py-4 sm:py-6">
                        <h3 class="text-base sm:text-lg font-bold text-slate-800 group-hover:text-blue-700 transition">My Logbook Directory</h3>
                        <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">Kelola daftar seluruh riwayat logbook, status verifikasi, dan cetak PDF.</p>
                    </div>
                    <span class="inline-flex items-center border-t border-slate-100 pt-4 sm:pt-5 text-xs sm:text-sm font-bold text-blue-600">Buka Tabel Logbook<svg class="w-4 h-4 sm:w-5 sm:h-5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                </a>
            </div>

        </div>

        <!-- Right Column: Recent Logbooks & Activity Timeline -->
        <div class="space-y-4 sm:space-y-8">
            
            <!-- Recent Activity Widget -->
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex items-center justify-between mb-3 sm:mb-5">
                    <h2 class="text-sm sm:text-base font-bold text-slate-800">Aktivitas Terakhir</h2>
                    <a href="<?php echo e(route('ojt.history')); ?>" class="text-[10px] sm:text-xs font-bold text-[#2563eb] hover:underline">Lihat Semua</a>
                </div>

                <div class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $recentLogbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="py-4">
                            <div class="flex items-center justify-between gap-3">
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
                                <span class="shrink-0 text-[11px] font-medium text-slate-400"><?php echo e(\Carbon\Carbon::parse($log->date)->format('d M Y')); ?></span>
                            </div>
                             <a href="<?php echo e(route('ojt.logbooks.show', $log->id)); ?>" class="mt-2 block text-sm font-bold text-slate-800 hover:text-[#2563eb] break-words">
                                <?php echo e($log->logbook_number); ?>

                            </a>
                            <p class="mt-1 text-xs text-slate-500">
                                Unit: <span class="font-semibold text-slate-700"><?php echo e($log->equipment->unit_code ?? '-'); ?></span><span class="mx-1.5 text-slate-300">|</span>HM: <?php echo e(number_format($log->total_hm, 1)); ?>

                            </p>
                            <?php if($log->status === 'revision' && $log->revision_notes): ?>
                                <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs leading-relaxed text-amber-800">
                                    <strong>Catatan Trainer:</strong> <?php echo e(Str::limit($log->revision_notes, 110)); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-xs text-slate-400 py-4 text-center">Belum ada logbook yang dicatat.</p>
                    <?php endif; ?>
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

<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/ojt/dashboard.blade.php ENDPATH**/ ?>