<?php
    $payload = $logbook->sop_payload ?? [];
    $categoryCode = $logbook->equipmentCategory->code ?? '';

    $familyMap = [
        'DZ' => 'track', 'MG' => 'track',
        'EXC' => 'excavator',
        'HDT' => 'dumptruck', 'LDT' => 'dumptruck',
        'SDT' => 'semidump', 'ADT' => 'semidump',
        'WL' => 'wheelloader',
    ];
    $family = data_get($payload, 'meta.unit_family');
    if (empty($family) || !isset($payload[$family]) || empty($payload[$family])) {
        $family = $familyMap[$categoryCode] ?? '';
    }

    $definition = \App\Support\ChecklistTemplate::structure($family);
    $stored = $payload[$family] ?? [];
    $checklist = $definition;

    if ($definition) {
        foreach (['groups', 'compliance', 'behavior'] as $section) {
            if (!isset($definition[$section])) {
                continue;
            }
            $storedSection = data_get($stored, $section, []);

            if ($section === 'groups') {
                foreach ($definition['groups'] as $gi => $group) {
                    $storedGroup = $storedSection[$gi] ?? [];
                    $storedItems = $storedGroup['items'] ?? [];
                    foreach ($group['items'] as $ii => $item) {
                        $si = $storedItems[$ii] ?? [];
                        $checklist['groups'][$gi]['items'][$ii]['status'] = $si['status'] ?? null;
                        $checklist['groups'][$gi]['items'][$ii]['trainee_feedback']
                            = $si['trainee_feedback'] ?? $si['note'] ?? null;
                    }
                }
            } else {
                foreach ($definition[$section] as $ii => $item) {
                    $si = $storedSection[$ii] ?? [];
                    $checklist[$section][$ii]['status'] = $si['status'] ?? null;
                    $checklist[$section][$ii]['trainee_feedback']
                        = $si['trainee_feedback'] ?? $si['note'] ?? null;
                }
            }
        }
    }

    $unitType = data_get($payload, 'meta.unit_type');
    $categoryLabel = match($categoryCode) {
        'DZ' => 'Bulldozer (DZ) / Motor Grader (GR)',
        'HDT' => 'Heavy Dump Truck (HDT) / Light Dump Truck (LDT)',
        'SDT' => 'Semi Dump Trailler (SDT) / Articulated Dump Truck (ADT)',
        'EXC' => 'Heavy Excavator',
        'MG' => 'Motor Grader',
        'WL' => 'Wheel Loader',
        default => $logbook->equipmentCategory->name ?? '-',
    };
    if ($unitType && in_array($categoryCode, ['DZ', 'HDT', 'SDT'])) {
        $categoryLabel .= ' — ' . $unitType;
    }

    $company = data_get($payload, 'meta.company') ?: ($logbook->trainee->company ?? 'PT BERAU COAL / PT MTL');
    $certification = data_get($payload, 'meta.certification') ?: ($logbook->trainee->certification ?? 'Green');
    $stickerExpired = data_get($payload, 'meta.sticker_expired_at') ?: ($logbook->trainee->sticker_expired_at ?: null);
    $assessmentMode = data_get($payload, 'meta.assessment_mode', '');
    $assessmentStage = data_get($payload, 'meta.assessment_stage', '');
    $assessmentStageDetail = data_get($payload, 'meta.assessment_stage_detail', '');

    if (!$assessmentMode || !$assessmentStage) {
        $traineeCert = $logbook->trainee->certification ?? 'Green';
        $traineePhase = $logbook->trainee->current_phase ?? \App\Services\PhaseService::firstPhase($traineeCert);
        $phaseMeta = \App\Services\PhaseService::meta($traineeCert, $traineePhase);

        if (!$assessmentMode) {
            $assessmentMode = ($phaseMeta && ($phaseMeta['type'] ?? '') === 'bulanan') ? 'tanpa_pendampingan' : 'pendampingan';
        }
        if (!$assessmentStage && ($phaseMeta && ($phaseMeta['type'] ?? '') === 'bulanan')) {
            $assessmentStage = 'bulanan';
        }
        if (!$assessmentStageDetail && ($phaseMeta && ($phaseMeta['type'] ?? '') === 'bulanan')) {
            $bulananIndex = (int) str_replace('bulanan_', '', $traineePhase);
            $assessmentStageDetail = 'bulan_' . max(1, min(6, $bulananIndex - 4));
        }
    }

    $assessmentModeLabel = match($assessmentMode) {
        'pendampingan' => 'Pendampingan',
        'tanpa_pendampingan' => 'Tanpa Pendampingan',
        default => '-',
    };

    $assessmentStageLabel = match($assessmentStage) {
        'bulanan' => 'Bulanan',
        '3_bulan_pertama' => '3 Bulan Pertama',
        '3_bulan_kedua' => '3 Bulan Kedua',
        default => '-',
    };

    $assessmentStageDetailLabel = match($assessmentStageDetail) {
        'bulan_1' => 'Bulan ke-1',
        'bulan_2' => 'Bulan ke-2',
        'bulan_3' => 'Bulan ke-3',
        'bulan_4' => 'Bulan ke-4',
        'bulan_5' => 'Bulan ke-5',
        'bulan_6' => 'Bulan ke-6',
        default => '-',
    };
?>

<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <div>
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Form OJT Trainee</h2>
            <p class="text-[11px] text-slate-500 mt-1">Tampilan read-only sesuai tipe alat: <?php echo e($logbook->equipmentCategory->name ?? '-'); ?></p>
            <p class="text-[10px] text-slate-400 mt-1">Nilai skala trainee dikonversi otomatis: 1 (Belum) &amp; 2 (Cukup) = <span class="font-bold text-rose-600">BK</span>, 3 (Mampu) &amp; 4 (Mahir) = <span class="font-bold text-amber-600">K</span>.</p>
        </div>
        <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200"><?php echo e(strtoupper($family ?: 'N/A')); ?></span>
    </div>

    <div class="rounded-2xl border-2 border-slate-900 bg-white overflow-hidden shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r">
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NAMA</div>
                    <div class="px-3 py-1.5 border-b border-slate-900"><?php echo e($logbook->trainee->name); ?></div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HARI/ TANGGAL</div>
                    <div class="px-3 py-1.5 border-b border-slate-900"><?php echo e($logbook->date->format('d/m/Y')); ?></div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">SHIFT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">Shift <?php echo e($logbook->shift === 'day' ? 'Siang' : 'Malam'); ?></div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">LOKASI (OJT)</div>
                    <div class="px-3 py-1.5 border-b border-slate-900"><?php echo e($logbook->location); ?></div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">SERTIFIKASI</div>
                    <div class="px-3 py-1.5 border-b border-slate-900"><?php echo e($certification); ?></div>
                </div>
            </div>

            <div>
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">PERUSAHAAN</div>
                    <div class="px-3 py-1.5 border-b border-slate-900"><?php echo e($company); ?></div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">TIPE ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900"><?php echo e($categoryLabel); ?></div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NO ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900"><?php echo e($logbook->equipment_number); ?></div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HM/ KM AWAL</div>
                    <div class="px-3 py-1 border-b border-slate-900"><?php echo e(number_format($logbook->hm_start, 1)); ?></div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HM/ KM AKHIR</div>
                    <div class="px-3 py-1 border-b border-slate-900"><?php echo e(number_format($logbook->hm_end, 1)); ?></div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">EXPIRED DATE STIKER (SKO)</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        <?php echo e($stickerExpired ? \Carbon\Carbon::parse($stickerExpired)->format('d M Y') : '-'); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 border-t border-slate-900">
        <div class="border-b border-slate-900 lg:border-b-0 lg:border-r p-2 sm:p-3 text-[10px] sm:text-[11px] text-slate-900">
            <div class="font-semibold mb-1 sm:mb-2">Keterangan:</div>
            <ol class="space-y-1 pl-4 list-decimal">
                <li>Pilih salah satu angka 1 - 4 pada kolom "Penilaian" yang sesuai</li>
                <li>Kolom "Trainee Feedback" memuat penjelasan item evaluasi terkait</li>
                <li>(1) Belum &amp; (2) Cukup = (BK) Belum Kompeten, (3) Mampu &amp; 4) Mahir = (K) Kompeten</li>
                <li>Knw: Knowledge, Skl: Skill, Atd: Attitude</li>
            </ol>
        </div>
        <div class="p-2 sm:p-3 text-[10px] sm:text-[11px]">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <div class="font-semibold mb-2">Tahap Penilaian OJT</div>
                    <div class="font-medium"><?php echo e($assessmentModeLabel); ?></div>
                </div>
                <div>
                    <div class="font-semibold mb-2">Tahap Tanpa Pendampingan Lanjutan</div>
                    <div class="font-medium"><?php echo e($assessmentStageLabel); ?></div>
                    <div class="mt-1 text-slate-600">Keterangan: </div>
                    <div class="font-medium"><?php echo e($assessmentStageDetailLabel); ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="p-5 space-y-5">
    <?php $__empty_1 = true; $__currentLoopData = data_get($checklist, 'groups', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="rounded-xl border border-slate-200 overflow-hidden">
            <div class="bg-[#1e3a8a] px-4 py-3 text-white">
                <p class="text-xs font-bold uppercase"><?php echo e($group['title'] ?? 'Checklist Unit '.($groupIndex + 1)); ?></p>
                <p class="text-[10px] text-blue-100 mt-1"><?php echo e($group['subtitle'] ?? 'Tipe kompetensi sesuai SOP unit'); ?></p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[760px] w-full table-fixed text-xs">
                    <colgroup><col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72"></colgroup>
                    <thead class="bg-slate-50 text-slate-500 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left">No</th>
                            <th class="px-3 py-2 text-left">Tipe</th>
                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                            <th class="px-3 py-2 text-center">K</th>
                            <th class="px-3 py-2 text-center">BK</th>
                            <th class="px-3 py-2 text-left">Trainee Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__currentLoopData = $group['items'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="align-top">
                                <td class="px-3 py-3 font-bold"><?php echo e($item['code'] ?? ($groupIndex + 1).'.'.($itemIndex + 1)); ?></td>
                                <td class="px-3 py-3 text-slate-500"><?php echo e($item['kind'] ?? '-'); ?></td>
                                <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label'] ?? 'Item checklist SOP'); ?></td>
                                <?php echo $__env->make('ojt.logbooks.partials.kbk-readonly', ['status' => $item['status'] ?? null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <td class="px-3 py-3 text-slate-500 leading-relaxed break-words"><?php echo e($item['trainee_feedback'] ?? $item['note'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="p-5 text-xs text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">Checklist detail belum tersedia pada pengajuan ini. Form OJT baru akan menyimpan setiap judul dan item SOP sesuai tipe alatnya.</div>
    <?php endif; ?>

    <?php if(data_get($checklist, 'compliance')): ?>
        <div class="rounded-xl border border-slate-200 overflow-hidden">
            <div class="bg-[#1e3a8a] px-4 py-3 text-white">
                <p class="text-xs font-bold uppercase">Kepatuhan Terhadap Peraturan Kerja</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[760px] w-full table-fixed text-xs">
                    <colgroup><col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72"></colgroup>
                    <thead class="bg-slate-50 text-slate-500 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left">No</th>
                            <th class="px-3 py-2 text-left">Tipe</th>
                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                            <th class="px-3 py-2 text-center">K</th>
                            <th class="px-3 py-2 text-center">BK</th>
                            <th class="px-3 py-2 text-left">Trainee Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__currentLoopData = data_get($checklist, 'compliance', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="align-top">
                                <td class="px-3 py-3 font-bold"><?php echo e($item['code'] ?? ($itemIndex + 1)); ?></td>
                                <td class="px-3 py-3 text-slate-500"><?php echo e($item['kind'] ?? '-'); ?></td>
                                <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label'] ?? 'Item kepatuhan'); ?></td>
                                <?php echo $__env->make('ojt.logbooks.partials.kbk-readonly', ['status' => $item['status'] ?? null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <td class="px-3 py-3 text-slate-500 leading-relaxed break-words"><?php echo e($item['trainee_feedback'] ?? $item['note'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <?php if(data_get($checklist, 'behavior')): ?>
        <div class="rounded-xl border border-slate-200 overflow-hidden">
            <div class="bg-[#1e3a8a] px-4 py-3 text-white">
                <p class="text-xs font-bold uppercase">Kedisiplinan dan Komunikasi</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-[760px] w-full table-fixed text-xs">
                    <colgroup><col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72"></colgroup>
                    <thead class="bg-slate-50 text-slate-500 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left">No</th>
                            <th class="px-3 py-2 text-left">Tipe</th>
                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                            <th class="px-3 py-2 text-center">K</th>
                            <th class="px-3 py-2 text-center">BK</th>
                            <th class="px-3 py-2 text-left">Trainee Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__currentLoopData = data_get($checklist, 'behavior', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="align-top">
                                <td class="px-3 py-3 font-bold"><?php echo e($item['code'] ?? ($itemIndex + 1)); ?></td>
                                <td class="px-3 py-3 text-slate-500"><?php echo e($item['kind'] ?? '-'); ?></td>
                                <td class="px-3 py-3 text-slate-700 leading-relaxed"><?php echo e($item['label'] ?? 'Item kedisiplinan'); ?></td>
                                <?php echo $__env->make('ojt.logbooks.partials.kbk-readonly', ['status' => $item['status'] ?? null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <td class="px-3 py-3 text-slate-500 leading-relaxed break-words"><?php echo e($item['trainee_feedback'] ?? $item['note'] ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/trainer/reviews/partials/submitted-checklist.blade.php ENDPATH**/ ?>