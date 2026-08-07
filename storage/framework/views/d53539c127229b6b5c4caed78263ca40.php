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
    $quickAction = match ($role) {
        'trainee' => ['label' => 'Buka Logbook Saya', 'route' => 'ojt.logbooks.index'],
        'trainer' => ['label' => 'Buka Review Queue', 'route' => 'trainer.dashboard'],
        'department_ops' => ['label' => 'Buka Pending Approval', 'route' => 'department-operation.approvals.pending'],
        'admin' => ['label' => 'Buka Final Approval', 'route' => 'training-centre.approvals.index'],
        default => ['label' => 'Dashboard', 'route' => 'dashboard'],
    };
?>

<header class="h-16 bg-white border-b border-slate-200 px-6 flex items-center justify-between shadow-xs z-10">
    <div class="flex items-center space-x-4">
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100 focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="flex items-center space-x-2 text-xs">
            <span class="font-bold text-[#003829]">PT BERAU COAL / PT MTL</span>
            <span class="text-slate-300">/</span>
            <span class="text-slate-500 font-medium"><?php echo e($roleLabel); ?> Dashboard</span>
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <div class="hidden md:flex items-center space-x-2 px-3 py-1 bg-emerald-50 rounded-full border border-emerald-200">
            <span class="w-2 h-2 rounded-full bg-[#00A859] animate-pulse"></span>
            <span class="text-xs font-semibold text-[#003829]"><?php echo e($roleLabel); ?> Active</span>
        </div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="p-2 text-slate-500 hover:text-slate-700 rounded-lg hover:bg-slate-100 relative">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#F5A623] rounded-full"></span>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-slate-200 py-2 z-50">
                <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-slate-800"><?php echo e($user->name ?? 'User'); ?></p>
                        <p class="text-[11px] text-slate-500 mt-0.5"><?php echo e($user->nrp ?? '-'); ?> · <?php echo e($roleLabel); ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#003829] text-white flex items-center justify-center font-bold text-xs">
                        <?php echo e(collect(explode(' ', $user->name ?? 'U'))->filter()->take(2)->map(fn ($part) => strtoupper(substr($part, 0, 1)))->implode('') ?: 'U'); ?>

                    </div>
                </div>
                <a href="<?php echo e(route($quickAction['route'])); ?>" class="px-4 py-3 block hover:bg-emerald-50/60 transition">
                    <p class="text-xs font-semibold text-[#003829]">Akses Cepat</p>
                    <p class="text-[11px] text-slate-500 mt-0.5"><?php echo e($quickAction['label']); ?></p>
                </a>
                <a href="<?php echo e(route('profile.edit')); ?>" class="px-4 py-3 block hover:bg-emerald-50/60 transition">
                    <p class="text-xs font-semibold text-[#003829]">My Profile</p>
                    <p class="text-[11px] text-slate-500 mt-0.5">Kelola data akun dan tanda tangan</p>
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="border-t border-slate-100 mt-1 pt-1">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full px-4 py-3 text-left hover:bg-rose-50 transition">
                        <p class="text-xs font-semibold text-rose-700">Logout</p>
                        <p class="text-[11px] text-slate-500 mt-0.5">Keluar dari sesi aktif</p>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex items-center space-x-3 pl-3 border-l border-slate-200">
            <img class="w-8 h-8 rounded-full border-2 border-[#00A859] object-cover" src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->name ?? 'User')); ?>&background=003829&color=fff" alt="User Avatar">
            <div class="hidden lg:block text-left">
                <span class="text-xs font-bold text-slate-800 block leading-tight"><?php echo e($user->name ?? 'User'); ?></span>
                <span class="text-[10px] text-slate-500 font-medium block"><?php echo e($user->nrp ?? '-'); ?> · <?php echo e($roleLabel); ?></span>
            </div>
        </div>
    </div>
</header>
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/layouts/navbar.blade.php ENDPATH**/ ?>