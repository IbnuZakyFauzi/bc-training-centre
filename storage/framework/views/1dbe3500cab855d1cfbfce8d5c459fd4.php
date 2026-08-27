
<?php
    if (isset($scaleInputName)) {
        $scaleName = $scaleInputName;
        $scaleRaw = $scaleValueRaw ?? null;
    } else {
        $scaleName = 'sop_payload[' . implode('][', explode('.', $itemPath)) . '][status]';
        $scaleRaw = old('sop_payload.' . $itemPath . '.status', data_get($formPayload ?? [], $itemPath . '.status'));
    }

    $scaleCurrent = \App\Support\CompetencyScale::toScale($scaleRaw);
    $scaleOptions = \App\Support\CompetencyScale::options();
    $scaleIsRequired = (bool) ($scaleRequired ?? false);
    $scaleInitial = $scaleCurrent ?: 'null';
?>
<div x-data="{ value: <?php echo e($scaleInitial); ?>, toggle(v) { this.value = (this.value == v) ? null : v; } }"
     class="flex items-stretch gap-1" role="radiogroup" aria-label="Penilaian item evaluasi (1-4)">
    <?php $__currentLoopData = $scaleOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scaleValue => $scaleLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <label class="group relative flex-1 cursor-pointer select-none" title="<?php echo e($scaleValue); ?> - <?php echo e($scaleLabel); ?>">
            <input type="radio" name="<?php echo e($scaleName); ?>" value="<?php echo e($scaleValue); ?>" <?php if($scaleIsRequired): echo 'required'; endif; ?>
                   :value="<?php echo e($scaleValue); ?>" :checked="value == <?php echo e($scaleValue); ?>" @click="toggle(<?php echo e($scaleValue); ?>)"
                   class="peer absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0">
            <span class="pointer-events-none flex h-9 sm:h-10 w-full flex-col items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition group-hover:border-slate-300 group-hover:bg-slate-50 peer-checked:border-transparent peer-checked:bg-blue-600 peer-checked:text-white peer-checked:shadow-sm peer-focus-visible:ring-2 peer-focus-visible:ring-blue-300 peer-focus-visible:ring-offset-1">
                <span class="text-[11px] font-black leading-none"><?php echo e($scaleValue); ?></span>
                <span class="mt-0.5 text-[8px] font-bold uppercase leading-none tracking-tight"><?php echo e($scaleLabel); ?></span>
            </span>
        </label>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/ojt/logbooks/partials/scale-cell.blade.php ENDPATH**/ ?>