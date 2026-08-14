@php
    $payload = $logbook->sop_payload ?? [];
    $family = data_get($payload, 'meta.unit_family');
    $checklist = $payload[$family] ?? [];
    $categoryCode = $logbook->equipmentCategory->code ?? '';
    if (empty($checklist) && in_array($categoryCode, ['DZ', 'MG'])) {
        $family = 'track';
        $checklist = [
            'groups' => [
                ['title' => 'Teknik Pengoperasian', 'subtitle' => 'Dozing & digging untuk Bulldozer / grading & digging untuk Motor Grader', 'items' => [
                    ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan blade pada saat mendorong / grading'], ['code' => '1.2', 'kind' => 'Knw', 'label' => 'Penggunaan tilt blade'], ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara pengoperasian blade untuk mendorong / ditching'], ['code' => '1.4', 'kind' => 'Skl', 'label' => 'Cara pengoperasian blade untuk menggali / sloping'], ['code' => '1.5', 'kind' => 'Skl', 'label' => 'Penyesuaian beban dengan RPM / posisi transmisi'], ['code' => '1.6', 'kind' => 'Skl', 'label' => 'Teknik dozing / grading / digging'],
                ]],
                ['title' => 'Spreading & Leveling', 'subtitle' => 'Pengoperasian untuk meratakan, memadatkan, dan membentuk area kerja', 'items' => [
                    ['code' => '2.1', 'kind' => 'Knw', 'label' => 'Penggunaan speed / transmisi saat bergerak'], ['code' => '2.2', 'kind' => 'Knw', 'label' => 'Cara leveling menggunakan tilt'], ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Cara menghampar material untuk membuat jalan / menimbun lubang'], ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Filling pada saat melewatkan area kerja'], ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Penggunaan steering'], ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Penggunaan articulated (khusus unit GR)'], ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Teknik spreading / leveling'],
                ]],
                ['title' => 'Ripping', 'subtitle' => 'Khusus untuk pekerjaan ripping dan pembukaan material keras', 'items' => [
                    ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan ripper'], ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Teknik penetrasi ripping'], ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penyesuaian posisi ripper dengan kekerasan material'],
                ]],
                ['title' => 'Finishing', 'subtitle' => 'Finishing grading dan koreksi permukaan kerja', 'items' => [
                    ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Kesesuaian penggunaan speed'], ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Hasil akurasi / kualitas pekerjaan'],
                ]],
            ],
        ];
    } elseif (empty($checklist) && in_array($categoryCode, ['HDT', 'LDT'])) {
        $family = 'dumptruck';
        $checklist = [
            'groups' => [
                ['title' => 'Loading', 'subtitle' => 'Teknik pengambilan haluan, posisi terhadap alat muat, dan penggunaan transmisi/brake saat loading', 'items' => [
                    ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Pengambilan haluan untuk loading/ posisi antri/'], ['code' => '1.2', 'kind' => 'Skl', 'label' => 'Posisi terhadap Alat muat (rata, aman & keras)'], ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Tranmission "N" & Penggunaan brake saat loading'], ['code' => '1.4', 'kind' => 'Knw', 'label' => 'Perhatian saat loading terhadap beban/payload meter (Khusus untuk Unit HDT)'], ['code' => '1.5', 'kind' => 'Knw', 'label' => 'Perhatian saat loading thd operator alat muat (Khusus untuk Unit LDT)'], ['code' => '1.6', 'kind' => 'Knw', 'label' => 'Perhatian terhadap standart muatan (Khusus untuk Unit LDT)'],
                ]],
                ['title' => 'Hauling', 'subtitle' => 'Penggunaan speed/transmissi, clutch, shift limit, retarder, brake, dan keemihan mengemudi saat bergerak', 'items' => [
                    ['code' => '2.1', 'kind' => 'Knw', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari F1 - Khusus HD)'], ['code' => '2.2', 'kind' => 'Knw', 'label' => 'Penggunaan Clutch (Khusus untuk Unit LDT)'], ['code' => '2.3', 'kind' => 'Knw', 'label' => 'Penggunaan Shift Limit & Power/ Eco Mode (Khusus untuk Unit HDT)'], ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Penyesuaian tingkat kecepatan, RPM Engine & transmisi dengan kondisi medan (Jalan turunan, mendatar, dan tanjakan)'], ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Penggunaan Retarder waktu turunan (Khusus untuk Unit HDT)'], ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Penggunaan brake di turunan & menghentikan unit (Khusus untuk Unit LDT)'], ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Pengembalian haluan saat membelok/ di tikungan'], ['code' => '2.8', 'kind' => 'Skl', 'label' => 'Ketrampilan/ kelembutan mengemudi'], ['code' => '2.9', 'kind' => 'Knw', 'label' => 'Cycle time'],
                ]],
                ['title' => 'Dumping', 'subtitle' => 'Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping/vessel', 'items' => [
                    ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Pengambilan haluan untuk dumping/ manuver'], ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Posisi dumping (lokasi harus rata)'], ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penggunaan brake saat Dumping'], ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Prosedur Dumping (Penggunaan RPM)'], ['code' => '3.5', 'kind' => 'Knw', 'label' => 'Prosedur menurunkan Vessel'], ['code' => '3.6', 'kind' => 'Knw', 'label' => 'Penempatan material yang tepat di disposal (Khusus untuk Unit HDT)'], ['code' => '3.7', 'kind' => 'Knw', 'label' => 'Prosedur menurunkan vesel di hopper/ stock pile (Khusus untuk Unit LDT)'],
                ]],
            ],
        ];
    } elseif (empty($checklist) && in_array($categoryCode, ['SDT', 'ADT'])) {
        $family = 'semidump';
        $checklist = [
            'groups' => [
                ['title' => 'Loading', 'subtitle' => 'Teknik penempatan posisi, posisi trailer terhadap alat muat, dan penggunaan transmisi/brake saat loading', 'items' => [
                    ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Penempatan posisi untuk loading/ posisi antri'], ['code' => '1.2', 'kind' => 'Skl', 'label' => 'Posisi Trailer terhadap Alat muat (rata & aman)'], ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Transmission "N" & Penggunaan Parking Brake saat loading'], ['code' => '1.4', 'kind' => 'Knw', 'label' => 'Perhatian saat loading terhadap beban/ vessel penuh'],
                ]],
                ['title' => 'Hauling', 'subtitle' => 'Penggunaan speed/transmissi, power/offroad mode, trailer brake, dan keemihan mengemudi saat bergerak', 'items' => [
                    ['code' => '2.1', 'kind' => 'Knw', 'label' => 'Penggunaan Speed/ Transmissi saat bergerak (Pastikan saat awal muatan harus dari C low)'], ['code' => '2.2', 'kind' => 'Knw', 'label' => 'Penggunaan Power/ Offroad Mode'], ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Penyesuaian tingkat kecepatan dengan kondisi medan (jalan turunan, mendatar, dan tanjakan)'], ['code' => '2.4', 'kind' => 'Skl', 'label' => 'Penggunaan Trailer Brake'], ['code' => '2.5', 'kind' => 'Skl', 'label' => 'Pengembalian haluan saat membelok/ditikungan'], ['code' => '2.6', 'kind' => 'Skl', 'label' => 'Ketrampilan/kelembutan mengemudi'], ['code' => '2.7', 'kind' => 'Skl', 'label' => 'Cycle time'],
                ]],
                ['title' => 'Dumping', 'subtitle' => 'Teknik pengambilan haluan, posisi dumping, penggunaan brake, dan prosedur dumping/vessel', 'items' => [
                    ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Pengambilan haluan untuk dumping/ manuver'], ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Posisi dumping (lokasi harus rata)'], ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penggunaan brake saat Dumping'], ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Prosedur Dumping (Penggunaan RPM)'], ['code' => '3.5', 'kind' => 'Knw', 'label' => 'Prosedur menurunkan Vessel'], ['code' => '3.6', 'kind' => 'Knw', 'label' => 'Penempatan material yang tepat di Hopper/Stock Pile'],
                ]],
            ],
        ];
    } elseif (empty($checklist) && in_array($categoryCode, ['WL'])) {
        $family = 'wheelloader';
        $checklist = [
            'groups' => [
                ['title' => 'Traveling', 'subtitle' => 'Pengoperasian wheel loader saat bergerak: speed, manuver, dan pemilihan jalur', 'items' => [
                    ['code' => '1.1', 'kind' => 'Skl', 'label' => 'Memposisikan attachment dengan benar'], ['code' => '1.2', 'kind' => 'Skl', 'label' => 'Penyesuaian speed dengan kondisi medan'], ['code' => '1.3', 'kind' => 'Skl', 'label' => 'Cara manuver & membelok di tikungan'],
                ]],
                ['title' => 'Scoping & Loading', 'subtitle' => 'Teknik scopping, loading, dan load & carry ke hauling truck', 'items' => [
                    ['code' => '2.1', 'kind' => 'Skl', 'label' => 'Cara memposisikan bucket pada saat scopping'], ['code' => '2.2', 'kind' => 'Skl', 'label' => 'Cara manuver/ pengoperasian load & carry'], ['code' => '2.3', 'kind' => 'Skl', 'label' => 'Cara loading ke hauling truck'], ['code' => '2.4', 'kind' => 'Knw', 'label' => 'Cycle Time'],
                ]],
                ['title' => 'Digging', 'subtitle' => 'Teknik digging: posisi unit, penetrasi bucket, dan kapasitas', 'items' => [
                    ['code' => '3.1', 'kind' => 'Skl', 'label' => 'Position unit'], ['code' => '3.2', 'kind' => 'Knw', 'label' => 'Teknik penetrasi bucket'], ['code' => '3.3', 'kind' => 'Skl', 'label' => 'Penyesuaian posisi lift arm'], ['code' => '3.4', 'kind' => 'Knw', 'label' => 'Kapasitas bucket'],
                ]],
                ['title' => 'Leveling', 'subtitle' => 'Teknik leveling: speed, tilt, steering, dan penempatan material', 'items' => [
                    ['code' => '4.1', 'kind' => 'Skl', 'label' => 'Penggunaan speed/transmisi saat bergerak'], ['code' => '4.2', 'kind' => 'Skl', 'label' => 'Cara leveling menggunakan tilt'], ['code' => '4.3', 'kind' => 'Skl', 'label' => 'Cara menghampar material untuk membuat jalan, menimbun lubang dll'], ['code' => '4.4', 'kind' => 'Skl', 'label' => 'Filling pada saat melevelkan area kerja'], ['code' => '4.5', 'kind' => 'Skl', 'label' => 'Penggunaan steering'],
                ]],
            ],
        ];
    }
@endphp

<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <div><h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Logbook Trainee</h2><p class="text-[11px] text-slate-500 mt-1">Tampilan read-only sesuai tipe alat: {{ $logbook->equipmentCategory->name ?? '-' }}</p></div>
        <span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-[#00593E] border border-emerald-200">{{ strtoupper($family ?: 'N/A') }}</span>
    </div>
    <div class="mx-5 mt-5 rounded-xl border border-slate-300 overflow-hidden text-[11px]">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="divide-y divide-slate-200 border-b md:border-b-0 md:border-r"><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">NAMA</span><span class="p-2">{{ $logbook->trainee->name }}</span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">HARI / TANGGAL</span><span class="p-2">{{ $logbook->date->format('d/m/Y') }}</span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">SHIFT</span><span class="p-2">Shift {{ $logbook->shift === 'day' ? '1 (Siang: 07.00 - 17.00)' : '2 (Malam: 19.00 - 05.00)' }}</span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">LOKASI (OJT)</span><span class="p-2">{{ $logbook->location }}</span></div></div>
            <div class="divide-y divide-slate-200"><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">PERUSAHAAN</span><span class="p-2">{{ data_get($payload, 'meta.company', 'PT BERAU COAL / PT MTL') }}</span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">TIPE ALAT</span><span class="p-2">{{ $logbook->equipmentCategory->name ?? '-' }} ({{ $categoryCode }})</span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">NO ALAT</span><span class="p-2">{{ $logbook->unit_code }}</span></div><div class="grid grid-cols-[130px_1fr]"><span class="p-2 font-bold bg-slate-50">HM / KM</span><span class="p-2">{{ number_format($logbook->hm_start, 1) }} → {{ number_format($logbook->hm_end, 1) }}</span></div></div>
        </div>
    </div>
    <div class="p-5 space-y-5">
        <div class="rounded-xl border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-4 py-3 border-b border-slate-200">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wide">Catatan Harian Trainee</h3>
            </div>
            <div class="p-4 text-xs font-mono whitespace-pre-line leading-relaxed text-slate-800 bg-white">
                {{ $logbook->daily_activity ?: '-' }}
            </div>
        </div>
        @forelse(data_get($checklist, 'groups', []) as $groupIndex => $group)
            <div class="rounded-xl border border-slate-200 overflow-hidden">
                <div class="bg-[#003829] px-4 py-3 text-white"><p class="text-xs font-bold uppercase">{{ $group['title'] ?? 'Checklist Unit '.($groupIndex + 1) }}</p><p class="text-[10px] text-emerald-100 mt-1">{{ $group['subtitle'] ?? 'Tipe kompetensi sesuai SOP unit' }}</p></div>
                <div class="overflow-x-auto"><table class="min-w-[760px] w-full table-fixed text-xs"><colgroup><col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72"></colgroup><thead class="bg-slate-50 text-slate-500 uppercase"><tr><th class="px-3 py-2 text-left">No</th><th class="px-3 py-2 text-left">Tipe</th><th class="px-3 py-2 text-left">Item Evaluasi</th><th class="px-3 py-2 text-center">K</th><th class="px-3 py-2 text-center">BK</th><th class="px-3 py-2 text-left">Catatan Penguji</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach($group['items'] ?? [] as $itemIndex => $item)<tr class="align-top"><td class="px-3 py-3 font-bold">{{ $item['code'] ?? ($groupIndex + 1).'.'.($itemIndex + 1) }}</td><td class="px-3 py-3 text-slate-500">{{ $item['kind'] ?? '-' }}</td><td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] ?? 'Item checklist SOP' }}</td><td class="px-3 py-3 text-center">@if(($item['status'] ?? null) === 'K')<span class="text-[#00A859] font-black">✓</span>@endif</td><td class="px-3 py-3 text-center">@if(($item['status'] ?? null) === 'BK')<span class="text-rose-600 font-black">✓</span>@endif</td><td class="px-3 py-3 text-slate-500 leading-relaxed break-words">{{ $item['note'] ?? '-' }}</td></tr>@endforeach</tbody></table></div>
            </div>
        @empty
            <div class="p-5 text-xs text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">Checklist detail belum tersedia pada pengajuan ini. Logbook baru akan menyimpan setiap judul dan item SOP sesuai tipe alatnya.</div>
        @endforelse
        @if(data_get($checklist, 'compliance'))
            <div class="rounded-xl border border-slate-200 overflow-hidden"><div class="bg-[#003829] px-4 py-3 text-white"><p class="text-xs font-bold uppercase">Kepatuhan Terhadap Peraturan Kerja</p></div><div class="overflow-x-auto"><table class="min-w-[760px] w-full table-fixed text-xs"><colgroup><col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72"></colgroup><thead class="bg-slate-50 text-slate-500 uppercase"><tr><th class="px-3 py-2 text-left">No</th><th class="px-3 py-2 text-left">Tipe</th><th class="px-3 py-2 text-left">Item Evaluasi</th><th class="px-3 py-2 text-center">K</th><th class="px-3 py-2 text-center">BK</th><th class="px-3 py-2 text-left">Catatan Penguji</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach(data_get($checklist, 'compliance', []) as $itemIndex => $item)<tr class="align-top"><td class="px-3 py-3 font-bold">{{ $item['code'] ?? ($itemIndex + 1) }}</td><td class="px-3 py-3 text-slate-500">{{ $item['kind'] ?? '-' }}</td><td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] ?? 'Item kepatuhan' }}</td><td class="px-3 py-3 text-center">@if(($item['status'] ?? null) === 'K')<span class="text-[#00A859] font-black">✓</span>@endif</td><td class="px-3 py-3 text-center">@if(($item['status'] ?? null) === 'BK')<span class="text-rose-600 font-black">✓</span>@endif</td><td class="px-3 py-3 text-slate-500 leading-relaxed break-words">{{ $item['note'] ?? '-' }}</td></tr>@endforeach</tbody></table></div></div>
        @endif
        @if(data_get($checklist, 'behavior'))
            <div class="rounded-xl border border-slate-200 overflow-hidden"><div class="bg-[#003829] px-4 py-3 text-white"><p class="text-xs font-bold uppercase">Kedisiplinan dan Komunikasi</p></div><div class="overflow-x-auto"><table class="min-w-[760px] w-full table-fixed text-xs"><colgroup><col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72"></colgroup><thead class="bg-slate-50 text-slate-500 uppercase"><tr><th class="px-3 py-2 text-left">No</th><th class="px-3 py-2 text-left">Tipe</th><th class="px-3 py-2 text-left">Item Evaluasi</th><th class="px-3 py-2 text-center">K</th><th class="px-3 py-2 text-center">BK</th><th class="px-3 py-2 text-left">Catatan Penguji</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach(data_get($checklist, 'behavior', []) as $itemIndex => $item)<tr class="align-top"><td class="px-3 py-3 font-bold">{{ $item['code'] ?? ($itemIndex + 1) }}</td><td class="px-3 py-3 text-slate-500">{{ $item['kind'] ?? '-' }}</td><td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] ?? 'Item kedisiplinan' }}</td><td class="px-3 py-3 text-center">@if(($item['status'] ?? null) === 'K')<span class="text-[#00A859] font-black">✓</span>@endif</td><td class="px-3 py-3 text-center">@if(($item['status'] ?? null) === 'BK')<span class="text-rose-600 font-black">✓</span>@endif</td><td class="px-3 py-3 text-slate-500 leading-relaxed break-words">{{ $item['note'] ?? '-' }}</td></tr>@endforeach</tbody></table></div></div>
        @endif
    </div>
</section>
