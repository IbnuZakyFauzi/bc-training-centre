
<?php
    $kbkStatus = \App\Support\CompetencyScale::toStatus($status ?? null);
    $kbkScale = \App\Support\CompetencyScale::toScale($status ?? null);
    $kbkLabel = \App\Support\CompetencyScale::label($status ?? null);
    $kbkTitle = $kbkScale ? 'Nilai trainee: ' . $kbkScale . ' (' . $kbkLabel . ')' : 'Belum dinilai';
?>
<td class="px-3 py-3 text-center"><?php if($kbkStatus === \App\Support\CompetencyScale::STATUS_KOMPETEN): ?><span title="<?php echo e($kbkTitle); ?>" class="inline-flex h-5 w-5 items-center justify-center rounded border border-amber-300 bg-amber-50 text-[11px] font-black text-amber-600">&#10003;</span><?php else: ?><span class="inline-flex h-5 w-5 rounded border border-slate-200 bg-slate-50"></span><?php endif; ?></td>
<td class="px-3 py-3 text-center"><?php if($kbkStatus === \App\Support\CompetencyScale::STATUS_BELUM_KOMPETEN): ?><span title="<?php echo e($kbkTitle); ?>" class="inline-flex h-5 w-5 items-center justify-center rounded border border-rose-300 bg-rose-50 text-[11px] font-black text-rose-600">&#10003;</span><?php else: ?><span class="inline-flex h-5 w-5 rounded border border-slate-200 bg-slate-50"></span><?php endif; ?></td>


<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/ojt/logbooks/partials/kbk-readonly.blade.php ENDPATH**/ ?>