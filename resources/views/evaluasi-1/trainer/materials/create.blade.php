@extends('layouts.app', ['title' => 'Tambah Materi LMS Evaluasi 1'])

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <a href="{{ route('evaluasi1.trainer.materials.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-1 inline-flex items-center">
            ← Kembali ke Daftar Materi
        </a>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Tambah Materi / Video Pembelajaran</h1>
        <p class="text-xs text-gray-500">Materi in-class training untuk modul Evaluasi 1.</p>
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

    <form method="POST" action="{{ route('evaluasi1.trainer.materials.store') }}" enctype="multipart/form-data" class="bg-white border rounded-2xl p-6 sm:p-8 shadow-sm space-y-5">
        @csrf

        <div class="space-y-1">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Judul Materi *</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Contoh: Prosedur P2H & Golden Rules Operasi Tambang">
        </div>

        <div class="space-y-1">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Deskripsi Singkat</label>
            <textarea name="description" rows="3" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Rangkuman pokok materi pembelajaran...">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Tipe Konten *</label>
                <select name="type" required class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3">
                    <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video Interaktif</option>
                    <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Dokumen / PDF</option>
                    <option value="slide" {{ old('type') == 'slide' ? 'selected' : '' }}>Slide Presentasi</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Target Waktu Tonton (Detik)</label>
                <input type="number" name="min_duration_seconds" value="{{ old('min_duration_seconds', 180) }}" min="0" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Contoh: 180 (3 menit)">
            </div>
        </div>

        <div class="space-y-1">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">URL Video / Embed Link</label>
            <input type="url" name="content_url" value="{{ old('content_url') }}" class="w-full border-gray-300 rounded-xl text-xs focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="https://www.youtube.com/embed/...">
            <span class="text-[11px] text-gray-400">Gunakan link embed YouTube atau tautan video cloud.</span>
        </div>

        <div class="space-y-1">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Atau Upload Berkas Materi (PDF/MP4)</label>
            <input type="file" name="material_file" accept=".pdf,.mp4,.ppt,.pptx" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
            <a href="{{ route('evaluasi1.trainer.materials.index') }}" class="px-5 py-2.5 border rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                Simpan Materi
            </button>
        </div>
    </form>
</div>
@endsection
