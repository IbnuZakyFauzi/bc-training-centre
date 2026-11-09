@extends('layouts.app', ['title' => 'Buat Ujian CBT Baru'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('evaluasi1.trainer.exams.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-1 inline-flex items-center">
            ← Kembali ke Daftar Ujian
        </a>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Buat Paket Ujian CBT Baru</h1>
        <p class="text-xs text-gray-500">Tentukan konfigurasi ujian, durasi waktu, dan proteksi kamera.</p>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
            <strong>Terjadi kesalahan validasi:</strong>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('evaluasi1.trainer.exams.store') }}" class="bg-white border rounded-2xl p-6 sm:p-8 shadow-sm space-y-5">
        @csrf

        <div class="space-y-1">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Judul Ujian *</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Contoh: Ujian Akhir Evaluasi 1 - Teori Pengoperasian A2B">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Tipe Ujian *</label>
                <select name="type" id="examTypeSelect" required class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3">
                    <option value="post_test" {{ old('type') == 'post_test' ? 'selected' : '' }}>Post-Test Modul Materi</option>
                    <option value="evaluasi_1" {{ old('type') == 'evaluasi_1' ? 'selected' : '' }}>Ujian Utama Evaluasi 1 (SIMPER L1)</option>
                    <option value="pre_test" {{ old('type') == 'pre_test' ? 'selected' : '' }}>Pre-Test Pembelajaran</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Tautkan ke Modul Materi (Opsional)</label>
                <select name="material_id" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3">
                    <option value="">-- Tidak Terikat Materi Khusus (Ujian Umum) --</option>
                    @foreach($materials as $mat)
                        <option value="{{ $mat->id }}" {{ old('material_id') == $mat->id ? 'selected' : '' }}>
                            Modul {{ $mat->order }}: {{ $mat->title }}
                        </option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-500">Pilih modul jika ujian ini merupakan Post-Test materi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Durasi (Menit) *</label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 15) }}" min="5" max="180" required class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3">
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Passing Grade (%) *</label>
                <input type="number" step="0.5" name="passing_score" value="{{ old('passing_score', 80.0) }}" min="0" max="100" required class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3">
                <p class="text-[10px] text-gray-500">Standar resmi SOP Evaluasi 1: Nilai minimal 80.</p>
            </div>
        </div>

            <div class="flex items-center pt-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="proctoring_enabled" value="1" {{ old('proctoring_enabled', '1') ? 'checked' : '' }} class="w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                    <span class="ml-2 text-xs font-bold text-gray-800">Aktifkan Camera Proctoring (Pengawasan Webcam)</span>
                </label>
            </div>
        </div>

        <div class="space-y-1">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Petunjuk & Deskripsi Ujian</label>
            <textarea name="description" rows="3" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Petunjuk pengerjaan bagi siswa/trainee...">{{ old('description') }}</textarea>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
            <a href="{{ route('evaluasi1.trainer.exams.index') }}" class="px-5 py-2.5 border rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-brand-800 hover:bg-brand-900 text-white rounded-xl text-xs font-bold shadow-sm transition">
                Lanjut: Tambah Soal
            </button>
        </div>
    </form>
</div>
@endsection
