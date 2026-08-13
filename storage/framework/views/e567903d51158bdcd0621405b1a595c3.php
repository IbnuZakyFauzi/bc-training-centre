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
     <?php $__env->slot('title', null, []); ?> Tambah Pengguna <?php $__env->endSlot(); ?>

    <div class="mb-6">
        <div class="flex items-center space-x-2 text-xs font-semibold text-[#00A859] mb-1">
            <a href="<?php echo e(route('training-centre.users.index')); ?>" class="hover:underline">Manajemen Pengguna</a>
            <span>/</span>
            <span class="text-slate-500">Tambah Pengguna</span>
        </div>
        <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Tambah Pengguna Baru</h1>
            <p class="text-xs text-slate-500 mt-1">Daftarkan trainee, trainer, atau admin baru ke sistem.</p>
    </div>

    <form method="POST" action="<?php echo e(route('training-centre.users.store')); ?>" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <?php echo csrf_field(); ?>
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">SID <span class="text-rose-500">*</span></label>
                    <input type="text" name="sid" value="<?php echo e(old('sid')); ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Contoh: XXXXX">
                    <?php $__errorArgs = ['sid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="Contoh: Ahmad Rian">
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
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition" placeholder="contoh@beraucoal.co.id">
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
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Role <span class="text-rose-500">*</span></label>
                    <select name="role" required id="role-select" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                        <option value="">Pilih Role</option>
                        <option value="trainee" <?php echo e(old('role') === 'trainee' ? 'selected' : ''); ?>>Trainee</option>
                        <option value="trainer" <?php echo e(old('role') === 'trainer' ? 'selected' : ''); ?>>Trainer</option>
                        <option value="admin" <?php echo e(old('role') === 'admin' ? 'selected' : ''); ?>>Admin Training Centre</option>
                    </select>
                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div id="trainer-type-field" class="hidden">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tipe Trainer <span class="text-rose-500">*</span></label>
                    <select name="trainer_type" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#00A859] focus:bg-white transition">
                        <option value="">Pilih Tipe Trainer</option>
                        <option value="instruktur" <?php echo e(old('trainer_type') === 'instruktur' ? 'selected' : ''); ?>>Trainer - Instruktur</option>
                        <option value="pengawas" <?php echo e(old('trainer_type') === 'pengawas' ? 'selected' : ''); ?>>Trainer - Pengawas</option>
                        <option value="operator_pendamping" <?php echo e(old('trainer_type') === 'operator_pendamping' ? 'selected' : ''); ?>>Trainer - Operator Pendamping</option>
                    </select>
                    <?php $__errorArgs = ['trainer_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-rose-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div id="trainer-assignments" class="hidden md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Assign Trainer untuk Trainee</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php $__currentLoopData = ['instruktur' => 'Instruktur', 'pengawas' => 'Pengawas', 'operator_pendamping' => 'Operator Pendamping']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-slate-200 rounded-lg flex flex-col h-full">
                                <div class="px-3 py-2 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wide"><?php echo e($label); ?></span>
                                    <span class="text-[10px] text-slate-400 trainer-count" data-target="<?php echo e($type); ?>-options">0 terpilih</span>
                                </div>
                                <div class="p-2 flex-1 flex flex-col">
                                    <input type="text" placeholder="Cari <?php echo e(strtolower($label)); ?>..." class="trainer-search mb-2 w-full px-2 py-1 bg-white border border-slate-200 rounded text-xs focus:ring-2 focus:ring-[#00A859] focus:border-transparent" data-target="<?php echo e($type); ?>-options">
                                    <div class="flex-1 overflow-y-auto space-y-0.5" id="<?php echo e($type); ?>-options">
                                        <?php $__currentLoopData = $trainers->get($type, []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <label class="flex items-center gap-2 px-2 py-1.5 rounded hover:bg-slate-50 cursor-pointer transition">
                                                <input type="checkbox" name="assigned_trainers[<?php echo e($type); ?>][]" value="<?php echo e($tr->id); ?>" class="h-3.5 w-3.5 rounded border-slate-300 text-[#003829] focus:ring-[#00A859]">
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs text-slate-700 truncate"><?php echo e($tr->name); ?></p>
                                                    <p class="text-[10px] text-slate-400"><?php echo e($tr->sid); ?></p>
                                                </div>
                                            </label>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-4">
                <p class="text-xs text-amber-900 font-bold">Password default akan di-set menjadi <span class="font-black">password</span></p>
                <p class="text-[11px] text-amber-700 mt-1">Pengguna wajib mengganti password sendiri di menu My Profile setelah login pertama kali.</p>
            </div>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end space-x-3">
            <a href="<?php echo e(route('training-centre.users.index')); ?>" class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-600 rounded-xl text-xs font-bold transition border border-slate-200">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-[#00A859] hover:bg-emerald-600 text-white font-bold text-xs rounded-xl shadow-md transition">Simpan Pengguna</button>
        </div>
    </form>

    <script>
        const roleSelect = document.getElementById('role-select');
        const trainerTypeField = document.getElementById('trainer-type-field');
        const trainerAssignments = document.getElementById('trainer-assignments');

        function toggleFields() {
            if (roleSelect.value === 'trainer') {
                trainerTypeField.classList.remove('hidden');
                trainerAssignments.classList.add('hidden');
            } else if (roleSelect.value === 'trainee') {
                trainerTypeField.classList.add('hidden');
                trainerAssignments.classList.remove('hidden');
            } else {
                trainerTypeField.classList.add('hidden');
                trainerAssignments.classList.add('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleFields);
        toggleFields();

        function updateTrainerCount(targetId) {
            const container = document.getElementById(targetId);
            const checkboxes = container.querySelectorAll('input[type="checkbox"]');
            const checked = container.querySelectorAll('input[type="checkbox"]:checked');
            const countLabel = document.querySelector(`.trainer-count[data-target="${targetId}"]`);
            if (countLabel) {
                countLabel.textContent = checked.length + ' terpilih';
            }
        }

        document.querySelectorAll('.trainer-search').forEach(function(input) {
            input.addEventListener('input', function() {
                const targetId = this.dataset.target;
                const container = document.getElementById(targetId);
                const filter = this.value.toLowerCase();
                const items = container.querySelectorAll('label');
                items.forEach(function(item) {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(filter) ? '' : 'none';
                });
            });
        });

        document.querySelectorAll('#trainer-assignments input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const container = this.closest('[id$="-options"]') || this.closest('.space-y-1');
                if (container) {
                    updateTrainerCount(container.id);
                }
            });
        });

        document.querySelectorAll('[id$="-options"]').forEach(function(container) {
            updateTrainerCount(container.id);
        });
    </script>
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
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/training-centre/users/create.blade.php ENDPATH**/ ?>