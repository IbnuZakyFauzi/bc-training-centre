@extends('layouts.app', ['title' => 'Workspace Evaluasi 1 - Trainer & Admin TC'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold mb-2">
                MODUL EVALUASI 1
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Manajemen In-Class LMS & Ujian Proctoring</h1>
            <p class="text-xs text-gray-500">Kelola kurikulum in-class, bank soal CBT, pengawasan proctoring kamera, dan penerbitan SIMPER L1.</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('evaluasi1.trainer.materials.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Materi LMS
            </a>
            <a href="{{ route('evaluasi1.trainer.exams.create') }}" class="inline-flex items-center px-4 py-2 bg-brand-800 hover:bg-brand-900 text-white text-xs font-bold rounded-lg shadow-sm transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Buat Ujian CBT
            </a>
        </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border rounded-xl p-5 shadow-sm space-y-1">
            <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Materi Aktif</div>
            <div class="text-2xl font-black text-gray-900">{{ $materialsCount }} Modul</div>
            <a href="{{ route('evaluasi1.trainer.materials.index') }}" class="text-xs text-blue-600 hover:underline font-semibold inline-block pt-1">Kelola Materi →</a>
        </div>

        <div class="bg-white border rounded-xl p-5 shadow-sm space-y-1">
            <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Paket Ujian CBT</div>
            <div class="text-2xl font-black text-gray-900">{{ $examsCount }} Paket</div>
            <a href="{{ route('evaluasi1.trainer.exams.index') }}" class="text-xs text-blue-600 hover:underline font-semibold inline-block pt-1">Kelola Ujian & Soal →</a>
        </div>

        <div class="bg-white border rounded-xl p-5 shadow-sm space-y-1">
            <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Sertifikat SIMPER L1</div>
            <div class="text-2xl font-black text-emerald-600">{{ $totalCertificates }} Terbit</div>
            <div class="text-xs text-gray-500 font-medium pt-1">Kelulusan: <span class="font-bold text-gray-800">{{ $passRate }}%</span></div>
        </div>

        <div class="bg-white border rounded-xl p-5 shadow-sm space-y-1">
            <div class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Log Camera Proctoring</div>
            <div class="text-2xl font-black text-rose-600">{{ $suspiciousLogsCount }} Flagged</div>
            <a href="{{ route('evaluasi1.trainer.proctoring') }}" class="text-xs text-rose-600 hover:underline font-semibold inline-block pt-1">Pantau Kamera Live →</a>
        </div>
    </div>

    <!-- Quick Navigation Banner -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('evaluasi1.trainer.materials.index') }}" class="group bg-gradient-to-br from-blue-50 to-indigo-50/50 border border-blue-200/60 rounded-xl p-5 transition hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 group-hover:text-blue-700">Bank Materi & Video</h3>
                    <p class="text-xs text-gray-500">Upload dan kelola video pembelajaran LMS.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('evaluasi1.trainer.attendances.index') }}" class="group bg-gradient-to-br from-emerald-50 to-teal-50/50 border border-emerald-200/60 rounded-xl p-5 transition hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 group-hover:text-emerald-700">Rekap Absensi In-Class</h3>
                    <p class="text-xs text-gray-500">Verifikasi jam presensi & foto selfie trainee.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('evaluasi1.trainer.exams.index') }}" class="group bg-gradient-to-br from-amber-50 to-yellow-50/50 border border-amber-200/60 rounded-xl p-5 transition hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 group-hover:text-amber-700">Bank Soal & Ujian CBT</h3>
                    <p class="text-xs text-gray-500">Kelola soal & batas passing score.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('evaluasi1.trainer.proctoring') }}" class="group bg-gradient-to-br from-rose-50 to-pink-50/50 border border-rose-200/60 rounded-xl p-5 transition hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-rose-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 group-hover:text-rose-700">Camera Proctoring</h3>
                    <p class="text-xs text-gray-500">Inspeksi rekaman kamera webcam trainee.</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Riwayat Pengerjaan Ujian Terbaru -->
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Aktivitas Ujian CBT Terkini</h3>
            <span class="text-xs text-gray-400">10 data terbaru</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="px-5 py-3">Trainee</th>
                        <th class="px-5 py-3">Ujian</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Nilai</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Camera Flag</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attempts as $att)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-gray-900">{{ $att->user->name }}</div>
                                <div class="text-[11px] text-gray-500 font-mono">{{ $att->user->sid ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-medium text-gray-800">{{ $att->exam->title }}</div>
                                <div class="text-[10px] text-gray-400 uppercase">{{ $att->exam->type }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500">
                                {{ $att->created_at->translatedFormat('d M Y, H:i') }}
                            </td>
                            <td class="px-5 py-3.5 font-bold font-mono text-sm {{ $att->is_passed ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $att->total_score }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $att->is_passed ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $att->is_passed ? 'Lulus' : 'Belum Lulus' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @if($att->suspicious_flags_count > 0)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                        ⚠️ {{ $att->suspicious_flags_count }} Flag
                                    </span>
                                @else
                                    <span class="text-emerald-600 font-semibold text-[11px]">✓ Bersih</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('evaluasi1.trainer.proctoring', ['trainee_id' => $att->user_id]) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs">
                                    Lihat Kamera →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-400 text-xs">Belum ada riwayat pengerjaan ujian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
