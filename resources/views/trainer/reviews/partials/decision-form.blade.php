@php($trainer = auth()->user())
<section class="mt-8 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ action: '{{ old('action', 'verify') }}' }">
    <div class="bg-[#003829] px-6 py-4 text-white">
        <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-300">Keputusan Trainer Evaluator</p>
        <h2 class="mt-1 text-sm font-bold">Approval Logbook</h2>
    </div>

    @if($logbook->status === 'verified')
        <div class="p-6 text-xs text-emerald-900">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <p class="font-bold">Logbook telah diverifikasi.</p>
                @if($logbook->evaluation?->trainer_signature_path)
                    <img src="{{ asset('storage/'.$logbook->evaluation->trainer_signature_path) }}" alt="Tanda tangan trainer" class="mt-3 max-h-24 w-auto object-contain bg-white rounded-lg border border-emerald-200 p-2">
                    <a href="{{ asset('storage/'.$logbook->evaluation->trainer_signature_path) }}" target="_blank" class="mt-2 inline-flex font-bold text-[#00593E] hover:underline">Lihat tanda tangan digital</a>
                @endif
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('trainer.reviews.evaluate', $logbook->id) }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="safety" value="3">
            <input type="hidden" name="operation" value="3">
            <input type="hidden" name="procedure" value="3">
            <input type="hidden" name="communication" value="3">
            <input type="hidden" name="competency_status" value="competent">

            <div class="grid gap-5">
                <label class="block rounded-xl border border-emerald-200 bg-emerald-50 p-5 cursor-pointer">
                    <span class="flex items-center gap-2 text-xs font-bold text-[#00593E]"><input type="radio" name="action" value="verify" x-model="action"> Setujui & Verifikasi Logbook</span>
                    <span class="mt-2 block text-[11px] leading-relaxed text-emerald-800">Tanda tangan dari My Profile akan dipakai otomatis saat approve.</span>
                    @if($trainer?->signature_path)
                        <img src="{{ asset('storage/'.$trainer->signature_path) }}" alt="Signature profile" class="mt-4 max-h-20 w-auto object-contain bg-white rounded-lg border border-emerald-200 p-2">
                        <span class="mt-2 block text-[10px] text-slate-500">Signature tersimpan di profil dan tidak perlu diunggah lagi.</span>
                    @else
                        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-3 text-[11px] text-amber-900">
                            Simpan tanda tangan dulu di <a href="{{ route('profile.edit') }}" class="font-bold underline">My Profile</a> sebelum verifikasi.
                        </div>
                    @endif
                </label>

            </div>

            <div>
                <label for="trainer_comment" class="text-xs font-bold text-slate-700">Catatan tambahan trainer <span class="font-normal text-slate-400">(opsional)</span></label>
                <textarea id="trainer_comment" name="trainer_comment" rows="3" class="mt-2 w-full rounded-xl border-slate-300 text-xs" placeholder="Masukkan umpan balik tambahan untuk trainee...">{{ old('trainer_comment') }}</textarea>
            </div>

            @if($errors->any())<p class="text-xs text-rose-600">{{ $errors->first() }}</p>@endif
            <div class="flex justify-end"><button class="rounded-xl bg-[#00A859] px-5 py-3 text-xs font-bold text-white hover:bg-emerald-600">Simpan Keputusan</button></div>
        </form>
    @endif
</section>
