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
    $roleLabel = $roleLabels[$role] ?? 'User';

    $unreadNotifications = $user ? $user->unreadNotifications()->latest()->limit(10)->get() : collect();
    $unreadCount = $unreadNotifications->count();
?>

<header class="h-16 bg-[#1e3a8a] border-b border-amber-400/60 shadow-sm px-4 sm:px-6 flex items-center justify-between z-10">
    <div class="flex items-center space-x-3 sm:space-x-4">
        <button @click="sidebarOpen = !sidebarOpen" class="text-blue-100 hover:text-white p-2 rounded-lg hover:bg-white/10 focus:outline-none min-h-[44px] min-w-[44px] flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="flex items-center space-x-2 text-xs">
            <span class="font-bold text-white">PT BERAU COAL / PT MTL</span>
            <span class="text-blue-300 hidden sm:inline">/</span>
            <span class="text-blue-200 font-medium hidden sm:inline"><?php echo e($roleLabel); ?> Dashboard</span>
        </div>
    </div>

    <div class="flex items-center space-x-2 sm:space-x-4">
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="relative p-2 text-blue-100 hover:text-white rounded-lg hover:bg-white/10 min-h-[44px] min-w-[44px] flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/>
                </svg>
                <?php if($unreadCount > 0): ?>
                    <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center text-[10px] font-extrabold text-white bg-amber-500 rounded-full"><?php echo e($unreadCount); ?></span>
                <?php endif; ?>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-amber-400 py-2 z-50">
                <div class="px-4 py-2.5 border-b border-amber-200 flex items-center justify-between gap-3">
                    <p class="text-xs font-bold text-slate-800">Notifikasi</p>
                    <?php if($unreadCount > 0): ?>
                        <form method="POST" action="<?php echo e(route('notifications.read-all')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-[11px] font-semibold text-[#2563eb] hover:underline">Tandai semua dibaca</button>
                        </form>
                    <?php endif; ?>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    <?php $__empty_1 = true; $__currentLoopData = $unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $title = $n->data['title'] ?? 'Notifikasi';
                            $message = $n->data['message'] ?? $title;
                            $url = $n->data['url'] ?? route('notifications.read', $n->id);
                            $type = $n->data['type'] ?? $n->type;
                        ?>
                        <a href="<?php echo e(route('notifications.read', $n->id)); ?>" class="flex gap-3 px-4 py-3 hover:bg-blue-50/60 border-b border-slate-50 transition relative">
                            <div class="w-8 h-8 rounded-lg bg-[#2563eb]/10 text-[#2563eb] flex items-center justify-center flex-shrink-0">
                                <?php if(str_contains($type, 'LogbookSubmitted')): ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <?php elseif(str_contains($type, 'LogbookApproved')): ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php elseif(str_contains($type, 'LogbookRevision')): ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <?php elseif(str_contains($type, 'HmThreshold')): ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php else: ?>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/></svg>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-slate-800 leading-snug"><?php echo e($title); ?></p>
                                <p class="text-[11px] text-slate-500 mt-0.5 leading-snug"><?php echo e($message); ?></p>
                                <p class="text-[10px] text-slate-400 mt-1"><?php echo e($n->created_at->diffForHumans()); ?></p>
                            </div>
                            <div class="w-2 h-2 rounded-full bg-[#2563eb] flex-shrink-0 mt-1" title="Belum dibaca"></div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="px-4 py-6 text-center text-xs text-slate-400">Belum ada notifikasi.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="hidden md:flex items-center space-x-2 px-3 py-1 bg-amber-500/20 rounded-full border border-amber-400/40">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            <span class="text-xs font-semibold text-amber-200"><?php echo e($roleLabel); ?> Active</span>
        </div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-2 sm:space-x-3 pl-2 sm:pl-3 border-l border-amber-300/60 hover:bg-white/10 rounded-lg px-2 py-1 transition min-h-[44px]">
                <img class="w-8 h-8 rounded-full border-2 border-amber-500 object-cover" src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->name ?? 'User')); ?>&background=f59e0b&color=000000" alt="User Avatar">
                <div class="hidden lg:block text-left">
                    <span class="text-xs font-bold text-white block leading-tight"><?php echo e($user->name ?? 'User'); ?></span>
                    <span class="text-[10px] text-blue-200 font-medium block"><?php echo e($user->sid ?? '-'); ?> · <?php echo e($roleLabel); ?></span>
                </div>
                <svg class="w-4 h-4 text-blue-200 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>

            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-lg border border-amber-400 py-2 z-50">
                <div class="px-4 py-3 border-b border-amber-200">
                    <p class="text-xs font-bold text-slate-800"><?php echo e($user->name ?? 'User'); ?></p>
                    <p class="text-[11px] text-slate-500 mt-0.5"><?php echo e($user->sid ?? '-'); ?> · <?php echo e($roleLabel); ?></p>
                </div>
                <a href="<?php echo e(route('profile.edit')); ?>" class="px-4 py-3 block hover:bg-blue-50/60 transition">
                    <p class="text-xs font-semibold text-[#1e3a8a]">My Profile</p>
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
    </div>
</header>

<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/layouts/navbar.blade.php ENDPATH**/ ?>