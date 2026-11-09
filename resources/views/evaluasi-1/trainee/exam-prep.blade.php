@extends('layouts.app', ['title' => 'Persiapan Ujian CBT Proctoring'])

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="examPrep()">
    <!-- Header -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-3">
        <a href="{{ route('evaluasi1.trainee.lms') }}" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-1">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke LMS Evaluasi 1
        </a>
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold uppercase tracking-wider px-2.5 py-0.5 rounded bg-amber-100 text-amber-800">Ujian Berbasis Komputer (CBT)</span>
            @if($exam->proctoring_enabled)
                <span class="text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    Camera Proctoring Aktif
                </span>
            @endif
        </div>
        <h1 class="text-2xl font-black text-gray-900">{{ $exam->title }}</h1>
        <p class="text-xs text-gray-600 leading-relaxed">{{ $exam->description }}</p>
    </div>

    <!-- Parameter Ujian -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white border rounded-xl p-4 text-center">
            <div class="text-[11px] font-semibold text-gray-400 uppercase">Jumlah Soal</div>
            <div class="text-lg font-black text-gray-900 mt-1">{{ $exam->questions->count() }} Butir</div>
        </div>
        <div class="bg-white border rounded-xl p-4 text-center">
            <div class="text-[11px] font-semibold text-gray-400 uppercase">Waktu Ujian</div>
            <div class="text-lg font-black text-gray-900 mt-1">{{ $exam->duration_minutes }} Menit</div>
        </div>
        <div class="bg-white border rounded-xl p-4 text-center">
            <div class="text-[11px] font-semibold text-gray-400 uppercase">Passing Grade</div>
            <div class="text-lg font-black text-emerald-600 mt-1">{{ number_format($exam->passing_score, 0) }}%</div>
        </div>
        <div class="bg-white border rounded-xl p-4 text-center">
            <div class="text-[11px] font-semibold text-gray-400 uppercase">Status & Remedial</div>
            <div class="text-xs font-bold {{ $exam->material_id ? 'text-blue-700' : 'text-amber-700' }} mt-2">
                {{ $exam->material_id ? 'Remedial jika < 80' : 'SIMPER L1 & Eval 2' }}
            </div>
        </div>
    </div>

    <!-- Camera Check & Proctoring Verification -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            Uji Sambungan Kamera Web (Camera Proctoring)
        </h3>
        <p class="text-xs text-gray-500">
            Sistem mewajibkan webcam aktif selama ujian berlangsung. Foto wajah akan diambil secara berkala untuk memvalidasi integritas pengerjaan.
        </p>

        <div class="flex flex-col sm:flex-row items-center gap-6 pt-2">
            <!-- Camera Preview Box -->
            <div class="w-64 h-48 bg-gray-900 rounded-xl overflow-hidden relative border-2 flex items-center justify-center shrink-0" :class="cameraReady ? 'border-emerald-500 shadow-md' : 'border-gray-300'">
                <video id="prepVideo" x-ref="videoElement" autoplay playsinline muted class="w-full h-full object-cover" x-show="cameraReady"></video>
                <div x-show="!cameraReady" class="text-center p-4 text-gray-400 space-y-2">
                    <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div class="text-[11px]">Kamera belum aktif</div>
                </div>
                <div x-show="cameraReady" class="absolute top-2 left-2 bg-emerald-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                    WEBCAM OK
                </div>
            </div>

            <!-- Permission Button & Instruction -->
            <div class="space-y-3">
                <template x-if="!cameraReady">
                    <div>
                        <button type="button" @click="testCamera()" :disabled="testing" class="inline-flex items-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white text-xs font-bold rounded-lg shadow-sm transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span x-text="testing ? 'Menghubungkan...' : 'Izinkan & Tes Kamera'"></span>
                        </button>
                        <p class="text-[11px] text-gray-500 mt-2">Klik tombol di atas lalu pilih "Allow" pada browser saat diminta izin akses kamera.</p>
                    </div>
                </template>
                <template x-if="cameraReady">
                    <div class="text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                        ✓ Kamera terdeteksi dengan baik. Wajah Anda terlihat jelas pada layar monitor. Anda siap memulai ujian CBT.
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Aturan Ujian CBT -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 text-amber-900 text-xs space-y-2">
        <div class="font-bold uppercase tracking-wider flex items-center gap-1.5">
            <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            Tata Tertib Ujian Berbasis Camera Proctoring:
        </div>
        <ul class="list-disc list-inside space-y-1 text-amber-800">
            <li>Standar kelulusan adalah <strong>nilai minimal 80</strong> (skor {{ number_format($exam->passing_score, 0) }} / 100).</li>
            <li>Jika nilai belum mencapai 80 (&lt; 80), Anda dinyatakan <strong>REMEDIAL</strong> dan wajib mengulang ujian ini.</li>
            <li>Untuk kelulusan Evaluasi 1, SIMPER L1, dan membuka akses ke Evaluasi 2 (Gerakan Dasar), seluruh Post-Test materi dan Tes Akhir CBT wajib mencapai nilai minimal 80.</li>
        </ul>
    </div>

    <!-- Tombol Mulai Ujian -->
    <div class="pt-2">
        <a href="{{ route('evaluasi1.trainee.exam.start', $exam->id) }}" class="w-full inline-flex justify-center items-center px-6 py-3.5 bg-brand-700 hover:bg-brand-800 text-white font-extrabold text-sm rounded-xl shadow-lg transition">
            Mulai Kerjakan Ujian Sekarang
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </a>
    </div>
</div>

<script>
function examPrep() {
    return {
        cameraReady: false,
        testing: false,
        stream: null,
        async testCamera() {
            this.testing = true;
            try {
                const video = document.getElementById('prepVideo') || this.$refs.videoElement;
                if (this.stream) {
                    try { this.stream.getTracks().forEach(t => t.stop()); } catch (e) {}
                }
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' },
                    audio: false
                });
                this.stream = stream;
                if (video) {
                    video.srcObject = stream;
                    video.muted = true;
                    video.playsInline = true;
                    if (video.readyState < 1) {
                        await new Promise(r => { video.onloadedmetadata = r; setTimeout(r, 1000); });
                    }
                    try { await video.play(); } catch (e) {}
                }
                this.cameraReady = true;
            } catch (err) {
                alert('Akses kamera ditolak atau tidak tersedia: ' + (err.message || 'Periksa izin webcam browser Anda.'));
            } finally {
                this.testing = false;
            }
        }
    }
}
</script>
@endsection
