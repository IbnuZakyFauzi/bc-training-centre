{{--
    Dua kolom K / BK read-only untuk tampilan View Detail Logbook
    (Trainer & Admin Training Centre).

    Nilai skala yang tersimpan dikonversi otomatis:
        1 (Belum) / 2 (Cukup) -> centang read-only pada kolom BK
        3 (Bisa)  / 4 (Mahir) -> centang read-only pada kolom K

    Parameter:
        $status : nilai tersimpan pada item evaluasi (1-4, atau legacy 'K'/'BK')
--}}
@php
    $kbkStatus = \App\Support\CompetencyScale::toStatus($status ?? null);
    $kbkScale = \App\Support\CompetencyScale::toScale($status ?? null);
    $kbkLabel = \App\Support\CompetencyScale::label($status ?? null);
    $kbkTitle = $kbkScale ? 'Nilai trainee: ' . $kbkScale . ' (' . $kbkLabel . ')' : 'Belum dinilai';
@endphp
<td class="px-3 py-3 text-center">@if($kbkStatus === \App\Support\CompetencyScale::STATUS_KOMPETEN)<span title="{{ $kbkTitle }}" class="inline-flex h-5 w-5 items-center justify-center rounded border border-amber-300 bg-amber-50 text-[11px] font-black text-amber-600">&#10003;</span>@else<span class="inline-flex h-5 w-5 rounded border border-slate-200 bg-slate-50"></span>@endif</td>
<td class="px-3 py-3 text-center">@if($kbkStatus === \App\Support\CompetencyScale::STATUS_BELUM_KOMPETEN)<span title="{{ $kbkTitle }}" class="inline-flex h-5 w-5 items-center justify-center rounded border border-rose-300 bg-rose-50 text-[11px] font-black text-rose-600">&#10003;</span>@else<span class="inline-flex h-5 w-5 rounded border border-slate-200 bg-slate-50"></span>@endif</td>


