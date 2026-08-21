@php
    $payload = $logbook->sop_payload ?? [];
    $family = data_get($payload, 'meta.unit_family');
    $checklist = data_get($payload, $family, []);
@endphp

<form method="POST" action="{{ route('trainer.reviews.checklist.update', $logbook->id) }}" class="mt-8 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    @csrf
    @method('PUT')
    <div class="mb-4"><h2 class="text-sm font-bold text-slate-800">Edit Penilaian Item Evaluasi (Skala 1 - 4)</h2><p class="mt-1 text-[11px] text-slate-500">Trainer dapat menyesuaikan level nilai sebelum approval. 1 (Belum) &amp; 2 (Cukup) = BK, 3 (Bisa) &amp; 4 (Mahir) = K pada dokumen akhir.</p></div>
    @foreach(data_get($checklist, 'groups', []) as $groupIndex => $group)
        <div class="mb-5 overflow-hidden rounded-xl border border-slate-200">
            <div class="bg-[#003829] px-4 py-3 text-xs font-bold uppercase text-white">{{ $group['title'] ?? 'Checklist' }}</div>
            <div class="overflow-x-auto"><table class="min-w-[880px] w-full table-fixed text-xs"><colgroup><col class="w-20"><col><col class="w-[196px]"><col class="w-80"></colgroup><thead class="bg-slate-50 text-slate-500"><tr><th class="p-3 text-left">No</th><th class="p-3 text-left">Item Evaluasi</th><th class="p-3 text-center">Penilaian (1-4)</th><th class="p-3 text-left">Trainee Feedback</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach($group['items'] ?? [] as $itemIndex => $item)<tr><td class="p-3 font-bold align-middle">{{ $item['code'] ?? ($itemIndex + 1) }}</td><td class="p-3 leading-relaxed align-middle">{{ $item['label'] ?? '-' }}</td><td class="p-2 align-middle">@include('ojt.logbooks.partials.scale-cell', ['scaleInputName' => 'checklist[groups]['.$groupIndex.'][items]['.$itemIndex.'][status]', 'scaleValueRaw' => $item['status'] ?? null, 'scaleRequired' => true])</td><td class="p-3 align-middle"><input name="checklist[groups][{{ $groupIndex }}][items][{{ $itemIndex }}][note]" value="{{ $item['note'] ?? '' }}" class="w-full rounded border-slate-300 text-xs"></td></tr>@endforeach</tbody></table></div>
        </div>
    @endforeach
    @if(data_get($checklist, 'behavior'))
        <div class="mb-5 overflow-hidden rounded-xl border border-slate-200"><div class="bg-[#003829] px-4 py-3 text-xs font-bold uppercase text-white">Kedisiplinan dan Komunikasi</div><div class="overflow-x-auto"><table class="min-w-[880px] w-full table-fixed text-xs"><colgroup><col class="w-20"><col><col class="w-[196px]"><col class="w-80"></colgroup><thead class="bg-slate-50 text-slate-500"><tr><th class="p-3 text-left">No</th><th class="p-3 text-left">Item Evaluasi</th><th class="p-3 text-center">Penilaian (1-4)</th><th class="p-3 text-left">Trainee Feedback</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach(data_get($checklist, 'behavior', []) as $itemIndex => $item)<tr><td class="p-3 font-bold align-middle">{{ $item['code'] ?? ($itemIndex + 1) }}</td><td class="p-3 leading-relaxed align-middle">{{ $item['label'] ?? '-' }}</td><td class="p-2 align-middle">@include('ojt.logbooks.partials.scale-cell', ['scaleInputName' => 'checklist[behavior]['.$itemIndex.'][status]', 'scaleValueRaw' => $item['status'] ?? null, 'scaleRequired' => true])</td><td class="p-3 align-middle"><input name="checklist[behavior][{{ $itemIndex }}][note]" value="{{ $item['note'] ?? '' }}" class="w-full rounded border-slate-300 text-xs"></td></tr>@endforeach</tbody></table></div></div>
    @endif
    <div class="flex justify-end"><button class="rounded-xl bg-[#003829] px-5 py-3 text-xs font-bold text-white">Simpan Checklist</button></div>
</form>
