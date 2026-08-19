<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Evaluasi On the Job Training A2B - {{ $evaluation->logbook->logbook_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
            line-height: 1.3;
            padding: 10px;
        }

        .form-container { border: 1px solid #000; }

        table.header-table { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; }
        table.header-table td { border: 1px solid #000; padding: 4px; text-align: center; vertical-align: middle; }

        .logo-cell { width: 100px; padding: 4px !important; }
        .logo-img { height: 50px; width: auto; max-width: 95px; }

        .title-main { font-weight: bold; font-size: 12px; text-transform: uppercase; }
        .title-sub { font-weight: bold; font-size: 14px; text-transform: uppercase; margin: 2px 0; }
        .title-desc { font-weight: bold; font-size: 10px; }

        table.info-table { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; font-size: 10px; }
        table.info-table td { border: 1px solid #000; padding: 3px 5px; vertical-align: top; }
        .info-label { font-weight: bold; width: 120px; }

        table.eval-table { width: 100%; border-collapse: collapse; font-size: 9px; }
        table.eval-table th, table.eval-table td {
            border: 1px solid #000; padding: 3px 5px; vertical-align: middle;
        }
        table.eval-table th {
            background-color: #f3f4f6; font-weight: bold;
            text-align: center; text-transform: uppercase; font-size: 9px;
        }
        .section-header { background-color: #d1d5db; font-weight: bold; font-size: 10px; padding: 2px 5px; }

        .col-no { width: 30px; text-align: center; font-weight: bold; }
        .col-aspek { text-align: left; }
        .col-kbk { width: 60px; text-align: center; font-weight: bold; font-size: 12px; }

        .conclusion-box {
            font-size: 10px; font-weight: bold;
            text-align: center; padding: 5px;
            border-top: 1px solid #000; border-bottom: 1px solid #000;
            background-color: #f9fafb;
        }

        table.sig-grid { width: 100%; border-collapse: collapse; text-align: center; font-size: 9px; margin-top: 10px; }
        table.sig-grid td { border: 1px solid #000; padding: 5px; vertical-align: bottom; width: 25%; }
        .sig-header { background-color: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 9px; padding: 3px !important; }
        .sig-title { background-color: #f9fafb; font-weight: bold; font-size: 9px; padding: 3px !important; }
        .sig-space { height: 50px; }
        .sig-name { font-weight: bold; text-decoration: underline; font-size: 9px; margin-top: 5px; }
        .sig-date { font-size: 9px; margin-top: 2px; }

        @media print {
            @page { size: A4 portrait; margin: 10mm; }
            body { width: 100%; margin: 0; padding: 0; }
            .form-container { border: 1px solid #000 !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="form-container">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ asset('images/berau_coal_logo.svg') }}" alt="Berau Coal Logo" class="logo-img">
                </td>
                <td>
                    <div class="title-main">PT BERAU COAL / PT MTL</div>
                    <div class="title-sub">Formulir Evaluasi On the Job Training A2B</div>
                    <div class="title-desc">F-HCT-02.02, Revisi 2</div>
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td class="info-label">Nama Operator</td><td>: {{ $evaluation->nama_operator }}</td></tr>
                        <tr><td class="info-label">Perusahaan</td><td>: {{ $evaluation->perusahaan }}</td></tr>
                        <tr><td class="info-label">Lokasi Kerja</td><td>: {{ $evaluation->lokasi_kerja }}</td></tr>
                        <tr><td class="info-label">Jenis Unit A2B</td><td>: {{ $evaluation->jenis_unit_a2b }}</td></tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td class="info-label">Jenis Sertifikasi</td><td>: {{ $evaluation->jenis_sertifikasi }}</td></tr>
                        <tr><td class="info-label">Instruktur</td><td>: {{ $evaluation->instruktur }}</td></tr>
                        <tr><td class="info-label">Operator Pendamping</td><td>: {{ $evaluation->operator_pendamping }}</td></tr>
                        <tr><td class="info-label">Tanggal Penilaian</td><td>: {{ \Carbon\Carbon::parse($evaluation->tanggal_penilaian)->format('d/m/Y') }}</td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding: 5px;">
                    <strong>Penilaian Pasca Pelatihan:</strong>
                    &nbsp;&nbsp;
                    <span class="inline-block border border-black px-2 py-1 mr-2">{{ $evaluation->tahap_penilaian === 'pendampingan' ? '✓' : '' }} Tahap Pendampingan</span>
                    <span class="inline-block border border-black px-2 py-1">{{ $evaluation->tahap_penilaian === 'tanpa_pendampingan' ? '✓' : '' }} Tahap Tanpa Pendampingan</span>
                    @if($evaluation->tahap_penilaian === 'tanpa_pendampingan' && $evaluation->sub_tahap)
                        <span class="inline-block border border-black px-2 py-1 ml-2">{{ ucfirst(str_replace('_', ' ', $evaluation->sub_tahap)) }}</span>
                    @endif
                </td>
            </tr>
        </table>

        <table class="eval-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-aspek">Aspek Penilaian</th>
                    <th class="col-kbk">K</th>
                    <th class="col-kbk">BK</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="col-no">1</td>
                    <td class="col-aspek">
                        <strong>P2H</strong><br>
                        <span style="font-size: 8px;">Peserta Melakukan P2H setiap hari secara teratur dan lengkap sesuai dengan standar.</span>
                    </td>
                    <td class="col-kbk">{!! $evaluation->p2h_status === 'K' ? '✓' : '' !!}</td>
                    <td class="col-kbk">{!! $evaluation->p2h_status === 'BK' ? '✓' : '' !!}</td>
                </tr>
                <tr>
                    <td class="col-no">2</td>
                    <td class="col-aspek">
                        <strong>Teknik Pengoperasian Unit</strong><br>
                        <span style="font-size: 8px;">Peserta mengoperasikan unit dengan benar dan tidak menimbulkan kerusakan baik minor maupun major serta mencapai target kerja yang diharapkan.</span>
                    </td>
                    <td class="col-kbk">{!! $evaluation->teknik_pengoperasian_status === 'K' ? '✓' : '' !!}</td>
                    <td class="col-kbk">{!! $evaluation->teknik_pengoperasian_status === 'BK' ? '✓' : '' !!}</td>
                </tr>
                <tr>
                    <td class="col-no">3</td>
                    <td class="col-aspek">
                        <strong>Kepatuhan Terhadap Peraturan Kerja</strong><br>
                        <span style="font-size: 8px;">Peserta mematuhi peraturan kerja yang berlaku selama mengoperasikan unit termasuk mematuhi rambu-rambu.</span>
                    </td>
                    <td class="col-kbk">{!! $evaluation->kepatuhan_status === 'K' ? '✓' : '' !!}</td>
                    <td class="col-kbk">{!! $evaluation->kepatuhan_status === 'BK' ? '✓' : '' !!}</td>
                </tr>
                <tr>
                    <td class="col-no">4</td>
                    <td class="col-aspek">
                        <strong>Kedisiplinan dan Komunikasi</strong><br>
                        <span style="font-size: 8px;">Peserta bersikap disiplin terhadap aturan-aturan, tidak melakukan pelanggaran, kehadiran tepat waktu, dan dapat bekerjasama dengan pengawas dan rekan kerja sesama operator.</span>
                    </td>
                    <td class="col-kbk">{!! $evaluation->kedisiplinan_status === 'K' ? '✓' : '' !!}</td>
                    <td class="col-kbk">{!! $evaluation->kedisiplinan_status === 'BK' ? '✓' : '' !!}</td>
                </tr>
            </tbody>
        </table>

        <div class="conclusion-box">
            KESIMPULAN: {{ strtoupper(str_replace('_', ' ', $evaluation->kesimpulan)) }}
            @if($evaluation->catatan)
                <div style="font-weight: normal; font-size: 9px; margin-top: 3px;">Catatan: {{ $evaluation->catatan }}</div>
            @endif
        </div>

        <table class="sig-grid">
            <tr>
                <td>
                    <div class="sig-header">Dievaluasi oleh</div>
                    <div class="sig-title">Instruktur / Pengawas / Operator Pendamping</div>
                    <div class="sig-space">
                        @if($evaluation->instruktur_signature_path)
                            <img src="{{ asset('storage/'.$evaluation->instruktur_signature_path) }}" alt="Tanda tangan" style="max-height: 45px; max-width: 80px;">
                        @endif
                    </div>
                    <div class="sig-name">{{ $evaluation->trainer->name ?? '...............................' }}</div>
                    <div class="sig-date">Tanggal: .....................</div>
                </td>
                <td>
                    <div class="sig-header">Diperiksa oleh</div>
                    <div class="sig-title">Kabag OTD / LC Mitra Kerja</div>
                    <div class="sig-space">
                        @if($evaluation->kabag_signature_path)
                            <img src="{{ asset('storage/'.$evaluation->kabag_signature_path) }}" alt="Tanda tangan" style="max-height: 45px; max-width: 80px;">
                        @endif
                    </div>
                    <div class="sig-name">...............................</div>
                    <div class="sig-date">Tanggal: .....................</div>
                </td>
                <td>
                    <div class="sig-header">Disetujui oleh</div>
                    <div class="sig-title">Penanggung Jawab Operasional</div>
                    <div class="sig-space">
                        @if($evaluation->penanggung_jawab_signature_path)
                            <img src="{{ asset('storage/'.$evaluation->penanggung_jawab_signature_path) }}" alt="Tanda tangan" style="max-height: 45px; max-width: 80px;">
                        @endif
                    </div>
                    <div class="sig-name">...............................</div>
                    <div class="sig-date">Tanggal: .....................</div>
                </td>
                <td>
                    <div class="sig-header">Diverifikasi oleh</div>
                    <div class="sig-title">HSE Training Section PT. BC</div>
                    <div class="sig-space">
                        @if($evaluation->hse_signature_path)
                            <img src="{{ asset('storage/'.$evaluation->hse_signature_path) }}" alt="Tanda tangan" style="max-height: 45px; max-width: 80px;">
                        @endif
                    </div>
                    <div class="sig-name">...............................</div>
                    <div class="sig-date">Tanggal: {{ \Carbon\Carbon::parse($evaluation->tanggal_penilaian)->format('d/m/Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
