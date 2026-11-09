@extends('layouts.app', ['title' => 'Evaluasi 2 - Penilaian Gerakan Dasar (Gerdas)'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold mb-2">
                MODUL EVALUASI 2: GERAKAN DASAR (GERDAS)
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Penilaian Praktik Gerakan Dasar Unit A2B</h1>
            <p class="text-xs text-gray-500">Evaluasi praktik kendali dasar, manuver, pengereman, dan kepatuhan K3LH untuk penerbitan SIMPER L2.</p>
        </div>

        @if(auth()->user()->isTrainer() || auth()->user()->isTrainingCentre() || auth()->user()->isSuperAdmin())
            <a href="{{ route('evaluasi2.assessments.create') }}" class="inline-flex items-center px-4 py-2.5 bg-brand-800 hover:bg-brand-900 text-white text-xs font-bold rounded-xl shadow transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Input Penilaian Gerdas Baru
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if(auth()->user()->isTrainee() && ! $isEval1Passed)
        <div class="p-5 bg-amber-50 border-2 border-amber-300 rounded-2xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-xs mt-0.5 sm:mt-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-sm font-extrabold text-amber-900">Akses Evaluasi 2 (Gerakan Dasar) Belum Terbuka</h3>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        Anda belum memenuhi syarat kelulusan Evaluasi 1. Syarat kelulusan: <strong>Nilai minimal 80 pada setiap Post-Test modul materi</strong> dan <strong>minimal 80 pada Tes Akhir CBT</strong>. Jika nilai post-test di bawah 80, Anda wajib remedial pada modul tersebut terlebih dahulu.
                    </p>
                </div>
            </div>
            <a href="{{ route('evaluasi1.trainee.lms') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-xs transition shrink-0">
                Ke LMS Evaluasi 1
            </a>
        </div>
    @endif

    <!-- KPI Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white border rounded-xl p-4 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-400 uppercase">Total Penilaian</div>
            <div class="text-2xl font-black text-gray-900 mt-1">{{ $stats['total'] }} Sesi</div>
        </div>
        <div class="bg-white border rounded-xl p-4 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-400 uppercase">Kompeten</div>
            <div class="text-2xl font-black text-emerald-600 mt-1">{{ $stats['kompeten'] }} Trainee</div>
        </div>
        <div class="bg-white border rounded-xl p-4 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-400 uppercase">Belum Kompeten</div>
            <div class="text-2xl font-black text-rose-600 mt-1">{{ $stats['belum_kompeten'] }} Trainee</div>
        </div>
        <div class="bg-white border rounded-xl p-4 shadow-sm">
            <div class="text-[11px] font-semibold text-gray-400 uppercase">SIMPER L2 Terbit</div>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $stats['certificates'] }} Sertifikat</div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white border rounded-2xl p-4 shadow-sm">
        <form method="GET" action="{{ route('evaluasi2.assessments.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama trainee / SID..." class="border-gray-300 rounded-xl text-xs p-2.5">
            <select name="kesimpulan" class="border-gray-300 rounded-xl text-xs p-2.5">
                <option value="all">Semua Status Hasil</option>
                <option value="kompeten" {{ request('kesimpulan') == 'kompeten' ? 'selected' : '' }}>Kompeten (Lulus)</option>
                <option value="belum_kompeten" {{ request('kesimpulan') == 'belum_kompeten' ? 'selected' : '' }}>Belum Kompeten</option>
            </select>
            <button type="submit" class="py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
                Cari & Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="px-5 py-3">Trainee</th>
                        <th class="px-5 py-3">Unit / Model</th>
                        <th class="px-5 py-3">Lokasi / Shift</th>
                        <th class="px-5 py-3">Instruktur Penilai</th>
                        <th class="px-5 py-3">Skor Akhir</th>
                        <th class="px-5 py-3">Kesimpulan</th>
                        <th class="px-5 py-3 text-right">Aksi & Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($assessments as $item)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-gray-900">{{ $item->trainee->name }}</div>
                                <div class="text-[11px] text-gray-500 font-mono">{{ $item->trainee->sid ?? '-' }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-gray-800">{{ $item->unit_model }}</div>
                                <div class="text-[10px] text-gray-500 font-mono">No. {{ $item->unit_number }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600">
                                <div>{{ $item->lokasi_kerja }}</div>
                                <div class="text-[10px] text-gray-400">{{ $item->tanggal_penilaian->translatedFormat('d M Y') }} • Shift {{ ucfirst($item->shift) }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-gray-700 font-medium">
                                {{ $item->trainer->name }}
                            </td>
                            <td class="px-5 py-3.5 font-bold font-mono text-sm {{ $item->isKompeten() ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $item->total_score }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $item->isKompeten() ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ str_replace('_', ' ', $item->kesimpulan) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-2">
                                <a href="{{ route('evaluasi2.assessments.show', $item->id) }}" class="text-blue-600 hover:text-blue-800 font-bold text-xs">
                                    Detail
                                </a>
                                @if($item->certificate)
                                    <a href="{{ route('evaluasi2.certificate.print', $item->certificate->id) }}" target="_blank" class="text-amber-600 hover:text-amber-800 font-bold text-xs" title="Cetak SIMPER L2">
                                        SIMPER L2 🖨️
                                    </a>
                                @endif
                                <a href="{{ route('evaluasi2.rekap-mtl.print', $item->trainee_id) }}" target="_blank" class="text-emerald-700 hover:text-emerald-900 font-bold text-xs" title="Rekap PT MTL">
                                    Rekap MTL 📄
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-400 text-xs">Belum ada data penilaian Gerakan Dasar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $assessments->links() }}
        </div>
    </div>
</div>
@endsection
