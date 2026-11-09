@extends('layouts.app', ['title' => 'Rekap Presensi In-Class Evaluasi 1'])

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('evaluasi1.trainer.dashboard') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition mb-1 inline-flex items-center">
                ← Kembali ke Workspace Evaluasi 1
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Rekap Absensi Online In-Class Training</h1>
            <p class="text-xs text-gray-500">Monitoring kehadiran, durasi jam belajar, foto selfie, dan verifikasi presensi trainee Evaluasi 1.</p>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold rounded-xl flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border rounded-2xl p-5 shadow-sm">
            <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Total Kehadiran</div>
            <div class="text-2xl font-black text-gray-900 mt-1">{{ $totalPresent }} <span class="text-xs font-semibold text-gray-500">Sesi Hadir</span></div>
            <div class="text-[11px] text-emerald-600 font-semibold mt-1">Presensi terdata di sistem</div>
        </div>

        <div class="bg-white border rounded-2xl p-5 shadow-sm">
            <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Akumulasi Jam Pelatihan</div>
            <div class="text-2xl font-black text-blue-600 mt-1">{{ number_format($totalHours, 1) }} <span class="text-xs font-semibold text-gray-500">Jam Total</span></div>
            <div class="text-[11px] text-gray-500 mt-1">Seluruh trainee in-class</div>
        </div>

        <div class="bg-white border rounded-2xl p-5 shadow-sm">
            <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Menunggu Verifikasi Instruktur</div>
            <div class="text-2xl font-black {{ $totalUnverified > 0 ? 'text-amber-600' : 'text-gray-900' }} mt-1">
                {{ $totalUnverified }} <span class="text-xs font-semibold text-gray-500">Data</span>
            </div>
            <div class="text-[11px] text-amber-600 font-semibold mt-1">{{ $totalUnverified > 0 ? 'Perlu diverifikasi' : 'Semua telah diverifikasi' }}</div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white border rounded-2xl p-5 shadow-sm">
        <form method="GET" action="{{ route('evaluasi1.trainer.attendances.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-gray-600 uppercase">Pilih Trainee</label>
                <select name="trainee_id" class="w-full border-gray-300 rounded-xl text-xs p-2.5">
                    <option value="">Semua Trainee</option>
                    @foreach($trainees as $t)
                        <option value="{{ $t->id }}" {{ request('trainee_id') == $t->id ? 'selected' : '' }}>
                            {{ $t->name }} ({{ $t->sid ?? 'No SID' }}) - {{ $t->certification ?? 'Green' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-gray-600 uppercase">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full border-gray-300 rounded-xl text-xs p-2.5">
            </div>

            <div class="space-y-1">
                <label class="block text-[11px] font-bold text-gray-600 uppercase">Status Kehadiran</label>
                <select name="status" class="w-full border-gray-300 rounded-xl text-xs p-2.5">
                    <option value="all">Semua Status</option>
                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ request('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                    Filter Data
                </button>
                <a href="{{ route('evaluasi1.trainer.attendances.index') }}" class="px-3 py-2.5 border border-gray-300 text-gray-600 hover:bg-gray-50 text-xs font-bold rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tabel Presensi -->
    <div class="bg-white border rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 border-b text-[11px] font-black text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4">Trainee</th>
                        <th class="py-3.5 px-4">Tanggal & Waktu</th>
                        <th class="py-3.5 px-4">Durasi Jam</th>
                        <th class="py-3.5 px-4">Topik Materi SOP</th>
                        <th class="py-3.5 px-4 text-center">Foto Bukti</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Verifikasi Instruktur</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($attendances as $att)
                        @php
                            $badge = $att->status_badge;
                            $isVerified = !is_null($att->verified_at);
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition">
                            <!-- Trainee -->
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-gray-900">{{ $att->trainee->name ?? 'Trainee' }}</div>
                                <div class="text-[10px] text-gray-500 font-mono">{{ $att->trainee->sid ?? '-' }} | Jalur: <span class="font-bold text-blue-700">{{ $att->trainee->certification ?? 'Green' }}</span></div>
                            </td>

                            <!-- Tanggal & Waktu -->
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</div>
                                <div class="text-[10px] text-gray-500 mt-0.5">
                                    In: <span class="font-mono font-bold text-emerald-700">{{ $att->clock_in ? \Carbon\Carbon::parse($att->clock_in)->timezone('Asia/Makassar')->format('H:i') . ' WITA' : '-' }}</span>
                                    • Out: <span class="font-mono font-bold text-blue-700">{{ $att->clock_out ? \Carbon\Carbon::parse($att->clock_out)->timezone('Asia/Makassar')->format('H:i') . ' WITA' : '-' }}</span>
                                </div>
                            </td>

                            <!-- Durasi Jam -->
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-800 font-bold font-mono">
                                    {{ number_format($att->total_hours, 1) }} Jam
                                </span>
                            </td>

                            <!-- Topik Materi -->
                            <td class="py-3.5 px-4">
                                <div class="max-w-xs font-semibold text-gray-800 leading-snug">{{ $att->topic }}</div>
                                @if($att->notes)
                                    <div class="text-[10px] text-gray-500 mt-0.5 italic">"{{ $att->notes }}"</div>
                                @endif
                            </td>

                            <!-- Foto Bukti -->
                            <td class="py-3.5 px-4 text-center">
                                @if($att->selfie_path)
                                    <button type="button" onclick="openPhotoModal('{{ asset('storage/' . $att->selfie_path) }}', '{{ $att->trainee->name }} - {{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}')" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-[10px] font-bold transition">
                                        <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Lihat Foto
                                    </button>
                                @else
                                    <span class="text-[10px] text-gray-400 italic">Tanpa Foto</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-full border {{ $badge['bg'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </td>

                            <!-- Verifikasi -->
                            <td class="py-3.5 px-4">
                                @if($isVerified)
                                    <div class="flex items-center gap-1.5 text-emerald-700 font-bold">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        <span>Diverifikasi</span>
                                    </div>
                                    <div class="text-[10px] text-gray-500 font-medium">Oleh: {{ $att->verifier->name ?? 'Instruktur' }}</div>
                                @else
                                    <span class="text-amber-700 font-bold text-[10px] bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                                        Belum Diverifikasi
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right">
                                @if(! $isVerified)
                                    <form method="POST" action="{{ route('evaluasi1.trainer.attendances.verify', $att->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Verifikasi kehadiran trainee ini?')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] rounded-lg shadow-sm transition">
                                            Verifikasi ✓
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-[10px]">Tervalidasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-sm font-semibold text-gray-600">Belum ada riwayat absensi online ditemukan.</p>
                                <p class="text-xs text-gray-400 mt-0.5">Data presensi akan otomatis muncul saat trainee melakukan Clock In di LMS.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendances->hasPages())
            <div class="px-4 py-3 border-t bg-gray-50">
                {{ $attendances->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Foto Kehadiran -->
<div id="photoModal" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl space-y-4 p-5">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 id="photoModalTitle" class="text-sm font-bold text-gray-900">Bukti Foto Kehadiran</h3>
            <button type="button" onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-700">✕</button>
        </div>
        <div class="rounded-xl overflow-hidden bg-black flex items-center justify-center">
            <img id="photoModalImg" src="" alt="Bukti Foto" class="max-h-96 w-auto object-contain">
        </div>
        <div class="text-right pt-2">
            <button type="button" onclick="closePhotoModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs rounded-xl">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
function openPhotoModal(url, title) {
    document.getElementById('photoModalImg').src = url;
    document.getElementById('photoModalTitle').innerText = title;
    document.getElementById('photoModal').classList.remove('hidden');
}
function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
    document.getElementById('photoModalImg').src = '';
}
</script>
@endsection
