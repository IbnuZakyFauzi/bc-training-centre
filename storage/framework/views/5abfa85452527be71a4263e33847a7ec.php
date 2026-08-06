<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $classes = match(strtolower($status)) {
        'draft' => 'bg-slate-100 text-slate-700 border-slate-300',
        'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
        'revision' => 'bg-amber-50 text-amber-800 border-amber-300 font-bold',
        'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-300',
        'approved', 'supervisor_approved' => 'bg-teal-50 text-teal-800 border-teal-300',
        'final_approved' => 'bg-[#003829] text-white border-[#00241A]',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };

    $label = match(strtolower($status)) {
        'draft' => 'Draft',
        'submitted' => 'Submitted / Menunggu',
        'revision' => 'Perlu Revisi',
        'verified' => 'Terverifikasi Trainer',
        'approved', 'supervisor_approved' => 'Disetujui Pengawas',
        'final_approved' => 'Final Approved TC',
        default => ucfirst($status),
    };
?>

<span <?php echo e($attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {$classes}"])); ?>>
    <span class="w-1.5 h-1.5 rounded-full mr-1.5 <?php echo e(strtolower($status) === 'revision' ? 'bg-amber-500 animate-ping' : (in_array(strtolower($status), ['approved', 'supervisor_approved', 'final_approved'], true) ? 'bg-emerald-400' : 'bg-current')); ?>"></span>
    <?php echo e($label); ?>

</span>
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/components/badge.blade.php ENDPATH**/ ?>