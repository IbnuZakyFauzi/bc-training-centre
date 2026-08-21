@php
    $payload = $logbook->sop_payload ?? [];
    $categoryCode = $logbook->equipmentCategory->code ?? '';

    // Tentukan family: utamakan meta.unit_family, fallback ke kode kategori alat
    // (beberapa logbook lama menyimpan meta.unit_family = null).
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

    // Bangun checklist secara dinamis dari definisi kanonik (sama persis dengan
    // yang diisi Trainee), lalu gabungkan nilai (status) dan feedback trainee
    // yang tersimpan berdasarkan posisi item.
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
@endphp

<section class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <div><h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Logbook Trainee</h2><p class="text-[11px] text-slate-500 mt-1">Tampilan read-only sesuai tipe alat: {{ $logbook->equipmentCategory->name ?? '-' }}</p><p class="text-[10px] text-slate-400 mt-1">Nilai skala trainee dikonversi otomatis: 1 (Belum) &amp; 2 (Cukup) = <span class="font-bold text-rose-600">BK</span>, 3 (Bisa) &amp; 4 (Mahir) = <span class="font-bold text-[#00A859]">K</span>.</p></div>
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
                <div class="overflow-x-auto"><table class="min-w-[760px] w-full table-fixed text-xs"><colgroup><col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72"></colgroup><thead class="bg-slate-50 text-slate-500 uppercase"><tr><th class="px-3 py-2 text-left">No</th><th class="px-3 py-2 text-left">Tipe</th><th class="px-3 py-2 text-left">Item Evaluasi</th><th class="px-3 py-2 text-center">K</th><th class="px-3 py-2 text-center">BK</th><th class="px-3 py-2 text-left">Trainee Feedback</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach($group['items'] ?? [] as $itemIndex => $item)<tr class="align-top"><td class="px-3 py-3 font-bold">{{ $item['code'] ?? ($groupIndex + 1).'.'.($itemIndex + 1) }}</td><td class="px-3 py-3 text-slate-500">{{ $item['kind'] ?? '-' }}</td><td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] ?? 'Item checklist SOP' }}</td>@include('ojt.logbooks.partials.kbk-readonly', ['status' => $item['status'] ?? null])<td class="px-3 py-3 text-slate-500 leading-relaxed break-words">{{ $item['trainee_feedback'] ?? $item['note'] ?? '-' }}</td></tr>@endforeach</tbody></table></div>
            </div>
        @empty
            <div class="p-5 text-xs text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">Checklist detail belum tersedia pada pengajuan ini. Logbook baru akan menyimpan setiap judul dan item SOP sesuai tipe alatnya.</div>
        @endforelse
        @if(data_get($checklist, 'compliance'))
            <div class="rounded-xl border border-slate-200 overflow-hidden"><div class="bg-[#003829] px-4 py-3 text-white"><p class="text-xs font-bold uppercase">Kepatuhan Terhadap Peraturan Kerja</p></div><div class="overflow-x-auto"><table class="min-w-[760px] w-full table-fixed text-xs"><colgroup><col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72"></colgroup><thead class="bg-slate-50 text-slate-500 uppercase"><tr><th class="px-3 py-2 text-left">No</th><th class="px-3 py-2 text-left">Tipe</th><th class="px-3 py-2 text-left">Item Evaluasi</th><th class="px-3 py-2 text-center">K</th><th class="px-3 py-2 text-center">BK</th><th class="px-3 py-2 text-left">Trainee Feedback</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach(data_get($checklist, 'compliance', []) as $itemIndex => $item)<tr class="align-top"><td class="px-3 py-3 font-bold">{{ $item['code'] ?? ($itemIndex + 1) }}</td><td class="px-3 py-3 text-slate-500">{{ $item['kind'] ?? '-' }}</td><td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] ?? 'Item kepatuhan' }}</td>@include('ojt.logbooks.partials.kbk-readonly', ['status' => $item['status'] ?? null])<td class="px-3 py-3 text-slate-500 leading-relaxed break-words">{{ $item['trainee_feedback'] ?? $item['note'] ?? '-' }}</td></tr>@endforeach</tbody></table></div></div>
        @endif
        @if(data_get($checklist, 'behavior'))
            <div class="rounded-xl border border-slate-200 overflow-hidden"><div class="bg-[#003829] px-4 py-3 text-white"><p class="text-xs font-bold uppercase">Kedisiplinan dan Komunikasi</p></div><div class="overflow-x-auto"><table class="min-w-[760px] w-full table-fixed text-xs"><colgroup><col class="w-14"><col class="w-16"><col><col class="w-14"><col class="w-14"><col class="w-72"></colgroup><thead class="bg-slate-50 text-slate-500 uppercase"><tr><th class="px-3 py-2 text-left">No</th><th class="px-3 py-2 text-left">Tipe</th><th class="px-3 py-2 text-left">Item Evaluasi</th><th class="px-3 py-2 text-center">K</th><th class="px-3 py-2 text-center">BK</th><th class="px-3 py-2 text-left">Trainee Feedback</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach(data_get($checklist, 'behavior', []) as $itemIndex => $item)<tr class="align-top"><td class="px-3 py-3 font-bold">{{ $item['code'] ?? ($itemIndex + 1) }}</td><td class="px-3 py-3 text-slate-500">{{ $item['kind'] ?? '-' }}</td><td class="px-3 py-3 text-slate-700 leading-relaxed">{{ $item['label'] ?? 'Item kedisiplinan' }}</td>@include('ojt.logbooks.partials.kbk-readonly', ['status' => $item['status'] ?? null])<td class="px-3 py-3 text-slate-500 leading-relaxed break-words">{{ $item['trainee_feedback'] ?? $item['note'] ?? '-' }}</td></tr>@endforeach</tbody></table></div></div>
        @endif
    </div>
</section>
