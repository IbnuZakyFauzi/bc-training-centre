@php($operator = auth()->user())
<section class="mt-8 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ action: '{{ old('action', 'approve') }}' }">
    <div class="bg-[#003829] px-6 py-4 text-white">
        <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-300">Keputusan Pengawas</p>
        <h2 class="mt-1 text-sm font-bold">Approval atau Permintaan Revisi</h2>
    </div>

    @if($isPending)
        <form method="POST" action="{{ route('department-operation.approvals.decide', $logbook->id) }}" class="p-6 space-y-5">
            @csrf
            <div class="grid gap-5 lg:grid-cols-2">
                <label class="block rounded-xl border border-emerald-200 bg-emerald-50 p-5 cursor-pointer">
                    <span class="flex items-center gap-2 text-xs font-bold text-[#00593E]"><input type="radio" name="action" value="approve" x-model="action"> Setujui Logbook</span>
                    <span class="mt-2 block text-[11px] leading-relaxed text-emerald-800">Tanda tangan profil akan dipakai otomatis saat approve.</span>
                    @if($operator?->signature_path)
                        <img src="{{ asset('storage/'.$operator->signature_path) }}" alt="Signature profile" class="mt-4 max-h-20 w-auto object-contain bg-white rounded-lg border border-emerald-200 p-2">
                    @else
                        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-3 text-[11px] text-amber-900">
                            Simpan tanda tangan dulu di <a href="{{ route('profile.edit') }}" class="font-bold underline">My Profile</a> sebelum approve.
                        </div>
                    @endif
                </label>
                <label class="block rounded-xl border border-amber-200 bg-amber-50 p-5 cursor-pointer">
                    <span class="flex items-center gap-2 text-xs font-bold text-amber-900"><input type="radio" name="action" value="revision" x-model="action"> Minta Revisi dari Trainee</span>
                    <span class="mt-2 block text-[11px] leading-relaxed text-amber-800">Catatan ini akan tampil pada logbook Trainee untuk ditindaklanjuti.</span>
                    <span class="mt-4 block text-xs font-bold text-slate-700">Catatan revisi <span class="text-rose-600">*</span></span>
                    <textarea name="approval_notes" rows="3" class="mt-2 w-full rounded-lg border-amber-300 text-xs" placeholder="Tuliskan bagian yang perlu diperbaiki..." :disabled="action !== 'revision'">{{ old('approval_notes') }}</textarea>
                </label>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700">Catatan tambahan Pengawas <span class="font-normal text-slate-400">(opsional)</span></label>
                <textarea name="approval_notes" rows="3" class="mt-2 w-full rounded-xl border-slate-300 text-xs" placeholder="Masukkan catatan tambahan untuk trainee..." :disabled="action === 'revision'">{{ old('approval_notes') }}</textarea>
            </div>
            @if($errors->any())<p class="text-xs text-rose-600">{{ $errors->first() }}</p>@endif
            <div class="flex justify-end"><button class="rounded-xl bg-[#00A859] px-5 py-3 text-xs font-bold text-white hover:bg-emerald-600">Simpan Keputusan</button></div>
        </form>
    @else
        <div class="p-6"><div class="rounded-xl border {{ in_array($logbook->status, ['approved', 'supervisor_approved', 'final_approved'], true) ? 'border-emerald-200 bg-emerald-50 text-emerald-900' : 'border-amber-200 bg-amber-50 text-amber-900' }} p-4 text-xs"><p class="font-bold">Keputusan Pengawas: {{ in_array($logbook->status, ['approved', 'supervisor_approved', 'final_approved'], true) ? 'Disetujui' : 'Dikembalikan untuk revisi' }}</p><p class="mt-2 whitespace-pre-line">{{ $logbook->pjo_notes ?: 'Tidak ada catatan keputusan.' }}</p></div></div>
    @endif
</section>
