<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Formulir OJT - {{ $logbook->logbook_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body { background: #fff; width: 100%; }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 6px;
            color: #000;
            line-height: 1.05;
            padding: 4px;
        }

        .no-print { margin-bottom: 8px; text-align: right; }

        .btn-print {
            background-color: #2563eb; color: white; border: none;
            padding: 6px 14px; font-weight: bold; font-size: 11px;
            border-radius: 5px; cursor: pointer;
        }
        .btn-print:hover { background-color: #008f4c; }

        .form-container { border: 1px solid #000; width: 100%; }

        table.header-table { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; }
        table.header-table td { border: 1px solid #000; padding: 2px 4px; text-align: center; vertical-align: middle; }

        .logo-cell { width: 100px; padding: 1px 2px !important; vertical-align: middle !important; }
        .logo-img  { height: 36px; width: auto; max-width: 95px; display: block; margin: 0 auto; object-fit: contain; }

        .title-main { font-weight: bold; font-size: 7px; text-transform: uppercase; }
        .title-sub  { font-weight: bold; font-size: 8px; text-transform: uppercase; margin: 0.5px 0; }
        .title-desc { font-weight: bold; font-size: 6.5px; }

        table.meta-table { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; font-size: 6px; }
        table.meta-table td { border: 1px solid #000; padding: 1px 2px; vertical-align: top; }
        .meta-label { font-weight: bold; width: 80px; }

        .checkbox-box  { font-size: 6px; line-height: 1.1; }
        .checkbox-item { display: flex; align-items: center; gap: 1.5px; margin-bottom: 0.5px; }
        .checkbox-rect {
            display: inline-block; width: 6.5px; height: 6.5px;
            border: 1px solid #000; text-align: center; line-height: 5.5px;
            font-size: 5.5px; font-weight: bold; flex-shrink: 0;
        }
        .keterangan-box { font-size: 6px; line-height: 1.05; }

        .unit-banner {
            background-color: #e5e7eb; font-weight: bold; font-size: 6px;
            padding: 1px 3px; border-bottom: 1px solid #000; border-top: 1px solid #000;
        }

        table.eval-table { width: 100%; border-collapse: collapse; font-size: 6px; }
        table.eval-table th, table.eval-table td {
            border: 1px solid #000; padding: 0.5px 1.5px; vertical-align: middle;
        }
        table.eval-table th {
            background-color: #f3f4f6; font-weight: bold;
            text-align: center; text-transform: uppercase; font-size: 5.5px;
        }
        .section-header { background-color: #d1d5db; font-weight: bold; font-size: 6px; padding: 0.5px 2px; text-transform: uppercase; }
        .sub-header     { background-color: #e5e7eb; font-weight: bold; font-size: 5.5px; padding: 0.5px 2px; }

        .col-no     { width: 18px; text-align: center; font-weight: bold; }
        .col-aspek  { width: 20px; text-align: center; font-weight: bold; }
        .col-item   { text-align: left; }
        .col-kbk    { width: 14px; text-align: center; font-weight: bold; font-size: 7px; }
        .col-catatan{ width: 130px; text-align: left; }

        .statement-box {
            font-size: 6px; font-weight: bold; font-style: italic;
            text-align: center; padding: 1.5px;
            border-top: 1px solid #000; border-bottom: 1px solid #000;
            background-color: #f9fafb;
        }

        table.footer-grid { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; }
        table.footer-grid td { border: 1px solid #000; padding: 1.5px 2px; vertical-align: top; }
        .conclusion-title { font-weight: bold; font-size: 6px; margin-bottom: 1px; }

        table.sig-grid { width: 100%; border-collapse: collapse; text-align: center; font-size: 6px; }
        table.sig-grid td { border: 1px solid #000; padding: 2px 1px; vertical-align: bottom; }
        .sig-header { background-color: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 6px; padding: 1px !important; }
        .sig-title  { background-color: #f9fafb; font-weight: bold; font-size: 5.5px; padding: 1px !important; }
        .sig-space  { height: 26px; text-align: center; vertical-align: middle; }
        .sig-img    { max-height: 22px; max-width: 75px; height: auto; width: auto; object-fit: contain; display: block; margin: 0 auto; }
        .sig-name   { font-weight: bold; text-decoration: underline; font-size: 6px; margin-top: 1px; }
        .sig-sid    { font-size: 5.5px; margin-top: 0.5px; }

        @media print {
            @page { size: A4 portrait; margin: 4mm; }
            html, body { width: 100%; margin: 0; padding: 0; background: #fff; }
            .no-print { display: none !important; }
            .form-container { border: 1px solid #000 !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="setTimeout(() => window.print(), 150)" class="btn-print">
            🖨️ Cetak / Download PDF
        </button>
    </div>

    @include('ojt.logbooks.partials.print-body')

</body>
</html>


