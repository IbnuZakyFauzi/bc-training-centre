@props(['status'])

@php
    $classes = match(strtolower($status)) {
        'draft' => 'bg-slate-100 text-slate-700 border-slate-300',
        'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
        'revision' => 'bg-amber-50 text-amber-800 border-amber-300 font-bold',
        'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-300',
        'approved', 'supervisor_approved' => 'bg-teal-50 text-teal-800 border-teal-300',
        'final_approved' => 'bg-[#003829] text-white border-[#00241A]',
        default => 'bg-slate-100 text-slate-700 border-slate-200',
    };

    $label = match(strtolower($status)) {
        'draft' => 'Draft',
        'submitted' => 'Submitted / Menunggu',
        'revision' => 'Perlu Revisi',
        'verified' => 'Terverifikasi Trainer',
        'approved', 'supervisor_approved' => 'Disetujui Pengawas',
        'final_approved' => 'Final Approved TC',
        default => ucfirst($status),
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {$classes}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ strtolower($status) === 'revision' ? 'bg-amber-500 animate-ping' : (in_array(strtolower($status), ['approved', 'supervisor_approved', 'final_approved'], true) ? 'bg-emerald-400' : 'bg-current') }}"></span>
    {{ $label }}
</span>
