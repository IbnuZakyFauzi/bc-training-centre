<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir OJT - <?php echo e($logbook->logbook_number); ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 5mm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body { background: #fff; width: 100%; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7px;
            color: #000;
            line-height: 1.1;
            padding: 6px;
        }

        .no-print { margin-bottom: 8px; text-align: right; }

        .btn-print {
            background-color: #00A859; color: white; border: none;
            padding: 6px 14px; font-weight: bold; font-size: 11px;
            border-radius: 5px; cursor: pointer;
        }
        .btn-print:hover { background-color: #008f4c; }

        .form-container { border: 1px solid #000; width: 100%; }

        /* Header */
        table.header-table { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; }
        table.header-table td { border: 1px solid #000; padding: 2px 4px; text-align: center; vertical-align: middle; }

        .logo-cell { width: 110px; padding: 2px 3px !important; vertical-align: middle !important; }
        .logo-img  { height: 44px; width: auto; max-width: 105px; display: block; margin: 0 auto; object-fit: contain; }

        .title-main { font-weight: bold; font-size: 7.5px; text-transform: uppercase; }
        .title-sub  { font-weight: bold; font-size: 8.5px; text-transform: uppercase; margin: 1px 0; }
        .title-desc { font-weight: bold; font-size: 7px; }

        /* Metadata */
        table.meta-table { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; font-size: 7px; }
        table.meta-table td { border: 1px solid #000; padding: 1.5px 3px; vertical-align: top; }
        .meta-label { font-weight: bold; width: 88px; }

        .checkbox-box  { font-size: 6.5px; line-height: 1.15; }
        .checkbox-item { display: flex; align-items: center; gap: 2px; margin-bottom: 1px; }
        .checkbox-rect {
            display: inline-block; width: 7.5px; height: 7.5px;
            border: 1px solid #000; text-align: center; line-height: 6.5px;
            font-size: 6px; font-weight: bold; flex-shrink: 0;
        }
        .keterangan-box { font-size: 6.5px; line-height: 1.1; }

        .unit-banner {
            background-color: #e5e7eb; font-weight: bold; font-size: 7px;
            padding: 1.5px 4px; border-bottom: 1px solid #000; border-top: 1px solid #000;
        }

        /* Eval Table */
        table.eval-table { width: 100%; border-collapse: collapse; font-size: 6.8px; }
        table.eval-table th, table.eval-table td {
            border: 1px solid #000; padding: 1px 2px; vertical-align: middle;
        }
        table.eval-table th {
            background-color: #f3f4f6; font-weight: bold;
            text-align: center; text-transform: uppercase; font-size: 6.5px;
        }
        .section-header { background-color: #d1d5db; font-weight: bold; font-size: 7px; padding: 1px 3px; text-transform: uppercase; }
        .sub-header     { background-color: #e5e7eb; font-weight: bold; font-size: 6.5px; padding: 1px 3px; }

        .col-no     { width: 20px; text-align: center; font-weight: bold; }
        .col-aspek  { width: 24px; text-align: center; font-weight: bold; }
        .col-item   { text-align: left; }
        .col-kbk    { width: 16px; text-align: center; font-weight: bold; font-size: 8px; }
        .col-catatan{ width: 150px; text-align: left; }

        /* Statement */
        .statement-box {
            font-size: 6.5px; font-weight: bold; font-style: italic;
            text-align: center; padding: 2px;
            border-top: 1px solid #000; border-bottom: 1px solid #000;
            background-color: #f9fafb;
        }

        /* Footer / Notes */
        table.footer-grid { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; }
        table.footer-grid td { border: 1px solid #000; padding: 2px 3px; vertical-align: top; }
        .conclusion-title { font-weight: bold; font-size: 7px; margin-bottom: 2px; }

        /* Signatures */
        table.sig-grid { width: 100%; border-collapse: collapse; text-align: center; font-size: 7px; }
        table.sig-grid td { border: 1px solid #000; padding: 2px; vertical-align: bottom; }
        .sig-header { background-color: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 7px; padding: 2px !important; }
        .sig-title  { background-color: #f9fafb; font-weight: bold; font-size: 6.5px; padding: 2px !important; }
        .sig-space  { height: 34px; display: flex; align-items: center; justify-content: center; }
        .sig-img    { max-height: 30px; max-width: 85px; object-fit: contain; }
        .sig-name   { font-weight: bold; text-decoration: underline; font-size: 7px; }
        .sig-sid    { font-size: 6.5px; }

        @media print {
            @page { size: A4 portrait; margin: 5mm; }
            html, body { width: 100%; margin: 0; padding: 0; background: #fff; }
            .no-print { display: none !important; }
            .form-container { border: 1px solid #000 !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Cetak / Download PDF
        </button>
    </div>

    <?php
        $payload = $logbook->sop_payload ?? [];
        $categoryCode = $logbook->equipmentCategory->code ?? 'DZ';
        $categoryName = $logbook->equipmentCategory->name ?? 'Bulldozer & Motor Grader';
        $family = data_get($payload, 'meta.unit_family');
        if (!$family) {
            $family = in_array($categoryCode, ['DZ', 'MG']) ? 'track' : (in_array($categoryCode, ['EXC', 'EX']) ? 'excavator' : 'track');
        }

        $checklist = $payload[$family] ?? [];
        $certification = data_get($payload, 'meta.certification', 'Green');
        $company = data_get($payload, 'meta.company', 'PT BERAU COAL / PT MTL');
        $stickerExp = data_get($payload, 'meta.sticker_expired_at');
        $assessmentMode = data_get($payload, 'meta.assessment_mode', 'pendampingan');
        $assessmentStage = data_get($payload, 'meta.assessment_stage', 'bulanan');

        // Extract Section A Groups
        $groups = data_get($checklist, 'groups', []);
        if (empty($groups)) {
            if ($family === 'excavator') {
                $groups = [
                    [
                        'title' => '1. Positioning',
                        'items' => [
                            ['code' => '1.1', 'kind' => 'Kwn', 'label' => 'Posisi unit di front loading', 'status' => 'K'],
                            ['code' => '1.2', 'kind' => 'Skl', 'label' => 'Sudut kerja bucket ke material', 'status' => 'K'],
                            ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara menempatkan bucket dengan aman', 'status' => 'K'],
                            ['code' => '1.4', 'kind' => 'Skl', 'label' => 'Posisi travel dan swing saat bekerja', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '2. Loading & Dumping',
                        'items' => [
                            ['code' => '2.1', 'kind' => 'Kwn', 'label' => 'Cara swing menuju truck', 'status' => 'K'],
                            ['code' => '2.2', 'kind' => 'Kwn', 'label' => 'Cara loading material ke dump truck', 'status' => 'K'],
                            ['code' => '2.3', 'kind' => 'Kwn', 'label' => 'Kontrol bucket saat dumping', 'status' => 'K'],
                            ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Cara dumping dan kerapian muatan', 'status' => 'K'],
                            ['code' => '2.5', 'kind' => 'Kwn', 'label' => 'Cycle time', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '3. Digging',
                        'items' => [
                            ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Self positioning dan penempatan bucket', 'status' => 'K'],
                            ['code' => '3.2', 'kind' => 'Skl', 'label' => 'Teknik kombinasi maju pada saat digging', 'status' => 'K'],
                            ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Cara pengambilan material pada saat digging', 'status' => 'K'],
                            ['code' => '3.4', 'kind' => 'Kwn', 'label' => 'Volume bucket', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '4. Sloping',
                        'items' => [
                            ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Keterampilan saat sloping', 'status' => 'K'],
                            ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Ketinggian / sudut penempatan slope', 'status' => 'K'],
                            ['code' => '4.3', 'kind' => 'Skl', 'label' => 'Kecepatan kerja ketika sloping', 'status' => 'K'],
                            ['code' => '4.4', 'kind' => 'Skl', 'label' => 'Hasil akhir permukaan slope', 'status' => 'K'],
                        ]
                    ],
                ];
            } else {
                $groups = [
                    [
                        'title' => '1. Dozing & Digging untuk Unit (DZ) / Grading & Digging untuk Unit (GR)',
                        'items' => [
                            ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan Blade pada saat mendorong/ grading', 'status' => 'K'],
                            ['code' => '1.2', 'kind' => 'Knw', 'label' => 'Penggunaan Tilt Blade', 'status' => 'K'],
                            ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara Pengoperasian blade untuk mendorong/ ditching', 'status' => 'K'],
                            ['code' => '1.4', 'kind' => 'Skl', 'label' => 'Cara Pengoperasian blade untuk menggali/ sloping', 'status' => 'K'],
                            ['code' => '1.5', 'kind' => 'Skl', 'label' => 'Penyesuaian beban dengan RPM/ posisi transmisi', 'status' => 'K'],
                            ['code' => '1.6', 'kind' => 'Skl', 'label' => 'Teknik dozing/ grading/ digging', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '2. Spreading & Leveling',
                        'items' => [
                            ['code' => '2.1', 'kind' => 'Knw', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak', 'status' => 'K'],
                            ['code' => '2.2', 'kind' => 'Knw', 'label' => 'Cara leveling menggunakan tilt', 'status' => 'K'],
                            ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Cara menghampar material untuk membuat jalan, menimbun lubang dll', 'status' => 'K'],
                            ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Filling pada saat melevelkan area kerja', 'status' => 'K'],
                            ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Penggunaan Steering', 'status' => 'K'],
                            ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Penggunaan Articulated (Khusus untuk unit GR)', 'status' => 'K'],
                            ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Teknik spreading/ levelling', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '3. Ripping',
                        'items' => [
                            ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan Ripper', 'status' => 'K'],
                            ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Teknik Penetrasi Ripping', 'status' => 'K'],
                            ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penyesuaian posisi ripper dengan kekerasan material', 'status' => 'K'],
                        ]
                    ],
                    [
                        'title' => '4. Finishing',
                        'items' => [
                            ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Kesesuaian penggunaan speed', 'status' => 'K'],
                            ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Hasil akhir pendorongan (hasil pekerjaan)', 'status' => 'K'],
                        ]
                    ],
                ];
            }
        }

        // Section B: Compliance
        $complianceItems = [
            ['code' => '1', 'kind' => 'Knw', 'label' => 'Kesehatan fisik dan perlengkapan/ penggunaan APD'],
            ['code' => '2', 'kind' => 'Skl', 'label' => 'Menaiki dan menuruni Unit (Three point contact)'],
            ['code' => '3', 'kind' => 'Skl', 'label' => 'Penyetelan tempat duduk'],
            ['code' => '4', 'kind' => 'Att', 'label' => 'Penggunaan sabuk pengaman/ safety belt'],
            ['code' => '5', 'kind' => 'Skl', 'label' => 'Penggunaan klakson dan lampu-lampu'],
            ['code' => '6', 'kind' => 'Skl', 'label' => 'Keselamatan saat digging, dozing, spreading, levelling, ripping, travelling'],
            ['code' => '7', 'kind' => 'Skl', 'label' => 'Penyesuaian jenis alat dengan lokasi pekerjaan'],
            ['code' => '8', 'kind' => 'Att', 'label' => 'Kepedulian terhadap patok-patok survey dan rambu'],
            ['code' => '9', 'kind' => 'Skl', 'label' => 'Parkir unit ditempat yang rata dan aman (pasang lock dan cara meletakkan attachment)'],
            ['code' => '10', 'kind' => 'Knw', 'label' => 'Keselamatan selama operasi'],
        ];

        // Section C: Discipline & Communication
        $savedBehavior = data_get($checklist, 'behavior', []);
        $disciplineItems = [
            ['code' => '1', 'kind' => 'Att', 'label' => 'Mempedulikan pemakaian fuel/ bahan bakar'],
            ['code' => '2', 'kind' => 'Att', 'label' => 'Mempedulikan pemakaian tyre/ undercarriage'],
            ['code' => '3', 'kind' => 'Att', 'label' => 'Mempedulikan akan ketidaknormalan unit'],
            ['code' => '4', 'kind' => 'Att', 'label' => 'Mempedulikan untuk bekerja dengan efektif dan efisien'],
            ['code' => '5', 'kind' => 'Att', 'label' => 'Mempedulikan untuk meniadakan pemborosan dimanapun'],
            ['code' => '6', 'kind' => 'Att', 'label' => 'Melaksanakan aktivitas sesuai instruksi'],
            ['code' => '7', 'kind' => 'Att', 'label' => 'Berusaha untuk melakukan yang terbaik'],
            ['code' => '8', 'kind' => 'Att', 'label' => 'Selalu siap menerima tugas yang diberikan'],
            ['code' => '9', 'kind' => 'Att', 'label' => 'Berani mengingatkan jika ada yang berbuat kesalahan'],
            ['code' => '10', 'kind' => 'Att', 'label' => 'Disiplin waktu saat pelaksanaan pelatihan'],
            ['code' => '11', 'kind' => 'Att', 'label' => 'Mematuhi semua aturan yang berlaku'],
            ['code' => '12', 'kind' => 'Att', 'label' => 'Tidak pernah mangkir'],
            ['code' => '13', 'kind' => 'Att', 'label' => 'Melaksanakan tugas kelompok bersama-sama'],
            ['code' => '14', 'kind' => 'Att', 'label' => 'Berinisiatif untuk membantu'],
            ['code' => '15', 'kind' => 'Att', 'label' => 'Selalu antusias jika diberi tugas'],
            ['code' => '16', 'kind' => 'Att', 'label' => 'Melaporkan setiap kejadian diluar wewenangnya'],
            ['code' => '17', 'kind' => 'Att', 'label' => 'Tidak ragu-ragu jika diberi instruksi'],
            ['code' => '18', 'kind' => 'Att', 'label' => 'Mengoperasikan unit dengan penuh keyakinan'],
            ['code' => '19', 'kind' => 'Att', 'label' => 'Bersikap proaktif di setiap kegiatan'],
            ['code' => '20', 'kind' => 'Att', 'label' => 'Tidak malu untuk bertanya jika ada kesulitan'],
        ];

        // Merge saved behavior statuses if exists
        foreach ($disciplineItems as $i => $item) {
            if (isset($savedBehavior[$i])) {
                $disciplineItems[$i]['status'] = $savedBehavior[$i]['status'] ?? 'K';
                $disciplineItems[$i]['note'] = $savedBehavior[$i]['note'] ?? '';
            } else {
                $disciplineItems[$i]['status'] = 'K';
                $disciplineItems[$i]['note'] = '';
            }
        }
    ?>

    <div class="form-container">

        <!-- Official Header -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <div style="text-align: center; padding: 1px 2px;">
                        <img src="<?php echo e(asset('images/berau_coal_logo.svg')); ?>" alt="Berau Coal Logo" class="logo-img">
                    </div>
                </td>
                <td>
                    <div class="title-main">BERAU COAL GREEN MINING SYSTEM</div>
                    <div class="title-sub">FORMULIR</div>
                    <div class="title-desc">Pelaksanaan On Job Training (OJT) Unit <?php echo e($categoryName); ?> (<?php echo e($categoryCode); ?>)</div>
                </td>
            </tr>
        </table>

        <!-- Metadata Grid -->
        <table class="meta-table">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td class="meta-label">NAMA</td><td>: <?php echo e($logbook->trainee->name ?? 'Ahmad Rian Syahputra'); ?></td></tr>
                        <tr><td class="meta-label">HARI/ TANGGAL</td><td>: <?php echo e(\Carbon\Carbon::parse($logbook->date)->translatedFormat('l, d F Y')); ?></td></tr>
                        <tr><td class="meta-label">SHIFT</td><td>: Shift <?php echo e(ucfirst($logbook->shift)); ?> (<?php echo e($logbook->shift === 'day' ? 'Siang: 07.00 - 17.00' : 'Malam: 19.00 - 05.00'); ?>)</td></tr>
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
                            <td style="width: 50%; vertical-align: top;">
                                <b>Tahap Tanpa Pendampingan Lanjutan</b><br>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentStage === 'bulanan' ? '✓' : '&nbsp;'; ?></span> Bulanan</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentStage === '3_bulan_pertama' ? '✓' : '&nbsp;'; ?></span> 3 Bulan Pertama</div>
                                <div class="checkbox-item"><span class="checkbox-rect"><?php echo $assessmentStage === '3_bulan_kedua' ? '✓' : '&nbsp;'; ?></span> 3 Bulan Kedua</div>
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
                    <th class="col-aspek">Aspek</th>
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
                        ?>
                        <tr>
                            <td class="col-no"><?php echo e($item['code'] ?? ($groupIndex + 1).'.'.($itemIndex + 1)); ?></td>
                            <td class="col-aspek"><?php echo e($item['kind'] ?? 'Skl'); ?></td>
                            <td class="col-item"><?php echo e($item['label'] ?? ''); ?></td>
                            <td class="col-kbk"><?php echo $status === 'K' ? '✓' : ''; ?></td>
                            <td class="col-kbk"><?php echo $status === 'BK' ? '✓' : ''; ?></td>
                            <td class="col-catatan"><?php echo e($item['note'] ?? ''); ?></td>
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
                    ?>
                    <tr>
                        <td class="col-no"><?php echo e($item['code']); ?></td>
                        <td class="col-aspek"><?php echo e($item['kind']); ?></td>
                        <td class="col-item"><?php echo e($item['label']); ?></td>
                        <td class="col-kbk"><?php echo $status === 'K' ? '✓' : ''; ?></td>
                        <td class="col-kbk"><?php echo $status === 'BK' ? '✓' : ''; ?></td>
                        <td class="col-catatan"><?php echo e($item['note'] ?? ''); ?></td>
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
                    ?>
                    <tr>
                        <td class="col-no"><?php echo e($item['code']); ?></td>
                        <td class="col-aspek"><?php echo e($item['kind']); ?></td>
                        <td class="col-item"><?php echo e($item['label']); ?></td>
                        <td class="col-kbk"><?php echo $status === 'K' ? '✓' : ''; ?></td>
                        <td class="col-kbk"><?php echo $status === 'BK' ? '✓' : ''; ?></td>
                        <td class="col-catatan"><?php echo e($item['note'] ?? ''); ?></td>
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
<?php echo e($logbook->evaluation?->trainer_comment ?? $logbook->daily_activity); ?>

                    </div>
                </td>
                <td style="width: 35%;">
                    <div class="conclusion-title">KESIMPULAN</div>
                    <?php
                        $isKompeten = ($logbook->evaluation?->competency_status === 'K' || $logbook->status === 'final_approved');
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
                <td style="width: 33.33%;" class="sig-title">Instruktur/ Pengawas/ Operator Pendamping</td>
                <td style="width: 33.33%;" class="sig-title">Kabag OTDI/ LC</td>
            </tr>
            <tr>
                <td>
                    <div class="sig-space">
                        <?php if($logbook->trainee?->signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->trainee->signature_path)); ?>" alt="Trainee signature" class="sig-img">
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?php echo e($logbook->trainee->name ?? 'Ahmad Rian Syahputra'); ?></div>
                    <div class="sig-sid">No. SID : <?php echo e($logbook->trainee->nrp ?? '-'); ?></div>
                </td>
                <td>
                    <?php
                        $trainerSig = $logbook->evaluation?->trainer_signature_path ?? $logbook->trainer_signature_path ?? $logbook->pjo_signature_path;
                        $trainerUser = $logbook->trainer ?? $logbook->supervisor;
                    ?>
                    <div class="sig-space">
                        <?php if($trainerSig): ?>
                            <img src="<?php echo e(asset('storage/'.$trainerSig)); ?>" alt="Trainer signature" class="sig-img">
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?php echo e($trainerUser->name ?? 'Bambang Hermawan'); ?></div>
                    <div class="sig-sid">No. SID : <?php echo e($trainerUser->nrp ?? '-'); ?></div>
                </td>
                <td>
                    <div class="sig-space">
                        <?php if($logbook->training_centre_signature_path): ?>
                            <img src="<?php echo e(asset('storage/'.$logbook->training_centre_signature_path)); ?>" alt="Kabag signature" class="sig-img">
                        <?php endif; ?>
                    </div>
                    <div class="sig-name"><?php echo e($logbook->trainingCentre->name ?? 'Kabag Training Centre'); ?></div>
                    <div class="sig-sid">No. SID : <?php echo e($logbook->trainingCentre->nrp ?? '-'); ?></div>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>
<?php /**PATH D:\KULIAH\BERAU COAL INTERN\logbook\resources\views/ojt/logbooks/print.blade.php ENDPATH**/ ?>