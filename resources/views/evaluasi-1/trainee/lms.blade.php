@extends('layouts.app', ['title' => 'Evaluasi 1 - In-Class LMS & Ujian CBT'])

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 rounded-2xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/20 text-amber-300 text-xs font-bold border border-amber-400/30">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    MODUL IN-CLASS TRAINING & CBT PROCTORING
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Evaluasi 1: Pembelajaran & Ujian Teori</h1>
                <p class="text-blue-200 text-sm max-w-2xl leading-relaxed">
                    Selesaikan seluruh materi, penuhi jam absensi online in-class, dan ikuti Ujian CBT dengan Camera Proctoring untuk penerbitan Sertifikat Kompetensi SIMPER L1.
                </p>
            </div>

            <!-- Progress Cards Container -->
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Progress Jam Pelatihan SOP Card -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-3.5 flex items-center gap-3 min-w-[190px]">
                    <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-white/20" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-emerald-400" stroke-width="3.5" stroke-dasharray="{{ $attendancePercent }}, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-[11px] font-bold text-white">{{ $attendancePercent }}%</span>
                    </div>
                    <div>
                        <div class="text-[9px] text-blue-200 font-semibold uppercase tracking-wider">Presensi SOP</div>
                        <div class="text-sm font-extrabold text-white">{{ number_format($totalAttendanceHours, 1) }} / {{ $targetHours }} Jam</div>
                        <div class="text-[9px] {{ $totalAttendanceHours >= $targetHours ? 'text-emerald-300' : 'text-amber-300' }} mt-0.5 font-semibold">
                            {{ $totalAttendanceHours >= $targetHours ? '✓ Terpenuhi' : 'Kurang ' . number_format($targetHours - $totalAttendanceHours, 1) . ' Jam' }}
                        </div>
                    </div>
                </div>

                <!-- Progress Materi LMS Card -->
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-3.5 flex items-center gap-3 min-w-[190px]">
                    <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-white/20" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-amber-400" stroke-width="3.5" stroke-dasharray="{{ $progressPercent }}, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-[11px] font-bold text-white">{{ $progressPercent }}%</span>
                    </div>
                    <div>
                        <div class="text-[9px] text-blue-200 font-semibold uppercase tracking-wider">Materi Video</div>
                        <div class="text-sm font-extrabold text-white">{{ $completedCount }} / {{ $totalMaterials }} Selesai</div>
                        <div class="text-[9px] text-amber-300 mt-0.5 font-semibold">
                            {{ $completedCount >= $totalMaterials ? '✓ Selesai' : 'Belum Lengkap' }}
                        </div>
                    </div>
                </div>

                <!-- Progress Post-Test Modul (Min 80) Card -->
                @php
                    $postTestPct = $totalMaterials > 0 ? round(($eval1Summary['passed_post_tests'] / $totalMaterials) * 100) : 0;
                @endphp
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-3.5 flex items-center gap-3 min-w-[200px]">
                    <div class="relative w-12 h-12 flex items-center justify-center shrink-0">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-white/20" stroke-width="3.5" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="{{ $eval1Summary['remedial_post_tests'] > 0 ? 'text-rose-400' : 'text-emerald-400' }}" stroke-width="3.5" stroke-dasharray="{{ $postTestPct }}, 100" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute text-[11px] font-bold text-white">{{ $postTestPct }}%</span>
                    </div>
                    <div>
                        <div class="text-[9px] text-blue-200 font-semibold uppercase tracking-wider">Post-Test Modul (≥80)</div>
                        <div class="text-sm font-extrabold text-white">{{ $eval1Summary['passed_post_tests'] }} / {{ $totalMaterials }} Lulus</div>
                        <div class="text-[9px] font-semibold mt-0.5 {{ $eval1Summary['remedial_post_tests'] > 0 ? 'text-rose-300 font-black animate-pulse' : ($eval1Summary['passed_post_tests'] >= $totalMaterials ? 'text-emerald-300' : 'text-amber-300') }}">
                            @if($eval1Summary['remedial_post_tests'] > 0)
                                ⚠️ {{ $eval1Summary['remedial_post_tests'] }} Perlu Remedial
                            @elseif($eval1Summary['passed_post_tests'] >= $totalMaterials)
                                ✓ Lulus Seluruh Modul
                            @else
                                {{ $totalMaterials - $eval1Summary['passed_post_tests'] }} Belum Lulus
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-xl flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-bold rounded-xl flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="p-4 bg-blue-50 border border-blue-200 text-blue-800 text-xs font-bold rounded-xl flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
            {{ session('info') }}
        </div>
    @endif

    <!-- Sertifikat Status Callout jika sudah lulus -->
    @if($certificate)
    <div class="bg-emerald-50 border-2 border-emerald-500/40 rounded-xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider bg-emerald-100 px-2.5 py-0.5 rounded-full">Sertifikat Terbit</span>
                <h3 class="text-base font-bold text-gray-900 mt-1">Sertifikat Kompetensi SIMPER L1 Resmi Diterbitkan</h3>
                <p class="text-xs text-gray-600">Nomor: <span class="font-mono font-semibold">{{ $certificate->certificate_number }}</span> | Nilai Ujian: <span class="font-bold text-emerald-700">{{ $certificate->score }}</span> (Standar Kelulusan: Min 80)</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('evaluasi1.certificate.show', $certificate->id) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                Lihat Sertifikat
            </a>
            <a href="{{ route('evaluasi1.certificate.print', $certificate->id) }}" target="_blank" class="inline-flex items-center px-3 py-2 border border-emerald-600 text-emerald-700 hover:bg-emerald-50 text-xs font-bold rounded-lg transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak PDF
            </a>
        </div>
    </div>
    @endif

    <!-- ========================================================================= -->
    <!-- 1. SOP EVALUASI 1 (IN-CLASS TRAINING) REFERENCE CARD (SESUAI DOKUMEN RESMI) -->
    <!-- ========================================================================= -->
    <div class="bg-white border rounded-2xl p-5 sm:p-6 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b pb-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-blue-800 text-white">SOP RESMI</span>
                        <h2 class="text-base font-extrabold text-gray-900 tracking-tight">Standar Operasional Prosedur (SOP) In-Class Training & Evaluasi 1</h2>
                    </div>
                    <p class="text-xs text-gray-500">Standar kurikulum silabus materi, alokasi waktu pelatihan, dan ambang batas kelulusan (PT Berau Coal / PT MTL).</p>
                </div>
            </div>

            <!-- Trainee Active Path Badge -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl px-3.5 py-2 flex items-center gap-2 shrink-0">
                <div class="w-2.5 h-2.5 rounded-full bg-blue-600 animate-pulse"></div>
                <div class="text-xs">
                    <span class="text-gray-500 text-[10px] block leading-none">Jalur Sertifikasi Anda:</span>
                    <span class="font-extrabold text-blue-900">{{ $sop['label'] }} (Standar: {{ $targetHours }} Jam)</span>
                </div>
            </div>
        </div>

        <!-- Tabel Matriks SOP Resmi -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border border-gray-200 rounded-xl overflow-hidden">
                <thead class="bg-gray-100 text-gray-800 text-[11px] font-black uppercase tracking-wider text-center border-b">
                    <tr>
                        <th class="py-3 px-4 text-left border-r bg-gray-200/80" colspan="2">Tahap Pelatihan</th>
                        <th class="py-3 px-4 border-r {{ in_array(Auth::user()->certification, ['Green', null]) ? 'bg-blue-800 text-white font-black ring-2 ring-blue-400' : 'bg-gray-100 text-gray-800' }}">Green</th>
                        <th class="py-3 px-4 border-r {{ Auth::user()->certification === 'Skill-up' ? 'bg-blue-800 text-white font-black ring-2 ring-blue-400' : 'bg-gray-100 text-gray-800' }}">Skill Up</th>
                        <th class="py-3 px-4 {{ in_array(Auth::user()->certification, ['Experience_internal', 'Experience_external']) ? 'bg-blue-800 text-white font-black ring-2 ring-blue-400' : 'bg-gray-100 text-gray-800' }}" colspan="2">Experience</th>
                    </tr>
                    <tr class="text-[10px] bg-gray-50 border-b text-gray-600 font-semibold">
                        <th class="border-r" colspan="2"></th>
                        <th class="border-r"></th>
                        <th class="border-r"></th>
                        <th class="py-2 px-3 border-r">Operator Experience Eksternal</th>
                        <th class="py-2 px-3">Operator Experience Internal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Baris 1: Materi -->
                    <tr>
                        <td class="py-4 px-4 font-extrabold text-gray-900 bg-gray-50 border-r w-28 text-center italic" rowspan="2">
                            In-Class Training
                        </td>
                        <td class="py-4 px-3 font-bold text-gray-700 bg-gray-50/60 border-r w-24">
                            Materi
                        </td>
                        <!-- Green -->
                        <td class="py-4 px-4 border-r align-top {{ in_array(Auth::user()->certification, ['Green', null]) ? 'bg-blue-50/40 font-semibold' : '' }}">
                            <ol class="list-decimal list-inside space-y-1 text-gray-800 leading-relaxed">
                                <li>Kebijakan dan Pengetahuan K3L</li>
                                <li>Teknis Pengetahuan Alat</li>
                                <li>Defensive Driving termasuk pengendalian unit dalam kondisi emergency</li>
                            </ol>
                        </td>
                        <!-- Skill Up -->
                        <td class="py-4 px-4 border-r align-top {{ Auth::user()->certification === 'Skill-up' ? 'bg-blue-50/40 font-semibold' : '' }}">
                            <ol class="list-decimal list-inside space-y-1 text-gray-800 leading-relaxed">
                                <li>Kebijakan dan Pengetahuan K3L</li>
                                <li>Teknis Pengetahuan Alat</li>
                                <li>Defensive Driving termasuk pengendalian unit dalam kondisi emergency</li>
                            </ol>
                        </td>
                        <!-- Experience Eksternal & Internal -->
                        <td class="py-4 px-4 align-top text-gray-800 {{ in_array(Auth::user()->certification, ['Experience_internal', 'Experience_external']) ? 'bg-blue-50/40 font-semibold' : '' }}" colspan="2">
                            <ol class="list-decimal list-inside space-y-1 leading-relaxed">
                                <li>Kebijakan dan Pengetahuan K3L</li>
                                <li>Defensive Driving termasuk pengendalian unit dalam kondisi emergency</li>
                            </ol>
                            <span class="inline-block mt-2 text-[10px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                ⓘ Tanpa Materi Teknis Pengetahuan Alat
                            </span>
                        </td>
                    </tr>

                    <!-- Baris 2: Standar Waktu -->
                    <tr class="bg-gray-50/40">
                        <td class="py-3 px-3 font-bold text-gray-700 bg-gray-50/60 border-r">
                            Standar Waktu
                        </td>
                        <td class="py-3 px-4 border-r font-extrabold text-blue-900 text-center text-sm {{ in_array(Auth::user()->certification, ['Green', null]) ? 'bg-blue-100/60 text-blue-900 ring-1 ring-blue-300' : '' }}">
                            80 Jam
                        </td>
                        <td class="py-3 px-4 border-r font-extrabold text-blue-900 text-center text-sm {{ Auth::user()->certification === 'Skill-up' ? 'bg-blue-100/60 text-blue-900 ring-1 ring-blue-300' : '' }}">
                            32 Jam
                        </td>
                        <td class="py-3 px-4 font-extrabold text-blue-900 text-center text-sm {{ in_array(Auth::user()->certification, ['Experience_internal', 'Experience_external']) ? 'bg-blue-100/60 text-blue-900 ring-1 ring-blue-300' : '' }}" colspan="2">
                            16 Jam
                        </td>
                    </tr>

                    <!-- Baris 3: Evaluasi ke-1 -->
                    <tr class="bg-amber-50/60 border-t-2 border-amber-300">
                        <td class="py-3 px-4 font-black text-amber-900 border-r text-center" colspan="2">
                            Evaluasi ke-1 <span class="block text-[10px] font-normal text-amber-700">(oleh Instruktur)</span>
                        </td>
                        <td class="py-3 px-4 text-center font-black text-amber-900 text-sm" colspan="4">
                            Standar Kelulusan: <span class="bg-amber-200/80 px-3 py-1 rounded-full border border-amber-400">Skor Minimal 80</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. FITUR ABSENSI ONLINE IN-CLASS LMS OLEH TRAINEE                         -->
    <!-- ========================================================================= -->
    <div class="bg-white border rounded-2xl p-5 sm:p-6 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b pb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-700 text-white">PRESENSI ONLINE</span>
                        <h2 class="text-base font-extrabold text-gray-900 tracking-tight">Presensi Kehadiran In-Class Training</h2>
                    </div>
                    <p class="text-xs text-gray-500">Lakukan Clock In saat masuk sesi kelas dan Clock Out setelah sesi selesai untuk mengakumulasi jam pelatihan.</p>
                </div>
            </div>

            <!-- Jam Digital Real-time & Tanggal Hari Ini (WITA - Asia/Makassar) -->
            <div class="bg-gray-50 border rounded-xl px-4 py-2 text-right shrink-0">
                <div id="liveDate" class="text-[10px] font-bold text-gray-500 uppercase">{{ \Carbon\Carbon::now('Asia/Makassar')->translatedFormat('l, d F Y') }}</div>
                <div id="liveClock" class="text-lg font-black text-gray-900 font-mono">{{ \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s') }} WITA</div>
            </div>
        </div>

        <!-- Widget Status Presensi Hari Ini & Aksi Clock-In / Clock-Out -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
            <!-- Box Status Hari Ini -->
            <div class="md:col-span-2 bg-gradient-to-br from-slate-50 to-blue-50/50 border rounded-2xl p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Status Kehadiran Hari Ini</span>
                    @if(! $todayAttendance)
                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-gray-200 text-gray-700">Belum Presensi</span>
                    @elseif(! $todayAttendance->clock_out)
                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                            Sesi Berlangsung (Clock In)
                        </span>
                    @else
                        <span class="px-2.5 py-1 text-[11px] font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1.5">
                            ✓ Presensi Selesai
                        </span>
                    @endif
                </div>

                @if(! $todayAttendance)
                    <div class="space-y-1">
                        <p class="text-sm font-extrabold text-gray-900">Anda belum melakukan Absen Masuk hari ini.</p>
                        <p class="text-xs text-gray-600">Klik tombol <strong>"Absen Masuk (Clock In)"</strong> untuk memulai sesi in-class dan memilih topik materi SOP hari ini.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-1">
                        <div class="bg-white p-2.5 rounded-xl border">
                            <span class="text-[10px] text-gray-400 font-bold block uppercase">Jam Masuk</span>
                            <span class="text-sm font-black text-emerald-700 font-mono">{{ $todayAttendance->clock_in ? \Carbon\Carbon::parse($todayAttendance->clock_in)->timezone('Asia/Makassar')->format('H:i') . ' WITA' : '-' }}</span>
                        </div>
                        <div class="bg-white p-2.5 rounded-xl border">
                            <span class="text-[10px] text-gray-400 font-bold block uppercase">Jam Pulang</span>
                            <span class="text-sm font-black text-blue-700 font-mono">{{ $todayAttendance->clock_out ? \Carbon\Carbon::parse($todayAttendance->clock_out)->timezone('Asia/Makassar')->format('H:i') . ' WITA' : 'Belum Out' }}</span>
                        </div>
                        <div class="bg-white p-2.5 rounded-xl border">
                            <span class="text-[10px] text-gray-400 font-bold block uppercase">Durasi Hari Ini</span>
                            <span class="text-sm font-black text-gray-900 font-mono">{{ number_format($todayAttendance->total_hours, 1) }} Jam</span>
                        </div>
                        <div class="bg-white p-2.5 rounded-xl border">
                            <span class="text-[10px] text-gray-400 font-bold block uppercase">Verifikasi</span>
                            <span class="text-xs font-bold {{ $todayAttendance->verified_at ? 'text-emerald-700' : 'text-amber-600' }}">
                                {{ $todayAttendance->verified_at ? '✓ Diverifikasi' : 'Menunggu' }}
                            </span>
                        </div>
                    </div>
                    <div class="text-xs text-gray-700 pt-1">
                        <span class="font-bold">Topik Pembelajaran Hari Ini:</span>
                        <span class="text-blue-900 font-semibold">{{ $todayAttendance->topic }}</span>
                    </div>
                @endif
            </div>

            <!-- Box Tombol Aksi -->
            <div class="space-y-2.5">
                @if(! $todayAttendance || ! $todayAttendance->clock_in)
                    <button type="button" onclick="openClockInModal()" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Absen Masuk (Clock In)
                    </button>
                @elseif(! $todayAttendance->clock_out)
                    <form method="POST" action="{{ route('evaluasi1.trainee.attendance.clock_out') }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Selesaikan sesi in-class hari ini dan lakukan Absen Pulang (Clock Out)?')" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Absen Pulang (Clock Out)
                        </button>
                    </form>
                @else
                    <div class="text-center p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-emerald-800 text-xs font-bold">
                        ✓ Presensi Hari Ini Telah Lengkap
                    </div>
                @endif

                <button type="button" onclick="openManualAttendanceModal()" class="w-full py-2.5 px-4 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Input Presensi / Izin / Sakit
                </button>
            </div>
        </div>

        <!-- Tabel Riwayat Kehadiran Trainee -->
        <div class="space-y-3 pt-2">
            <h3 class="text-sm font-extrabold text-gray-900 flex items-center justify-between">
                <span>Riwayat Kehadiran Trainee</span>
                <span class="text-xs font-semibold text-gray-500">Total: {{ $attendances->count() }} Hari Sesi</span>
            </h3>

            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b text-[10px] font-black text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-3.5">Tanggal</th>
                            <th class="py-3 px-3.5">Jam Masuk (WITA)</th>
                            <th class="py-3 px-3.5">Jam Pulang (WITA)</th>
                            <th class="py-3 px-3.5">Durasi</th>
                            <th class="py-3 px-3.5">Topik Materi SOP</th>
                            <th class="py-3 px-3.5 text-center">Foto Selfie</th>
                            <th class="py-3 px-3.5">Status</th>
                            <th class="py-3 px-3.5 text-right">Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($attendances as $att)
                            @php
                                $badge = $att->status_badge;
                            @endphp
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="py-3 px-3.5 font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}
                                </td>
                                <td class="py-3 px-3.5 font-mono text-emerald-700 font-bold">
                                    {{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->timezone('Asia/Makassar')->format('H:i') . ' WITA' : '-' }}
                                </td>
                                <td class="py-3 px-3.5 font-mono text-blue-700 font-bold">
                                    {{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->timezone('Asia/Makassar')->format('H:i') . ' WITA' : '-' }}
                                </td>
                                <td class="py-3 px-3.5 font-bold font-mono">
                                    {{ number_format($att->total_hours, 1) }} Jam
                                </td>
                                <td class="py-3 px-3.5 font-medium text-gray-800 max-w-xs">
                                    {{ $att->topic }}
                                </td>
                                <td class="py-3 px-3.5 text-center">
                                    @if($att->selfie_path)
                                        <button type="button" onclick="viewPhoto('{{ asset('storage/' . $att->selfie_path) }}')" class="p-1 rounded bg-gray-100 hover:bg-gray-200 text-blue-600 inline-block" title="Lihat Foto Selfie">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </button>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3.5">
                                    <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-full border {{ $badge['bg'] }}">
                                        {{ $badge['label'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-3.5 text-right font-medium">
                                    @if($att->verified_at)
                                        <span class="text-emerald-700 font-bold text-[10px]">✓ Diverifikasi</span>
                                    @else
                                        <span class="text-gray-400 text-[10px]">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-400 text-xs">
                                    Belum ada data absensi yang tercatat. Silakan lakukan Absen Masuk untuk hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 3. MATERI LMS & UJIAN CBT EVALUASI 1                                     -->
    <!-- ========================================================================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri & Tengah: Materi Pembelajaran LMS & Post-Test Modul -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Materi Pelatihan In-Class & Post-Test Modul
                    </h2>
                    <p class="text-xs text-gray-500">Pelajari materi hingga selesai, lalu ikuti Post-Test pada setiap modul. Syarat kelulusan: <strong class="text-blue-900">Nilai minimal 80</strong> (remedial jika &lt; 80).</p>
                </div>
            </div>

            @php
                $detailsByMatId = collect($eval1Summary['material_details'])->keyBy('material.id');
            @endphp

            <div class="space-y-4">
                @forelse($materials as $material)
                    @php
                        $matDetail = $detailsByMatId->get($material->id);
                        $isCompleted = $matDetail['is_completed'] ?? false;
                        $postTest = $matDetail['post_test'] ?? null;
                        $matStatus = $matDetail['status'] ?? 'locked'; // locked, pending, passed, remedial
                        $matScore = $matDetail['score'] ?? null;
                        $bestAttempt = $matDetail['best_attempt'] ?? null;
                    @endphp
                    <div class="bg-white border rounded-2xl p-4 sm:p-5 transition-all hover:shadow-md {{ $matStatus === 'remedial' ? 'border-rose-300 ring-1 ring-rose-300 bg-rose-50/10' : ($matStatus === 'passed' ? 'border-emerald-300 bg-emerald-50/10' : 'border-gray-200') }}">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl {{ $isCompleted ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center shrink-0">
                                    @if($material->type === 'video')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-gray-100 text-gray-700">Modul {{ $material->order }}</span>
                                        <span class="text-[11px] font-medium text-gray-500 uppercase">{{ $material->type }}</span>
                                        @if($isCompleted)
                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                Materi Selesai Ditonton
                                            </span>
                                        @endif
                                    </div>
                                    <h3 class="text-sm sm:text-base font-bold text-gray-900">{{ $material->title }}</h3>
                                    <p class="text-xs text-gray-600 line-clamp-2 leading-relaxed">{{ $material->description }}</p>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <a href="{{ route('evaluasi1.trainee.material.show', $material->id) }}" class="inline-flex items-center px-3.5 py-2 text-xs font-bold rounded-lg {{ $isCompleted ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-blue-600 text-white hover:bg-blue-700 shadow-sm' }} transition">
                                    {{ $isCompleted ? 'Buka Materi' : 'Pelajari Video' }}
                                    <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Sub-Panel Post-Test Modul & Remedial Status -->
                        @if($matStatus === 'locked')
                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-400 bg-gray-50/70 -mx-4 sm:-mx-5 -mb-4 sm:-mb-5 px-4 py-3 rounded-b-2xl">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>Post-Test Terkunci: Tonton modul materi hingga tuntas untuk membuka ujian Post-Test.</span>
                                </div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 shrink-0">Syarat: Min 80</span>
                            </div>
                        @elseif($matStatus === 'pending')
                            <div class="mt-4 pt-3 border-t border-blue-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 bg-blue-50/50 -mx-4 sm:-mx-5 -mb-4 sm:-mb-5 px-4 py-3 rounded-b-2xl">
                                <div class="flex items-center gap-2 text-xs text-blue-900 font-semibold">
                                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <span>Materi selesai. Silakan ikuti <strong>Post-Test Modul {{ $material->order }}</strong> (Standar kelulusan: Skor minimal 80).</span>
                                </div>
                                @if($postTest)
                                    <a href="{{ route('evaluasi1.trainee.exam.prep', $postTest->id) }}" class="inline-flex items-center justify-center px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition shrink-0">
                                        Mulai Post-Test Modul
                                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                @endif
                            </div>
                        @elseif($matStatus === 'remedial')
                            <div class="mt-4 pt-3 border-t border-rose-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-rose-50/80 -mx-4 sm:-mx-5 -mb-4 sm:-mb-5 px-4 py-3.5 rounded-b-2xl">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-rose-600 text-white shadow-xs">
                                            ⚠️ REMEDIAL POST-TEST: Skor {{ number_format($matScore, 1) }} / 100
                                        </span>
                                        <span class="text-xs text-rose-800 font-bold">(Standar Kelulusan: Min. 80)</span>
                                    </div>
                                    <p class="text-xs text-rose-700 leading-relaxed">
                                        Nilai belum mencapai batas kelulusan 80. Anda berstatus <strong>Remedial pada Modul ini</strong> dan harus mengulang Post-Test agar bisa membuka Tes Akhir CBT & SIMPER L1.
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($bestAttempt)
                                        <a href="{{ route('evaluasi1.trainee.exam.result', $bestAttempt->id) }}" class="px-3 py-1.5 text-xs font-bold text-rose-800 bg-white border border-rose-200 hover:bg-rose-50 rounded-lg transition">
                                            Hasil Nilai
                                        </a>
                                    @endif
                                    @if($postTest)
                                        <a href="{{ route('evaluasi1.trainee.exam.prep', $postTest->id) }}" class="inline-flex items-center px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black rounded-lg shadow-sm transition">
                                            🔄 Ulangi Post-Test (Remedial)
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @elseif($matStatus === 'passed')
                            <div class="mt-4 pt-3 border-t border-emerald-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 bg-emerald-50/60 -mx-4 sm:-mx-5 -mb-4 sm:-mb-5 px-4 py-3 rounded-b-2xl">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-emerald-600 text-white shadow-xs">
                                        ✓ LULUS POST-TEST: Skor {{ number_format($matScore, 1) }} / 100
                                    </span>
                                    <span class="text-xs text-emerald-800 font-semibold">Tuntas memenuhi syarat kelulusan modul (≥ 80).</span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    @if($bestAttempt)
                                        <a href="{{ route('evaluasi1.trainee.exam.result', $bestAttempt->id) }}" class="px-3 py-1.5 text-xs font-bold text-emerald-800 bg-white border border-emerald-300 hover:bg-emerald-50 rounded-lg transition">
                                            Lihat Rincian
                                        </a>
                                    @endif
                                    @if($postTest)
                                        <a href="{{ route('evaluasi1.trainee.exam.prep', $postTest->id) }}" class="px-2.5 py-1.5 text-xs font-semibold text-gray-500 hover:text-gray-800 transition" title="Ikuti ujian lagi untuk meningkatkan nilai">
                                            Ulangi
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-xl border border-dashed text-gray-500 text-sm">
                        Belum ada materi pelatihan yang aktif saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Kanan: Tes Akhir CBT Evaluasi 1 & Syarat Kelulusan SIMPER L1 -->
        <div class="space-y-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Ujian Akhir CBT & SIMPER L1
                </h2>
                <p class="text-xs text-gray-500">Standar Kelulusan: <span class="font-bold text-amber-700">Skor Minimal 80</span> di setiap Post-Test dan Tes Akhir CBT.</p>
            </div>

            <!-- Card Tes Akhir CBT Evaluasi 1 -->
            @php
                $finalExam = $eval1Summary['final_exam'];
                $canTakeFinal = $eval1Summary['can_take_final_exam'];
                $finalStatus = $eval1Summary['final_status']; // not_taken, remedial, passed
                $finalScore = $eval1Summary['final_score'];
                $finalAttempt = $eval1Summary['final_attempt'];
            @endphp

            @if($finalExam)
            <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-4 {{ $finalStatus === 'passed' ? 'border-emerald-300 ring-2 ring-emerald-100' : ($finalStatus === 'remedial' ? 'border-rose-300 ring-2 ring-rose-100' : ($canTakeFinal ? 'border-blue-300' : 'border-gray-200')) }}">
                <div class="flex items-start justify-between">
                    <span class="text-[10px] font-black uppercase px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 border border-amber-300">
                        Tes Akhir CBT Evaluasi 1
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                        Webcam Proctoring
                    </span>
                </div>

                <div>
                    <h3 class="text-base font-extrabold text-gray-900 leading-snug">{{ $finalExam->title }}</h3>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $finalExam->description }}</p>
                </div>

                <!-- Prasyarat Kelayakan Card -->
                <div class="bg-gray-50 rounded-xl p-3.5 border space-y-2 text-xs">
                    <div class="text-[10px] font-black text-gray-500 uppercase tracking-wider">Syarat Mengikuti Tes Akhir & Kelulusan:</div>
                    <ul class="space-y-1.5 text-gray-700">
                        <li class="flex items-center justify-between">
                            <span>1. Jam Presensi SOP (≥ {{ $targetHours }} Jam)</span>
                            <span class="font-bold {{ $totalAttendanceHours >= $targetHours ? 'text-emerald-700' : 'text-amber-600' }}">
                                {{ number_format($totalAttendanceHours, 1) }}/{{ $targetHours }} Jam {{ $totalAttendanceHours >= $targetHours ? '✓' : '— Belum' }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span>2. Tonton Semua Materi Video</span>
                            <span class="font-bold {{ $completedCount >= $totalMaterials ? 'text-emerald-700' : 'text-amber-600' }}">
                                {{ $completedCount }}/{{ $totalMaterials }} Modul {{ $completedCount >= $totalMaterials ? '✓' : '— Belum' }}
                            </span>
                        </li>
                        <li class="flex items-center justify-between">
                            <span>3. Lulus Post-Test Semua Modul (Min. 80)</span>
                            <span class="font-bold {{ $eval1Summary['passed_post_tests'] >= $totalMaterials ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $eval1Summary['passed_post_tests'] }}/{{ $totalMaterials }} Lulus {{ $eval1Summary['passed_post_tests'] >= $totalMaterials ? '✓' : '— Belum' }}
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Info Nilai & Status Kelulusan Tes Akhir -->
                <div class="grid grid-cols-3 gap-2 py-2 border-y border-gray-100 text-center">
                    <div>
                        <div class="text-[9px] text-gray-400 font-semibold uppercase">Durasi</div>
                        <div class="text-xs font-bold text-gray-800">{{ $finalExam->duration_minutes }} Mnt</div>
                    </div>
                    <div>
                        <div class="text-[9px] text-gray-400 font-semibold uppercase">Passing</div>
                        <div class="text-xs font-bold text-emerald-700">{{ $finalExam->passing_score }}%</div>
                    </div>
                    <div>
                        <div class="text-[9px] text-gray-400 font-semibold uppercase">Hasil Akhir</div>
                        @if($finalStatus === 'passed')
                            <div class="text-xs font-black text-emerald-600">Lulus ({{ number_format($finalScore, 1) }})</div>
                        @elseif($finalStatus === 'remedial')
                            <div class="text-xs font-black text-rose-600">Remedial ({{ number_format($finalScore, 1) }})</div>
                        @else
                            <div class="text-xs font-medium text-gray-500">Belum Ikut</div>
                        @endif
                    </div>
                </div>

                <!-- Tombol Aksi Tes Akhir -->
                <div>
                    @if(! $canTakeFinal)
                        <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl space-y-2">
                            <div class="flex items-center gap-2 text-amber-900 font-bold text-xs">
                                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Tes Akhir Terkunci</span>
                            </div>
                            <p class="text-[11px] text-amber-800 leading-relaxed">
                                @if($eval1Summary['remedial_post_tests'] > 0)
                                    Ada <strong class="text-rose-700">{{ $eval1Summary['remedial_post_tests'] }} materi berstatus REMEDIAL</strong>. Anda wajib mengulang post-test materi tersebut hingga mencapai nilai minimal 80 untuk membuka Tes Akhir CBT.
                                @else
                                    Selesaikan seluruh materi video dan lulus seluruh Post-Test materi untuk membuka Tes Akhir CBT.
                                @endif
                            </p>
                            <button type="button" disabled class="w-full py-2.5 px-4 bg-gray-200 text-gray-500 text-xs font-bold rounded-lg cursor-not-allowed">
                                🔒 Tes Akhir Belum Terbuka
                            </button>
                        </div>
                    @elseif($finalStatus === 'passed')
                        <div class="space-y-2">
                            <div class="p-3 bg-emerald-50 border border-emerald-300 rounded-xl text-center space-y-1">
                                <span class="text-xs font-black text-emerald-800 uppercase block">✓ KOMPETEN EVALUASI 1</span>
                                <p class="text-[11px] text-emerald-700">Telah lulus seluruh Post-Test materi & Tes Akhir CBT. Sertifikat SIMPER L1 aktif & Anda berhak lanjut ke Evaluasi 2.</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                @if($finalAttempt)
                                    <a href="{{ route('evaluasi1.trainee.exam.result', $finalAttempt->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                                        Lihat Hasil & Rincian Ujian
                                    </a>
                                @endif
                                <a href="{{ route('evaluasi2.assessments.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-emerald-600 text-emerald-800 hover:bg-emerald-50 text-xs font-bold rounded-lg transition">
                                    Lanjut ke Evaluasi 2 (Gerdas)
                                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @elseif($finalStatus === 'remedial')
                        <div class="space-y-2">
                            <div class="p-3 bg-rose-50 border border-rose-300 rounded-xl space-y-1 text-center">
                                <span class="text-xs font-black text-rose-800 uppercase block">⚠️ REMEDIAL TES AKHIR CBT</span>
                                <p class="text-[11px] text-rose-700">Skor Anda {{ number_format($finalScore, 1) }} (Batas minimal 80). Silakan ikuti ujian remedial untuk kelulusan SIMPER L1.</p>
                            </div>
                            <a href="{{ route('evaluasi1.trainee.exam.prep', $finalExam->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-black rounded-lg shadow-sm transition">
                                🔄 Ulangi Tes Akhir CBT (Remedial)
                            </a>
                        </div>
                    @else
                        <a href="{{ route('evaluasi1.trainee.exam.prep', $finalExam->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-xs font-black rounded-lg shadow-md transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Mulai Tes Akhir CBT (SIMPER L1)
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Pre-Test In-Class Card -->
            @php
                $preTestExam = $exams->firstWhere('type', 'pre_test');
                $preAttempt = $preTestExam ? $attempts->get($preTestExam->id) : null;
            @endphp
            @if($preTestExam)
            <div class="bg-white border rounded-2xl p-4 shadow-xs space-y-3 border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-gray-100 text-gray-700">Pre-Test Teori</span>
                    <span class="text-[10px] text-gray-500 font-semibold">{{ $preTestExam->duration_minutes }} Menit</span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-900">{{ $preTestExam->title }}</h4>
                    <p class="text-[11px] text-gray-500 mt-0.5">Ujian awal tolak ukur pengetahuan sebelum pembelajaran.</p>
                </div>
                <div>
                    @if($preAttempt)
                        <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-xl text-xs">
                            <span class="font-bold text-gray-700">Nilai Pre-Test:</span>
                            <span class="font-extrabold font-mono text-blue-800">{{ number_format($preAttempt->total_score, 1) }} / 100</span>
                        </div>
                    @else
                        <a href="{{ route('evaluasi1.trainee.exam.prep', $preTestExam->id) }}" class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-lg transition">
                            Ikuti Pre-Test
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL CLOCK IN (ABSEN MASUK) DENGAN WEBCAM SELFIE CAPTURE                -->
<!-- ========================================================================= -->
<div id="clockInModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="text-base font-black text-gray-900">Absen Masuk (Clock In) In-Class</h3>
            </div>
            <button type="button" onclick="closeClockInModal()" class="text-gray-400 hover:text-gray-700 font-bold">✕</button>
        </div>

        <form method="POST" action="{{ route('evaluasi1.trainee.attendance.clock_in') }}" class="space-y-4" id="clockInForm">
            @csrf
            <input type="hidden" name="selfie_image" id="clockInSelfieInput">

            <!-- Pilihan Topik Sesuai SOP -->
            <div class="space-y-1">
                <label class="block text-[11px] font-black text-gray-700 uppercase">Topik Materi Pembelajaran Hari Ini <span class="text-rose-500">*</span></label>
                <select name="topic" required class="w-full border-gray-300 rounded-xl text-xs p-2.5 bg-gray-50 font-medium">
                    <option value="">-- Pilih Topik Silabus SOP --</option>
                    @foreach($sopTopics as $topic)
                        <option value="{{ $topic }}">{{ $topic }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-500">Topik disesuaikan dengan jalur sertifikasi: <strong class="text-blue-700">{{ $sop['label'] }}</strong>.</p>
            </div>

            <!-- Lokasi -->
            <div class="space-y-1">
                <label class="block text-[11px] font-black text-gray-700 uppercase">Lokasi Pembelajaran</label>
                <input type="text" name="location" value="Training Centre Berau Coal (In-Class LMS)" class="w-full border-gray-300 rounded-xl text-xs p-2.5 bg-gray-50">
            </div>

            <!-- Catatan -->
            <div class="space-y-1">
                <label class="block text-[11px] font-black text-gray-700 uppercase">Catatan / Keterangan Sesi (Opsional)</label>
                <input type="text" name="notes" placeholder="Contoh: Sesi pagi teori K3L bersama Instruktur" class="w-full border-gray-300 rounded-xl text-xs p-2.5 bg-gray-50">
            </div>

            <!-- Webcam Snapshot Card -->
            <div class="space-y-2 border rounded-xl p-3.5 bg-gray-50">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black text-gray-700 uppercase flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Foto Selfie Presensi
                    </span>
                    <button type="button" id="startCamBtn" onclick="initClockInCamera()" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 underline">
                        Nyalakan Kamera
                    </button>
                </div>

                <div class="relative bg-black rounded-xl overflow-hidden aspect-video flex items-center justify-center">
                    <video id="clockInVideo" autoplay playsinline muted class="w-full h-full object-cover hidden"></video>
                    <canvas id="clockInCanvas" class="hidden"></canvas>
                    <img id="clockInSnapshotPreview" class="w-full h-full object-cover hidden" alt="Snapshot">
                    
                    <div id="camPlaceholder" class="text-center p-4 text-gray-400 text-xs space-y-1">
                        <svg class="w-8 h-8 mx-auto text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <p>Kamera siap digunakan untuk bukti presensi valid.</p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <button type="button" id="captureBtn" onclick="takeClockInSnapshot()" class="hidden px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold rounded-lg transition">
                        Ambil Foto 📸
                    </button>
                    <button type="button" id="retakeBtn" onclick="retakeClockInPhoto()" class="hidden text-[10px] font-bold text-amber-600 hover:underline">
                        Foto Ulang
                    </button>
                    <span id="camStatusMsg" class="text-[10px] text-gray-500">Klik "Nyalakan Kamera"</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t">
                <button type="button" onclick="closeClockInModal()" class="px-4 py-2 border rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl shadow-md transition">
                    Konfirmasi Clock In Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL PRESENSI MANUAL (IZIN / SAKIT / SESI TERTENTU)                     -->
<!-- ========================================================================= -->
<div id="manualModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-black text-gray-900">Form Izin / Sakit / Presensi Manual</h3>
            <button type="button" onclick="closeManualAttendanceModal()" class="text-gray-400 hover:text-gray-700 font-bold">✕</button>
        </div>

        <form method="POST" action="{{ route('evaluasi1.trainee.attendance.store') }}" class="space-y-4">
            @csrf
            <div class="space-y-1">
                <label class="block text-[11px] font-black text-gray-700 uppercase">Tanggal</label>
                <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-xl text-xs p-2.5 bg-gray-50">
            </div>

            <div class="space-y-1">
                <label class="block text-[11px] font-black text-gray-700 uppercase">Status Kehadiran</label>
                <select name="status" id="manualStatus" required onchange="toggleManualHours()" class="w-full border-gray-300 rounded-xl text-xs p-2.5 bg-gray-50 font-bold">
                    <option value="hadir">Hadir (8 Jam Pembelajaran)</option>
                    <option value="izin">Izin (Tidak Masuk Kelas)</option>
                    <option value="sakit">Sakit (Dengan Keterangan)</option>
                </select>
            </div>

            <div class="space-y-1">
                <label class="block text-[11px] font-black text-gray-700 uppercase">Topik Materi</label>
                <select name="topic" required class="w-full border-gray-300 rounded-xl text-xs p-2.5 bg-gray-50">
                    @foreach($sopTopics as $topic)
                        <option value="{{ $topic }}">{{ $topic }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1" id="manualHoursField">
                <label class="block text-[11px] font-black text-gray-700 uppercase">Durasi Jam</label>
                <input type="number" step="0.5" name="total_hours" value="8.0" min="1" max="10" class="w-full border-gray-300 rounded-xl text-xs p-2.5 bg-gray-50">
            </div>

            <div class="space-y-1">
                <label class="block text-[11px] font-black text-gray-700 uppercase">Keterangan / Alasan</label>
                <textarea name="notes" rows="2" placeholder="Tuliskan keterangan detail..." class="w-full border-gray-300 rounded-xl text-xs p-2.5 bg-gray-50"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t">
                <button type="button" onclick="closeManualAttendanceModal()" class="px-4 py-2 border rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black rounded-xl shadow-md transition">
                    Simpan Presensi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL PREVIEW FOTO SELFIE                                                 -->
<!-- ========================================================================= -->
<div id="photoViewerModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full overflow-hidden shadow-2xl p-5 space-y-3">
        <div class="flex items-center justify-between border-b pb-2">
            <h4 class="text-xs font-bold text-gray-800">Foto Selfie Bukti Presensi</h4>
            <button type="button" onclick="closePhotoViewer()" class="text-gray-400 hover:text-gray-700 font-bold">✕</button>
        </div>
        <div class="rounded-xl overflow-hidden bg-black flex items-center justify-center">
            <img id="viewerImg" src="" alt="Bukti Foto" class="max-h-80 w-auto object-contain">
        </div>
        <button type="button" onclick="closePhotoViewer()" class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl">
            Tutup
        </button>
    </div>
</div>

<script>
// Jam Digital Real-time WITA (Asia/Makassar / UTC+8)
// Hitung selisih waktu antara server Berau Coal (Asia/Makassar) dan browser client
const serverTimeMs = {{ round(microtime(true) * 1000) }};
const clientPageLoadMs = Date.now();
const serverOffsetMs = serverTimeMs - clientPageLoadMs;

function updateLiveClock() {
    const currentWitaDate = new Date(Date.now() + serverOffsetMs);

    // Format jam dalam format 24-jam (HH:mm:ss) dengan timezone Asia/Makassar (WITA, UTC+8)
    const timeFormatter = new Intl.DateTimeFormat('en-GB', {
        timeZone: 'Asia/Makassar',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    });

    // Format hari dan tanggal bahasa Indonesia dengan timezone Asia/Makassar
    const dateFormatter = new Intl.DateTimeFormat('id-ID', {
        timeZone: 'Asia/Makassar',
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    const clockEl = document.getElementById('liveClock');
    if (clockEl) {
        clockEl.innerText = `${timeFormatter.format(currentWitaDate)} WITA`;
    }

    const dateEl = document.getElementById('liveDate');
    if (dateEl) {
        dateEl.innerText = dateFormatter.format(currentWitaDate).toUpperCase();
    }
}
setInterval(updateLiveClock, 1000);
updateLiveClock();

// Modal Functions
function openClockInModal() {
    document.getElementById('clockInModal').classList.remove('hidden');
}
function closeClockInModal() {
    document.getElementById('clockInModal').classList.add('hidden');
    stopClockInCamera();
}
function openManualAttendanceModal() {
    document.getElementById('manualModal').classList.remove('hidden');
}
function closeManualAttendanceModal() {
    document.getElementById('manualModal').classList.add('hidden');
}
function toggleManualHours() {
    const status = document.getElementById('manualStatus').value;
    const hoursField = document.getElementById('manualHoursField');
    if (status !== 'hadir') {
        hoursField.classList.add('hidden');
    } else {
        hoursField.classList.remove('hidden');
    }
}
function viewPhoto(url) {
    document.getElementById('viewerImg').src = url;
    document.getElementById('photoViewerModal').classList.remove('hidden');
}
function closePhotoViewer() {
    document.getElementById('photoViewerModal').classList.add('hidden');
    document.getElementById('viewerImg').src = '';
}

// Camera Handling for Clock-In
let clockInStream = null;

async function initClockInCamera() {
    const video = document.getElementById('clockInVideo');
    const placeholder = document.getElementById('camPlaceholder');
    const captureBtn = document.getElementById('captureBtn');
    const startBtn = document.getElementById('startCamBtn');
    const statusMsg = document.getElementById('camStatusMsg');

    try {
        clockInStream = await navigator.mediaDevices.getUserMedia({
            video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' },
            audio: false
        });
        video.srcObject = clockInStream;
        video.classList.remove('hidden');
        placeholder.classList.add('hidden');
        captureBtn.classList.remove('hidden');
        startBtn.classList.add('hidden');
        statusMsg.innerText = 'Kamera aktif. Posisikan wajah Anda lalu tekan Ambil Foto.';
    } catch (err) {
        alert('Gagal mengakses kamera webcam: ' + err.message + '. Anda tetap dapat melanjutkan Clock In.');
        statusMsg.innerText = 'Kamera tidak dapat diakses (opsional).';
    }
}

function takeClockInSnapshot() {
    const video = document.getElementById('clockInVideo');
    const canvas = document.getElementById('clockInCanvas');
    const preview = document.getElementById('clockInSnapshotPreview');
    const input = document.getElementById('clockInSelfieInput');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const statusMsg = document.getElementById('camStatusMsg');

    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
    input.value = dataUrl;
    preview.src = dataUrl;

    video.classList.add('hidden');
    preview.classList.remove('hidden');
    captureBtn.classList.add('hidden');
    retakeBtn.classList.remove('hidden');
    statusMsg.innerText = '✓ Foto selfie berhasil diambil!';
}

function retakeClockInPhoto() {
    const video = document.getElementById('clockInVideo');
    const preview = document.getElementById('clockInSnapshotPreview');
    const captureBtn = document.getElementById('captureBtn');
    const retakeBtn = document.getElementById('retakeBtn');
    const input = document.getElementById('clockInSelfieInput');

    input.value = '';
    preview.classList.add('hidden');
    video.classList.remove('hidden');
    captureBtn.classList.remove('hidden');
    retakeBtn.classList.add('hidden');
}

function stopClockInCamera() {
    if (clockInStream) {
        clockInStream.getTracks().forEach(track => track.stop());
        clockInStream = null;
    }
}
</script>
@endsection
