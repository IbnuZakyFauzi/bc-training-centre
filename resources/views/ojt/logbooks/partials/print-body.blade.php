    @php
        $payload = $logbook->sop_payload ?? [];
        $categoryCode = $logbook->equipmentCategory->code ?? 'DZ';
        $categoryName = $logbook->equipmentCategory->name ?? 'Bulldozer & Motor Grader';
        $family = data_get($payload, 'meta.unit_family');
        if (!$family) {
            $family = in_array($categoryCode, ['DZ', 'MG']) ? 'track' : (in_array($categoryCode, ['EXC', 'EX']) ? 'excavator' : (in_array($categoryCode, ['HDT', 'LDT']) ? 'dumptruck' : (in_array($categoryCode, ['SDT', 'ADT']) ? 'semidump' : ($categoryCode === 'WL' ? 'wheelloader' : 'track'))));
        }

        $checklist = $payload[$family] ?? [];
        $certification = data_get($payload, 'meta.certification', 'Green');
        $company = data_get($payload, 'meta.company', 'PT BERAU COAL / PT MTL');
        $stickerExp = data_get($payload, 'meta.sticker_expired_at');
        $assessmentMode = data_get($payload, 'meta.assessment_mode', '');
        $assessmentStage = data_get($payload, 'meta.assessment_stage', '');
        $assessmentStageDetail = data_get($payload, 'meta.assessment_stage_detail', '');

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
    @endphp

    <div class="form-container">

        <!-- Official Header -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <div style="text-align: center; padding: 1px 2px;">
                        <img src="{{ asset('images/berau_coal_logo.svg') }}" alt="Berau Coal Logo" class="logo-img">
                    </div>
                </td>
                <td>
                    <div class="title-main">BERAU COAL GREEN MINING SYSTEM</div>
                    <div class="title-sub">FORMULIR</div>
                     <div class="title-desc">Pelaksanaan On Job Training (OJT) Unit {{ in_array($categoryCode, ['HDT', 'LDT']) ? 'Heavy Dump Truck (HDT) / Light Dump Truck (LDT)' : (in_array($categoryCode, ['SDT', 'ADT']) ? 'Semi Dump Trailer (SDT) / Articulated Dump Truck (ADT)' : ($categoryCode === 'WL' ? 'Wheel Loader (WL)' : ($categoryName . ' (' . $categoryCode . ')'))) }}</div>
                </td>
            </tr>
        </table>

        <!-- Metadata Grid -->
        <table class="meta-table">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td class="meta-label">NAMA</td><td>: {{ $logbook->trainee->name ?? 'Ahmad Rian Syahputra' }}</td></tr>
                        <tr><td class="meta-label">HARI/ TANGGAL</td><td>: {{ \Carbon\Carbon::parse($logbook->date)->translatedFormat('l, d F Y') }}</td></tr>
                        <tr><td class="meta-label">SHIFT</td><td>: Shift {{ ucfirst($logbook->shift) }} ({{ $logbook->shift === 'day' ? 'Siang: 07.00 - 17.00' : 'Malam: 19.00 - 05.00' }})</td></tr>
                        <tr><td class="meta-label">LOKASI (OJT)</td><td>: {{ $logbook->location }}</td></tr>
                        <tr><td class="meta-label">SERTIFIKASI</td><td>: 
                            <span style="{{ $certification === 'Green' ? 'font-weight:bold; text-decoration: underline;' : 'color: #888;' }}">Green</span> / 
                            <span style="{{ $certification === 'Skill-up' ? 'font-weight:bold; text-decoration: underline;' : 'color: #888;' }}">Skill-up</span> / 
                            <span style="{{ $certification === 'Experience' ? 'font-weight:bold; text-decoration: underline;' : 'color: #888;' }}">Experience</span> 
                            <span style="font-size: 7.5px; font-style: italic;">(Coret yang tidak sesuai)</span>
                        </td></tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td class="meta-label">PERUSAHAAN</td><td>: {{ $company }}</td></tr>
                         <tr><td class="meta-label">TIPE ALAT</td><td>: {{ $categoryName }} ({{ $categoryCode }})</td></tr>
                         @if(in_array($family, ['track', 'dumptruck', 'semidump'], true))
                             @php
                                 $unitType = data_get($payload, 'meta.unit_type', $family === 'track' ? 'DZ' : ($family === 'semidump' ? 'SDT' : 'HDT'));
                              $unitTypeLabel = $family === 'track' ? ($unitType === 'GR' ? 'GR (Motor Grader)' : 'DZ (Bulldozer)') : ($family === 'semidump' ? ($unitType === 'ADT' ? 'ADT (Articulated Dump Truck)' : 'SDT (Semi Dump Trailer)') : ($unitType === 'LDT' ? 'LDT (Light Dump Truck)' : 'HDT (Heavy Dump Truck)'));
                          @endphp
                         <tr><td class="meta-label">UNIT TYPE</td><td>: {{ $unitTypeLabel }}</td></tr>
                         @endif
                        <tr><td class="meta-label">NO ALAT</td><td>: {{ $logbook->unit_code }}</td></tr>
                         <tr><td class="meta-label">HM/ KM AWAL</td><td>: {{ number_format($logbook->hm_start, 1) }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>HM/ KM AKHIR:</b> {{ number_format($logbook->hm_end, 1) }}</td></tr>
                         <tr><td class="meta-label">EXPIRED DATE STIKER (SKO)</td><td>: {{ $stickerExp ? \Carbon\Carbon::parse($stickerExp)->format('d/m/Y') : '......................20....' }}</td></tr>
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
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $assessmentMode === 'pendampingan' ? '✓' : '&nbsp;' !!}</span> Pendampingan</div>
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $assessmentMode === 'tanpa_pendampingan' ? '✓' : '&nbsp;' !!}</span> Tanpa Pendampingan</div>
                            </td>
                            <td style="width: 25%; vertical-align: top;">
                                <b>Tahap Tanpa Pendampingan Lanjutan</b><br>
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $assessmentStage === 'bulanan' ? '✓' : '&nbsp;' !!}</span> Bulanan</div>
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $assessmentStage === '3_bulan_pertama' ? '✓' : '&nbsp;' !!}</span> 3 Bulan Pertama</div>
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $assessmentStage === '3_bulan_kedua' ? '✓' : '&nbsp;' !!}</span> 3 Bulan Kedua</div>
                            </td>
                            <td style="width: 25%; vertical-align: top;">
                                <b>Keterangan</b><br>
                                @php
                                    $detailValue = $assessmentStageDetail ?: $assessmentStage;
                                @endphp
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $detailValue === 'bulan_1' ? '✓' : '&nbsp;' !!}</span> Bulan ke-1</div>
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $detailValue === 'bulan_2' ? '✓' : '&nbsp;' !!}</span> Bulan ke-2</div>
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $detailValue === 'bulan_3' ? '✓' : '&nbsp;' !!}</span> Bulan ke-3</div>
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $detailValue === 'bulan_4' ? '✓' : '&nbsp;' !!}</span> Bulan ke-4</div>
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $detailValue === 'bulan_5' ? '✓' : '&nbsp;' !!}</span> Bulan ke-5</div>
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $detailValue === 'bulan_6' ? '✓' : '&nbsp;' !!}</span> Bulan ke-6</div>
                                <div class="checkbox-item"><span class="checkbox-rect">{!! $detailValue === '-' ? '✓' : '&nbsp;' !!}</span> -</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Banner Code -->
        <div class="unit-banner">
            UNIT*: {{ $categoryCode }}
        </div>

        {{--
            Dokumen akhir tetap memakai kolom K / BK.
            Nilai skala pengisian dikonversi otomatis oleh App\Support\CompetencyScale:
            1 (Belum) & 2 (Cukup) => BK, 3 (Bisa) & 4 (Mahir) => K.
        --}}
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
                @foreach($groups as $groupIndex => $group)
                    @if(!empty($group['title']))
                        <tr>
                            <td class="col-no">{{ $groupIndex + 1 }}</td>
                            <td colspan="5" class="sub-header">{{ $group['title'] }}</td>
                        </tr>
                    @endif
                    @foreach($group['items'] ?? [] as $itemIndex => $item)
                        @php
                            $status = $item['status'] ?? 'K';
                            $note = trim((string)($item['trainee_feedback'] ?? $item['note'] ?? ''));
                        @endphp
                        <tr>
                            <td class="col-no">{{ $item['code'] ?? ($groupIndex + 1).'.'.($itemIndex + 1) }}</td>
                            <td class="col-aspek">{{ $item['kind'] ?? 'Skl' }}</td>
                            <td class="col-item">{{ $item['label'] ?? '' }}</td>
                            <td class="col-kbk">{!! (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isKompeten($status)) ? '✓' : '' !!}</td>
                            <td class="col-kbk">{!! (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isBelumKompeten($status)) ? '✓' : '' !!}</td>
                            <td class="col-catatan">{{ $item['trainee_feedback'] ?? $item['note'] ?? '' }}</td>
                        </tr>
                    @endforeach
                @endforeach

                <!-- SECTION B -->
                <tr>
                    <td class="col-no">B</td>
                    <td colspan="5" class="section-header">Kepatuhan Terhadap Peraturan Kerja</td>
                </tr>
                @foreach($complianceItems as $itemIndex => $item)
                    @php
                        $status = $item['status'] ?? 'K';
                        $note = trim((string)($item['trainee_feedback'] ?? $item['note'] ?? ''));
                    @endphp
                    <tr>
                        <td class="col-no">{{ $item['code'] }}</td>
                        <td class="col-aspek">{{ $item['kind'] }}</td>
                        <td class="col-item">{{ $item['label'] }}</td>
                        <td class="col-kbk">{!! (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isKompeten($status)) ? '✓' : '' !!}</td>
                        <td class="col-kbk">{!! (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isBelumKompeten($status)) ? '✓' : '' !!}</td>
                        <td class="col-catatan">{{ $item['trainee_feedback'] ?? $item['note'] ?? '' }}</td>
                    </tr>
                @endforeach

                <!-- SECTION C -->
                <tr>
                    <td class="col-no">C</td>
                    <td colspan="5" class="section-header">Kedisiplinan dan Komunikasi</td>
                </tr>
                @foreach($disciplineItems as $itemIndex => $item)
                    @php
                        $status = $item['status'] ?? 'K';
                        $note = trim((string)($item['trainee_feedback'] ?? $item['note'] ?? ''));
                    @endphp
                    <tr>
                        <td class="col-no">{{ $item['code'] }}</td>
                        <td class="col-aspek">{{ $item['kind'] }}</td>
                        <td class="col-item">{{ $item['label'] }}</td>
                        <td class="col-kbk">{!! (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isKompeten($status)) ? '✓' : '' !!}</td>
                        <td class="col-kbk">{!! (!in_array($note, ['N/A', 'n/a', '-']) && \App\Support\CompetencyScale::isBelumKompeten($status)) ? '✓' : '' !!}</td>
                        <td class="col-catatan">{{ $item['trainee_feedback'] ?? $item['note'] ?? '' }}</td>
                    </tr>
                @endforeach
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
{{ $logbook->evaluation?->trainer_comment ?? $logbook->daily_activity }}
                    </div>
                </td>
                <td style="width: 35%;">
                    <div class="conclusion-title">KESIMPULAN</div>
                    @php
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
                    @endphp
                    <div style="margin-bottom: 4px; font-weight: bold; font-size: 10px;">
                        <span class="checkbox-rect">{!! $isKompeten ? '✓' : '&nbsp;' !!}</span> &nbsp;KOMPETEN (K)
                    </div>
                    <div style="margin-bottom: 6px; font-weight: bold; font-size: 10px;">
                        <span class="checkbox-rect">{!! !$isKompeten ? '✓' : '&nbsp;' !!}</span> &nbsp;BELUM KOMPETEN (BK)
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
                    @php
                        $approverTitle = 'Instruktur/ Pengawas/ Operator Pendamping';
                        $approverName = $logbook->trainer->name ?? $logbook->supervisor->name ?? '-';
                        $approverSid = $logbook->trainer->sid ?? $logbook->supervisor->sid ?? '-';
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
                    @endphp
                    {{ $approverTitle }}
                </td>
                <td style="width: 33.33%;" class="sig-title">Kabag OTDI/ LC</td>
            </tr>
            <tr>
                <td>
                    <div class="sig-space">
                        @if($logbook->trainee?->signature_path)
                            <img src="{{ asset('storage/'.$logbook->trainee->signature_path) }}" alt="Trainee signature" class="sig-img">
                        @endif
                    </div>
                    <div class="sig-name">{{ $logbook->trainee->name ?? 'Ahmad Rian Syahputra' }}</div>
                    <div class="sig-sid">No. SID : {{ $logbook->trainee->sid ?? '-' }}</div>
                </td>
                <td>
                    <div class="sig-space">
                        @if($approverSig ?? null)
                            <img src="{{ asset('storage/'.$approverSig) }}" alt="Approver signature" class="sig-img">
                        @endif
                    </div>
                    <div class="sig-name">{{ $approverName }}</div>
                    <div class="sig-sid">No. SID : {{ $approverSid }}</div>
                </td>
                <td>
                    <div class="sig-space">
                        @if($logbook->training_centre_signature_path)
                            <img src="{{ asset('storage/'.$logbook->training_centre_signature_path) }}" alt="Kabag signature" class="sig-img">
                        @endif
                    </div>
                    <div class="sig-name">{{ $logbook->trainingCentre->name ?? 'Kabag Training Centre' }}</div>
                    <div class="sig-sid">No. SID : {{ $logbook->trainingCentre->sid ?? '-' }}</div>
                </td>
            </tr>
        </table>

    </div>


