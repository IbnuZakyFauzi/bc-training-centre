@extends('layouts.app', ['title' => 'Kelola Ujian CBT Evaluasi 1'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('evaluasi1.trainer.dashboard') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-1 inline-flex items-center">
                ← Kembali ke Workspace Evaluasi 1
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Manajemen Paket Ujian CBT</h1>
            <p class="text-xs text-gray-500">Kelola soal, passing grade, dan pengaturan Camera Proctoring.</p>
        </div>

        <a href="{{ route('evaluasi1.trainer.exams.create') }}" class="inline-flex items-center px-4 py-2.5 bg-brand-800 hover:bg-brand-900 text-white text-xs font-bold rounded-xl shadow transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Ujian Baru
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($exams as $exam)
            <div class="bg-white border rounded-2xl p-6 shadow-sm space-y-4 hover:shadow-md transition">
                <div class="flex items-start justify-between gap-2">
                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-1 rounded {{ $exam->type === 'evaluasi_1' ? 'bg-amber-100 text-amber-800' : ($exam->type === 'post_test' ? 'bg-indigo-100 text-indigo-800' : 'bg-blue-100 text-blue-800') }}">
                        {{ $exam->type === 'evaluasi_1' ? 'Ujian Evaluasi 1 (SIMPER L1)' : ($exam->type === 'post_test' ? 'Post-Test Modul' : 'Pre-Test') }}
                    </span>
                    @if($exam->proctoring_enabled)
                        <span class="text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                            Proctoring ON
                        </span>
                    @endif
                </div>

                <div>
                    <h3 class="text-base font-bold text-gray-900">{{ $exam->title }}</h3>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $exam->description }}</p>
                </div>

                <div class="grid grid-cols-3 gap-2 py-3 border-y border-gray-100 text-center">
                    <div>
                        <div class="text-[10px] text-gray-400 font-semibold uppercase">Butir Soal</div>
                        <div class="text-base font-extrabold text-gray-900">{{ $exam->questions_count }} Soal</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-gray-400 font-semibold uppercase">Passing Grade</div>
                        <div class="text-base font-extrabold text-emerald-600">{{ $exam->passing_score }}%</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-gray-400 font-semibold uppercase">Peserta Ikut</div>
                        <div class="text-base font-extrabold text-blue-600">{{ $exam->attempts_count }} Sesi</div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-1">
                    <a href="{{ route('evaluasi1.trainer.exams.questions', $exam->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold rounded-lg transition">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Kelola Bank Soal ({{ $exam->questions_count }})
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-2 text-center py-12 bg-white rounded-2xl border border-dashed text-gray-400 text-xs">
                Belum ada paket ujian CBT yang dibuat.
            </div>
        @endforelse
    </div>
</div>
@endsection
