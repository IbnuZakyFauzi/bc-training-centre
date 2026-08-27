    <?php
        $payload = $logbook->sop_payload ?? [];
        $categoryCode = $logbook->equipmentCategory->code ?? 'DZ';
        $categoryName = $logbook->equipmentCategory->name ?? 'Bulldozer & Motor Grader';
        $family = data_get($payload, 'meta.unit_family');
        if (!$family) {
            $family = in_array($categoryCode, ['DZ', 'MG']) ? 'track' : (in_array($categoryCode, ['EXC', 'EX']) ? 'excavator' : (in_array($categoryCode, ['HDT', 'LDT']) ? 'dumptruck' : (in_array($categoryCode, ['SDT', 'ADT']) ? 'semidump' : ($categoryCode === 'WL' ? 'wheelloader' : 'track'))));
        }

        $checklist = $payload[$family] ?? [];
        $certification = data_get($payload, 'meta.certification') ?: ($logbook->trainee->certification ?? 'Green');
        $company = data_get($payload, 'meta.company') ?: ($logbook->trainee->company ?? 'PT BERAU COAL / PT MTL');
        $stickerExp = data_get($payload, 'meta.sticker_expired_at') ?: ($logbook->trainee->sticker_expired_at ?: null);
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

        $definition = \App\Support\ChecklistTemplate::structure($family);
        $stored = $payload[$family] ?? [];
        $groups = [];
        $complianceItems = [];
        $disciplineItems = [];

        if ($definition) {
            $groups = $definition['groups'] ?? [];
            $complianceItems = $definition['compliance'] ?? [];
            $disciplineItems = $definition['behavior'] ?? [];

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
                            $groups[$gi]['items'][$ii]['status'] = $si['status'] ?? null;
                            $groups[$gi]['items'][$ii]['trainee_feedback']
                                = $si['trainee_feedback'] ?? $si['note'] ?? null;
                        }
                    }
                } else {
                    foreach ($definition[$section] as $ii => $item) {
                        $si = $storedSection[$ii] ?? [];
                        if ($section === 'compliance') {
                            $complianceItems[$ii]['status'] = $si['status'] ?? null;
                            $complianceItems[$ii]['trainee_feedback']
                                = $si['trainee_feedback'] ?? $si['note'] ?? null;
                        } else {
                            $disciplineItems[$ii]['status'] = $si['status'] ?? null;
                            $disciplineItems[$ii]['trainee_feedback']
                                = $si['trainee_feedback'] ?? $si['note'] ?? null;
                        }
                    }
                }
            }
        }
    ?>

    <div class="form-container">

        <!-- Official Header -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <div style="text-align: center; padding: 1px 2px;">
                        <img src="<?php echo e(asset('images/berau_coal_logo.png')); ?>" alt="Berau Coal Logo" class="logo-img">
                    </div>
                </td>
                <td>
                    <div class="title-main">BERAU COAL GREEN MINING SYSTEM</div>
                    <div class="title-sub">FORMULIR</div>
                     <div class="title-desc">Pelaksanaan On Job Training (OJT) Unit <?php echo e(in_array($categoryCode, ['HDT', 'LDT']) ? 'Heavy Dump Truck (HDT) / Light Dump Truck (LDT)' : (in_array($categoryCode, ['SDT', 'ADT']) ? 'Semi Dump Trailer (SDT) / Articulated Dump Truck (ADT)' : ($categoryCode === 'WL' ? 'Wheel Loader (WL)' : ($categoryName . ' (' . $categoryCode . ')')))); ?></div>
                </td>
            </tr>
        </table>

        <!-- Metadata Grid -->
        <table class="meta-table">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td class="meta-label">NAMA</td><td>: <?php echo e($logbook->trainee->name ?? 'Belum Ditunjuk'); ?></td></tr>
                        <tr><td class="meta-label">HARI/ TANGGAL</td><td>: <?php echo e(\Carbon\Carbon::parse($logbook->date)->translatedFormat('l, d F Y')); ?></td></tr>
                        <tr><td class="meta-label">SHIFT</td><td>: Shift <?php echo e(ucfirst($logbook->shift)); ?> (<?php echo e($logbook->shift === 'day' ? 'Siang' : 'Malam'); ?>)</td></tr>
                        <tr><td class="meta-label">LOKASI (OJT)</td><td>: <?php echo e($logbook->location); ?></td></tr>
                        <tr><td class="meta-label">SERTIFIKASI</td><td>: 
                            <span style="<?php echo e($certification === 'Green' ? 'font-weight:bold; text-decoration: underline;' : 'color: #888;'); ?>">Green</span> / 
                            <span style="<?php echo e($certification === 'Skill-up' ? 'font-weight:bold; text-decoration: underline;' : 'color: #888;'); ?>">Skill-up</span> / 
                            <span style="<?php echo e($certification === 'Experience' ? 'font-weight:bold; text-decoration: underline;' : 'color: #888;'); ?>">Experience</span> 
                            <span style="font-size: 7.5px; font-style: italic;">(Coret yang tidak sesuai)</span>
                        </td></tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td class="meta-label">PERUSAHAAN</td><td>: <?php echo e($company); ?></td></tr>
                         <tr><td class="meta-label">TIPE ALAT</td><td>: <?php echo e($categoryName); ?> (<?php echo e($categoryCode); ?>)</td></tr>
                         <?php if(in_array($family, ['track', 'dumptruck', 'semidump'], true)): ?>
                             <?php
                                 $unitType = data_get($payload, 'meta.unit_type', $family === 'track' ? 'DZ' : ($family === 'semidump' ? 'SDT' : 'HDT'));
                              $unitTypeLabel = $family === 'track' ? ($unitType === 'GR' ? 'GR (Motor Grader)' : 'DZ (Bulldozer)') : ($family === 'semidump' ? ($unitType === 'ADT' ? 'ADT (Articulated Dump Truck)' : 'SDT (Semi Dump Trailer)') : ($unitType === 'LDT' ? 'LDT (Light Dump Truck)' : 'HDT (Heavy Dump Truck)'));
                          ?>
                         <tr><td class="meta-label">UNIT TYPE</td><td>: <?php echo e($unitTypeLabel); ?></td></tr>
                         <?php endif; ?>
                        <tr><td class="meta-label">NO ALAT</td><td>: <?php echo e($logbook->unit_code); ?></td></tr>
                         <tr><td class="meta-label">HM/ KM AWAL</td><td>: <?php echo e(number_format($logbook->hm_start, 1)); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>HM/ KM AKHIR:</b> <?php echo e(number_format($logbook->hm_end, 1)); ?></td></tr>
                          <tr><td class="meta-label">EXPIRED DATE STIKER (SKO)</td><td>: <?php echo e($stickerExp ? \Carbon\Carbon::parse($stickerExp)->format('d/m/Y') : '......................20....'); ?></td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;" class="keterangan-box">
                    <b>Keterangan:</b><br>
                    1. Beri tanda "✓" pada kolom yang sesuai<br>
                    2. Kolom "Catatan Penguji" memuat penjelasan item evaluasi terkait<br>
                    3. (K) Kompeten, (BK) Belum Kompeten<br>
                    4. Knw: Knowledge, Skl: Skill, Att: Attitude<br>
                    5. (*) Coret yang tidak sesuai
                </td>
                <td style="width: 50%;" class="checkbox-box">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%; vertical-align: top;">
                                <b>Tahap Penilaian OJT</b><br>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentMode === 'pendampingan' ? '✓' : '&nbsp;'; ?></span> Pendampingan</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentMode === 'tanpa_pendampingan' ? '✓' : '&nbsp;'; ?></span> Tanpa Pendampingan</div>
                            </td>
                            <td style="width: 25%; vertical-align: top;">
                                <b>Tahap Tanpa Pendampingan Lanjutan</b><br>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentStage === 'bulanan' ? '✓' : '&nbsp;'; ?></span> Bulanan</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentStage === '3_bulan_pertama' ? '✓' : '&nbsp;'; ?></span> 3 Bulan Pertama</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentStage === '3_bulan_kedua' ? '✓' : '&nbsp;'; ?></span> 3 Bulan Kedua</div>
                            </td>
                            <td style="width: 25%; vertical-align: top;">
                                <b>Keterangan</b><br>
                                <?php
                                    $detailValue = $assessmentStageDetail ?: $assessmentStage;
                                ?>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_1' ? '✓' : '&nbsp;'; ?></span> Bulan ke-1</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_2' ? '✓' : '&nbsp;'; ?></span> Bulan ke-2</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_3' ? '✓' : '&nbsp;'; ?></span> Bulan ke-3</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_4' ? '✓' : '&nbsp;'; ?></span> Bulan ke-4</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_5' ? '✓' : '&nbsp;'; ?></span> Bulan ke-5</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $detailValue === 'bulan_6' ? '✓' : '&nbsp;'; ?></span> Bulan ke-6</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Banner Code -->
        <div class="unit-banner">
            UNIT*: <?php echo e($categoryCode); ?>

        </div>

        
        <!-- Main Evaluation Table -->
        <table class="eval-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-aspek">Tipe</th>
                    <th class="col-item">Item Evaluasi</th>
                    <th class="col-kbk">K</th>
                    <th class="col-kbk">BK</th>
                    <th class="col-catatan">Catatan Penguji</th>
                </tr>
            </thead>
            <tbody>
                <!-- SECTION A -->
                <tr>
                    <td class="col-no">A</td>
                    <td colspan="5" class="section-header">Teknik Pengoperasian</td>
                </tr>
                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupIndex => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($group['title'])): ?>
                        <tr>
                            <td class="col-no"><?php echo e($groupIndex + 1); ?></td>
                            <td colspan="5" class="sub-header"><?php echo e($group['title']); ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php $__currentLoopData = $group['items'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $status = $item['status'] ?? 'K';
                            $note = trim((string)($item['trainee_feedback'] ?? $item['note'] ?? ''));
                        ?>
                        <tr>
                            <td class="col-no"><?php echo e($item['code'] ?? ($groupIndex + 1).'.'.($itemIndex + 1)); ?></td>
                            <td class="col-aspek"><?php echo e($item['kind'] ?? 'Skl'); ?></td>
                            <td class="col-item"><?php echo e($item['label'] ?? ''); ?></td>
                            <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isKompeten($status)) ? '✓' : ''; ?></td>
                            <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isBelumKompeten($status)) ? '✓' : ''; ?></td>
                            <td class="col-catatan"><?php echo e($item['trainee_feedback'] ?? $item['note'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- SECTION B -->
                <tr>
                    <td class="col-no">B</td>
                    <td colspan="5" class="section-header">Kepatuhan Terhadap Peraturan Kerja</td>
                </tr>
                <?php $__currentLoopData = $complianceItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $status = $item['status'] ?? 'K';
                        $note = trim((string)($item['trainee_feedback'] ?? $item['note'] ?? ''));
                    ?>
                    <tr>
                        <td class="col-no"><?php echo e($item['code']); ?></td>
                        <td class="col-aspek"><?php echo e($item['kind']); ?></td>
                        <td class="col-item"><?php echo e($item['label']); ?></td>
                        <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isKompeten($status)) ? '✓' : ''; ?></td>
                        <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isBelumKompeten($status)) ? '✓' : ''; ?></td>
                        <td class="col-catatan"><?php echo e($item['trainee_feedback'] ?? $item['note'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <!-- SECTION C -->
                <tr>
                    <td class="col-no">C</td>
                    <td colspan="5" class="section-header">Kedisiplinan dan Komunikasi</td>
                </tr>
                <?php $__currentLoopData = $disciplineItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemIndex => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $status = $item['status'] ?? 'K';
                        $note = trim((string)($item['trainee_feedback'] ?? $item['note'] ?? ''));
                    ?>
                    <tr>
                        <td class="col-no"><?php echo e($item['code']); ?></td>
                        <td class="col-aspek"><?php echo e($item['kind']); ?></td>
                        <td class="col-item"><?php echo e($item['label']); ?></td>
                        <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isKompeten($status)) ? '✓' : ''; ?></td>
                        <td class="col-kbk"><?php echo (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isBelumKompeten($status)) ? '✓' : ''; ?></td>
                        <td class="col-catatan"><?php echo e($item['trainee_feedback'] ?? $item['note'] ?? ''); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <!-- Declaration Statement -->
        <div class="statement-box">
            Pengisian form Pelaksanaan OJT ini saya lakukan dengan benar dan item Evaluasi yang saya beri rekomendasi (K) / (BK) dapat saya pertanggungjawabkan.
        </div>

        <!-- Instructor Notes & Conclusion Box -->
        <table class="footer-grid">
            <tr>
                <td style="width: 65%;">
                    <div style="font-weight: bold; text-transform: uppercase; margin-bottom: 4px;">Catatan Instruktur:</div>
                    <div style="font-size: 9.5px; line-height: 1.5; min-height: 60px; white-space: pre-line;">
<?php echo e($logbook->evaluation?->trainer_comment ?? ''); ?>

                    </div>
                </td>
                <td style="width: 35%;">
                    <div class="conclusion-title">KESIMPULAN</div>
                    <?php
                        $hasAnyBk = false;

                        foreach ($groups as $group) {
                            foreach ($group['items'] ?? [] as $item) {
                                if (\App\Support\CompetencyScale::isBelumKompeten($item['status'] ?? 'K')) {
                                    $hasAnyBk = true;
                                    break 2;
                                }
                            }
                        }

                        if (!$hasAnyBk) {
                            foreach ($complianceItems as $item) {
                                if (\App\Support\CompetencyScale::isBelumKompeten($item['status'] ?? 'K')) {
                                    $hasAnyBk = true;
                                    break;
                                }
                            }
                        }

                        if (!$hasAnyBk) {
                            foreach ($disciplineItems as $item) {
                                if (\App\Support\CompetencyScale::isBelumKompeten($item['status'] ?? 'K')) {
                                    $hasAnyBk = true;
                                    break;
                                }
                            }
                        }

                        $storedStatus = $logbook->evaluation?->competency_status;
                        if ($storedStatus === 'not_yet_competent') {
                            $hasAnyBk = true;
                        }

                        $isKompeten = !$hasAnyBk;
                    ?>
                    <div style="margin-bottom: 4px; font-weight: bold; font-size: 10px;">
                        <span class="checkbox-rect"><?php echo $isKompeten ? '✓' : '&nbsp;'; ?></span> &nbsp;KOMPETEN (K)
                    </div>
                    <div style="margin-bottom: 6px; font-weight: bold; font-size: 10px;">
                        <span class="checkbox-rect"><?php echo !$isKompeten ? '✓' : '&nbsp;'; ?></span> &nbsp;BELUM KOMPETEN (BK)
                    </div>
                    <div style="font-size: 7.5px; font-weight: bold; border-top: 1px solid #000; padding-top: 3px; line-height: 1.2;">
                        NOTE:<br>
                        WAJIB SEMUA ITEM EVALUASI (K), ADA YANG (BK), BERARTI SECARA KESIMPULAN (BK)
                    </div>
                </td>
            </tr>
        </table>

        <!-- Signatures Grid -->
        <table class="sig-grid">
            <tr>
                <td colspan="2" class="sig-header">Tanda Tangan</td>
                <td class="sig-header">Disetujui</td>
            </tr>
            <tr>
                <td style="width: 33.33%;" class="sig-title">Peserta/ Trainee</td>
                <td style="width: 33.33%;" class="sig-title">
                    <?php
                        $approverTitle = 'Instruktur/ Pengawas/ Operator Pendamping';
                        $approverName = $logbook->trainer->name ?? '-';
                        $approverSid = $logbook->trainer->sid ?? '-';
                        $approverSig = null;

                        if ($logbook->evaluation?->trainer_signature_path) {
                            $approver = $logbook->evaluation->trainer;
                            $approverTitle = match($approver->trainer_type) {
                                'pengawas' => 'Pengawas',
                                'operator_pendamping' => 'Operator Pendamping',
                                default => 'Instruktur',
                            };
                            $approverName = $approver->name;
                            $approverSid = $approver->sid;
                            $approverSig = $logbook->evaluation->trainer_signature_path;
                        } elseif ($logbook->pjo_signature_path) {
                            $approverTitle = 'Pengawas';
                            $approverName = $logbook->pengawasTrainer->name ?? $approverName;
                            $approverSid = $logbook->pengawasTrainer->sid ?? $approverSid;
                            $approverSig = $logbook->pjo_signature_path;
                        }
                    ?>
                    <?php echo e($approverTitle); ?>

                </td>
                <td style="width: 33.33%;" class="sig-title">Kabag OTDI/ LC</td>
            </tr>
            <tr>
                <td>
                    <div class="sig-space">
                        <?php if($logbook->trainee?->signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->trainee->signature_path)); ?>" alt="Trainee signature" class="sig-img">
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?php echo e($logbook->trainee->name ?? 'Belum Ditunjuk'); ?></div>
                    <div class="sig-sid">No. SID : <?php echo e($logbook->trainee->sid ?? '-'); ?></div>
                </td>
                <td>
                    <div class="sig-space">
                        <?php if($approverSig ?? null): ?>
                            <img src="<?php echo e(asset('storage/'.$approverSig)); ?>" alt="Approver signature" class="sig-img">
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?php echo e($approverName); ?></div>
                    <div class="sig-sid">No. SID : <?php echo e($approverSid); ?></div>
                </td>
                <td>
                    <div class="sig-space">
                        <?php if($logbook->training_centre_signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->training_centre_signature_path)); ?>" alt="Kabag signature" class="sig-img">
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?php echo e($logbook->trainingCentre->name ?? 'Kabag Training Centre'); ?></div>
                    <div class="sig-sid">No. SID : <?php echo e($logbook->trainingCentre->sid ?? '-'); ?></div>
                </td>
            </tr>
        </table>

    </div>


<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/ojt/logbooks/partials/print-body.blade.php ENDPATH**/ ?>