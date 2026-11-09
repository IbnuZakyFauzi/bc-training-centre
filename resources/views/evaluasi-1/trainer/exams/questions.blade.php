@extends('layouts.app', ['title' => 'Bank Soal: ' . $exam->title])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('evaluasi1.trainer.exams.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-1 inline-flex items-center">
                ← Kembali ke Paket Ujian
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Bank Soal: {{ $exam->title }}</h1>
            <p class="text-xs text-gray-500">Total: {{ $exam->questions->count() }} Soal | Passing: {{ $exam->passing_score }}% | Durasi: {{ $exam->duration_minutes }} Mnt</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Tambah Soal (Left Col) -->
        <div class="lg:col-span-1">
            <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-4 sticky top-6">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Butir Soal Baru
                </h3>

                <form method="POST" action="{{ route('evaluasi1.trainer.exams.questions.store', $exam->id) }}" class="space-y-4">
                    @csrf

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-700 uppercase">Teks Pertanyaan *</label>
                        <textarea name="question_text" rows="3" required class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-2.5" placeholder="Tuliskan teks pertanyaan..."></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase">Pilihan Jawaban</label>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400">PILIHAN A</span>
                            <input type="text" name="option_a" required class="w-full border-gray-300 rounded-lg text-xs p-2">
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400">PILIHAN B</span>
                            <input type="text" name="option_b" required class="w-full border-gray-300 rounded-lg text-xs p-2">
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400">PILIHAN C</span>
                            <input type="text" name="option_c" required class="w-full border-gray-300 rounded-lg text-xs p-2">
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400">PILIHAN D</span>
                            <input type="text" name="option_d" required class="w-full border-gray-300 rounded-lg text-xs p-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase">Kunci Jawaban *</label>
                            <select name="correct_answer" required class="w-full border-gray-300 rounded-lg text-xs p-2">
                                <option value="A">Kunci: A</option>
                                <option value="B">Kunci: B</option>
                                <option value="C">Kunci: C</option>
                                <option value="D">Kunci: D</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase">Bobot Poin *</label>
                            <input type="number" step="0.5" name="score_weight" value="20" required class="w-full border-gray-300 rounded-lg text-xs p-2">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                        Tambahkan Soal
                    </button>
                </form>
            </div>
        </div>

        <!-- Daftar Soal Tersimpan (Right 2 cols) -->
        <div class="lg:col-span-2 space-y-4">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Daftar Soal Aktif ({{ $exam->questions->count() }})</h3>

            <div class="space-y-4">
                @forelse($exam->questions as $idx => $q)
                    <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-3">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 rounded-md bg-blue-100 text-blue-700 font-bold text-xs flex items-center justify-center">
                                    {{ $idx + 1 }}
                                </span>
                                <span class="text-xs font-bold text-gray-500">Bobot: {{ $q->score_weight }} poin</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                Kunci: {{ $q->correct_answer }}
                            </span>
                        </div>

                        <p class="text-sm font-semibold text-gray-900">{{ $q->question_text }}</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2">
                            @foreach($q->options as $opt)
                                <div class="p-2.5 rounded-xl border text-xs flex items-center gap-2 {{ $opt['key'] === $q->correct_answer ? 'border-emerald-400 bg-emerald-50 text-emerald-900 font-bold' : 'border-gray-100 bg-gray-50 text-gray-700' }}">
                                    <span class="w-5 h-5 rounded font-bold text-[10px] flex items-center justify-center {{ $opt['key'] === $q->correct_answer ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                                        {{ $opt['key'] }}
                                    </span>
                                    <span>{{ $opt['text'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-2xl border border-dashed text-gray-400 text-xs">
                        Belum ada soal pada ujian ini. Silakan tambahkan soal melalui formulir di sebelah kiri.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
