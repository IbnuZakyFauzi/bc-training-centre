<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logbook OJT - {{ $logbook->logbook_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; padding: 20px; }
        .header { border-bottom: 2px solid #003829; padding-bottom: 12px; margin-bottom: 20px; }
        .header table { width: 100%; border-collapse: collapse; }
        .header-title { text-align: right; }
        .header-title h1 { font-size: 16px; margin: 0; color: #003829; font-weight: 800; text-transform: uppercase; }
        .header-title p { font-size: 9px; color: #64748b; margin-top: 2px; }
        
        .section-title { background-color: #003829; color: #ffffff; padding: 6px 10px; font-weight: bold; font-size: 11px; text-transform: uppercase; margin-top: 15px; margin-bottom: 10px; }
        
        table.grid { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.grid th, table.grid td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        table.grid th { background-color: #f8fafc; font-weight: bold; color: #334155; }
        
        .activity-box { border: 1px solid #cbd5e1; padding: 12px; font-family: monospace; white-space: pre-line; background-color: #fafafa; border-radius: 4px; min-height: 100px; }
        
        .signature-table { width: 100%; margin-top: 40px; border-collapse: collapse; text-align: center; }
        .signature-table td { width: 33%; vertical-align: top; padding: 10px; }
        .signature-space { height: 60px; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="background-color: #00A859; color: white; border: none; padding: 8px 16px; font-weight: bold; border-radius: 6px; cursor: pointer;">
            Cetak / Download PDF
        </button>
    </div>

    <!-- Official Header -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 50%;">
                    <h2 style="margin: 0; color: #003829; font-size: 14px;">PT BERAU COAL / PT MTL</h2>
                    <p style="margin: 2px 0 0 0; color: #475569; font-weight: bold;">TRAINING & COMPETENCY CENTRE DIVISION</p>
                </td>
                <td class="header-title">
                    <h1>LEMBAR VERIFIKASI OJT LOGBOOK</h1>
                    <p>No. Dokumen: {{ $logbook->logbook_number }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- Section A: General Info -->
    <div class="section-title">A. INFORMASI UMUM OPERASIONAL</div>
    <table class="grid">
        <tr>
            <th>Nama Trainee</th>
            <td>{{ $logbook->trainee->name ?? 'Ahmad Rian Syahputra' }}</td>
            <th>NRP Trainee</th>
            <td>{{ $logbook->trainee->nrp ?? 'BC-60491' }}</td>
        </tr>
        <tr>
            <th>Tanggal Operations</th>
            <td>{{ \Carbon\Carbon::parse($logbook->date)->format('d F Y') }}</td>
            <th>Shift Kerja</th>
            <td>Shift {{ ucfirst($logbook->shift) }}</td>
        </tr>
        <tr>
            <th>Departemen</th>
            <td>{{ $logbook->department->name ?? 'Mining Operations' }}</td>
            <th>Lokasi Pit / Area</th>
            <td>{{ $logbook->location }}</td>
        </tr>
        <tr>
            <th>Unit Alat Berat</th>
            <td>{{ $logbook->equipment->unit_code ?? '-' }} ({{ $logbook->equipment->model_name ?? '-' }})</td>
            <th>Status Logbook</th>
            <td><strong>{{ strtoupper($logbook->status) }}</strong></td>
        </tr>
    </table>

    <!-- Section B: HM & Hours -->
    <div class="section-title">B. CATATAN HOUR METER (HM) & JAM KERJA</div>
    <table class="grid">
        <tr>
            <th style="width: 25%;">Jam Mulai - Selesai</th>
            <td style="width: 25%;">{{ $logbook->start_time }} - {{ $logbook->finish_time }}</td>
            <th style="width: 25%;">HM Awal Unit</th>
            <td style="width: 25%;">{{ number_format($logbook->hm_start, 1) }}</td>
        </tr>
        <tr>
            <th>HM Akhir Unit</th>
            <td>{{ number_format($logbook->hm_end, 1) }}</td>
            <th style="background-color: #e2e8f0;">TOTAL HM OPERASIONAL</th>
            <td style="background-color: #e2e8f0; font-weight: bold; font-size: 13px; color: #003829;">{{ number_format($logbook->total_hm, 1) }} Jam</td>
        </tr>
    </table>

    <!-- Section C: Daily Activities -->
    <div class="section-title">C. URAIAN PEKERJAAN & CATATAN P2H HARIAN</div>
    <div class="activity-box">
        {{ $logbook->daily_activity }}
    </div>

    <!-- Section D: Signatures Block -->
    <table class="signature-table">
        <tr>
            <td>
                <p><b>Disiapkan Oleh:</b><br>Trainee Operator</p>
                <div class="signature-space"></div>
                <p><b><u>{{ $logbook->trainee->name ?? 'Ahmad Rian Syahputra' }}</u></b><br>NRP: {{ $logbook->trainee->nrp ?? 'BC-60491' }}</p>
            </td>
            <td>
                <p><b>Diverifikasi Oleh:</b><br>Trainer Evaluator</p>
                <div class="signature-space"></div>
                <p><b><u>{{ $logbook->trainer->name ?? 'Bambang Hermawan' }}</u></b><br>Senior Instructor</p>
            </td>
            <td>
                <p><b>Disetujui Oleh:</b><br>Supervisor Lapangan</p>
                <div class="signature-space"></div>
                <p><b><u>{{ $logbook->supervisor->name ?? 'Rahmat Hidayat' }}</u></b><br>Pit Superintendent</p>
            </td>
        </tr>
    </table>

</body>
</html>
