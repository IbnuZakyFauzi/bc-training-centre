@extends('layouts.app', ['title' => 'Ujian CBT: ' . $exam->title])

@section('content')
<div class="max-w-6xl mx-auto space-y-6" x-data="cbtExam({{ $exam->id }}, {{ $attempt->id }}, {{ $exam->duration_minutes }}, {{ $exam->proctoring_enabled ? 'true' : 'false' }})">

    <!-- Top Sticky Bar: Timer, Proctoring status, & Submit -->
    <div class="sticky top-4 z-40 bg-white/95 backdrop-blur-md border border-gray-200 shadow-md rounded-2xl p-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="text-xs font-extrabold uppercase tracking-wider px-2.5 py-1 rounded bg-blue-100 text-blue-800">
                CBT EVALUASI 1
            </span>
            <div class="hidden sm:block">
                <div class="text-xs font-bold text-gray-900 truncate max-w-xs">{{ $exam->title }}</div>
                <div class="text-[10px] text-gray-500">Soal Terjawab: <span class="font-bold text-blue-600" x-text="answeredCount"></span> / {{ $exam->questions->count() }}</div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <!-- Countdown Timer -->
            <div class="flex items-center gap-2 bg-gray-900 text-white px-3.5 py-1.5 rounded-xl font-mono text-sm font-bold shadow-inner">
                <svg class="w-4 h-4 text-amber-400 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="timerDisplay"></span>
            </div>

            <!-- Submit Button -->
            <button type="button" @click="confirmSubmit()" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                Selesaikan Ujian
            </button>
        </div>
    </div>

    <!-- Main Layout: Question Area & Right Panel (Webcam Proctoring & Number Grid) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Question View (Left 2 cols) -->
        <div class="lg:col-span-2 space-y-6">
            <form id="examForm" method="POST" action="{{ route('evaluasi1.trainee.exam.submit', $attempt->id) }}">
                @csrf
                @foreach($exam->questions as $index => $q)
                    <div x-show="currentQuestion === {{ $index + 1 }}" x-cloak class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                Pertanyaan {{ $index + 1 }} dari {{ $exam->questions->count() }}
                            </span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                                Bobot: {{ $q->score_weight }} Poin
                            </span>
                        </div>

                        <!-- Question Text -->
                        <div class="text-base font-semibold text-gray-900 leading-relaxed">
                            {{ $q->question_text }}
                        </div>

                        <!-- Options Radio List -->
                        <div class="space-y-3 pt-2">
                            @foreach($q->options as $opt)
                                <label class="flex items-center p-4 border rounded-xl cursor-pointer transition-all hover:bg-blue-50/50"
                                       :class="answers[{{ $q->id }}] === '{{ $opt['key'] }}' ? 'border-blue-600 bg-blue-50/70 shadow-sm' : 'border-gray-200'">
                                    <input type="radio"
                                           name="answers[{{ $q->id }}]"
                                           value="{{ $opt['key'] }}"
                                           x-model="answers[{{ $q->id }}]"
                                           @change="saveLocalAnswer()"
                                           class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <div class="ml-3.5 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-lg bg-gray-100 text-gray-700 text-xs font-bold flex items-center justify-center shrink-0">
                                            {{ $opt['key'] }}
                                        </span>
                                        <span class="text-sm text-gray-800 font-medium">{{ $opt['text'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <!-- Navigation Prev / Next -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                            <button type="button"
                                    @click="prevQuestion()"
                                    :disabled="currentQuestion === 1"
                                    class="inline-flex items-center px-4 py-2 border text-xs font-semibold rounded-lg text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed">
                                ← Sebelumnya
                            </button>
                            <button type="button"
                                    @click="nextQuestion()"
                                    :disabled="currentQuestion === {{ $exam->questions->count() }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 disabled:opacity-40 disabled:cursor-not-allowed">
                                Selanjutnya →
                            </button>
                        </div>
                    </div>
                @endforeach
            </form>
        </div>

        <!-- Right Column: Live Webcam Proctoring & Question Navigator -->
        <div class="space-y-6">

            <!-- Webcam Monitor Card -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full" :class="camActive ? 'bg-emerald-500 animate-pulse' : (camError ? 'bg-rose-500' : 'bg-amber-500 animate-ping')"></span>
                        Live Camera Proctoring
                    </span>
                    <span class="text-[10px] font-bold text-gray-400 font-mono" x-text="'Snapshot: #' + snapshotCount"></span>
                </div>

                <div class="relative w-full aspect-video bg-gray-900 rounded-xl overflow-hidden border border-gray-200 shadow-inner flex items-center justify-center">
                    <video id="proctorVideo" autoplay playsinline muted class="w-full h-full object-cover" :class="camActive ? 'block' : 'hidden'"></video>
                    <canvas id="proctorCanvas" class="hidden" width="320" height="240"></canvas>
                    
                    <!-- Loading / Connecting State -->
                    <div x-show="!camActive && !camError" class="text-center p-4 text-slate-300 space-y-2">
                        <svg class="w-7 h-7 mx-auto animate-spin text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <div class="text-[11px] font-semibold text-slate-300">Menghubungkan Kamera...</div>
                        <div class="text-[9px] text-slate-400">Pastikan izin kamera diizinkan (Allow)</div>
                    </div>

                    <!-- Error / Permission Blocked State -->
                    <div x-show="camError" x-cloak class="text-center p-3 text-rose-300 space-y-2 bg-gray-900 w-full h-full flex flex-col items-center justify-center">
                        <svg class="w-6 h-6 mx-auto text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div class="text-[10px] text-rose-200 font-bold max-w-[200px]" x-text="camErrorMessage || 'Kamera belum dapat diakses'"></div>
                        <button type="button" @click="initWebcam()" class="px-3 py-1 bg-rose-600 hover:bg-rose-700 text-white text-[10px] font-bold rounded-lg shadow transition">
                            Izinkan / Coba Lagi
                        </button>
                    </div>

                    <!-- Live Indicator Badge -->
                    <div x-show="camActive" class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-sm text-white text-[9px] font-mono px-2 py-0.5 rounded flex items-center gap-1.5 border border-white/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                        REC LIVE • PROCTORING ACTIVE
                    </div>
                </div>

                <div class="text-[11px] text-gray-500 leading-snug flex items-center justify-between">
                    <span>Pengawasan kamera aktif. Jangan berpindah jendela aplikasi atau browser saat ujian berlangsung.</span>
                    <button type="button" @click="initWebcam()" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 underline ml-2 shrink-0">
                        Refresh Cam
                    </button>
                </div>
            </div>

            <!-- Question Numbers Palette -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-3">
                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Navigasi Nomor Soal</h4>
                <div class="grid grid-cols-5 gap-2">
                    @foreach($exam->questions as $i => $q)
                        <button type="button"
                                @click="currentQuestion = {{ $i + 1 }}"
                                class="h-9 rounded-lg font-bold text-xs transition border flex items-center justify-center"
                                :class="{
                                    'border-blue-600 ring-2 ring-blue-500': currentQuestion === {{ $i + 1 }},
                                    'bg-blue-600 text-white border-blue-600': answers[{{ $q->id }}],
                                    'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100': !answers[{{ $q->id }}] && currentQuestion !== {{ $i + 1 }}
                                }">
                            {{ $i + 1 }}
                        </button>
                    @endforeach
                </div>
                <div class="flex items-center justify-between text-[10px] text-gray-500 pt-2 border-t border-gray-100">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 bg-blue-600 rounded"></span> Terjawab
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 bg-gray-100 border border-gray-300 rounded"></span> Belum
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function cbtExam(examId, attemptId, durationMinutes, proctoringEnabled) {
    return {
        examId: examId,
        attemptId: attemptId,
        currentQuestion: 1,
        totalQuestions: {{ $exam->questions->count() }},
        answers: {},
        remainingSeconds: durationMinutes * 60,
        timerInterval: null,
        snapshotInterval: null,
        snapshotCount: 0,
        camActive: false,
        camError: false,
        camErrorMessage: '',
        mediaStream: null,

        get timerDisplay() {
            const m = Math.floor(this.remainingSeconds / 60);
            const s = this.remainingSeconds % 60;
            return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        },

        get answeredCount() {
            return Object.keys(this.answers).filter(k => this.answers[k] !== undefined && this.answers[k] !== '').length;
        },

        init() {
            // Restore saved answers from localStorage if any
            const saved = localStorage.getItem('cbt_attempt_' + this.attemptId);
            if (saved) {
                try { this.answers = JSON.parse(saved); } catch (e) {}
            }

            // Start countdown timer
            this.timerInterval = setInterval(() => {
                this.remainingSeconds--;
                if (this.remainingSeconds <= 0) {
                    clearInterval(this.timerInterval);
                    alert('Waktu ujian telah habis! Sistem akan mengumpulkan jawaban Anda secara otomatis.');
                    document.getElementById('examForm').submit();
                }
            }, 1000);

            // Inisialisasi Webcam & Snapshot interval jika proctoring diaktifkan
            if (proctoringEnabled) {
                setTimeout(() => {
                    this.initWebcam();
                    this.setupSuspiciousListeners();
                }, 300);
            }
        },

        saveLocalAnswer() {
            localStorage.setItem('cbt_attempt_' + this.attemptId, JSON.stringify(this.answers));
        },

        nextQuestion() {
            if (this.currentQuestion < this.totalQuestions) this.currentQuestion++;
        },

        prevQuestion() {
            if (this.currentQuestion > 1) this.currentQuestion--;
        },

        async initWebcam() {
            this.camError = false;
            this.camErrorMessage = '';

            const video = document.getElementById('proctorVideo');
            if (!video) {
                console.warn('Elemen proctorVideo belum siap di DOM.');
                return;
            }

            // Hentikan stream sebelumnya jika ada
            if (this.mediaStream) {
                try {
                    this.mediaStream.getTracks().forEach(t => t.stop());
                } catch (e) {}
                this.mediaStream = null;
            }

            try {
                let stream = null;
                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: {
                            width: { ideal: 640 },
                            height: { ideal: 480 },
                            facingMode: 'user'
                        },
                        audio: false
                    });
                } catch (firstErr) {
                    // Fallback constraints
                    stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                }

                this.mediaStream = stream;
                video.srcObject = stream;
                video.muted = true;
                video.playsInline = true;

                // Tunggu hingga metadata termuat
                if (video.readyState < 1) {
                    await new Promise(resolve => {
                        video.onloadedmetadata = () => resolve();
                        setTimeout(resolve, 1500); // safety fallback
                    });
                }

                try {
                    await video.play();
                } catch (playErr) {
                    console.warn('Video play tertahan autoplay browser:', playErr);
                }

                this.camActive = true;
                this.camError = false;

                // Ambil snapshot awal setelah video stabil (2 detik)
                setTimeout(() => this.captureSnapshot('initial_capture', 'Ujian dimulai, webcam snapshot'), 2000);

                // Ambil snapshot berkala setiap 45 detik
                if (this.snapshotInterval) clearInterval(this.snapshotInterval);
                this.snapshotInterval = setInterval(() => {
                    this.captureSnapshot('periodic_capture', 'Pengawasan rutin webcam');
                }, 45000);

            } catch (err) {
                console.error('Webcam gagal diakses:', err);
                this.camActive = false;
                this.camError = true;
                if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
                    this.camErrorMessage = 'Akses kamera ditolak. Silakan izinkan akses webcam pada ikon gembok browser Anda.';
                } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
                    this.camErrorMessage = 'Kamera webcam tidak ditemukan pada perangkat Anda.';
                } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
                    this.camErrorMessage = 'Kamera sedang dipakai oleh aplikasi lain (Zoom, browser lain, dll).';
                } else {
                    this.camErrorMessage = 'Kamera belum dapat aktif: ' + (err.message || 'Error');
                }
            }
        },

        captureSnapshot(eventType, details) {
            const video = document.getElementById('proctorVideo');
            const canvas = document.getElementById('proctorCanvas');
            if (!video || !canvas || video.readyState < 2) return;

            canvas.width = video.videoWidth || 320;
            canvas.height = video.videoHeight || 240;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/jpeg', 0.6);

            this.snapshotCount++;

            // Kirim ke server
            fetch("{{ route('evaluasi1.trainee.exam.snapshot', $attempt->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    image: dataUrl,
                    event_type: eventType,
                    details: details
                })
            }).catch(e => console.warn('Gagal upload proctoring log:', e));
        },

        setupSuspiciousListeners() {
            // Deteksi ketika trainee berpindah tab browser
            window.addEventListener('blur', () => {
                this.captureSnapshot('tab_switch', 'Peringatan: Peserta beralih dari jendela/tab ujian');
            });
        },

        confirmSubmit() {
            const unanswered = this.totalQuestions - this.answeredCount;
            let msg = 'Apakah Anda yakin ingin menyelesaikan dan mengumpulkan ujian ini?';
            if (unanswered > 0) {
                msg = `Perhatian: Masih ada ${unanswered} soal yang belum dijawab. Apakah Anda yakin tetap ingin mengumpulkan?`;
            }

            if (confirm(msg)) {
                if (this.mediaStream) {
                    try {
                        this.mediaStream.getTracks().forEach(t => t.stop());
                    } catch (e) {}
                }
                localStorage.removeItem('cbt_attempt_' + this.attemptId);
                document.getElementById('examForm').submit();
            }
        }
    }
}
</script>
@endsection
