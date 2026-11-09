@extends('layouts.app', ['title' => 'Hasil Ujian CBT - ' . $attempt->exam->title])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white border rounded-2xl p-6 sm:p-8 shadow-sm text-center space-y-6 {{ $attempt->is_passed ? 'border-emerald-300' : 'border-rose-300' }}">
        <!-- Result Icon Badge -->
        <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center shadow-lg {{ $attempt->is_passed ? 'bg-emerald-100 text-emerald-600 ring-8 ring-emerald-50' : 'bg-rose-100 text-rose-600 ring-8 ring-rose-50' }}">
            @if($attempt->is_passed)
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            @else
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            @endif
        </div>

        <div class="space-y-2">
            <span class="text-xs font-black uppercase tracking-widest px-3 py-1 rounded-full {{ $attempt->is_passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800 animate-pulse' }}">
                {{ $attempt->is_passed ? '✓ KOMPETEN / LULUS' : '⚠️ STATUS: REMEDIAL' }}
            </span>
            <h1 class="text-2xl font-black text-gray-900">{{ $attempt->exam->title }}</h1>
            <p class="text-xs text-gray-500">
                Diselesaikan pada {{ $attempt->submitted_at?->timezone('Asia/Makassar')->translatedFormat('d F Y, H:i') ?? '-' }} WITA
            </p>
        </div>

        <!-- Score Meter -->
        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex items-center justify-around">
            <div>
                <div class="text-xs text-gray-400 font-semibold uppercase">Nilai Perolehan</div>
                <div class="text-3xl font-black mt-1 {{ $attempt->is_passed ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ number_format($attempt->total_score, 1) }}
                </div>
            </div>
            <div class="h-10 w-px bg-gray-200"></div>
            <div>
                <div class="text-xs text-gray-400 font-semibold uppercase">Standar Kelulusan</div>
                <div class="text-3xl font-black text-gray-700 mt-1">
                    {{ number_format($attempt->exam->passing_score, 0) }}
                </div>
            </div>
            <div class="h-10 w-px bg-gray-200"></div>
            <div>
                <div class="text-xs text-gray-400 font-semibold uppercase">Hasil Evaluasi</div>
                <div class="text-xs font-extrabold mt-2 {{ $attempt->is_passed ? 'text-emerald-700' : 'text-rose-700' }}">
                    {{ $attempt->is_passed ? 'Lulus (≥ 80)' : 'Remedial (< 80)' }}
                </div>
            </div>
        </div>

        <!-- Callout Lulus / Sertifikat / Remedial -->
        @if($attempt->is_passed && $attempt->certificate)
            <div class="bg-emerald-50 border border-emerald-300 rounded-xl p-5 text-left flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <div class="text-xs font-bold text-emerald-800 uppercase tracking-wide">Sertifikat Resmi Diterbitkan</div>
                    <div class="text-sm font-extrabold text-emerald-900 mt-0.5">Sertifikat Kompetensi SIMPER L1</div>
                    <div class="text-xs text-emerald-700 font-mono mt-0.5">{{ $attempt->certificate->certificate_number }}</div>
                    <p class="text-xs text-emerald-800 mt-1">Seluruh syarat kelulusan Evaluasi 1 terpenuhi. Anda berhak melanjutkan ke Evaluasi 2 (Gerakan Dasar).</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('evaluasi1.certificate.show', $attempt->certificate->id) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                        Buka Sertifikat
                    </a>
                    <a href="{{ route('evaluasi2.assessments.index') }}" class="inline-flex items-center px-3.5 py-2 bg-blue-700 hover:bg-blue-800 text-white text-xs font-bold rounded-lg shadow-sm transition">
                        Lanjut Evaluasi 2 →
                    </a>
                </div>
            </div>
        @elseif($attempt->is_passed && $attempt->exam->material_id)
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-xs text-emerald-900 leading-relaxed text-left">
                <strong>✓ Selamat!</strong> Anda telah lulus Post-Test untuk materi <strong>{{ $attempt->exam->material?->title }}</strong> dengan skor <strong>{{ number_format($attempt->total_score, 1) }}</strong> (Standar minimal: 80). Silakan lanjutkan ke modul pembelajaran berikutnya.
            </div>
        @elseif(!$attempt->is_passed)
            <div class="bg-rose-50 border border-rose-300 rounded-xl p-4 text-xs text-rose-900 leading-relaxed text-left space-y-1.5">
                <div class="font-extrabold flex items-center gap-1.5 text-rose-800">
                    <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    STATUS: REMEDIAL (Wajib Ujian Ulang)
                </div>
                <p>
                    Nilai perolehan Anda (<strong>{{ number_format($attempt->total_score, 1) }}</strong>) belum mencapai standar batas kelulusan minimal <strong>80.0</strong>.
                    @if($attempt->exam->material_id)
                        Anda wajib mengikuti remedial pada Post-Test modul ini agar modul dinyatakan tuntas dan dapat membuka akses ke Tes Akhir CBT serta Evaluasi 2.
                    @else
                        Anda wajib mengikuti remedial pada Tes Akhir CBT ini untuk memperoleh Sertifikat SIMPER L1 dan melanjutkan ke Evaluasi 2.
                    @endif
                </p>
            </div>
        @endif

        <div class="pt-2 flex items-center justify-center gap-3">
            <a href="{{ route('evaluasi1.trainee.lms') }}" class="inline-flex items-center px-5 py-2.5 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 text-xs font-bold rounded-xl transition">
                Kembali ke Dashboard LMS
            </a>
            @if(!$attempt->is_passed)
                <a href="{{ route('evaluasi1.trainee.exam.prep', $attempt->exam_id) }}" class="inline-flex items-center px-5 py-2.5 bg-brand-700 hover:bg-brand-800 text-white text-xs font-bold rounded-xl shadow transition">
                    Coba Ujian Lagi (Remedial)
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
