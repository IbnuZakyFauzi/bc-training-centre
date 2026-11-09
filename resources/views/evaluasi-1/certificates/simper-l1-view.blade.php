@extends('layouts.app', ['title' => 'Sertifikat Kompetensi SIMPER L1'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ url()->previous() }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition inline-flex items-center">
            ← Kembali
        </a>
        <a href="{{ route('evaluasi1.certificate.print', $certificate->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Format Sertifikat PDF
        </a>
    </div>

    <!-- Certificate Mockup Card -->
    <div class="bg-white border-4 border-amber-500/80 rounded-3xl p-8 sm:p-12 shadow-2xl relative overflow-hidden text-center space-y-8">
        <!-- Watermark / Background Ornament -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-blue-900/5 rounded-full blur-2xl pointer-events-none"></div>

        <!-- Header -->
        <div class="flex items-center justify-between border-b pb-6 border-gray-200">
            <img src="{{ asset('images/mtl-logo.png') }}" alt="Logo MTL" class="h-12 w-auto object-contain">
            <div class="text-center">
                <h2 class="text-sm font-extrabold uppercase tracking-widest text-gray-500">PT BERAU COAL / PT MITRA TUAH LESTARI</h2>
                <h3 class="text-xs font-bold text-gray-400">OPERATIONAL TRAINING & COMPETENCY CENTRE</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center font-bold text-xs text-amber-700 font-mono">
                L1
            </div>
        </div>

        <!-- Title -->
        <div class="space-y-2">
            <div class="text-xs font-extrabold tracking-widest text-amber-600 uppercase">SURAT KETERANGAN KOMPETENSI IN-CLASS</div>
            <h1 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight uppercase">SERTIFIKAT KELAYAKAN SIMPER L1</h1>
            <p class="text-xs font-mono text-gray-500">Nomor Registrasi: {{ $certificate->certificate_number }}</p>
        </div>

        <!-- Recipient -->
        <div class="space-y-1">
            <p class="text-xs text-gray-500 uppercase tracking-widest">Diberikan kepada peserta:</p>
            <div class="text-2xl font-black text-brand-900 underline decoration-amber-400 decoration-2 underline-offset-8">
                {{ $certificate->user->name }}
            </div>
            <p class="text-xs font-mono text-gray-600 mt-2">
                SID / NRP: <span class="font-bold text-gray-900">{{ $certificate->user->sid ?? '-' }}</span> | Perusahaan: <span class="font-bold text-gray-900">{{ $certificate->user->company ?? 'PT BERAU COAL / MITRA' }}</span>
            </p>
        </div>

        <!-- Statement -->
        <div class="max-w-xl mx-auto text-xs text-gray-600 leading-relaxed bg-amber-50/50 border border-amber-200/50 p-4 rounded-2xl">
            Telah menyelesaikan seluruh modul pembelajaran In-Class LMS dan dinyatakan <strong>LULUS & KOMPETEN</strong> pada Ujian Evaluasi 1 berbasis Computer Based Test (CBT) dengan pengawasan Camera Proctoring.
        </div>

        <!-- Grid Attributes -->
        <div class="grid grid-cols-3 gap-4 max-w-lg mx-auto py-2 text-center border-y border-gray-100">
            <div>
                <div class="text-[10px] text-gray-400 font-semibold uppercase">Nilai CBT</div>
                <div class="text-base font-black text-emerald-600">{{ $certificate->score }}</div>
            </div>
            <div>
                <div class="text-[10px] text-gray-400 font-semibold uppercase">Tanggal Terbit</div>
                <div class="text-xs font-bold text-gray-800">{{ $certificate->issue_date->translatedFormat('d M Y') }}</div>
            </div>
            <div>
                <div class="text-[10px] text-gray-400 font-semibold uppercase">Masa Berlaku</div>
                <div class="text-xs font-bold text-gray-800">{{ $certificate->valid_until ? $certificate->valid_until->translatedFormat('d M Y') : '6 Bulan' }}</div>
            </div>
        </div>

        <!-- Footer Signatures -->
        <div class="pt-6 grid grid-cols-2 gap-8 text-center text-xs">
            <div>
                <div class="text-gray-400 text-[10px] uppercase font-semibold">Trainee Peserta</div>
                <div class="h-14 flex items-center justify-center font-script text-gray-700 italic">Terverifikasi Digital</div>
                <div class="font-bold text-gray-900">{{ $certificate->user->name }}</div>
            </div>
            <div>
                <div class="text-gray-400 text-[10px] uppercase font-semibold">Admin Training Centre</div>
                <div class="h-14 flex items-center justify-center font-script text-blue-900 font-bold">TC APPROVED</div>
                <div class="font-bold text-gray-900">Head of Training Centre</div>
            </div>
        </div>
    </div>
</div>
@endsection
