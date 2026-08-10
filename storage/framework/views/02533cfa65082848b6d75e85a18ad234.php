<?php ($operator = auth()->user()); ?>
<section class="mt-8 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ action: '<?php echo e(old('action', 'approve')); ?>' }">
    <div class="bg-[#003829] px-6 py-4 text-white">
        <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-300">Keputusan Pengawas</p>
        <h2 class="mt-1 text-sm font-bold">Approval atau Permintaan Revisi</h2>
    </div>

    <?php if($isPending): ?>
        <form method="POST" action="<?php echo e(route('department-operation.approvals.decide', $logbook->id)); ?>" class="p-6 space-y-5">
            <?php echo csrf_field(); ?>
            <div class="grid gap-5">
                <label class="block rounded-xl border border-emerald-200 bg-emerald-50 p-5 cursor-pointer">
                    <span class="flex items-center gap-2 text-xs font-bold text-[#00593E]"><input type="radio" name="action" value="approve" x-model="action"> Setujui Logbook</span>
                    <span class="mt-2 block text-[11px] leading-relaxed text-emerald-800">Tanda tangan profil akan dipakai otomatis saat approve.</span>
                    <?php if($operator?->signature_path): ?>
                        <img src="<?php echo e(asset('storage/'.$operator->signature_path)); ?>" alt="Signature profile" class="mt-4 max-h-20 w-auto object-contain bg-white rounded-lg border border-emerald-200 p-2">
                        <span class="mt-2 block text-[10px] text-slate-500">Signature tersimpan di profil dan tidak perlu diunggah lagi.</span>
                    <?php else: ?>
                        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-3 text-[11px] text-amber-900">
                            Simpan tanda tangan dulu di <a href="<?php echo e(route('profile.edit')); ?>" class="font-bold underline">My Profile</a> sebelum approve.
                        </div>
                    <?php endif; ?>
                </label>

                <label class="flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 p-5 cursor-pointer">
                    <input type="radio" name="action" value="revision" x-model="action" class="accent-amber-600">
                    <span class="text-xs font-bold text-amber-900">Kembalikan untuk Revisi</span>
                </label>
            </div>

            <div x-show="action === 'revision'" x-cloak class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                <label for="approval_notes_revision" class="text-xs font-bold text-amber-900">Instruksi Revisi <span class="font-normal text-amber-700">(wajib diisi saat meminta revisi)</span></label>
                <textarea id="approval_notes_revision" name="approval_notes" rows="4" class="mt-2 w-full rounded-xl border-amber-300 bg-white text-xs" placeholder="Jelaskan bagian yang harus diperbaiki trainee..."><?php echo e(old('approval_notes')); ?></textarea>
            </div>

            <div>
                <label for="approval_notes_extra" class="text-xs font-bold text-slate-700">Catatan tambahan Pengawas <span class="font-normal text-slate-400">(opsional untuk persetujuan)</span></label>
                <textarea id="approval_notes_extra" name="approval_notes" rows="3" class="mt-2 w-full rounded-xl border-slate-300 text-xs" placeholder="Masukkan catatan tambahan untuk trainee..." :disabled="action === 'revision'"><?php echo e(old('approval_notes')); ?></textarea>
            </div>

            <?php if($errors->any()): ?><p class="text-xs text-rose-600"><?php echo e($errors->first()); ?></p><?php endif; ?>
            <div class="flex justify-end"><button class="rounded-xl bg-[#00A859] px-5 py-3 text-xs font-bold text-white hover:bg-emerald-600">Simpan Keputusan</button></div>
        </form>
    <?php else: ?>
        <div class="p-6"><div class="rounded-xl border <?php echo e(in_array($logbook->status, ['approved', 'supervisor_approved', 'final_approved'], true) ? 'border-emerald-200 bg-emerald-50 text-emerald-900' : 'border-amber-200 bg-amber-50 text-amber-900'); ?> p-4 text-xs"><p class="font-bold">Keputusan Pengawas: <?php echo e(in_array($logbook->status, ['approved', 'supervisor_approved', 'final_approved'], true) ? 'Disetujui' : 'Dikembalikan untuk revisi'); ?></p><p class="mt-2 whitespace-pre-line"><?php echo e($logbook->pjo_notes ?: 'Tidak ada catatan keputusan.'); ?></p></div></div>
    <?php endif; ?>
</section>
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/department-operation/approvals/partials/decision-form.blade.php ENDPATH**/ ?>