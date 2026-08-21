<?php
    $user = auth()->user();

    $roleLabels = [
        'trainee' => 'Trainee',
        'trainer' => 'Trainer',
        'admin' => 'Admin TC',
        'pjo' => 'PJO',
        'hse_ct' => 'HSE CT',
    ];

    $role = $user?->role;
    $roleLabel = match (true) {
        $role === 'trainer' && $user?->trainer_type === 'pengawas' => 'Pengawas',
        $role === 'trainer' && $user?->trainer_type === 'operator_pendamping' => 'Operator Pendamping',
        default => $roleLabels[$role] ?? 'User',
    };
    $initials = collect(explode(' ', $user?->name ?? 'U'))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    $dashboardRoute = match ($role) {
        'trainee' => 'ojt.dashboard',
        'trainer' => 'trainer.dashboard',
        'admin' => 'training-centre.dashboard',
        'pjo' => 'pjo.dashboard',
        'hse_ct' => 'hse-ct.dashboard',
        default => 'dashboard',
    };

    $menuSections = [
        'trainee' => [
            'label' => 'OJT Evaluation',
            'tag' => 'TRAINEE',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'ojt.dashboard', 'match' => 'ojt.dashboard'],
                ['label' => 'My Submission', 'route' => 'ojt.logbooks.index', 'match' => 'ojt.logbooks.*'],
                ['label' => 'Form OJT', 'route' => 'ojt.logbooks.create', 'match' => 'ojt.logbooks.create'],
            ],
        ],
        'trainer' => [
            'label' => 'Trainer Workspace',
            'tag' => 'TRAINER',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'trainer.dashboard', 'match' => 'trainer.dashboard'],
                ['label' => 'Review Logbook', 'route' => 'trainer.reviews.index', 'match' => 'trainer.reviews.*'],
                ['label' => 'Evaluasi Akhir A2B', 'route' => 'trainer.final-evaluations.index', 'match' => 'trainer.final-evaluations.*'],
                ['label' => 'Approval Pengawas', 'route' => 'supervisor.approvals.index', 'match' => 'supervisor.approvals.*'],
            ],
        ],
        'pengawas' => [
            'label' => 'Pengawas Workspace',
            'tag' => 'PENGAWAS',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'trainer.dashboard', 'match' => 'trainer.dashboard'],
                ['label' => 'Review Logbook', 'route' => 'trainer.reviews.index', 'match' => 'trainer.reviews.*'],
                ['label' => 'Evaluasi Akhir A2B', 'route' => 'trainer.final-evaluations.index', 'match' => 'trainer.final-evaluations.*'],
                ['label' => 'Approval Saya', 'route' => 'supervisor.approvals.index', 'match' => 'supervisor.approvals.*'],
            ],
        ],
        'operator_pendamping' => [
            'label' => 'Operator Pendamping',
            'tag' => 'OPERATOR',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'trainer.dashboard', 'match' => 'trainer.dashboard'],
                ['label' => 'Review Logbook', 'route' => 'trainer.reviews.index', 'match' => 'trainer.reviews.*'],
                ['label' => 'Evaluasi Akhir A2B', 'route' => 'trainer.final-evaluations.index', 'match' => 'trainer.final-evaluations.*'],
            ],
        ],
        'admin' => [
            'label' => 'Admin Training Centre',
            'tag' => 'ADMIN',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'training-centre.dashboard', 'match' => 'training-centre.dashboard'],
                ['label' => 'Manajemen Pengguna', 'route' => 'training-centre.users.index', 'match' => 'training-centre.users.*'],
            ],
        ],
        'pjo' => [
            'label' => 'PJO Workspace',
            'tag' => 'PJO',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'pjo.dashboard', 'match' => 'pjo.dashboard'],
                ['label' => 'Persetujuan Evaluasi', 'route' => 'pjo.final-evaluations.index', 'match' => 'pjo.final-evaluations.*'],
            ],
        ],
        'hse_ct' => [
            'label' => 'HSE CT Workspace',
            'tag' => 'HSE CT',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'hse-ct.dashboard', 'match' => 'hse-ct.dashboard'],
                ['label' => 'Persetujuan Evaluasi', 'route' => 'hse-ct.final-evaluations.index', 'match' => 'hse-ct.final-evaluations.*'],
            ],
        ],
    ];

    $currentSection = match (true) {
        $role === 'trainee' => $menuSections['trainee'],
        $role === 'admin' => $menuSections['admin'],
        $role === 'pjo' => $menuSections['pjo'],
        $role === 'hse_ct' => $menuSections['hse_ct'],
        $role === 'trainer' && $user?->trainer_type === 'pengawas' => $menuSections['pengawas'],
        $role === 'trainer' && $user?->trainer_type === 'operator_pendamping' => $menuSections['operator_pendamping'],
        default => $menuSections['trainer'],
    };
?>

<aside x-show="sidebarOpen" x-cloak class="fixed inset-y-0 left-0 z-50 w-64 bg-[#1e3a8a] text-white flex flex-col shadow-xl lg:relative lg:z-20 lg:translate-x-0 lg:block transition-transform duration-300">
    <div class="h-16 px-6 flex items-center justify-between border-b border-amber-400/40 bg-[#172554]">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-amber-500 flex items-center justify-center font-bold text-slate-900 shadow-md border border-amber-300/50">
                <svg class="w-5 h-5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h1 class="font-extrabold text-sm tracking-wide text-white leading-none">BERAU COAL</h1>
                <p class="text-[10px] text-amber-200 font-medium tracking-wider uppercase mt-1"><?php echo e($roleLabel); ?> Portal</p>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-6">
        <?php if($currentSection): ?>
            <div class="space-y-1">
                <a href="<?php echo e(route($dashboardRoute)); ?>"
                   class="flex items-center px-3 py-2.5 text-xs font-semibold rounded-lg transition-colors group <?php echo e(request()->routeIs($currentSection['items'][0]['match']) ? 'bg-[#2563eb] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/60 hover:text-white'); ?>">
                     <svg class="w-4 h-4 mr-3 <?php echo e(request()->routeIs($currentSection['items'][0]['match']) ? 'text-white' : 'text-blue-400 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>
            </div>

            <div>
                <div class="px-3 mb-2 text-[11px] font-bold text-blue-400 uppercase tracking-widest flex items-center justify-between">
                    <span><?php echo e($currentSection['label']); ?></span>
                    <span class="bg-[#f59e0b] text-gray-900 text-[9px] font-extrabold px-1.5 py-0.5 rounded"><?php echo e($currentSection['tag']); ?></span>
                </div>

                <nav class="space-y-1">
                    <?php $__currentLoopData = $currentSection['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($item['route'] === $dashboardRoute) continue; ?>
                        <a href="<?php echo e(route($item['route'])); ?>"
                           class="flex items-center px-3 py-2.5 text-xs font-semibold rounded-lg transition-colors group <?php echo e(request()->routeIs($item['match']) ? 'bg-[#2563eb] text-white shadow-md' : 'text-blue-100 hover:bg-blue-900/60 hover:text-white'); ?>">
                             <svg class="w-4 h-4 mr-3 <?php echo e(request()->routeIs($item['match']) ? 'text-white' : 'text-blue-400 group-hover:text-white'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M8 8h8m-9 8h10"/>
                            </svg>
                            <span><?php echo e($item['label']); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </nav>
            </div>
        <?php endif; ?>
</aside>

<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>