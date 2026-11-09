@extends('layouts.app', ['title' => 'Monitor Camera Proctoring'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('evaluasi1.trainer.dashboard') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-1 inline-flex items-center">
                ← Kembali ke Workspace Evaluasi 1
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Dashboard Camera Proctoring</h1>
            <p class="text-xs text-gray-500">Pemantauan foto & log aktivitas kamera webcam trainee secara berkala dan real-time saat ujian CBT.</p>
        </div>
    </div>

    <!-- Live Active Session Alert (jika ada yang sedang ujian) -->
    @if($liveAttempts->isNotEmpty())
        <div class="bg-gradient-to-r from-rose-900 to-indigo-900 rounded-2xl p-5 text-white shadow-lg space-y-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-400 animate-ping"></span>
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-rose-200">Sesi Ujian Sedang Berlangsung (Live)</h3>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($liveAttempts as $live)
                    <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/20 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-rose-500 flex items-center justify-center font-bold text-xs shrink-0">
                            {{ substr($live->user->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold truncate">{{ $live->user->name }}</div>
                            <div class="text-[10px] text-blue-200 truncate">{{ $live->exam->title }}</div>
                            <div class="text-[9px] text-amber-300 font-mono">Mulai: {{ $live->started_at->format('H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="bg-white border rounded-2xl p-5 shadow-sm">
        <form method="GET" action="{{ route('evaluasi1.trainer.proctoring') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-gray-600 uppercase">Pilih Trainee</label>
                <select name="trainee_id" class="w-full border-gray-300 rounded-xl text-xs p-2.5">
                    <option value="">Semua Trainee</option>
                    @foreach($trainees as $t)
                        <option value="{{ $t->id }}" {{ request('trainee_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->name }} ({{ $t->sid ?? 'No SID' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-gray-600 uppercase">Tipe Kejadian (Event)</label>
                <select name="event_type" class="w-full border-gray-300 rounded-xl text-xs p-2.5">
                    <option value="all">Semua Kejadian</option>
                    <option value="periodic_capture" {{ request('event_type') == 'periodic_capture' ? 'selected' : '' }}>Snapshot Berkala</option>
                    <option value="tab_switch" {{ request('event_type') == 'tab_switch' ? 'selected' : '' }}>Peringatan: Pindah Tab / Layar</option>
                    <option value="initial_capture" {{ request('event_type') == 'initial_capture' ? 'selected' : '' }}>Snapshot Awal Mulai</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Snapshot Gallery Grid -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Rekaman Kamera & Log Proctoring</h3>
            <span class="text-xs text-gray-400">Total: {{ $logs->total() }} Log</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($logs as $log)
                @php
                    $isSuspicious = in_array($log->event_type, ['tab_switch', 'multiple_faces', 'no_face']);
                @endphp
                <div class="bg-white border rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition space-y-3 p-3 {{ $isSuspicious ? 'border-amber-400 ring-2 ring-amber-100' : 'border-gray-200' }}">
                    <!-- Photo Box -->
                    <div class="aspect-video bg-gray-900 rounded-xl overflow-hidden relative flex items-center justify-center">
                        @if($log->snapshot_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($log->snapshot_path))
                            <img src="{{ asset('storage/' . $log->snapshot_path) }}" alt="Webcam Capture" class="w-full h-full object-cover">
                        @else
                            <div class="text-center p-3 text-gray-400 space-y-1">
                                <svg class="w-7 h-7 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span class="text-[9px] uppercase font-mono">Snapshot Telemetri</span>
                            </div>
                        @endif

                        <span class="absolute top-2 left-2 text-[9px] font-bold px-2 py-0.5 rounded {{ $isSuspicious ? 'bg-amber-500 text-gray-900' : 'bg-black/60 text-white backdrop-blur-sm' }}">
                            {{ $log->event_type === 'tab_switch' ? '⚠️ TAB SWITCH' : 'CAPTURE' }}
                        </span>

                        <span class="absolute bottom-2 right-2 text-[9px] font-mono text-white/80 bg-black/60 px-1.5 py-0.5 rounded">
                            {{ $log->created_at->format('H:i:s') }}
                        </span>
                    </div>

                    <!-- Metadata -->
                    <div class="space-y-1">
                        <div class="font-bold text-xs text-gray-900 truncate">{{ $log->user->name }}</div>
                        <div class="text-[10px] text-gray-500 truncate">{{ $log->attempt->exam->title ?? 'Ujian CBT' }}</div>
                        <p class="text-[10px] text-gray-600 line-clamp-2 leading-tight pt-1 border-t border-gray-100">
                            {{ $log->event_details }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-dashed text-gray-400 text-xs">
                    Belum ada rekaman log kamera proctoring yang sesuai filter.
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
