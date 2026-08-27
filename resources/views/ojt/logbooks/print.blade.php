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
            font-size: 7px;
            color: #000;
            line-height: 1.1;
            padding: 6px;
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

        .logo-cell { width: 110px; padding: 2px 3px !important; vertical-align: middle !important; }
        .logo-img  { height: 44px; width: auto; max-width: 105px; display: block; margin: 0 auto; object-fit: contain; }

        .title-main { font-weight: bold; font-size: 7.5px; text-transform: uppercase; }
        .title-sub  { font-weight: bold; font-size: 8.5px; text-transform: uppercase; margin: 1px 0; }
        .title-desc { font-weight: bold; font-size: 7px; }

        table.meta-table { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; font-size: 7px; }
        table.meta-table td { border: 1px solid #000; padding: 1.5px 3px; vertical-align: top; }
        .meta-label { font-weight: bold; width: 88px; }

        .checkbox-box  { font-size: 6.5px; line-height: 1.15; }
        .checkbox-item { display: flex; align-items: center; gap: 2px; margin-bottom: 1px; }
        .checkbox-rect {
            display: inline-block; width: 7.5px; height: 7.5px;
            border: 1px solid #000; text-align: center; line-height: 6.5px;
            font-size: 6px; font-weight: bold; flex-shrink: 0;
        }
        .keterangan-box { font-size: 6.5px; line-height: 1.1; }

        .unit-banner {
            background-color: #e5e7eb; font-weight: bold; font-size: 7px;
            padding: 1.5px 4px; border-bottom: 1px solid #000; border-top: 1px solid #000;
        }

        table.eval-table { width: 100%; border-collapse: collapse; font-size: 6.8px; }
        table.eval-table th, table.eval-table td {
            border: 1px solid #000; padding: 1px 2px; vertical-align: middle;
        }
        table.eval-table th {
            background-color: #f3f4f6; font-weight: bold;
            text-align: center; text-transform: uppercase; font-size: 6.5px;
        }
        .section-header { background-color: #d1d5db; font-weight: bold; font-size: 7px; padding: 1px 3px; text-transform: uppercase; }
        .sub-header     { background-color: #e5e7eb; font-weight: bold; font-size: 6.5px; padding: 1px 3px; }

        .col-no     { width: 20px; text-align: center; font-weight: bold; }
        .col-aspek  { width: 24px; text-align: center; font-weight: bold; }
        .col-item   { text-align: left; }
        .col-kbk    { width: 16px; text-align: center; font-weight: bold; font-size: 8px; }
        .col-catatan{ width: 150px; text-align: left; }

        .statement-box {
            font-size: 6.5px; font-weight: bold; font-style: italic;
            text-align: center; padding: 2px;
            border-top: 1px solid #000; border-bottom: 1px solid #000;
            background-color: #f9fafb;
        }

        table.footer-grid { width: 100%; border-collapse: collapse; border-bottom: 1px solid #000; }
        table.footer-grid td { border: 1px solid #000; padding: 2px 3px; vertical-align: top; }
        .conclusion-title { font-weight: bold; font-size: 7px; margin-bottom: 2px; }

        table.sig-grid { width: 100%; border-collapse: collapse; text-align: center; font-size: 7px; }
        table.sig-grid td { border: 1px solid #000; padding: 2px; vertical-align: bottom; }
        .sig-header { background-color: #f3f4f6; font-weight: bold; text-transform: uppercase; font-size: 7px; padding: 2px !important; }
        .sig-title  { background-color: #f9fafb; font-weight: bold; font-size: 6.5px; padding: 2px !important; }
        .sig-space  { height: 34px; display: flex; align-items: center; justify-content: center; }
        .sig-img    { max-height: 30px; max-width: 85px; object-fit: contain; }
        .sig-name   { font-weight: bold; text-decoration: underline; font-size: 7px; }
        .sig-sid    { font-size: 6.5px; }

        @media print {
            @page { size: A4 portrait; margin: 5mm; }
            html, body { width: 100%; margin: 0; padding: 0; background: #fff; }
            .no-print { display: none !important; }
            .form-container { border: 1px solid #000 !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Cetak / Download PDF
        </button>
    </div>

    @include('ojt.logbooks.partials.print-body')

</body>
</html>


