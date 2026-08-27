@props(['status'])

@php
    $classes = match(strtolower($status)) {
        'draft' => 'bg-slate-100 text-slate-700 border-slate-300',
        'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
        'revision' => 'bg-amber-50 text-amber-800 border-amber-300 font-bold',
        'verified' => 'bg-blue-50 text-blue-700 border-blue-300',
        'final_approved' => 'bg-[#1e3a8a] text-white border-[#172554]',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };

    $label = match(strtolower($status)) {
        'draft' => 'Draft',
        'submitted' => 'Submitted / Menunggu',
        'revision' => 'Perlu Revisi',
        'verified' => 'Terverifikasi Trainer',
        'final_approved' => 'Final Approved TC',
        default => ucfirst($status),
    };
@endphp

    <span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {$classes}"]) }}>
        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ strtolower($status) === 'revision' ? 'bg-amber-500 animate-ping' : (in_array(strtolower($status), ['verified', 'final_approved'], true) ? 'bg-blue-400' : 'bg-current') }}"></span>
        {{ $label }}
    </span>

