@php
    $payload = $logbook->sop_payload ?? [];
    $family = data_get($payload, 'meta.unit_family');
    $checklist = data_get($payload, $family, []);
@endphp

<form method="POST" action="{{ route('ojt.logbooks.checklist.update', $logbook->id) }}" class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    @csrf
    @method('PUT')
    <div class="mb-4"><h2 class="text-sm font-bold text-slate-800">Edit Checklist K / BK</h2><p class="mt-1 text-[11px] text-slate-500">Lanjutkan pengisian checklist SOP sebelum submit logbook.</p></div>
    @foreach(data_get($checklist, 'groups', []) as $groupIndex => $group)
        <div class="mb-5 overflow-hidden rounded-xl border border-slate-200">
            <div class="bg-[#003829] px-4 py-3 text-xs font-bold uppercase text-white">{{ $group['title'] ?? 'Checklist' }}</div>
            <div class="overflow-x-auto"><table class="min-w-[760px] w-full table-fixed text-xs"><colgroup><col class="w-20"><col><col class="w-16"><col class="w-16"><col class="w-80"></colgroup><thead class="bg-slate-50 text-slate-500"><tr><th class="p-3 text-left">No</th><th class="p-3 text-left">Item Evaluasi</th><th class="p-3 text-center">K</th><th class="p-3 text-center">BK</th><th class="p-3 text-left">Catatan Penguji</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach($group['items'] ?? [] as $itemIndex => $item)<tr><td class="p-3 font-bold align-middle">{{ $item['code'] ?? ($itemIndex + 1) }}</td><td class="p-3 leading-relaxed align-middle">{{ $item['label'] ?? '-' }}</td><td class="p-3 text-center align-middle"><input required type="radio" name="checklist[groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="K" @checked(($item['status'] ?? '') === 'K')></td><td class="p-3 text-center align-middle"><input type="radio" name="checklist[groups][{{ $groupIndex }}][items][{{ $itemIndex }}][status]" value="BK" @checked(($item['status'] ?? '') === 'BK')></td><td class="p-3 align-middle"><input name="checklist[groups][{{ $groupIndex }}][items][{{ $itemIndex }}][note]" value="{{ $item['note'] ?? '' }}" class="w-full rounded border-slate-300 text-xs"></td></tr>@endforeach</tbody></table></div>
        </div>
    @endforeach
    @if(data_get($checklist, 'behavior'))
        <div class="mb-5 overflow-hidden rounded-xl border border-slate-200"><div class="bg-[#003829] px-4 py-3 text-xs font-bold uppercase text-white">Kedisiplinan dan Komunikasi</div><div class="overflow-x-auto"><table class="min-w-[760px] w-full table-fixed text-xs"><colgroup><col class="w-20"><col><col class="w-16"><col class="w-16"><col class="w-80"></colgroup><thead class="bg-slate-50 text-slate-500"><tr><th class="p-3 text-left">No</th><th class="p-3 text-left">Item Evaluasi</th><th class="p-3 text-center">K</th><th class="p-3 text-center">BK</th><th class="p-3 text-left">Catatan Penguji</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach(data_get($checklist, 'behavior', []) as $itemIndex => $item)<tr><td class="p-3 font-bold align-middle">{{ $item['code'] ?? ($itemIndex + 1) }}</td><td class="p-3 leading-relaxed align-middle">{{ $item['label'] ?? '-' }}</td><td class="p-3 text-center align-middle"><input required type="radio" name="checklist[behavior][{{ $itemIndex }}][status]" value="K" @checked(($item['status'] ?? '') === 'K')></td><td class="p-3 text-center align-middle"><input type="radio" name="checklist[behavior][{{ $itemIndex }}][status]" value="BK" @checked(($item['status'] ?? '') === 'BK')></td><td class="p-3 align-middle"><input name="checklist[behavior][{{ $itemIndex }}][note]" value="{{ $item['note'] ?? '' }}" class="w-full rounded border-slate-300 text-xs"></td></tr>@endforeach</tbody></table></div></div>
    @endif
    <div class="flex justify-end"><button class="rounded-xl bg-[#003829] px-5 py-3 text-xs font-bold text-white">Simpan Checklist</button></div>
</form>
