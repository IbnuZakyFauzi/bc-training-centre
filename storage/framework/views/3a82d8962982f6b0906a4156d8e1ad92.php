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
     <?php $__env->slot('title', null, []); ?> My Profile <?php $__env->endSlot(); ?>

    <div class="mb-6">
        <div class="bg-gradient-to-r from-[#003829] to-[#00593E] text-white rounded-2xl p-6 shadow-sm border border-emerald-900">
            <p class="text-emerald-300 text-[10px] font-bold uppercase tracking-widest">Account Settings</p>
            <h1 class="mt-1 text-2xl font-extrabold">My Profile</h1>
            <p class="mt-2 text-xs text-emerald-100">Simpan tanda tangan sekali di sini, lalu dipakai otomatis untuk approval trainer, pengawas, dan kabag.</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs text-emerald-900">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="grid gap-6 lg:grid-cols-2">
        <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div>
                <label class="text-xs font-bold text-slate-700">Nama</label>
                <input name="name" value="<?php echo e(old('name', $user->name)); ?>" class="mt-2 w-full rounded-xl border-slate-300 text-sm" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="mt-2 w-full rounded-xl border-slate-300 text-sm" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700">Phone</label>
                <input name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="mt-2 w-full rounded-xl border-slate-300 text-sm" placeholder="Opsional">
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700">Upload Tanda Tangan</label>
                <input type="file" name="signature" accept="image/png,image/jpeg" class="mt-2 block w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#003829] file:px-3 file:py-2 file:text-xs file:font-bold file:text-white">
                <p class="mt-2 text-[11px] text-slate-500">Format PNG, JPG, atau JPEG. Maksimal 2 MB. Tanda tangan ini akan dipakai otomatis saat approval.</p>
                <?php $__errorArgs = ['signature'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="flex justify-end">
                <button class="rounded-xl bg-[#00A859] px-5 py-3 text-xs font-bold text-white hover:bg-emerald-600">Simpan Profil</button>
            </div>
        </form>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Informasi Akun</p>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500 text-xs">NRP</span>
                        <span class="font-semibold text-slate-800"><?php echo e($user->nrp); ?></span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500 text-xs">Role</span>
                        <span class="font-semibold text-slate-800"><?php echo e(strtoupper($user->role)); ?></span>
                    </div>
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-slate-500 text-xs">Department</span>
                        <span class="font-semibold text-slate-800"><?php echo e($user->department->name ?? '-'); ?></span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Tanda Tangan Tersimpan</p>
                <div class="mt-4 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4">
                    <?php if($user->signature_path): ?>
                        <img src="<?php echo e(asset('storage/'.$user->signature_path)); ?>" alt="Signature" class="max-h-32 w-auto object-contain">
                        <p class="mt-3 text-[11px] text-slate-500">Dipakai otomatis saat approval.</p>
                    <?php else: ?>
                        <p class="text-sm text-slate-500">Belum ada tanda tangan tersimpan.</p>
                        <p class="mt-2 text-[11px] text-slate-500">Upload sekali dari halaman ini agar approval berikutnya bisa langsung klik approve.</p>
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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/profile/edit.blade.php ENDPATH**/ ?>