<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir Evaluasi On the Job Training A2B - {{ $evaluation->nama_operator }} ({{ $evaluation->id }})</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
            color: #000;
            line-height: 1.15;
        }

        .form-container {
            border: 1px solid #000;
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            padding: 8mm 10mm;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        table { border-collapse: collapse; width: 100%; }
        td, th { border: 1px solid #000; padding: 2px 3px; vertical-align: middle; }

        .header-top td { padding: 2px 3px; vertical-align: middle; }
        .logo-cell { width: 80px; text-align: center; }
        .logo-img { height: 36px; width: auto; max-width: 75px; }
        .title-cell { text-align: center; }
        .title-main { font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .title-sub { font-weight: bold; font-size: 11px; text-transform: uppercase; margin: 1px 0; }

        .info-table td { padding: 2px 3px; font-size: 8px; }
        .info-label { font-weight: bold; width: 100px; }

        .penilaian-section td { padding: 3px 4px; font-size: 8px; vertical-align: top; border: 1px solid #000; }
        .penilaian-section .section-title { font-weight: bold; text-align: center; margin-bottom: 2px; font-size: 8px; }
        .penilaian-section .check-item { display: flex; align-items: center; gap: 3px; margin: 1px 0; font-size: 8px; }
        .penilaian-section .check-box { width: 9px; height: 9px; border: 1px solid #000; display: inline-flex; align-items: center; justify-content: center; font-size: 7px; line-height: 1; flex-shrink: 0; }
        .penilaian-section .check-box.checked::after { content: "☑"; }
        .penilaian-section .check-box.unchecked::after { content: "☐"; }

        .eval-table { font-size: 7.5px; }
        .eval-table th, .eval-table td { padding: 2px 3px; }
        .eval-table th { font-weight: bold; text-align: center; background: #e5e7eb; }
        .col-no { width: 22px; text-align: center; font-weight: bold; }
        .col-aspek { text-align: left; }
        .col-kbk { width: 28px; text-align: center; font-weight: bold; }

        .conclusion-box {
            font-size: 8px; font-weight: bold;
            text-align: center; padding: 2px;
            border-top: 1px solid #000; border-bottom: 1px solid #000;
            background: #f9fafb;
        }

        table.sig-grid { table-layout: fixed; }
        table.sig-grid td { width: 25%; padding: 3px; vertical-align: bottom; text-align: center; font-size: 7.5px; }
        .sig-header { font-weight: bold; text-transform: uppercase; background: #e5e7eb; padding: 2px !important; }
        .sig-title { font-weight: bold; background: #f9fafb; padding: 2px !important; }
        .sig-space { height: 45px; }
        .sig-name { font-weight: bold; text-decoration: underline; margin-top: 2px; }
        .sig-date { margin-top: 1px; }

        .footer-table td { padding: 2px 4px; font-size: 8px; }

        .evaluation-form .checkbox-symbol { font-size: 9px; }

        /* Blok tengah yang mengisi sisa ruang kosong secara merata */
        .spacer-grow {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
        }

        @media print {
            /* PENTING: margin harus 0, sama dengan non-print, supaya tinggi 297mm pas 1 halaman */
            @page { size: A4 portrait; margin: 0; }
            html, body { width: 100%; height: 100%; margin: 0; padding: 0; }
            .form-container { border: 1px solid #000 !important; width: 210mm; height: 297mm; margin: 0 auto; padding: 8mm 10mm !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .print-btn { display: none !important; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn" style="position: fixed; top: 16px; right: 16px; z-index: 9999; padding: 8px 14px; border-radius: 8px; background: #1e3a8a; color: #fff; font-size: 12px; font-weight: bold; border: none; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.25);">
        Cetak / Download PDF
    </button>
    <div class="form-container">
        @include('final-evaluations.partials.print-body')
    </div>
</body>
</html>