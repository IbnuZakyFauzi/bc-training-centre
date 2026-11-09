@extends('layouts.app', ['title' => 'Kelola Materi LMS Evaluasi 1'])

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('evaluasi1.trainer.dashboard') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-1 inline-flex items-center">
                ← Kembali ke Workspace Evaluasi 1
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Daftar Materi & Video LMS</h1>
            <p class="text-xs text-gray-500">Materi in-class yang dapat diakses oleh trainee.</p>
        </div>

        <a href="{{ route('evaluasi1.trainer.materials.create') }}" class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow transition">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Materi Baru
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 text-gray-600 uppercase font-semibold border-b">
                    <tr>
                        <th class="px-5 py-3 w-16 text-center">Urutan</th>
                        <th class="px-5 py-3">Judul Materi</th>
                        <th class="px-5 py-3">Tipe</th>
                        <th class="px-5 py-3">Durasi Minimal</th>
                        <th class="px-5 py-3">Dibuat Oleh</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($materials as $m)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-5 py-3.5 text-center font-bold text-gray-700">#{{ $m->order }}</td>
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-gray-900">{{ $m->title }}</div>
                                <div class="text-[11px] text-gray-500 line-clamp-1">{{ $m->description }}</div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $m->type === 'video' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $m->type }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 font-mono text-gray-600">
                                {{ floor($m->min_duration_seconds / 60) }} menit {{ $m->min_duration_seconds % 60 }} dtk
                            </td>
                            <td class="px-5 py-3.5 text-gray-600">
                                {{ $m->creator->name ?? 'Admin TC' }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <form action="{{ route('evaluasi1.trainer.materials.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini? Data progres trainee terkait juga akan terhapus.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-400 text-xs">Belum ada materi pelatihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
