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
     <?php $__env->slot('title', null, []); ?> Manajemen Pengguna <?php $__env->endSlot(); ?>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-xs font-semibold text-[#00A859] mb-1">
                <a href="<?php echo e(route('training-centre.dashboard')); ?>" class="hover:underline">Dashboard</a>
                <span>/</span>
                <span class="text-slate-500">Manajemen Pengguna</span>
            </div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Manajemen Pengguna</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar trainee, trainer (instruktur/pengawas/operator pendamping), dan admin yang terdaftar di sistem.</p>
        </div>
        <a href="<?php echo e(route('training-centre.users.create')); ?>" class="px-4 py-2 bg-[#00A859] hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
            + Tambah Pengguna
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-[11px] text-slate-500 font-bold uppercase">Total</p>
            <p class="text-2xl font-black text-slate-800"><?php echo e($counts['total']); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-[11px] text-slate-500 font-bold uppercase">Trainee</p>
            <p class="text-2xl font-black text-[#00A859]"><?php echo e($counts['trainee']); ?></p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-[11px] text-slate-500 font-bold uppercase">Trainer</p>
            <p class="text-2xl font-black text-[#003829]"><?php echo e($counts['trainer']); ?></p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="font-bold text-slate-800">Daftar Pengguna</h2>
                <p class="text-xs text-slate-500 mt-1">Kelola akun trainee, trainer, dan pengawas.</p>
            </div>
            <form class="flex gap-2" method="GET">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari SID, nama, atau email..." class="text-xs rounded-xl border-slate-300 focus:border-[#00A859] focus:ring-[#00A859]">
                <select name="role" class="text-xs rounded-xl border-slate-300">
                    <option value="">Semua Role</option>
                    <option value="trainee" <?php echo e(request('role') === 'trainee' ? 'selected' : ''); ?>>Trainee</option>
                    <option value="trainer" <?php echo e(request('role') === 'trainer' ? 'selected' : ''); ?>>Trainer</option>
                    <option value="admin" <?php echo e(request('role') === 'admin' ? 'selected' : ''); ?>>Admin TC</option>
                </select>
                <button class="px-4 rounded-xl bg-[#003829] text-white text-xs font-bold">Filter</button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-[10px] uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3">SID / Nama</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Role</th>
                        <th class="px-5 py-3">Tipe Trainer</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-emerald-50/30">
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-slate-800"><?php echo e($user->sid); ?></p>
                                <p class="text-[11px] text-slate-500 mt-0.5"><?php echo e($user->name); ?></p>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-600"><?php echo e($user->email); ?></td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold
                                    <?php echo e($user->role === 'trainee' ? 'bg-emerald-50 text-[#00593E]' : ''); ?>

                                    <?php echo e($user->role === 'trainer' ? 'bg-blue-50 text-blue-700' : ''); ?>

                                    <?php echo e($user->role === 'admin' ? 'bg-violet-50 text-violet-700' : ''); ?>

                                ">
                                    <?php echo e(match($user->role) {
                                        'trainee' => 'Trainee',
                                        'trainer' => 'Trainer',
                                        'admin' => 'Admin TC',
                                        default => $user->role,
                                    }); ?>

                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold
                                    <?php echo e($user->role === 'trainee' ? 'bg-emerald-50 text-[#00593E]' : ''); ?>

                                    <?php echo e($user->role === 'trainer' ? 'bg-blue-50 text-blue-700' : ''); ?>

                                    <?php echo e($user->role === 'admin' ? 'bg-violet-50 text-violet-700' : ''); ?>

                                ">
                                    <?php echo e(match($user->role) {
                                        'trainee' => 'Trainee',
                                        'trainer' => 'Trainer',
                                        'admin' => 'Admin TC',
                                        default => $user->role,
                                    }); ?>

                                </span>
                                <?php if($user->role === 'trainer' && $user->trainer_type): ?>
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-700 ml-1">
                                        <?php echo e(match($user->trainer_type) {
                                            'instruktur' => 'Instruktur',
                                            'pengawas' => 'Pengawas',
                                            'operator_pendamping' => 'Operator Pendamping',
                                            default => $user->trainer_type,
                                        }); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <?php if($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()): ?>
                                    <span class="text-[10px] text-slate-400">Super Admin</span>
                                <?php else: ?>
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?php echo e(route('training-centre.users.edit', $user->id)); ?>" class="inline-flex px-3 py-2 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-bold">Edit</a>
                                        <?php if($user->id !== auth()->id()): ?>
                                                <form method="POST" action="<?php echo e(route('training-centre.users.destroy', $user->id)); ?>" onsubmit="return confirm('Hapus pengguna <?php echo e($user->name); ?> (<?php echo e($user->sid); ?>)? Tindakan ini tidak dapat dibatalkan.')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="inline-flex px-3 py-2 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold">Hapus</button>
                                            </form>
                                            <form method="POST" action="<?php echo e(route('training-centre.users.reset-password', $user->id)); ?>" onsubmit="return confirm('Reset password <?php echo e($user->name); ?> (<?php echo e($user->sid); ?>) ke default (password)?')">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="inline-flex px-3 py-2 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold">Reset Password</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-400">Belum ada pengguna terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t border-slate-100">
            <?php echo e($users->links()); ?>

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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views\training-centre\users\index.blade.php ENDPATH**/ ?>