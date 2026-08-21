{{--
    Kolom penilaian item evaluasi (form pengisian Trainee & Trainer).

    Menggantikan toggle K / BK dengan skala angka 1-4:
        1 (Belum) & 2 (Cukup) -> ekivalen BK pada dokumen akhir
        3 (Bisa)  & 4 (Mahir) -> ekivalen K pada dokumen akhir

    Parameter (pilih salah satu cara pakai):
      A. Form logbook (sop_payload):
         $itemPath        : path item di dalam sop_payload tanpa prefix, contoh
                            "track.groups.0.items.3" atau "track.compliance.2"
         $formPayload     : (opsional, diwarisi view induk) payload lama untuk prefill
      B. Nama input custom:
         $scaleInputName  : nama input radio secara eksplisit
         $scaleValueRaw   : nilai tersimpan saat ini (1-4 atau legacy 'K'/'BK')

    Opsional:
         $scaleRequired   : true bila pemilihan wajib

    Nilai yang dikirim ke server selalu 1|2|3|4.

    Interaksi:
      - Klik salah satu angka untuk memilih; klik lagi angka yang sama
        untuk membatalkan pilihan (toggle off).
      - Opsi terpilih selalu berwarna hijau (emerald), Baik 1/2 maupun 3/4.
      - Input radio disembunyikan dengan overlay transparan (bukan `sr-only`)
        agar fokus tidak memicu scroll pada kontainer utama `overflow-y-auto`.
--}}
@php
    if (isset($scaleInputName)) {
        $scaleName = $scaleInputName;
        $scaleRaw = $scaleValueRaw ?? null;
    } else {
        $scaleName = 'sop_payload[' . implode('][', explode('.', $itemPath)) . '][status]';
        $scaleRaw = old('sop_payload.' . $itemPath . '.status', data_get($formPayload ?? [], $itemPath . '.status'));
    }

    $scaleCurrent = \App\Support\CompetencyScale::toScale($scaleRaw);
    $scaleOptions = \App\Support\CompetencyScale::options();
    $scaleIsRequired = (bool) ($scaleRequired ?? false);
    $scaleInitial = $scaleCurrent ?: 'null';
@endphp
<div x-data="{ value: {{ $scaleInitial }}, toggle(v) { this.value = (this.value == v) ? null : v; } }"
     class="flex items-stretch gap-1" role="radiogroup" aria-label="Penilaian item evaluasi (1-4)">
    @foreach($scaleOptions as $scaleValue => $scaleLabel)
        <label class="group relative flex-1 cursor-pointer select-none" title="{{ $scaleValue }} - {{ $scaleLabel }}">
            <input type="radio" name="{{ $scaleName }}" value="{{ $scaleValue }}" @required($scaleIsRequired)
                   :value="{{ $scaleValue }}" :checked="value == {{ $scaleValue }}" @click="toggle({{ $scaleValue }})"
                   class="peer absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0">
            <span class="pointer-events-none flex h-9 w-full flex-col items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition group-hover:border-slate-300 group-hover:bg-slate-50 peer-checked:border-transparent peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-sm peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-300 peer-focus-visible:ring-offset-1">
                <span class="text-[11px] font-black leading-none">{{ $scaleValue }}</span>
                <span class="mt-0.5 text-[8px] font-bold uppercase leading-none tracking-tight">{{ $scaleLabel }}</span>
            </span>
        </label>
    @endforeach
</div>
