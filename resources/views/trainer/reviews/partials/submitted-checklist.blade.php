@php
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
@endphp

<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <div>
            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Form OJT Trainee</h2>
            <p class="text-[11px] text-slate-500 mt-1">Tampilan read-only sesuai tipe alat: {{ $logbook->equipmentCategory->name ?? '-' }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Nilai skala trainee dikonversi otomatis: 1 (Belum) &amp; 2 (Cukup) = <span class="font-bold text-rose-600">BK</span>, 3 (Mampu) &amp; 4 (Mahir) = <span class="font-bold text-amber-600">K</span>.</p>
        </div>
        <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200">{{ strtoupper($family ?: 'N/A') }}</span>
    </div>

    <div class="rounded-2xl border-2 border-slate-900 bg-white overflow-hidden shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-2">
            <div class="border-b border-slate-900 lg:border-b-0 lg:border-r">
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NAMA</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $logbook->trainee->name }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HARI/ TANGGAL</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $logbook->date->format('d/m/Y') }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">SHIFT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">Shift {{ $logbook->shift === 'day' ? 'Siang' : 'Malam' }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">LOKASI (OJT)</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $logbook->location }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">SERTIFIKASI</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $certification === 'Green' ? 'Green' : ($certification === 'Skill-up' ? 'Skill-up' : ($certification === 'Experience_internal' ? 'Experience Internal' : ($certification === 'Experience_external' ? 'Experience External' : $certification))) }}</div>
                </div>
            </div>

            <div>
                <div class="grid grid-cols-[120px_minmax(0,1fr)] gap-x-3 gap-y-0 text-[11px] text-slate-900">
                    <div class="px-3 py-2 font-semibold border-b border-slate-900">PERUSAHAAN</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $company }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">TIPE ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $categoryLabel }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">NO ALAT</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">{{ $logbook->equipment_number }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HM/ KM AWAL</div>
                    <div class="px-3 py-1 border-b border-slate-900">{{ number_format($logbook->hm_start, 1) }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">HM/ KM AKHIR</div>
                    <div class="px-3 py-1 border-b border-slate-900">{{ number_format($logbook->hm_end, 1) }}</div>

                    <div class="px-3 py-2 font-semibold border-b border-slate-900">EXPIRED DATE STIKER (SKO)</div>
                    <div class="px-3 py-1.5 border-b border-slate-900">
                        {{ $stickerExpired ? \Carbon\Carbon::parse($stickerExpired)->format('d M Y') : '-' }}
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
                    <div class="font-medium">{{ $assessmentModeLabel }}</div>
                </div>
                <div>
                    <div class="font-semibold mb-2">Tahap Tanpa Pendampingan Lanjutan</div>
                    <div class="font-medium">{{ $assessmentStageLabel }}</div>
                    <div class="mt-1 text-slate-600">Keterangan: </div>
                    <div class="font-medium">{{ $assessmentStageDetailLabel }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="p-4 sm:p-5 space-y-5">
    @forelse(data_get($checklist, 'groups', []) as $groupIndex => $group)
        <div class="rounded-2xl border border-slate-200 overflow-hidden">
            <div class="bg-[#1e3a8a] px-4 py-3 text-white">
                <p class="text-xs font-bold uppercase">{{ $group['title'] ?? 'Checklist Unit '.($groupIndex + 1) }}</p>
                <p class="text-[10px] text-blue-100 mt-1">{{ $group['subtitle'] ?? 'Tipe kompetensi sesuai SOP unit' }}</p>
            </div>
            <div class="w-full">
                <table class="w-full text-xs block md:table">
                    <colgroup class="hidden md:table-column-group">
                        <col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72">
                    </colgroup>
                    <thead class="hidden md:table-header-group bg-slate-50 text-slate-500 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left">No</th>
                            <th class="px-3 py-2 text-left">Tipe</th>
                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                            <th class="px-3 py-2 text-center">K</th>
                            <th class="px-3 py-2 text-center">BK</th>
                            <th class="px-3 py-2 text-left">Trainee Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group divide-y divide-slate-100">
                        @foreach($group['items'] ?? [] as $itemIndex => $item)
                            @php
                                $itemStatus = $item['status'] ?? null;
                                $kbkStatus = \App\Support\CompetencyScale::toStatus($itemStatus);
                                $kbkScale = \App\Support\CompetencyScale::toScale($itemStatus);
                                $kbkLabel = \App\Support\CompetencyScale::label($itemStatus);
                            @endphp
                            <tr class="block md:table-row p-3.5 space-y-2 bg-white md:p-0 md:space-y-0">
                                <td class="block md:table-cell p-0 md:px-3 md:py-3 font-bold">
                                    <div class="flex items-center gap-1.5 md:block">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-blue-50 text-[#1e3a8a] text-[10px] font-black border border-blue-200 md:bg-transparent md:text-slate-700 md:border-0 md:p-0 md:text-xs">
                                            {{ $item['code'] ?? ($groupIndex + 1).'.'.($itemIndex + 1) }}
                                        </span>
                                        <span class="md:hidden px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold">
                                            {{ $item['kind'] ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell px-3 py-3 text-slate-500">{{ $item['kind'] ?? '-' }}</td>
                                <td class="block md:table-cell p-0 md:px-3 md:py-3 text-slate-700 leading-relaxed font-medium md:font-normal break-words">
                                    {{ $item['label'] ?? 'Item checklist SOP' }}
                                </td>
                                @include('ojt.logbooks.partials.kbk-readonly', ['status' => $itemStatus])
                                <td class="block md:hidden p-0">
                                    <div class="text-[10px] font-bold text-slate-500 uppercase mb-1">Status Penilaian:</div>
                                    @if($kbkStatus === \App\Support\CompetencyScale::STATUS_KOMPETEN)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                            <span>✓</span> Kompeten (K) · Skala {{ $kbkScale }} ({{ $kbkLabel }})
                                        </span>
                                    @elseif($kbkStatus === \App\Support\CompetencyScale::STATUS_BELUM_KOMPETEN)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold">
                                            <span>⚠️</span> Belum Kompeten (BK) · Skala {{ $kbkScale }} ({{ $kbkLabel }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-400 text-xs font-medium">
                                            Belum dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="block md:table-cell p-0 md:px-3 md:py-3 text-slate-500 leading-relaxed break-words">
                                    <div class="md:hidden text-[10px] font-bold text-slate-500 uppercase mb-0.5">Trainee Feedback:</div>
                                    <span class="text-xs">{{ $item['trainee_feedback'] ?? $item['note'] ?? '-' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="p-5 text-xs text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">Checklist detail belum tersedia pada pengajuan ini. Form OJT baru akan menyimpan setiap judul dan item SOP sesuai tipe alatnya.</div>
    @endforelse

    @if(data_get($checklist, 'compliance'))
        <div class="rounded-2xl border border-slate-200 overflow-hidden">
            <div class="bg-[#1e3a8a] px-4 py-3 text-white">
                <p class="text-xs font-bold uppercase">Kepatuhan Terhadap Peraturan Kerja</p>
            </div>
            <div class="w-full">
                <table class="w-full text-xs block md:table">
                    <colgroup class="hidden md:table-column-group">
                        <col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72">
                    </colgroup>
                    <thead class="hidden md:table-header-group bg-slate-50 text-slate-500 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left">No</th>
                            <th class="px-3 py-2 text-left">Tipe</th>
                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                            <th class="px-3 py-2 text-center">K</th>
                            <th class="px-3 py-2 text-center">BK</th>
                            <th class="px-3 py-2 text-left">Trainee Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group divide-y divide-slate-100">
                        @foreach(data_get($checklist, 'compliance', []) as $itemIndex => $item)
                            @php
                                $itemStatus = $item['status'] ?? null;
                                $kbkStatus = \App\Support\CompetencyScale::toStatus($itemStatus);
                                $kbkScale = \App\Support\CompetencyScale::toScale($itemStatus);
                                $kbkLabel = \App\Support\CompetencyScale::label($itemStatus);
                            @endphp
                            <tr class="block md:table-row p-3.5 space-y-2 bg-white md:p-0 md:space-y-0">
                                <td class="block md:table-cell p-0 md:px-3 md:py-3 font-bold">
                                    <div class="flex items-center gap-1.5 md:block">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-blue-50 text-[#1e3a8a] text-[10px] font-black border border-blue-200 md:bg-transparent md:text-slate-700 md:border-0 md:p-0 md:text-xs">
                                            {{ $item['code'] ?? ($itemIndex + 1) }}
                                        </span>
                                        <span class="md:hidden px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold">
                                            {{ $item['kind'] ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell px-3 py-3 text-slate-500">{{ $item['kind'] ?? '-' }}</td>
                                <td class="block md:table-cell p-0 md:px-3 md:py-3 text-slate-700 leading-relaxed font-medium md:font-normal break-words">
                                    {{ $item['label'] ?? 'Item kepatuhan' }}
                                </td>
                                @include('ojt.logbooks.partials.kbk-readonly', ['status' => $itemStatus])
                                <td class="block md:hidden p-0">
                                    <div class="text-[10px] font-bold text-slate-500 uppercase mb-1">Status Penilaian:</div>
                                    @if($kbkStatus === \App\Support\CompetencyScale::STATUS_KOMPETEN)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                            <span>✓</span> Kompeten (K) · Skala {{ $kbkScale }} ({{ $kbkLabel }})
                                        </span>
                                    @elseif($kbkStatus === \App\Support\CompetencyScale::STATUS_BELUM_KOMPETEN)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold">
                                            <span>⚠️</span> Belum Kompeten (BK) · Skala {{ $kbkScale }} ({{ $kbkLabel }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-400 text-xs font-medium">
                                            Belum dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="block md:table-cell p-0 md:px-3 md:py-3 text-slate-500 leading-relaxed break-words">
                                    <div class="md:hidden text-[10px] font-bold text-slate-500 uppercase mb-0.5">Trainee Feedback:</div>
                                    <span class="text-xs">{{ $item['trainee_feedback'] ?? $item['note'] ?? '-' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if(data_get($checklist, 'behavior'))
        <div class="rounded-2xl border border-slate-200 overflow-hidden">
            <div class="bg-[#1e3a8a] px-4 py-3 text-white">
                <p class="text-xs font-bold uppercase">Kedisiplinan dan Komunikasi</p>
            </div>
            <div class="w-full">
                <table class="w-full text-xs block md:table">
                    <colgroup class="hidden md:table-column-group">
                        <col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72">
                    </colgroup>
                    <thead class="hidden md:table-header-group bg-slate-50 text-slate-500 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left">No</th>
                            <th class="px-3 py-2 text-left">Tipe</th>
                            <th class="px-3 py-2 text-left">Item Evaluasi</th>
                            <th class="px-3 py-2 text-center">K</th>
                            <th class="px-3 py-2 text-center">BK</th>
                            <th class="px-3 py-2 text-left">Trainee Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group divide-y divide-slate-100">
                        @foreach(data_get($checklist, 'behavior', []) as $itemIndex => $item)
                            @php
                                $itemStatus = $item['status'] ?? null;
                                $kbkStatus = \App\Support\CompetencyScale::toStatus($itemStatus);
                                $kbkScale = \App\Support\CompetencyScale::toScale($itemStatus);
                                $kbkLabel = \App\Support\CompetencyScale::label($itemStatus);
                            @endphp
                            <tr class="block md:table-row p-3.5 space-y-2 bg-white md:p-0 md:space-y-0">
                                <td class="block md:table-cell p-0 md:px-3 md:py-3 font-bold">
                                    <div class="flex items-center gap-1.5 md:block">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-blue-50 text-[#1e3a8a] text-[10px] font-black border border-blue-200 md:bg-transparent md:text-slate-700 md:border-0 md:p-0 md:text-xs">
                                            {{ $item['code'] ?? ($itemIndex + 1) }}
                                        </span>
                                        <span class="md:hidden px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold">
                                            {{ $item['kind'] ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell px-3 py-3 text-slate-500">{{ $item['kind'] ?? '-' }}</td>
                                <td class="block md:table-cell p-0 md:px-3 md:py-3 text-slate-700 leading-relaxed font-medium md:font-normal break-words">
                                    {{ $item['label'] ?? 'Item kedisiplinan' }}
                                </td>
                                @include('ojt.logbooks.partials.kbk-readonly', ['status' => $itemStatus])
                                <td class="block md:hidden p-0">
                                    <div class="text-[10px] font-bold text-slate-500 uppercase mb-1">Status Penilaian:</div>
                                    @if($kbkStatus === \App\Support\CompetencyScale::STATUS_KOMPETEN)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                            <span>✓</span> Kompeten (K) · Skala {{ $kbkScale }} ({{ $kbkLabel }})
                                        </span>
                                    @elseif($kbkStatus === \App\Support\CompetencyScale::STATUS_BELUM_KOMPETEN)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 text-xs font-bold">
                                            <span>⚠️</span> Belum Kompeten (BK) · Skala {{ $kbkScale }} ({{ $kbkLabel }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-400 text-xs font-medium">
                                            Belum dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="block md:table-cell p-0 md:px-3 md:py-3 text-slate-500 leading-relaxed break-words">
                                    <div class="md:hidden text-[10px] font-bold text-slate-500 uppercase mb-0.5">Trainee Feedback:</div>
                                    <span class="text-xs">{{ $item['trainee_feedback'] ?? $item['note'] ?? '-' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
