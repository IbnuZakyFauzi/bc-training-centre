<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Evaluasi - {{ $evaluation->logbook->logbook_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#003829',
                        secondary: '#00593E',
                        accent: '#00A859',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="max-w-5xl mx-auto p-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <img src="{{ asset('images/berau_coal_logo.svg') }}" alt="Berau Coal Logo" class="h-16 w-auto">
                </div>
                <div class="text-right">
                    <h1 class="text-xl font-bold text-primary">Formulir Evaluasi On the Job Training A2B</h1>
                    <p class="text-sm text-slate-600">F-HCT-02.02, Revisi 2</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div><span class="text-xs font-bold text-slate-500">Nama Operator</span><div class="text-sm font-semibold">{{ $evaluation->nama_operator }}</div></div>
                <div><span class="text-xs font-bold text-slate-500">Perusahaan</span><div class="text-sm font-semibold">{{ $evaluation->perusahaan }}</div></div>
                <div><span class="text-xs font-bold text-slate-500">Lokasi Kerja</span><div class="text-sm font-semibold">{{ $evaluation->lokasi_kerja }}</div></div>
                <div><span class="text-xs font-bold text-slate-500">Jenis Unit A2B</span><div class="text-sm font-semibold">{{ $evaluation->jenis_unit_a2b }}</div></div>
                <div><span class="text-xs font-bold text-slate-500">Jenis Sertifikasi</span><div class="text-sm font-semibold">{{ $evaluation->jenis_sertifikasi }}</div></div>
                <div><span class="text-xs font-bold text-slate-500">Instruktur</span><div class="text-sm font-semibold">{{ $evaluation->instruktur }}</div></div>
                <div><span class="text-xs font-bold text-slate-500">Operator Pendamping</span><div class="text-sm font-semibold">{{ $evaluation->operator_pendamping }}</div></div>
                <div><span class="text-xs font-bold text-slate-500">Tanggal Penilaian</span><div class="text-sm font-semibold">{{ \Carbon\Carbon::parse($evaluation->tanggal_penilaian)->format('d/m/Y') }}</div></div>
            </div>

            <div class="bg-slate-50 rounded-xl p-4 mb-6">
                <h3 class="text-sm font-bold text-slate-800 mb-2">Penilaian Pasca Pelatihan</h3>
                <div class="flex flex-wrap gap-4">
                    <span class="text-sm">Tahap: <strong>{{ ucfirst(str_replace('_', ' ', $evaluation->tahap_penilaian)) }}</strong></span>
                    @if($evaluation->sub_tahap)
                        <span class="text-sm">Sub-tahap: <strong>{{ ucfirst(str_replace('_', ' ', $evaluation->sub_tahap)) }}</strong></span>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-6">
                <table class="w-full text-sm">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="px-4 py-3 text-left w-16">No</th>
                            <th class="px-4 py-3 text-left">Aspek Penilaian</th>
                            <th class="px-4 py-3 text-center w-24">K</th>
                            <th class="px-4 py-3 text-center w-24">BK</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-3 font-bold">1</td>
                            <td class="px-4 py-3">
                                <div class="font-bold">P2H</div>
                                <div class="text-xs text-slate-500">Peserta Melakukan P2H setiap hari secara teratur dan lengkap sesuai dengan standar.</div>
                            </td>
                            <td class="px-4 py-3 text-center font-bold {{ $evaluation->p2h_status === 'K' ? 'text-green-600' : 'text-slate-300' }}">{{ $evaluation->p2h_status === 'K' ? '✓' : '' }}</td>
                            <td class="px-4 py-3 text-center font-bold {{ $evaluation->p2h_status === 'BK' ? 'text-red-600' : 'text-slate-300' }}">{{ $evaluation->p2h_status === 'BK' ? '✓' : '' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-bold">2</td>
                            <td class="px-4 py-3">
                                <div class="font-bold">Teknik Pengoperasian Unit</div>
                                <div class="text-xs text-slate-500">Peserta mengoperasikan unit dengan benar dan tidak menimbulkan kerusakan baik minor maupun major serta mencapai target kerja yang diharapkan.</div>
                            </td>
                            <td class="px-4 py-3 text-center font-bold {{ $evaluation->teknik_pengoperasian_status === 'K' ? 'text-green-600' : 'text-slate-300' }}">{{ $evaluation->teknik_pengoperasian_status === 'K' ? '✓' : '' }}</td>
                            <td class="px-4 py-3 text-center font-bold {{ $evaluation->teknik_pengoperasian_status === 'BK' ? 'text-red-600' : 'text-slate-300' }}">{{ $evaluation->teknik_pengoperasian_status === 'BK' ? '✓' : '' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-bold">3</td>
                            <td class="px-4 py-3">
                                <div class="font-bold">Kepatuhan Terhadap Peraturan Kerja</div>
                                <div class="text-xs text-slate-500">Peserta mematuhi peraturan kerja yang berlaku selama mengoperasikan unit termasuk mematuhi rambu-rambu.</div>
                            </td>
                            <td class="px-4 py-3 text-center font-bold {{ $evaluation->kepatuhan_status === 'K' ? 'text-green-600' : 'text-slate-300' }}">{{ $evaluation->kepatuhan_status === 'K' ? '✓' : '' }}</td>
                            <td class="px-4 py-3 text-center font-bold {{ $evaluation->kepatuhan_status === 'BK' ? 'text-red-600' : 'text-slate-300' }}">{{ $evaluation->kepatuhan_status === 'BK' ? '✓' : '' }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-bold">4</td>
                            <td class="px-4 py-3">
                                <div class="font-bold">Kedisiplinan dan Komunikasi</div>
                                <div class="text-xs text-slate-500">Peserta bersikap disiplin terhadap aturan-aturan, tidak melakukan pelanggaran, kehadiran tepat waktu, dan dapat bekerjasama dengan pengawas dan rekan kerja sesama operator.</div>
                            </td>
                            <td class="px-4 py-3 text-center font-bold {{ $evaluation->kedisiplinan_status === 'K' ? 'text-green-600' : 'text-slate-300' }}">{{ $evaluation->kedisiplinan_status === 'K' ? '✓' : '' }}</td>
                            <td class="px-4 py-3 text-center font-bold {{ $evaluation->kedisiplinan_status === 'BK' ? 'text-red-600' : 'text-slate-300' }}">{{ $evaluation->kedisiplinan_status === 'BK' ? '✓' : '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="bg-slate-50 rounded-xl p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 mb-1">Kesimpulan Akhir</h3>
                        <p class="text-xs text-slate-500">K = KOMPETEN, BK = BELUM KOMPETEN</p>
                    </div>
                    <span class="px-4 py-2 rounded-lg font-bold text-sm {{ $evaluation->kesimpulan === 'kompeten' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ strtoupper(str_replace('_', ' ', $evaluation->kesimpulan)) }}
                    </span>
                </div>
                @if($evaluation->catatan)
                    <div class="mt-3">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan</label>
                        <p class="text-sm text-slate-600">{{ $evaluation->catatan }}</p>
                    </div>
                @endif
            </div>

            <div class="flex justify-between">
                <a href="{{ route('trainer.reviews.index') }}" class="px-6 py-2 rounded-lg border border-slate-300 text-sm font-bold text-slate-600 hover:bg-slate-50">
                    Kembali
                </a>
                <a href="{{ route('trainer.final-evaluations.print', $evaluation->id) }}" target="_blank" class="px-6 py-2 rounded-lg bg-primary text-white text-sm font-bold hover:bg-secondary">
                    Cetak / Download PDF
                </a>
            </div>
        </div>
    </div>
</body>
</html>
