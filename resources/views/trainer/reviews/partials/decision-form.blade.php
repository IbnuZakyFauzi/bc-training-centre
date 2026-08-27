@php($trainer = auth()->user())
<section class="mt-4 sm:mt-8 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ action: '{{ old('action', 'verify') }}' }">
    <div class="bg-[#1e3a8a] px-4 sm:px-6 py-3 sm:py-4 text-white">
        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-300">Keputusan Trainer Evaluator</p>
        <h2 class="mt-1 text-xs sm:text-sm font-bold">Approval Form OJT</h2>
    </div>

    @if($logbook->status === 'verified')
        <div class="p-4 sm:p-6 text-xs text-blue-900">
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-3 sm:p-4">
                <p class="font-bold">Form OJT telah diverifikasi.</p>
                @if($logbook->evaluation?->trainer_signature_path)
                    <img src="{{ asset('storage/'.$logbook->evaluation->trainer_signature_path) }}" alt="Tanda tangan trainer" class="mt-3 max-h-20 sm:max-h-24 w-auto object-contain bg-white rounded-lg border border-blue-200 p-2">
                    <a href="{{ asset('storage/'.$logbook->evaluation->trainer_signature_path) }}" target="_blank" class="mt-2 inline-flex font-bold text-[#1d4ed8] hover:underline">Lihat tanda tangan digital</a>
                @endif
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('trainer.reviews.evaluate', $logbook->id) }}" class="p-4 sm:p-6 space-y-4 sm:space-y-5">
            @csrf
            <input type="hidden" name="safety" value="3">
            <input type="hidden" name="operation" value="3">
            <input type="hidden" name="procedure" value="3">
            <input type="hidden" name="communication" value="3">
            <input type="hidden" name="competency_status" value="competent">

            <div class="grid grid-cols-1 gap-4 sm:gap-5">
                <label class="block rounded-xl border border-blue-200 bg-blue-50 p-4 sm:p-5 cursor-pointer">
                    <span class="flex items-center gap-2 text-xs font-bold text-[#1d4ed8]"><input type="radio" name="action" value="verify" x-model="action"> Setujui & Verifikasi Form OJT</span>
                    <span class="mt-2 block text-[10px] sm:text-[11px] leading-relaxed text-blue-800">Tanda tangan dari My Profile akan dipakai otomatis saat approve.</span>
                    @if($trainer?->signature_path)
                            <img src="{{ asset('storage/'.$trainer->signature_path) }}" alt="Signature profile" class="mt-3 sm:mt-4 max-h-16 sm:max-h-20 w-auto object-contain bg-white rounded-lg border border-blue-200 p-2">
                        <span class="mt-2 block text-[10px] text-slate-500">Signature tersimpan di profil dan tidak perlu diunggah lagi.</span>
                    @else
                        <div class="mt-3 sm:mt-4 rounded-lg border border-amber-200 bg-amber-50 p-3 text-[10px] sm:text-[11px] text-amber-900">
                            Simpan tanda tangan dulu di <a href="{{ route('profile.edit') }}" class="font-bold underline">My Profile</a> sebelum verifikasi.
                        </div>
                    @endif
                </label>

                <label class="flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 p-4 sm:p-5 cursor-pointer">
                    <input type="radio" name="action" value="revision" x-model="action" class="accent-amber-600">
                    <span class="text-xs font-bold text-amber-900">Minta Revisi ke Trainee</span>
                </label>
            </div>

            <div x-show="action === 'revision'" x-cloak class="rounded-xl border border-amber-200 bg-amber-50 p-3 sm:p-4">
                <label for="revision_instruction" class="text-xs font-bold text-amber-900">Instruksi Revisi <span class="font-normal text-amber-700">(wajib diisi saat meminta revisi)</span></label>
                <textarea id="revision_instruction" name="revision_instruction" rows="4" class="mt-2 w-full rounded-xl border-amber-300 bg-white text-xs min-h-[44px]"                     placeholder="Jelaskan poin-poin yang harus diperbaiki trainee sebelum form OJT disetujui...">{{ old('revision_instruction') }}</textarea>
            </div>

            <div>
                <label for="trainer_comment" class="text-xs font-bold text-slate-700">Catatan Instruktur <span class="font-normal text-slate-400">(catatan ini akan masuk ke dokumen akhir)</span></label>
                <textarea id="trainer_comment" name="trainer_comment" rows="4" class="mt-2 w-full rounded-xl border-slate-300 text-xs min-h-[44px]" placeholder="Masukkan catatan instruktur untuk trainee...">{{ old('trainer_comment') }}</textarea>
            </div>

            @if($errors->any())<p class="text-xs text-rose-600">{{ $errors->first() }}</p>@endif
            <div class="flex flex-col sm:flex-row justify-end"><button class="rounded-xl bg-[#2563eb] px-4 sm:px-5 py-3 text-xs font-bold text-white hover:bg-blue-600 min-h-[44px]">Simpan Keputusan</button></div>
        </form>
    @endif
</section>

