<section class="mt-8 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ action: '{{ old('action', 'verify') }}' }">
    <div class="bg-[#003829] px-6 py-4 text-white">
        <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-300">Keputusan Trainer Evaluator</p>
        <h2 class="mt-1 text-sm font-bold">Approval atau Permintaan Revisi</h2>
    </div>

    @if($logbook->status === 'verified')
        <div class="p-6 text-xs text-emerald-900">
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                <p class="font-bold">Logbook telah diverifikasi.</p>
                @if($logbook->evaluation?->trainer_signature_path)
                    <a href="{{ asset('storage/'.$logbook->evaluation->trainer_signature_path) }}" target="_blank" class="mt-2 inline-flex font-bold text-[#00593E] hover:underline">Lihat tanda tangan digital</a>
                @endif
            </div>
        </div>
    @else
        <form method="POST" action="{{ route('trainer.reviews.evaluate', $logbook->id) }}" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="safety" value="3">
            <input type="hidden" name="operation" value="3">
            <input type="hidden" name="procedure" value="3">
            <input type="hidden" name="communication" value="3">
            <input type="hidden" name="competency_status" value="competent">

            <div class="grid gap-5 lg:grid-cols-2">
                <label class="block rounded-xl border border-emerald-200 bg-emerald-50 p-5 cursor-pointer">
                    <span class="flex items-center gap-2 text-xs font-bold text-[#00593E]"><input type="radio" name="action" value="verify" x-model="action"> Setujui & Verifikasi Logbook</span>
                    <span class="mt-2 block text-[11px] leading-relaxed text-emerald-800">Unggah tanda tangan digital sebagai pengesahan trainer.</span>
                    <span class="mt-4 block text-xs font-bold text-slate-700">Tanda tangan digital <span class="text-rose-600">*</span></span>
                    <input type="file" name="trainer_signature" accept="image/png,image/jpeg" class="mt-2 block w-full text-xs text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-[#003829] file:px-3 file:py-2 file:text-xs file:font-bold file:text-white" :disabled="action !== 'verify'">
                    <span class="mt-2 block text-[10px] text-slate-500">Format PNG, JPG, atau JPEG. Maksimal 2 MB.</span>
                </label>

                <label class="block rounded-xl border border-amber-200 bg-amber-50 p-5 cursor-pointer">
                    <span class="flex items-center gap-2 text-xs font-bold text-amber-900"><input type="radio" name="action" value="revision" x-model="action"> Minta Revisi dari Trainee</span>
                    <span class="mt-2 block text-[11px] leading-relaxed text-amber-800">Catatan ini akan tampil pada logbook trainee untuk ditindaklanjuti.</span>
                    <span class="mt-4 block text-xs font-bold text-slate-700">Catatan revisi <span class="text-rose-600">*</span></span>
                    <textarea name="revision_instruction" rows="3" class="mt-2 w-full rounded-lg border-amber-300 text-xs" placeholder="Tuliskan bagian yang perlu diperbaiki..." :disabled="action !== 'revision'">{{ old('revision_instruction') }}</textarea>
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
