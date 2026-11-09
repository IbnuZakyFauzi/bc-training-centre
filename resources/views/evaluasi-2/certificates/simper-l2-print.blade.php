<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat SIMPER L2 - {{ $certificate->trainee->name }} ({{ $certificate->certificate_number }})</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1a202c;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .cert-border {
            border: 6px double #1e3a8a;
            padding: 14mm 16mm;
            min-height: 185mm;
            text-align: center;
            position: relative;
            background: radial-gradient(circle, #fcfdff 0%, #f0f5ff 100%);
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8mm;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-img {
            max-height: 48px;
            width: auto;
        }
        .header-title-main {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .header-title-sub {
            font-size: 10pt;
            letter-spacing: 1px;
            color: #4b5563;
        }
        .cert-title-badge {
            font-size: 11pt;
            font-weight: bold;
            color: #1d4ed8;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 4mm;
        }
        .cert-main-heading {
            font-size: 24pt;
            font-weight: bold;
            letter-spacing: 2px;
            color: #111827;
            text-transform: uppercase;
            margin: 2mm 0;
        }
        .cert-number {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9pt;
            color: #6b7280;
            margin-bottom: 6mm;
        }
        .recipient-prefix {
            font-size: 11pt;
            font-style: italic;
            color: #4b5563;
        }
        .recipient-name {
            font-size: 22pt;
            font-weight: bold;
            color: #1e3a8a;
            text-decoration: underline;
            margin: 3mm 0;
            letter-spacing: 1px;
        }
        .recipient-meta {
            font-size: 10pt;
            color: #374151;
            margin-bottom: 6mm;
        }
        .statement-text {
            font-size: 11pt;
            line-height: 1.5;
            max-width: 200mm;
            margin: 0 auto 6mm auto;
            color: #1f2937;
        }
        .score-box {
            display: inline-block;
            border: 1px solid #2563eb;
            background: #eff6ff;
            padding: 2mm 8mm;
            border-radius: 4px;
            font-size: 11pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8mm;
        }
        .sig-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6mm;
        }
        .sig-table td {
            width: 33.3%;
            text-align: center;
            vertical-align: bottom;
            font-size: 10pt;
        }
        .sig-space {
            height: 18mm;
        }
        .sig-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .no-print {
            text-align: center;
            padding: 10px;
            background: #f3f4f6;
            margin-bottom: 15px;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 8px 18px; font-weight: bold; background: #1e3a8a; color: #fff; border: none; border-radius: 6px; cursor: pointer;">
            🖨️ Cetak Sertifikat SIMPER L2
        </button>
    </div>

    <div class="cert-border">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="width: 20%; text-align: left;">
                    <img src="{{ asset('images/mtl-logo.png') }}" class="logo-img" alt="MTL Logo">
                </td>
                <td style="width: 60%; text-align: center;">
                    <div class="header-title-main">PT BERAU COAL / PT MITRA TUAH LESTARI</div>
                    <div class="header-title-sub">TRAINING & COMPETENCY CENTRE SECTION</div>
                </td>
                <td style="width: 20%; text-align: right; font-weight: bold; font-size: 14pt; color: #1e3a8a;">
                    SIMPER L2
                </td>
            </tr>
        </table>

        <!-- Certificate Body -->
        <div class="cert-title-badge">SURAT KETERANGAN KOMPETENSI GERAKAN DASAR (GERDAS)</div>
        <div class="cert-main-heading">SERTIFIKAT KELAYAKAN SIMPER LEVEL 2</div>
        <div class="cert-number">NO. REGISTRASI: {{ $certificate->certificate_number }}</div>

        <div class="recipient-prefix">Diberikan secara sah dan resmi kepada:</div>
        <div class="recipient-name">{{ $certificate->trainee->name }}</div>
        <div class="recipient-meta">
            SID / NRP: <strong>{{ $certificate->trainee->sid ?? '-' }}</strong> &nbsp;|&nbsp; Perusahaan: <strong>{{ $certificate->trainee->company ?? 'PT BERAU COAL' }}</strong>
        </div>

        <div class="statement-text">
            Telah menyelesaikan dan dinyatakan <strong>KOMPETEN</strong> dalam Pengujian Praktik Gerakan Dasar (Gerdas) Unit Alat Berat (P2H, Kontrol Dasar, Manuver Maju/Mundur/Parkir, serta Kepatuhan K3LH Tambang) di bawah bimbingan dan pengawasan Instruktur Training Centre.
        </div>

        <div class="score-box">
            NILAI PRAKTIK GERDAS: {{ $certificate->total_score }} &nbsp;|&nbsp; STATUS: KOMPETEN (SIMPER L2 ACTIVE)
        </div>

        <!-- Tanda Tangan -->
        <table class="sig-table">
            <tr>
                <td>
                    <div>Berau, {{ $certificate->issue_date->translatedFormat('d F Y') }}</div>
                    <div style="font-weight: bold; margin-top: 2px;">Peserta Trainee</div>
                    <div class="sig-space"></div>
                    <div class="sig-name">{{ $certificate->trainee->name }}</div>
                    <div>SID. {{ $certificate->trainee->sid ?? '-' }}</div>
                </td>
                <td>
                    <div>Instruktur Penguji</div>
                    <div style="font-weight: bold; margin-top: 2px;">Senior Instructor</div>
                    <div class="sig-space">
                        <div style="color: #1e40af; font-weight: bold; padding-top: 8mm;">[ VERIFIED ]</div>
                    </div>
                    <div class="sig-name">{{ $certificate->assessment->trainer->name ?? 'Instruktur TC' }}</div>
                    <div>Instructor PT MTL / BC</div>
                </td>
                <td>
                    <div>Mengetahui,</div>
                    <div style="font-weight: bold; margin-top: 2px;">Head of Training Centre</div>
                    <div class="sig-space">
                        <div style="color: #1e3a8a; font-weight: bold; padding-top: 8mm;">[ TC APPROVED ]</div>
                    </div>
                    <div class="sig-name">Superintendent Training Centre</div>
                    <div>PT Berau Coal / PT MTL</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
