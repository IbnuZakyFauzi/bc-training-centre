@extends('layouts.app', ['title' => 'Monitoring Nilai Evaluasi 1 & 2 - Admin TC'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold mb-2">
                EXECUTIVE DASHBOARD MONITORING
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Monitoring Nilai & Tren Kompetensi (Evaluasi 1 & 2)</h1>
            <p class="text-xs text-gray-500">Pemantauan performa ujian in-class CBT, praktik gerakan dasar (Gerdas), dan kesiapan naik ke Evaluasi 3.</p>
        </div>
    </div>

    <!-- KPI Summary Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-1">
            <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Total Trainee</div>
            <div class="text-2xl font-black text-gray-900">{{ $totalTrainees }}</div>
            <div class="text-[11px] text-gray-500 font-medium">Terdaftar di Sistem</div>
        </div>

        <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-1">
            <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Rata-rata Eval 1 (CBT)</div>
            <div class="text-2xl font-black text-blue-600 font-mono">{{ $avgScoreEval1 }}</div>
            <div class="text-[11px] text-gray-500 font-medium">Standar: Min 75.0</div>
        </div>

        <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-1">
            <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Rata-rata Eval 2 (Gerdas)</div>
            <div class="text-2xl font-black text-emerald-600 font-mono">{{ $avgScoreEval2 }}</div>
            <div class="text-[11px] text-gray-500 font-medium">Standar: Min 75.0</div>
        </div>

        <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-1">
            <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">SIMPER L1 Terbit</div>
            <div class="text-2xl font-black text-amber-600">{{ $simperL1Count }}</div>
            <div class="text-[11px] text-gray-500 font-medium">Lulus Teori CBT</div>
        </div>

        <div class="bg-white border rounded-2xl p-5 shadow-sm space-y-1">
            <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">SIMPER L2 Terbit</div>
            <div class="text-2xl font-black text-purple-600">{{ $simperL2Count }}</div>
            <div class="text-[11px] text-gray-500 font-medium">Lulus Praktik Gerdas</div>
        </div>
    </div>

    <!-- Interactive Visual Charts (ApexCharts) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart 1: Perbandingan Distribusi Nilai Evaluasi 1 vs Evaluasi 2 -->
        <div class="bg-white border rounded-2xl p-6 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Distribusi Grade Kompetensi</h3>
                    <p class="text-xs text-gray-400">Komparasi nilai Teori CBT (Eval 1) vs Praktik Gerdas (Eval 2)</p>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-600">Grafik Batang</span>
            </div>
            <div id="gradeDistributionChart" class="w-full h-64"></div>
        </div>

        <!-- Chart 2: Proporsi Kelulusan SIMPER L1 & L2 -->
        <div class="bg-white border rounded-2xl p-6 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Kelayakan Lanjut ke Evaluasi 3</h3>
                    <p class="text-xs text-gray-400">Tingkat pemenuhan syarat SIMPER L1 & SIMPER L2</p>
                </div>
                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-600">Donut Chart</span>
            </div>
            <div id="simperStatusChart" class="w-full h-64"></div>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-white border rounded-2xl p-4 shadow-sm">
        <form method="GET" action="{{ route('monitoring.evaluasi1-2') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama trainee, SID, perusahaan..." class="border-gray-300 rounded-xl text-xs p-2.5">
            <select name="category_id" class="border-gray-300 rounded-xl text-xs p-2.5">
                <option value="">Semua Kategori Unit A2B</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->code }})
                    </option>
                @endforeach
            </select>
            <button type="submit" class="py-2.5 bg-brand-800 hover:bg-brand-900 text-white rounded-xl text-xs font-bold transition">
                Filter Data Trainee
            </button>
        </form>
    </div>

    <!-- Data Table Trainee: Evaluasi 1, Evaluasi 2 & Rekap PT MTL -->
    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Matriks Nilai Evaluasi 1 & Evaluasi 2 Trainee</h3>
                <p class="text-xs text-gray-400">Klik 'Cetak Rekap MTL' untuk menghasilkan formulir rekapitulasi kelulusan standar PT MTL.</p>
            </div>
            <span class="text-xs font-mono text-gray-500">Total: {{ $trainees->total() }} Trainee</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="px-5 py-3">Nama Trainee</th>
                        <th class="px-5 py-3">Unit / Kategori</th>
                        <th class="px-5 py-3 text-center">Evaluasi 1 (CBT)</th>
                        <th class="px-5 py-3 text-center">Evaluasi 2 (Gerdas)</th>
                        <th class="px-5 py-3 text-center">Rata-Rata Gabungan</th>
                        <th class="px-5 py-3 text-center">Kelayakan Eval 3</th>
                        <th class="px-5 py-3 text-right">Aksi & Rekap</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($traineeRows as $row)
                        @php
                            $t = $row['trainee'];
                            $isEligibleNext = ($row['eval1_score'] >= 75) && ($row['eval2_score'] >= 75);
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-gray-900">{{ $t->name }}</div>
                                <div class="text-[11px] text-gray-500 font-mono">{{ $t->sid ?? '-' }} • {{ $t->company ?? 'PT MTL' }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-gray-800">{{ $t->equipmentCategory->name ?? 'General Unit' }}</div>
                                <div class="text-[10px] text-gray-400">{{ $t->certification ?? 'Green' }}</div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($row['eval1_score'] !== null)
                                    <div class="font-bold font-mono text-sm {{ $row['eval1_passed'] ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $row['eval1_score'] }}
                                    </div>
                                    @if($row['has_cert_l1'])
                                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-amber-100 text-amber-800">
                                            SIMPER L1 ✓
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic">Belum Ujian</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($row['eval2_score'] !== null)
                                    <div class="font-bold font-mono text-sm {{ $row['eval2_passed'] ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $row['eval2_score'] }}
                                    </div>
                                    @if($row['has_cert_l2'])
                                        <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[9px] font-extrabold uppercase bg-blue-100 text-blue-800">
                                            SIMPER L2 ✓
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic">Belum Uji</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($row['combined_avg'] !== null)
                                    <span class="font-extrabold font-mono text-sm text-gray-900">
                                        {{ $row['combined_avg'] }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($isEligibleNext)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-800">
                                        LAYAK KE EVAL 3 ✓
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase bg-gray-100 text-gray-600">
                                        DALAM PROSES
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1">
                                <a href="{{ route('evaluasi2.rekap-mtl.print', $t->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-lg text-xs font-bold transition">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Rekap MTL
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-400 text-xs">Tidak ada data trainee yang sesuai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t">
            {{ $trainees->links() }}
        </div>
    </div>
</div>

<!-- Script Render ApexCharts Visuals -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Chart Distribusi Nilai
    const distChartOptions = {
        series: [{
            name: 'Evaluasi 1 (CBT Teori)',
            data: [{{ implode(',', array_values($distributionEval1)) }}]
        }, {
            name: 'Evaluasi 2 (Praktik Gerdas)',
            data: [{{ implode(',', array_values($distributionEval2)) }}]
        }],
        chart: {
            type: 'bar',
            height: 250,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif'
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                borderRadius: 4
            },
        },
        dataLabels: { enabled: false },
        stroke: { show: true, width: 2, colors: ['transparent'] },
        xaxis: {
            categories: ['Grade A (90-100)', 'Grade B (80-89)', 'Grade C (75-79)', 'Grade D (<75)'],
        },
        yaxis: { title: { text: 'Jumlah Peserta' } },
        fill: { opacity: 1 },
        colors: ['#2563eb', '#10b981'],
        tooltip: {
            y: { formatter: function (val) { return val + " Trainee"; } }
        }
    };
    new ApexCharts(document.querySelector("#gradeDistributionChart"), distChartOptions).render();

    // 2. Chart Donut Kelayakan SIMPER
    const simperChartOptions = {
        series: [{{ $simperL1Count }}, {{ $simperL2Count }}, {{ max(0, $totalTrainees - max($simperL1Count, $simperL2Count)) }}],
        chart: {
            type: 'donut',
            height: 250,
            fontFamily: 'Inter, sans-serif'
        },
        labels: ['SIMPER L1 Aktif', 'SIMPER L2 Aktif', 'Menunggu Penyelesaian'],
        colors: ['#f59e0b', '#2563eb', '#e2e8f0'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: false }
    };
    new ApexCharts(document.querySelector("#simperStatusChart"), simperChartOptions).render();
});
</script>
@endsection
