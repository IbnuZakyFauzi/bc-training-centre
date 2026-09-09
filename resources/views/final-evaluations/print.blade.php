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
            font-size: 6.5px;
            color: #000;
            line-height: 1.1;
        }

        .form-container {
            border: 1px solid #000;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 6mm 8mm;
            display: flex;
            flex-direction: column;
            overflow: visible;
        }

        table { border-collapse: collapse; width: 100%; }
        td, th { border: 1px solid #000; padding: 1.5px 2px; vertical-align: middle; }

        .header-top td { padding: 1.5px 2px; vertical-align: middle; }
        .logo-cell { width: 75px; text-align: center; }
        .logo-img { height: 30px; width: auto; max-width: 70px; }
        .title-cell { text-align: center; }
        .title-main { font-weight: bold; font-size: 9px; text-transform: uppercase; }
        .title-sub { font-weight: bold; font-size: 10px; text-transform: uppercase; margin: 0.5px 0; }

        .info-table td { padding: 1.5px 2px; font-size: 7px; }
        .info-label { font-weight: bold; width: 90px; }

        .penilaian-section td { padding: 2px 3px; font-size: 7px; vertical-align: top; border: 1px solid #000; }
        .penilaian-section .section-title { font-weight: bold; text-align: center; margin-bottom: 1px; font-size: 7px; }
        .penilaian-section .check-item { display: flex; align-items: center; gap: 2px; margin: 0.5px 0; font-size: 7px; }
        .penilaian-section .check-box { width: 8px; height: 8px; border: 1px solid #000; display: inline-flex; align-items: center; justify-content: center; font-size: 6px; line-height: 1; flex-shrink: 0; }
        .penilaian-section .check-box.checked::after { content: "☑"; }
        .penilaian-section .check-box.unchecked::after { content: "☐"; }

        .eval-table { font-size: 6.5px; }
        .eval-table th, .eval-table td { padding: 1.5px 2px; }
        .eval-table th { font-weight: bold; text-align: center; background: #e5e7eb; }
        .col-no { width: 20px; text-align: center; font-weight: bold; }
        .col-aspek { text-align: left; }
        .col-kbk { width: 26px; text-align: center; font-weight: bold; }

        .conclusion-box {
            font-size: 7px; font-weight: bold;
            text-align: center; padding: 1.5px;
            border-top: 1px solid #000; border-bottom: 1px solid #000;
            background: #f9fafb;
        }

        table.sig-grid { table-layout: fixed; }
        table.sig-grid td { width: 25%; padding: 2px; vertical-align: bottom; text-align: center; font-size: 6.5px; }
        .sig-header { font-weight: bold; text-transform: uppercase; background: #e5e7eb; padding: 1px !important; }
        .sig-title { font-weight: bold; background: #f9fafb; padding: 1px !important; }
        .sig-space { height: 34px; text-align: center; vertical-align: middle; }
        .sig-img { max-height: 30px; max-width: 70px; height: auto; width: auto; object-fit: contain; display: block; margin: 0 auto; }
        .sig-name { font-weight: bold; text-decoration: underline; margin-top: 1px; }
        .sig-sid { font-size: 6px; margin-top: 0.5px; }
        .sig-date { margin-top: 0.5px; }

        .footer-table td { padding: 1.5px 3px; font-size: 7px; }

        .evaluation-form .checkbox-symbol { font-size: 8px; }

        /* Blok tengah yang mengisi sisa ruang kosong secara merata */
        .spacer-grow {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
        }

        @media print {
            @page { size: A4 portrait; margin: 3mm; }
            html, body { width: 100%; height: 100%; margin: 0; padding: 0; }
            .form-container { border: 1px solid #000 !important; width: 210mm; min-height: 297mm; margin: 0 auto; padding: 5mm 7mm !important; overflow: visible !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .print-btn { display: none !important; }
        }
    </style>
</head>
<body>
    <button onclick="setTimeout(() => window.print(), 150)" class="print-btn" style="position: fixed; top: 16px; right: 16px; z-index: 9999; padding: 8px 14px; border-radius: 8px; background: #1e3a8a; color: #fff; font-size: 12px; font-weight: bold; border: none; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.25);">
        Cetak / Download PDF
    </button>
    <div class="form-container">
        @include('final-evaluations.partials.print-body')
    </div>
</body>
</html>