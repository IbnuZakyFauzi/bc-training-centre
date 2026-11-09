@extends('layouts.app', ['title' => 'Form Penilaian Gerakan Dasar (Gerdas)'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <a href="{{ route('evaluasi2.assessments.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-1 inline-flex items-center">
            ← Kembali ke Daftar Evaluasi 2
        </a>
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Formulir Uji Gerakan Dasar (Gerdas) A2B</h1>
        <p class="text-xs text-gray-500">Penilaian praktik gerakan dasar oleh instruktur lapangan berstandar PT MTL.</p>
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

    <form method="POST" action="{{ route('evaluasi2.assessments.store') }}" class="bg-white border rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        @csrf

        <!-- Informasi Trainee & Unit -->
        <div class="space-y-4">
            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider pb-2 border-b">1. Informasi Trainee & Unit Alat Berat</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Pilih Trainee (Siswa) *</label>
                    <select name="trainee_id" required class="w-full border-gray-300 rounded-xl text-xs p-3">
                        <option value="">-- Pilih Trainee --</option>
                        @foreach($trainees as $t)
                            <option value="{{ $t->id }}" {{ old('trainee_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->name }} ({{ $t->sid ?? 'No SID' }}) - {{ $t->equipmentCategory->name ?? 'General' }} [{{ $t->eval1_passed ? '✓ LULUS EVALUASI 1 (SIMPER L1)' : '⚠️ BELUM LULUS EVALUASI 1' }}]
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-gray-500 mt-1">Prasyarat: Trainee wajib lulus nilai minimal 80 pada setiap Post-Test modul dan minimal 80 pada Tes Akhir CBT.</p>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Kategori Alat Berat (A2B)</label>
                    <select name="equipment_category_id" class="w-full border-gray-300 rounded-xl text-xs p-3">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('equipment_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Model / Type Unit *</label>
                    <input type="text" name="unit_model" value="{{ old('unit_model', 'Komatsu PC2000-8') }}" required class="w-full border-gray-300 rounded-xl text-xs p-3" placeholder="Contoh: CAT 777F / Komatsu PC2000">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Nomor Unit (CN) *</label>
                    <input type="text" name="unit_number" value="{{ old('unit_number', 'EX-2001') }}" required class="w-full border-gray-300 rounded-xl text-xs p-3" placeholder="Contoh: HT-7042 / EX-2001">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Lokasi Uji Praktik *</label>
                    <select name="lokasi_kerja" required class="w-full border-gray-300 rounded-xl text-xs p-3">
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ old('lokasi_kerja') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Tanggal Penilaian *</label>
                    <input type="date" name="tanggal_penilaian" value="{{ old('tanggal_penilaian', date('Y-m-d')) }}" required class="w-full border-gray-300 rounded-xl text-xs p-3">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase">Shift Kerja *</label>
                    <select name="shift" required class="w-full border-gray-300 rounded-xl text-xs p-3">
                        <option value="day" {{ old('shift') == 'day' ? 'selected' : '' }}>Shift Siang (Day)</option>
                        <option value="night" {{ old('shift') == 'night' ? 'selected' : '' }}>Shift Malam (Night)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Rubrik Penilaian Gerakan Dasar -->
        <div class="space-y-4 pt-4 border-t">
            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider pb-2 border-b">2. Aspek Rubrik Penilaian Gerakan Dasar (Bobot Skor 0 - 100)</h3>

            <div class="space-y-4 bg-gray-50/70 p-5 rounded-2xl border">
                <!-- Aspek 1 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                    <div class="md:col-span-3">
                        <div class="font-bold text-xs text-gray-900">A. Pemeriksaan Keliling & Prosedur P2H Awal (Bobot 20%)</div>
                        <div class="text-[11px] text-gray-500">Pemeriksaan oli mesin, level coolant, hidrolik, kelayakan tire/track, wheel chock & hazard report.</div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Nilai (0-100) *</label>
                        <input type="number" step="0.5" name="score_p2h" value="{{ old('score_p2h', 85) }}" min="0" max="100" required class="w-full border-gray-300 rounded-xl text-xs p-2.5 font-bold font-mono">
                    </div>
                </div>

                <!-- Aspek 2 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center pt-3 border-t">
                    <div class="md:col-span-3">
                        <div class="font-bold text-xs text-gray-900">B. Kontrol Dasar & Mekanisme Pengoperasian (Bobot 25%)</div>
                        <div class="text-[11px] text-gray-500">Penyetelan kursi/spion, pemakaian seatbelt, 1x klakson sebelum start, instrumen panel & kontrol tuas.</div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Nilai (0-100) *</label>
                        <input type="number" step="0.5" name="score_basic_control" value="{{ old('score_basic_control', 88) }}" min="0" max="100" required class="w-full border-gray-300 rounded-xl text-xs p-2.5 font-bold font-mono">
                    </div>
                </div>

                <!-- Aspek 3 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center pt-3 border-t">
                    <div class="md:col-span-3">
                        <div class="font-bold text-xs text-gray-900">C. Manuver Gerakan Dasar (Maju, Mundur, Belok Sempit, Parkir) (Bobot 35%)</div>
                        <div class="text-[11px] text-gray-500">Kelancaran akselerasi, belokan radius aman tanpa selip, pengamatan blind spot, dan perataan ground tool.</div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Nilai (0-100) *</label>
                        <input type="number" step="0.5" name="score_maneuver" value="{{ old('score_maneuver', 85) }}" min="0" max="100" required class="w-full border-gray-300 rounded-xl text-xs p-2.5 font-bold font-mono">
                    </div>
                </div>

                <!-- Aspek 4 -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center pt-3 border-t">
                    <div class="md:col-span-3">
                        <div class="font-bold text-xs text-gray-900">D. Kepatuhan K3LH & Prosedur Keselamatan Tambang (Bobot 20%)</div>
                        <div class="text-[11px] text-gray-500">APD lengkap, kepatuhan batas kecepatan gerak dasar, aplikasi parking brake & pemasangan ganjal saat berhenti.</div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Nilai (0-100) *</label>
                        <input type="number" step="0.5" name="score_safety_k3" value="{{ old('score_safety_k3', 90) }}" min="0" max="100" required class="w-full border-gray-300 rounded-xl text-xs p-2.5 font-bold font-mono">
                    </div>
                </div>
            </div>
        </div>

        <!-- Catatan Instruktur -->
        <div class="space-y-1">
            <label class="block text-xs font-bold text-gray-700 uppercase">Catatan Khusus & Rekomendasi Instruktur</label>
            <textarea name="catatan_instruktur" rows="3" class="w-full border-gray-300 rounded-xl text-xs p-3" placeholder="Catatan mengenai kelebihan atau hal yang perlu ditingkatkan trainee...">{{ old('catatan_instruktur') }}</textarea>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t">
            <a href="{{ route('evaluasi2.assessments.index') }}" class="px-5 py-2.5 border rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-brand-800 hover:bg-brand-900 text-white rounded-xl text-xs font-bold shadow-md transition">
                Simpan Penilaian & Terbitkan SIMPER L2
            </button>
        </div>
    </form>
</div>
@endsection
