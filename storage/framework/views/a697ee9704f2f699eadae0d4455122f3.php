<?php
    $user = auth()->user();

    $roleLabels = [
        'trainee' => 'Trainee',
        'trainer' => 'Trainer',
        'department_ops' => 'Dept Operation',
        'admin' => 'Admin TC',
    ];

    $role = $user?->role;
    $roleLabel = $roleLabels[$role] ?? 'User';
    $initials = collect(explode(' ', $user?->name ?? 'U'))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    $dashboardRoute = match ($role) {
        'trainee' => 'ojt.dashboard',
        'trainer' => 'trainer.dashboard',
        'department_ops' => 'department-operation.dashboard',
        'admin' => 'training-centre.dashboard',
        default => 'dashboard',
    };

    $menuSections = [
        'trainee' => [
            'label' => 'OJT Evaluation',
            'tag' => 'TRAINEE',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'ojt.dashboard', 'match' => 'ojt.dashboard'],
                ['label' => 'My Logbook', 'route' => 'ojt.logbooks.index', 'match' => 'ojt.logbooks.*'],
                ['label' => 'Create Logbook', 'route' => 'ojt.logbooks.create', 'match' => 'ojt.logbooks.create'],
                ['label' => 'Submission History', 'route' => 'ojt.history', 'match' => 'ojt.history'],
            ],
        ],
        'trainer' => [
            'label' => 'Trainer Workspace',
            'tag' => 'TRAINER',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'trainer.dashboard', 'match' => 'trainer.dashboard'],
                ['label' => 'Review Logbook', 'route' => 'trainer.reviews.index', 'match' => 'trainer.reviews.*'],
            ],
        ],
        'department_ops' => [
            'label' => 'Approval Pengawas',
            'tag' => 'PENGAWAS',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'department-operation.dashboard', 'match' => 'department-operation.dashboard'],
                ['label' => 'Pending Approval', 'route' => 'department-operation.approvals.pending', 'match' => 'department-operation.approvals.pending'],
                ['label' => 'Approval History', 'route' => 'department-operation.approvals.history', 'match' => 'department-operation.approvals.history'],
            ],
        ],
        'admin' => [
            'label' => 'Admin Training Centre',
            'tag' => 'ADMIN',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'training-centre.dashboard', 'match' => 'training-centre.dashboard'],
                ['label' => 'Final Approval', 'route' => 'training-centre.approvals.index', 'match' => 'training-centre.approvals.*'],
            ],
        ],
    ];

    $currentSection = $menuSections[$role] ?? null;
?>

<aside x-show="sidebarOpen" class="w-64 bg-[#003829] text-white flex flex-col flex-shrink-0 transition-all duration-300 shadow-xl z-20">
    <div class="h-16 px-6 flex items-center justify-between border-b border-emerald-900/60 bg-[#002B1F]">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-[#00A859] flex items-center justify-center font-bold text-white shadow-md border border-emerald-400/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="font-extrabold text-sm tracking-wide text-white leading-none">BERAU COAL</h1>
                <p class="text-[10px] text-emerald-300 font-medium tracking-wider uppercase mt-1"><?php echo e($roleLabel); ?> Portal</p>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-6">
        <?php if($currentSection): ?>
            <div class="space-y-1">
                <a href="<?php echo e(route($dashboardRoute)); ?>"
                   class="flex items-center px-3 py-2.5 text-xs font-semibold rounded-lg transition-colors group <?php echo e(request()->routeIs($currentSection['items'][0]['match']) ? 'bg-[#00A859] text-white shadow-md' : 'text-emerald-100 hover:bg-emerald-900/60 hover:text-white'); ?>">
                    <svg class="w-4 h-4 mr-3 <?php echo e(request()->routeIs($currentSection['items'][0]['match']) ? 'text-white' : 'text-emerald-400 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </div>

            <div>
                <div class="px-3 mb-2 text-[11px] font-bold text-emerald-400 uppercase tracking-widest flex items-center justify-between">
                    <span><?php echo e($currentSection['label']); ?></span>
                    <span class="bg-[#F5A623] text-gray-900 text-[9px] font-extrabold px-1.5 py-0.5 rounded"><?php echo e($currentSection['tag']); ?></span>
                </div>

                <nav class="space-y-1">
                    <?php $__currentLoopData = $currentSection['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item['route'] === $dashboardRoute) continue; ?>
                        <a href="<?php echo e(route($item['route'])); ?>"
                           class="flex items-center px-3 py-2.5 text-xs font-semibold rounded-lg transition-colors group <?php echo e(request()->routeIs($item['match']) ? 'bg-[#00A859] text-white shadow-md' : 'text-emerald-100 hover:bg-emerald-900/60 hover:text-white'); ?>">
                            <svg class="w-4 h-4 mr-3 <?php echo e(request()->routeIs($item['match']) ? 'text-white' : 'text-emerald-400 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M8 8h8m-9 8h10"/>
                            </svg>
                            <span><?php echo e($item['label']); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </nav>
            </div>
        <?php endif; ?>

        <div class="bg-[#00261C] p-3.5 rounded-xl border border-emerald-800/40 text-xs">
            <div class="flex items-center space-x-2 text-[#F5A623] font-bold mb-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>OJT TARGET</span>
            </div>
            <p class="text-emerald-200 text-[11px] leading-relaxed">
                Min. 200 HM Hours required for Excavator & Heavy Unit Certification.
            </p>
        </div>
    </div>

    <div class="p-4 border-t border-emerald-900/60 bg-[#002319] flex items-center justify-between">
        <div class="flex items-center space-x-3 min-w-0">
            <div class="w-8 h-8 rounded-full bg-emerald-700 flex items-center justify-center font-bold text-xs text-white border border-emerald-400">
                <?php echo e($initials ?: 'U'); ?>

            </div>
            <div class="overflow-hidden text-ellipsis whitespace-nowrap">
                <p class="text-xs font-bold text-white truncate"><?php echo e($user->name ?? 'User'); ?></p>
                <p class="text-[10px] text-emerald-300 font-mono truncate"><?php echo e($user->nrp ?? '-'); ?> (<?php echo e($roleLabel); ?>)</p>
            </div>
        </div>
    </div>
</aside>
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views\layouts\sidebar.blade.php ENDPATH**/ ?>