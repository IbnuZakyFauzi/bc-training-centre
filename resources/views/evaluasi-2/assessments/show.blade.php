@extends('layouts.app', ['title' => 'Detail Penilaian Gerdas - ' . $assessment->trainee->name])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('evaluasi2.assessments.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-1 inline-flex items-center">
                ← Kembali ke Daftar Penilaian
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Hasil Uji Gerakan Dasar (Evaluasi 2)</h1>
            <p class="text-xs text-gray-500">Trainee: {{ $assessment->trainee->name }} ({{ $assessment->trainee->sid ?? '-' }})</p>
        </div>

        <div class="flex items-center gap-2">
            @if($assessment->certificate)
                <a href="{{ route('evaluasi2.certificate.print', $assessment->certificate->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak SIMPER L2
                </a>
            @endif
            <a href="{{ route('evaluasi2.rekap-mtl.print', $assessment->trainee_id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Cetak Rekap Standar PT MTL
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="bg-white border rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-6 border-b">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full {{ $assessment->isKompeten() ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    {{ $assessment->isKompeten() ? 'KOMPETEN (LULUS)' : 'BELUM KOMPETEN' }}
                </span>
                <div class="text-xs text-gray-500 mt-2">
                    Dinilai pada {{ $assessment->tanggal_penilaian->translatedFormat('d F Y') }} oleh Instruktur <strong>{{ $assessment->trainer->name }}</strong>
                </div>
            </div>
            <div class="text-right">
                <div class="text-[11px] text-gray-400 font-semibold uppercase">Total Nilai Akhir</div>
                <div class="text-3xl font-black font-mono {{ $assessment->isKompeten() ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $assessment->total_score }}
                </div>
            </div>
        </div>

        <!-- Detail Meta Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div>
                <div class="text-gray-400 font-semibold uppercase text-[10px]">Model Unit</div>
                <div class="font-bold text-gray-900 mt-0.5">{{ $assessment->unit_model }}</div>
            </div>
            <div>
                <div class="text-gray-400 font-semibold uppercase text-[10px]">Nomor Unit (CN)</div>
                <div class="font-bold text-gray-900 mt-0.5">{{ $assessment->unit_number }}</div>
            </div>
            <div>
                <div class="text-gray-400 font-semibold uppercase text-[10px]">Lokasi Praktik</div>
                <div class="font-bold text-gray-900 mt-0.5">{{ $assessment->lokasi_kerja }}</div>
            </div>
            <div>
                <div class="text-gray-400 font-semibold uppercase text-[10px]">Shift Kerja</div>
                <div class="font-bold text-gray-900 mt-0.5">Shift {{ ucfirst($assessment->shift) }}</div>
            </div>
        </div>

        <!-- Rincian Aspek Rubrik -->
        <div class="space-y-3 pt-4 border-t">
            <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Rincian Nilai Aspek Gerakan Dasar</h3>
            
            <div class="border rounded-xl overflow-hidden">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-600 font-semibold border-b">
                        <tr>
                            <th class="px-4 py-3">Aspek Penilaian</th>
                            <th class="px-4 py-3 text-center w-24">Bobot</th>
                            <th class="px-4 py-3 text-center w-24">Nilai</th>
                            <th class="px-4 py-3 text-center w-24">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if(is_array($assessment->criteria_scores))
                            @foreach($assessment->criteria_scores as $c)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-gray-900">{{ $c['aspek'] ?? '-' }}</div>
                                        <div class="text-[11px] text-gray-500">{{ $c['kriteria'] ?? '' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-mono text-gray-600">{{ $c['bobot'] ?? '-' }}%</td>
                                    <td class="px-4 py-3 text-center font-bold font-mono text-gray-900">{{ $c['nilai'] ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ ($c['status'] ?? 'K') === 'K' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ ($c['status'] ?? 'K') === 'K' ? 'K (Kompeten)' : 'BK (Belum)' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-4 py-3 font-medium">Pemeriksaan Keliling P2H Awal</td>
                                <td class="px-4 py-3 text-center font-mono">20%</td>
                                <td class="px-4 py-3 text-center font-bold font-mono">{{ $assessment->score_p2h }}</td>
                                <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">K</span></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium">Kontrol Dasar & Mekanisme Pengoperasian</td>
                                <td class="px-4 py-3 text-center font-mono">25%</td>
                                <td class="px-4 py-3 text-center font-bold font-mono">{{ $assessment->score_basic_control }}</td>
                                <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">K</span></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium">Manuver Gerakan Dasar (Maju, Mundur, Radius Belok, Parkir)</td>
                                <td class="px-4 py-3 text-center font-mono">35%</td>
                                <td class="px-4 py-3 text-center font-bold font-mono">{{ $assessment->score_maneuver }}</td>
                                <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">K</span></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-medium">Kepatuhan K3LH & Keselamatan Operasi Tambang</td>
                                <td class="px-4 py-3 text-center font-mono">20%</td>
                                <td class="px-4 py-3 text-center font-bold font-mono">{{ $assessment->score_safety_k3 }}</td>
                                <td class="px-4 py-3 text-center"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">K</span></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Catatan Instruktur -->
        @if($assessment->catatan_instruktur)
            <div class="bg-amber-50/60 border border-amber-200/60 rounded-xl p-4 text-xs text-amber-900 space-y-1">
                <div class="font-bold uppercase tracking-wider text-[10px] text-amber-700">Catatan Khusus Instruktur:</div>
                <p class="leading-relaxed">{{ $assessment->catatan_instruktur }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
