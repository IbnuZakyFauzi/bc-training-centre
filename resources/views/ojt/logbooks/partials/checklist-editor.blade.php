@php
    $payload = $logbook->sop_payload ?? [];
    $family = data_get($payload, 'meta.unit_family');
    $checklist = data_get($payload, $family, []);
@endphp

<form method="POST" action="{{ route('ojt.logbooks.checklist.update', $logbook->id) }}" class="mt-8 rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 shadow-sm">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <h2 class="text-sm font-bold text-slate-800">Edit Penilaian Item Evaluasi (Skala 1 - 4)</h2>
        <p class="mt-1 text-[11px] text-slate-500">Lanjutkan pengisian checklist SOP sebelum submit form OJT. 1 (Belum) &amp; 2 (Cukup) = BK, 3 (Mampu) &amp; 4 (Mahir) = K pada dokumen akhir.</p>
    </div>
    @foreach(data_get($checklist, 'groups', []) as $groupIndex => $group)
        <div class="mb-5 overflow-hidden rounded-2xl border border-slate-200">
            <div class="bg-[#1e3a8a] px-4 py-3 text-xs font-bold uppercase text-white">{{ $group['title'] ?? 'Checklist' }}</div>
            <div class="w-full">
                <table class="w-full text-xs block md:table">
                    <colgroup class="hidden md:table-column-group">
                        <col class="w-20"><col><col class="w-[200px]"><col class="w-80">
                    </colgroup>
                    <thead class="hidden md:table-header-group bg-slate-50 text-slate-500">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">Item Evaluasi</th>
                            <th class="p-3 text-center">Penilaian (1-4)</th>
                            <th class="p-3 text-left">Trainee Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group divide-y divide-slate-100">
                        @foreach($group['items'] ?? [] as $itemIndex => $item)
                            <tr class="block md:table-row p-3.5 space-y-2.5 bg-white md:p-0 md:space-y-0">
                                <td class="block md:table-cell p-0 md:p-3 font-bold align-middle">
                                    <span class="inline-flex md:inline px-2 py-0.5 rounded bg-blue-50 text-[#1e3a8a] text-[10px] font-black border border-blue-200 md:bg-transparent md:text-slate-700 md:border-0 md:p-0 md:text-xs">
                                        {{ $item['code'] ?? ($itemIndex + 1) }}
                                    </span>
                                </td>
                                <td class="block md:table-cell p-0 md:p-3 leading-relaxed align-middle text-slate-700 font-medium md:font-normal break-words">
                                    {{ $item['label'] ?? '-' }}
                                </td>
                                <td class="block md:table-cell p-0 md:p-2 align-middle">
                                    <div class="md:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Penilaian (1-4):</div>
                                    @include('ojt.logbooks.partials.scale-cell', ['scaleInputName' => 'checklist[groups]['.$groupIndex.'][items]['.$itemIndex.'][status]', 'scaleValueRaw' => $item['status'] ?? null, 'scaleRequired' => true])
                                </td>
                                <td class="block md:table-cell p-0 md:p-3 align-middle">
                                    <div class="md:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Trainee Feedback:</div>
                                    <input name="checklist[groups][{{ $groupIndex }}][items][{{ $itemIndex }}][note]" value="{{ $item['note'] ?? '' }}" placeholder="Feedback / catatan..." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs leading-relaxed focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
    @if(data_get($checklist, 'behavior'))
        <div class="mb-5 overflow-hidden rounded-2xl border border-slate-200">
            <div class="bg-[#1e3a8a] px-4 py-3 text-xs font-bold uppercase text-white">Kedisiplinan dan Komunikasi</div>
            <div class="w-full">
                <table class="w-full text-xs block md:table">
                    <colgroup class="hidden md:table-column-group">
                        <col class="w-20"><col><col class="w-[200px]"><col class="w-80">
                    </colgroup>
                    <thead class="hidden md:table-header-group bg-slate-50 text-slate-500">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">Item Evaluasi</th>
                            <th class="p-3 text-center">Penilaian (1-4)</th>
                            <th class="p-3 text-left">Trainee Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="block md:table-row-group divide-y divide-slate-100">
                        @foreach(data_get($checklist, 'behavior', []) as $itemIndex => $item)
                            <tr class="block md:table-row p-3.5 space-y-2.5 bg-white md:p-0 md:space-y-0">
                                <td class="block md:table-cell p-0 md:p-3 font-bold align-middle">
                                    <span class="inline-flex md:inline px-2 py-0.5 rounded bg-blue-50 text-[#1e3a8a] text-[10px] font-black border border-blue-200 md:bg-transparent md:text-slate-700 md:border-0 md:p-0 md:text-xs">
                                        {{ $item['code'] ?? ($itemIndex + 1) }}
                                    </span>
                                </td>
                                <td class="block md:table-cell p-0 md:p-3 leading-relaxed align-middle text-slate-700 font-medium md:font-normal break-words">
                                    {{ $item['label'] ?? '-' }}
                                </td>
                                <td class="block md:table-cell p-0 md:p-2 align-middle">
                                    <div class="md:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Penilaian (1-4):</div>
                                    @include('ojt.logbooks.partials.scale-cell', ['scaleInputName' => 'checklist[behavior]['.$itemIndex.'][status]', 'scaleValueRaw' => $item['status'] ?? null, 'scaleRequired' => true])
                                </td>
                                <td class="block md:table-cell p-0 md:p-3 align-middle">
                                    <div class="md:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Trainee Feedback:</div>
                                    <input name="checklist[behavior][{{ $itemIndex }}][note]" value="{{ $item['note'] ?? '' }}" placeholder="Feedback / catatan..." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs leading-relaxed focus:ring-2 focus:ring-brand-500 focus:bg-white transition">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
    <div class="flex justify-end">
        <button class="rounded-xl bg-[#1e3a8a] px-4 sm:px-5 py-3 text-xs font-bold text-white min-h-[44px]">Simpan Checklist</button>
    </div>
</form>

