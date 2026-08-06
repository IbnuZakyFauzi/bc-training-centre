<?php
    $payload = $logbook->sop_payload ?? [];
    $family = data_get($payload, 'meta.unit_family');
    $checklist = $payload[$family] ?? [];
    $categoryCode = $logbook->equipmentCategory->code ?? '';
    if (empty($checklist) && in_array($categoryCode, ['DZ', 'MG'])) {
        $family = 'track';
        $checklist = [
            'groups' => [
                ['title' => 'Teknik Pengoperasian', 'subtitle' => 'Dozing & digging untuk Bulldozer / grading & digging untuk Motor Grader', 'items' => [
                    ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan blade pada saat mendorong / grading'], ['code' => '1.2', 'kind' => 'Kwn', 'label' => 'Penggunaan tilt blade'], ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara pengoperasian blade untuk mendorong / ditching'], ['code' => '1.4', 'kind' => 'Skl', 'label' => 'Cara pengoperasian blade untuk menggali / sloping'], ['code' => '1.5', 'kind' => 'Skl', 'label' => 'Penyesuaian beban dengan RPM / posisi transmisi'], ['code' => '1.6', 'kind' => 'Skl', 'label' => 'Teknik dozing / grading / digging'],
                ]],
                ['title' => 'Spreading & Leveling', 'subtitle' => 'Pengoperasian untuk meratakan, memadatkan, dan membentuk area kerja', 'items' => [
                    ['code' => '2.1', 'kind' => 'Kwn', 'label' => 'Penggunaan speed / transmisi saat bergerak'], ['code' => '2.2', 'kind' => 'Kwn', 'label' => 'Cara leveling menggunakan tilt'], ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Cara menghampar material untuk membuat jalan / menimbun lubang'], ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Filling pada saat melewatkan area kerja'], ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Penggunaan steering'], ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Penggunaan articulated (khusus unit GR)'], ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Teknik spreading / leveling'],
                ]],
                ['title' => 'Ripping', 'subtitle' => 'Khusus untuk pekerjaan ripping dan pembukaan material keras', 'items' => [
                    ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan ripper'], ['code' => '3.2', 'kind' => 'Kwn', 'label' => 'Teknik penetrasi ripping'], ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penyesuaian posisi ripper dengan kekerasan material'],
                ]],
                ['title' => 'Finishing', 'subtitle' => 'Finishing grading dan koreksi permukaan kerja', 'items' => [
                    ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Kesesuaian penggunaan speed'], ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Hasil akurasi / kualitas pekerjaan'],
                ]],
            ],
        ];
    }
?>

<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <div><h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Logbook Trainee</h2><p class="text-[11px] text-slate-500 mt-1">Tampilan read-only sesuai tipe alat: <?php echo e($logbook->equipmentCategory->name ?? '-'); ?></p></div>
        <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-[#00593E] border border-emerald-200"><?php echo e(strtoupper($family ?: 'N/A')); ?></span>
    </div>
    <div class="mx-5 mt-5 rounded-xl border border-slate-300 overflow-hidden text-[11px]">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="divide-y divide-slate-200 border-b md:border-b-0 md:border-r"><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">NAMA</span><span class="p-2"><?php echo e($logbook->trainee->name); ?></span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">HARI / TANGGAL</span><span class="p-2"><?php echo e($logbook->date->format('d/m/Y')); ?></span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">SHIFT</span><span class="p-2">Shift <?php echo e($logbook->shift === 'day' ? '1 (Siang: 07.00 - 17.00)' : '2 (Malam: 19.00 - 05.00)'); ?></span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">LOKASI (OJT)</span><span class="p-2"><?php echo e($logbook->location); ?></span></div></div>
            <div class="divide-y divide-slate-200"><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">PERUSAHAAN</span><span class="p-2"><?php echo e(data_get($payload, 'meta.company', 'PT BERAU COAL / PT MTL')); ?></span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">TIPE ALAT</span><span class="p-2"><?php echo e($logbook->equipmentCategory->name ?? '-'); ?> (<?php echo e($categoryCode); ?>)</span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">NO ALAT</span><span class="p-2"><?php echo e($logbook->equipment->unit_code ?? '-'); ?></span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">HM / KM</span><span class="p-2"><?php echo e(number_format($logbook->hm_start, 1)); ?> → <?php echo e(number_format($logbook->hm_end, 1)); ?></span></div></div>
        </div>
    </div>
    <div class="p-5 space-y-5">
        <?php $__empty_1 = true; $__currentLoopData = data_get($checklist, 'groups', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-[#003829] px-4 py-3 text-white"><p class="text-xs font-bold uppercase"><?php echo e($group['title'] ?? 'Checklist Unit '.($groupIndex + 1)); ?></p><p class="text-[10px] text-emerald-100 mt-1"><?php echo e($group['subtitle'] ?? 'Aspek kompetensi sesuai SOP unit'); ?></p></div>
                <div class="overflow-x-auto"><table class="min-w-[760px] w-full table-fixed text-xs"><colgroup><col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72"></colgroup><thead class="bg-slate-50 text-slate-500 uppercase"><tr><th class="px-3 py-2 text-left">No</th><th class="px-3 py-2 text-left">Aspek</th><th class="px-3 py-2 text-left">Item Evaluasi</th><th class="px-3 py-2 text-center">K</th><th class="px-3 py-2 text-center">BK</th><th class="px-3 py-2 text-left">Catatan Penguji</th></tr></thead><tbody class="divide-y divide-slate-100"><?php $__currentLoopData = $group['items'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr class="align-top"><td class="px-3 py-3 font-bold"><?php echo e($item['code'] ?? ($groupIndex + 1).'.'.($itemIndex + 1)); ?></td><td class="px-3 py-3 text-slate-500"><?php echo e($item['kind'] ?? '-'); ?></td><td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label'] ?? 'Item checklist SOP'); ?></td><td class="px-3 py-3 text-center"><?php if(($item['status'] ?? null) === 'K'): ?><span class="text-[#00A859] font-black">✓</span><?php endif; ?></td><td class="px-3 py-3 text-center"><?php if(($item['status'] ?? null) === 'BK'): ?><span class="text-rose-600 font-black">✓</span><?php endif; ?></td><td class="px-3 py-3 text-slate-500 leading-relaxed break-words"><?php echo e($item['note'] ?? '-'); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody></table></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-5 text-xs text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">Checklist detail belum tersedia pada pengajuan ini. Logbook baru akan menyimpan setiap judul dan item SOP sesuai tipe alatnya.</div>
        <?php endif; ?>
        <?php if(data_get($checklist, 'behavior')): ?>
            <div class="rounded-xl border border-slate-200 overflow-hidden"><div class="bg-[#003829] px-4 py-3 text-white"><p class="text-xs font-bold uppercase">Kedisiplinan dan Komunikasi</p></div><div class="overflow-x-auto"><table class="min-w-[760px] w-full table-fixed text-xs"><colgroup><col class="w-14"><col class="w-16"><col><col class="w-28"><col class="w-72"></colgroup><thead class="bg-slate-50 text-slate-500 uppercase"><tr><th class="px-3 py-2 text-left">No</th><th class="px-3 py-2 text-left">Aspek</th><th class="px-3 py-2 text-left">Item Evaluasi</th><th class="px-3 py-2 text-center">Status</th><th class="px-3 py-2 text-left">Catatan Penguji</th></tr></thead><tbody class="divide-y divide-slate-100"><?php $__currentLoopData = data_get($checklist, 'behavior', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><tr class="align-top"><td class="px-3 py-3 font-bold"><?php echo e($item['code'] ?? ($itemIndex + 1)); ?></td><td class="px-3 py-3 text-slate-500"><?php echo e($item['kind'] ?? '-'); ?></td><td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label'] ?? 'Item kedisiplinan'); ?></td><td class="px-3 py-3 text-center font-bold"><?php echo e($item['status'] ?? '-'); ?></td><td class="px-3 py-3 text-slate-500 leading-relaxed break-words"><?php echo e($item['note'] ?? '-'); ?></td></tr><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></tbody></table></div></div>
        <?php endif; ?>
    </div>
</section>
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views\trainer\reviews\partials\submitted-checklist.blade.php ENDPATH**/ ?>