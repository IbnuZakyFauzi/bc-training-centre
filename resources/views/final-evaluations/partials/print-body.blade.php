    <div class="evaluation-form">
    <div class="evaluation-print-wrapper">
        <style>
            .evaluation-form table { border-collapse: collapse; width: 100%; }
            .evaluation-form td, .evaluation-form th { border: 1px solid #000; padding: 1.5px 2px; vertical-align: middle; }
            .evaluation-form .header-top td { padding: 1.5px 2px; vertical-align: middle; }
            .evaluation-form .logo-cell { width: 75px; text-align: center; }
            .evaluation-form .logo-img { height: 30px; width: auto; max-width: 70px; }
            .evaluation-form .title-cell { text-align: center; }
            .evaluation-form .title-main { font-weight: bold; font-size: 9px; text-transform: uppercase; }
            .evaluation-form .title-sub { font-weight: bold; font-size: 10px; text-transform: uppercase; margin: 0.5px 0; }
            .evaluation-form .info-table td { padding: 1.5px 2px; font-size: 7px; }
            .evaluation-form .penilaian-section td { padding: 2px 3px; font-size: 7px; vertical-align: top; border: 1px solid #000; }
            .evaluation-form .penilaian-section .section-title { font-weight: bold; text-align: center; margin-bottom: 1px; font-size: 7px; }
            .evaluation-form .penilaian-section .check-item { display: flex; align-items: center; gap: 2px; margin: 0.5px 0; font-size: 7px; }
            .evaluation-form .eval-table { font-size: 6.5px; }
            .evaluation-form .eval-table th, .evaluation-form .eval-table td { padding: 1.5px 2px; }
            .evaluation-form .eval-table th { font-weight: bold; text-align: center; background: #e5e7eb; }
            .evaluation-form .col-no { width: 20px; text-align: center; font-weight: bold; }
            .evaluation-form .col-aspek { text-align: left; }
            .evaluation-form .col-kbk { width: 26px; text-align: center; font-weight: bold; }
            .evaluation-form .conclusion-box {
                font-size: 7px; font-weight: bold;
                text-align: center; padding: 1.5px;
                border-top: 1px solid #000; border-bottom: 1px solid #000;
                background: #f9fafb;
            }
            .evaluation-form table.sig-grid { table-layout: fixed; }
            .evaluation-form table.sig-grid td { width: 25%; padding: 2px; vertical-align: bottom; text-align: center; font-size: 6.5px; }
            .evaluation-form .footer-table td { padding: 1.5px 3px; font-size: 7px; }

            @media print {
                .evaluation-print-wrapper { margin: 0 !important; }
            }
        </style>
    </div>
    @php
        $trainee = $evaluation->logbook->trainee ?? null;
        $equipmentCategory = $evaluation->logbook->equipmentCategory ?? null;

        $namaOperator = $evaluation->nama_operator ?: ($trainee->name ?? '');
        $perusahaan = $evaluation->perusahaan ?: ($trainee->company ?? '');
        $jenisSertifikasi = $evaluation->jenis_sertifikasi ?: ($trainee->certification ?? '');
        $jenisUnitA2B = $evaluation->jenis_unit_a2b ?: ($equipmentCategory->name ?? '');
    @endphp

     <table class="header-top">
         <tr>
             <td class="logo-cell">
                 <img src="{{ asset('images/berau_coal_logo.png') }}" alt="Berau Coal Logo" class="logo-img">
             </td>
             <td class="title-cell">
                 <div class="title-main">BERAU COAL GREEN MINING SYSTEM</div>
                 <div class="title-sub">FORMULIR</div>
                 <div class="title-sub">Evaluasi On the Job Training A2B</div>
             </td>
             <td style="width: 100px;"></td>
         </tr>
     </table>

     <table class="info-table" style="width: 100%; border-collapse: collapse;">
         <tr>
             <td style="width: 50%; border: 1px solid #000; padding: 1.5px 3px; vertical-align: top;">
                 <table style="width: 100%; border-collapse: collapse;">
                     <tr>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px; width: 90px;"><strong>Nama Operator</strong></td>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;">: {{ $namaOperator }}</td>
                     </tr>
                     <tr>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;"><strong>Lokasi Kerja</strong></td>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;">: {{ $evaluation->lokasi_kerja }}</td>
                     </tr>
                     <tr>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;"><strong>Jenis Sertifikasi</strong></td>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;">: {{ $jenisSertifikasi }}</td>
                     </tr>
                     <tr>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;"><strong>Instruktur</strong></td>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;">: {{ $evaluation->instruktur }}</td>
                     </tr>
                     <tr>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;"><strong>Operator Pendamping</strong></td>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;">: {{ $evaluation->operator_pendamping }}</td>
                     </tr>
                     <tr>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;"><strong>Tanggal Penilaian</strong></td>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;">: {{ \Carbon\Carbon::parse($evaluation->tanggal_penilaian)->format('d/m/Y') }}</td>
                     </tr>
                 </table>
             </td>
             <td style="width: 50%; border: 1px solid #000; padding: 1.5px 3px; vertical-align: top;">
                 <table style="width: 100%; border-collapse: collapse; height: 100%;">
                     <tr>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px; width: 85px;"><strong>Perusahaan</strong></td>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px;">: {{ $perusahaan }}</td>
                     </tr>
                     <tr>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px; vertical-align: top;"><strong>Jenis Unit A2B</strong></td>
                         <td style="border: none; padding: 0.5px 0; font-size: 7px; vertical-align: top;">: {{ $jenisUnitA2B }}</td>
                     </tr>
                 </table>
             </td>
         </tr>
     </table>

     <table class="penilaian-section" style="width: 100%; border-collapse: collapse; border: 1px solid #000; margin-top: 0px;">
         <tr>
             <td style="width: 33%; border-right: 1px solid #000; padding: 2px 3px; vertical-align: top;">
                 <div class="section-title">Penilaian Pasca Pelatihan (Beri tanda "√" untuk yang sesuai)</div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! $evaluation->tahap_penilaian === 'pendampingan' ? '☑' : '☐' !!}</span> Tahap Pendampingan
                 </div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! $evaluation->tahap_penilaian === 'tanpa_pendampingan' ? '☑' : '☐' !!}</span> Tahap Tanpa Pendampingan
                 </div>
             </td>
             <td style="width: 33%; border-right: 1px solid #000; padding: 2px 3px; vertical-align: top;">
                 <div class="section-title">Tahap Tanpa Pendampingan Lanjutan</div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! $evaluation->sub_tahap === 'bulanan' ? '☑' : '☐' !!}</span> Bulanan
                 </div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! $evaluation->sub_tahap === '3_bulan_pertama' ? '☑' : '☐' !!}</span> 3 Bulan pertama
                 </div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! $evaluation->sub_tahap === '3_bulan_kedua' ? '☑' : '☐' !!}</span> 3 Bulan kedua
                 </div>
             </td>
             <td style="width: 34%; padding: 2px 3px; vertical-align: top;">
                 <div class="section-title">Keterangan</div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! ($evaluation->sub_tahap_keterangan ?? '') === 'bulan_1' ? '☑' : '☐' !!}</span> Bulan ke-1
                 </div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! ($evaluation->sub_tahap_keterangan ?? '') === 'bulan_2' ? '☑' : '☐' !!}</span> Bulan ke-2
                 </div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! ($evaluation->sub_tahap_keterangan ?? '') === 'bulan_3' ? '☑' : '☐' !!}</span> Bulan ke-3
                 </div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! ($evaluation->sub_tahap_keterangan ?? '') === 'bulan_4' ? '☑' : '☐' !!}</span> Bulan ke-4
                 </div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! ($evaluation->sub_tahap_keterangan ?? '') === 'bulan_5' ? '☑' : '☐' !!}</span> Bulan ke-5
                 </div>
                 <div class="check-item">
                     <span class="checkbox-symbol">{!! ($evaluation->sub_tahap_keterangan ?? '') === 'bulan_6' ? '☑' : '☐' !!}</span> Bulan ke-6
                 </div>
             </td>
         </tr>
     </table>

     <table class="eval-table" style="margin-top: 0px;">
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
                     <span style="font-size: 6px;">Peserta Melakukan P2H setiap hari secara teratur dan lengkap sesuai dengan standar.</span>
                 </td>
                 <td class="col-kbk">{!! $evaluation->p2h_status === 'K' ? '✓' : '' !!}</td>
                 <td class="col-kbk">{!! $evaluation->p2h_status === 'BK' ? '✓' : '' !!}</td>
             </tr>
             <tr>
                 <td class="col-no">2</td>
                 <td class="col-aspek">
                     <strong>Teknik Pengoperasian Unit</strong><br>
                     <span style="font-size: 6px;">Peserta mengoperasikan unit dengan benar dan tidak menimbulkan kerusakan baik minor maupun major serta mencapai target kerja yang diharapkan.</span>
                 </td>
                 <td class="col-kbk">{!! $evaluation->teknik_pengoperasian_status === 'K' ? '✓' : '' !!}</td>
                 <td class="col-kbk">{!! $evaluation->teknik_pengoperasian_status === 'BK' ? '✓' : '' !!}</td>
             </tr>
             <tr>
                 <td class="col-no">3</td>
                 <td class="col-aspek">
                     <strong>Kepatuhan Terhadap Peraturan Kerja</strong><br>
                     <span style="font-size: 6px;">Peserta mematuhi peraturan kerja yang berlaku selama mengoperasikan unit termasuk mematuhi rambu-rambu.</span>
                 </td>
                 <td class="col-kbk">{!! $evaluation->kepatuhan_status === 'K' ? '✓' : '' !!}</td>
                 <td class="col-kbk">{!! $evaluation->kepatuhan_status === 'BK' ? '✓' : '' !!}</td>
             </tr>
             <tr>
                 <td class="col-no">4</td>
                 <td class="col-aspek">
                     <strong>Kedisiplinan dan Komunikasi</strong><br>
                     <span style="font-size: 6px;">Peserta bersikap disiplin terhadap aturan-aturan, tidak melakukan pelanggaran, kehadiran tepat waktu, dan dapat bekerjasama dengan pengawas dan rekan kerja sesama operator.</span>
                 </td>
                 <td class="col-kbk">{!! $evaluation->kedisiplinan_status === 'K' ? '✓' : '' !!}</td>
                 <td class="col-kbk">{!! $evaluation->kedisiplinan_status === 'BK' ? '✓' : '' !!}</td>
             </tr>
         </tbody>
     </table>

     <table style="width: 100%; border-collapse: collapse; margin-top: 1px;">
         <tr>
             <td style="border: 1px solid #000; padding: 1.5px 3px; font-size: 7px; line-height: 1.2;">
                 <strong>Keterangan:</strong><br>
                 K&nbsp;&nbsp;&nbsp;: Kompeten<br>
                 BK : Belum Kompeten
             </td>
         </tr>
     </table>

     <div class="conclusion-box" style="text-align: left; font-weight: normal; padding: 2px 4px; border-bottom: 1px solid transparent; background: #fff; margin-top: 1px;">
         Berdasarkan rangkuman penilaian di atas, kinerja operator ini dinyatakan:
         <div style="font-size: 9px; margin-top: 1px; text-align: center; font-weight: bold;">
             {{ strtoupper(str_replace('_', ' ', $evaluation->kesimpulan)) }}
         </div>
     </div>

     <table style="width: 100%; border-collapse: collapse; margin-top: 1px; border: 1px solid #000;">
         <tr>
             <td style="border: 1px solid #000; padding: 2px 4px; font-size: 7px;">
                 <strong>Rekomendasi:</strong>
                 @if($evaluation->catatan)
                     <div style="margin-top: 1px; font-weight: normal;">{{ $evaluation->catatan }}</div>
                 @endif
             </td>
         </tr>
     </table>

     <table style="width: 100%; border-collapse: collapse; margin-top: 1px; border: 1px solid #000;">
         <tr>
             <td style="border: 1px solid #000; padding: 1.5px 4px; font-size: 6.5px; font-style: italic;">
                 Catatan: Lampirkan bukti dari pelaksanaan P2H, Teknik Pengoprasian, Kepatuhan Terhadap Peraturan Kerja, Kedisiplinan dan Komunikasi
             </td>
         </tr>
     </table>

     <table class="sig-grid" style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-top: 1px;">
         @php
             $evaluatorTitle = 'Instruktur/ Pengawas/ Operator Pendamping';
             if ($evaluation->trainer) {
                 $evaluatorTitle = match($evaluation->trainer->trainer_type) {
                     'pengawas' => 'Pengawas',
                     'operator_pendamping' => 'Operator Pendamping',
                     default => 'Instruktur',
                 };
             }
         @endphp
         <tr>
             <td class="sig-title" style="width: 25%; border: 1px solid #000; vertical-align: top; padding: 2px; font-size: 6.5px;">
                 <div style="font-weight: bold; text-align: center; border-bottom: 1px solid #000; padding-bottom: 0.5px; margin-bottom: 0.5px;">Dievaluasi oleh</div>
                 <div class="sig-space">
                     @if($evaluation->instruktur_signature_path)
                         <img src="{{ asset('storage/'.$evaluation->instruktur_signature_path) }}" alt="TTD" class="sig-img">
                     @endif
                 </div>
                 <div style="text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 0.5px;">
                     {{ $evaluation->trainer->name ?? '...............................' }}
                 </div>
                 <div style="text-align: center; font-size: 6.5px; border-top: 1px solid #000; padding-top: 0.5px; margin-top: 0.5px; font-weight: bold;">
                     {{ $evaluatorTitle }}
                 </div>
             </td>
             <td class="sig-title" style="width: 25%; border: 1px solid #000; vertical-align: top; padding: 2px; font-size: 6.5px;">
                 <div style="font-weight: bold; text-align: center; border-bottom: 1px solid #000; padding-bottom: 0.5px; margin-bottom: 0.5px;">Diperiksa oleh</div>
                 <div class="sig-space">
                     @if($evaluation->kabag_signature_path)
                         <img src="{{ asset('storage/'.$evaluation->kabag_signature_path) }}" alt="TTD" class="sig-img">
                     @endif
                 </div>
                 <div style="text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 0.5px;">
                     {{ $evaluation->tcApprover->name ?? '...............................' }}
                 </div>
                 <div style="text-align: center; font-size: 6.5px; border-top: 1px solid #000; padding-top: 0.5px; margin-top: 0.5px; font-weight: bold;">
                     Kabag OTD/LC Mitra<br>Kerja
                 </div>
             </td>
             <td class="sig-title" style="width: 25%; border: 1px solid #000; vertical-align: top; padding: 2px; font-size: 6.5px;">
                 <div style="font-weight: bold; text-align: center; border-bottom: 1px solid #000; padding-bottom: 0.5px; margin-bottom: 0.5px;">Disetujui oleh</div>
                 <div class="sig-space">
                     @if($evaluation->penanggung_jawab_signature_path)
                         <img src="{{ asset('storage/'.$evaluation->penanggung_jawab_signature_path) }}" alt="TTD" class="sig-img">
                     @endif
                 </div>
                 <div style="text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 0.5px;">
                     {{ $evaluation->pjoApprover->name ?? '...............................' }}
                 </div>
                 <div style="text-align: center; font-size: 6.5px; border-top: 1px solid #000; padding-top: 0.5px; margin-top: 0.5px; font-weight: bold;">
                     Penanggung Jawab<br>Operasional
                 </div>
             </td>
             <td class="sig-title" style="width: 25%; border: 1px solid #000; vertical-align: top; padding: 2px; font-size: 6.5px;">
                 <div style="font-weight: bold; text-align: center; border-bottom: 1px solid #000; padding-bottom: 0.5px; margin-bottom: 0.5px;">Diverifikasi oleh</div>
                 <div class="sig-space">
                     @if($evaluation->hse_signature_path)
                         <img src="{{ asset('storage/'.$evaluation->hse_signature_path) }}" alt="TTD" class="sig-img">
                     @endif
                 </div>
                 <div style="text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 0.5px;">
                     {{ $evaluation->hseApprover->name ?? '...............................' }}
                 </div>
                 <div style="text-align: center; font-size: 6.5px; border-top: 1px solid #000; padding-top: 0.5px; margin-top: 0.5px; font-weight: bold;">
                     HSE Training Section PT.<br>BC
                 </div>
             </td>
         </tr>
     </table>

     <table class="footer-table" style="width: 100%; border-collapse: collapse; margin-top: 2px;">
         <tr>
             <td style="width: 50%; border: 1px solid #000; padding: 1.5px 3px; font-size: 7px; text-align: left;">
                 <strong>F-HCT-02.02</strong><br>
                 <strong>Revisi :</strong> 2
             </td>
             <td style="width: 50%; border: 1px solid #000; padding: 1.5px 3px; font-size: 7px; text-align: right;">
                 <strong>Tanggal Efektif :</strong> 17 April 2023<br>
                 <strong>Halaman:</strong> 1
             </td>
         </tr>
     </table>

    </div>
</div>

@php
    unset($evaluation);
@endphp
</div>
