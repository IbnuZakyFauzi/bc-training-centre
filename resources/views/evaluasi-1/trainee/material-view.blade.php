@extends('layouts.app', ['title' => $material->title . ' - Materi LMS'])

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="materialViewer({{ $material->id }}, {{ $material->min_duration_seconds }}, {{ $progress->watch_time_seconds }}, {{ $progress->is_completed ? 'true' : 'false' }})">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('evaluasi1.trainee.lms') }}" class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-2">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Dashboard Evaluasi 1
            </a>
            <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900">{{ $material->title }}</h1>
        </div>

        <div class="flex items-center gap-3">
            <template x-if="isCompleted">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-300">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Materi Selesai Dipelajari
                </span>
            </template>
            <template x-if="!isCompleted">
                <div class="text-right">
                    <div class="text-[11px] text-gray-500 font-medium">Syarat Waktu Tonton</div>
                    <div class="text-xs font-bold text-amber-700 font-mono" x-text="formatTime(remainingSeconds) + ' tersisa'"></div>
                </div>
            </template>
        </div>
    </div>

    <!-- Video Player / Content Box -->
    <div class="bg-black rounded-2xl overflow-hidden shadow-2xl border border-gray-800 aspect-video relative flex items-center justify-center">
        @if($material->content_url)
            <iframe class="w-full h-full" src="{{ $material->content_url }}" title="{{ $material->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        @elseif($material->file_path)
            <div class="p-8 text-center text-white space-y-4">
                <svg class="w-16 h-16 mx-auto text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <div class="text-lg font-bold">Dokumen Materi Pelatihan</div>
                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition shadow">
                    Unduh / Buka Dokumen Materi
                </a>
            </div>
        @else
            <div class="text-gray-400 text-sm">Media materi tidak tersedia.</div>
        @endif
    </div>

    <!-- Tracking Bar & Controls -->
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-4">
        <div class="space-y-2">
            <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-gray-700">Waktu Belajar: <span class="font-mono text-blue-600" x-text="formatTime(watchedSeconds)"></span></span>
                <span class="font-bold text-gray-500">Target Minimal: <span class="font-mono text-gray-700" x-text="formatTime(targetSeconds)"></span></span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" :style="'width: ' + progressPercent + '%'"></div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-3 border-t border-gray-100">
            <div class="text-xs text-gray-500">
                Status tersimpan secara otomatis setiap 10 detik.
            </div>
            <div>
                <button type="button" @click="markAsCompleted()" :disabled="isCompleted" class="inline-flex items-center px-4 py-2 text-xs font-bold rounded-lg transition" :class="isCompleted ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm'">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span x-text="isCompleted ? 'Sudah Ditandai Selesai' : 'Tandai Selesai Mempelajari'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Deskripsi & Catatan Materi -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-3">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Deskripsi & Pokok Bahasan</h3>
        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $material->description }}</p>
    </div>
</div>

<script>
function materialViewer(materialId, targetSeconds, initialWatched, initialCompleted) {
    return {
        materialId: materialId,
        targetSeconds: targetSeconds || 120,
        watchedSeconds: initialWatched || 0,
        isCompleted: initialCompleted || false,
        timer: null,

        get remainingSeconds() {
            return Math.max(0, this.targetSeconds - this.watchedSeconds);
        },
        get progressPercent() {
            if (this.isCompleted) return 100;
            return Math.min(100, Math.round((this.watchedSeconds / this.targetSeconds) * 100));
        },
        init() {
            if (!this.isCompleted) {
                this.timer = setInterval(() => {
                    this.watchedSeconds += 1;
                    if (this.watchedSeconds % 10 === 0) {
                        this.syncProgress(false);
                    }
                }, 1000);
            }
        },
        formatTime(seconds) {
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
        },
        async syncProgress(forceComplete = false) {
            try {
                const response = await fetch("{{ route('evaluasi1.trainee.material.progress', $material->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        seconds: this.watchedSeconds,
                        force_complete: forceComplete
                    })
                });
                const res = await response.json();
                if (res.is_completed) {
                    this.isCompleted = true;
                    if (this.timer) clearInterval(this.timer);
                }
            } catch (err) {
                console.error('Gagal sync progress:', err);
            }
        },
        markAsCompleted() {
            this.syncProgress(true);
        }
    }
}
</script>
@endsection
