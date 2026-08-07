<x-app-layout>
    <x-slot name="title">Edit Logbook {{ $logbook->logbook_number }}</x-slot>

    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('trainer.reviews.show', $logbook->id) }}" class="text-xs font-bold text-[#00A859] hover:underline">← Kembali ke review</a>
            <h1 class="mt-2 text-xl font-extrabold text-slate-800">Edit Logbook {{ $logbook->logbook_number }}</h1>
            <p class="mt-1 text-xs text-slate-500">Perubahan disimpan oleh trainer sebelum logbook disetujui.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('trainer.reviews.update', $logbook->id) }}" class="space-y-6">
        @csrf
        @method('PUT')
        @if($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-800">{{ $errors->first() }}</div>
        @endif
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-5 md:grid-cols-2">
                <label class="text-xs font-bold text-slate-700">Tanggal<input type="date" name="date" value="{{ old('date', $logbook->date->format('Y-m-d')) }}" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">Shift<select name="shift" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"><option value="day" @selected(old('shift', $logbook->shift) === 'day')>Shift 1</option><option value="night" @selected(old('shift', $logbook->shift) === 'night')>Shift 2</option></select></label>
                <label class="text-xs font-bold text-slate-700">Nomor alat<input type="text" name="equipment_number" value="{{ old('equipment_number', $logbook->equipment_number ?? $logbook->equipment?->unit_code) }}" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">Lokasi<input type="text" name="location" value="{{ old('location', $logbook->location) }}" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">Jam mulai<input type="time" name="start_time" value="{{ old('start_time', $logbook->start_time) }}" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">Jam selesai<input type="time" name="finish_time" value="{{ old('finish_time', $logbook->finish_time) }}" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">HM awal<input type="number" step="0.1" name="hm_start" value="{{ old('hm_start', $logbook->hm_start) }}" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
                <label class="text-xs font-bold text-slate-700">HM akhir<input type="number" step="0.1" name="hm_end" value="{{ old('hm_end', $logbook->hm_end) }}" required class="mt-2 w-full rounded-xl border-slate-300 text-xs"></label>
            </div>
            <label class="mt-5 block text-xs font-bold text-slate-700">Catatan kegiatan<textarea name="daily_activity" rows="7" required class="mt-2 w-full rounded-xl border-slate-300 text-xs">{{ old('daily_activity', $logbook->daily_activity) }}</textarea></label>
        </section>
        <div class="flex justify-end gap-3"><a href="{{ route('trainer.reviews.show', $logbook->id) }}" class="rounded-xl bg-slate-100 px-5 py-3 text-xs font-bold text-slate-700">Batal</a><button class="rounded-xl bg-[#00A859] px-5 py-3 text-xs font-bold text-white">Simpan Perubahan</button></div>
    </form>

    @include('trainer.reviews.partials.checklist-editor')
</x-app-layout>
