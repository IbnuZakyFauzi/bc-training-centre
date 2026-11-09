<?php

namespace App\Http\Controllers\Evaluasi1;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi1\Eval1Certificate;
use Illuminate\Support\Facades\Auth;

class CertificateL1Controller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'must.change.password']);
    }

    public function show($id)
    {
        $certificate = Eval1Certificate::with(['user.equipmentCategory', 'attempt.exam'])->findOrFail($id);
        $user = Auth::user();

        // Trainee hanya boleh melihat sertifikatnya sendiri, Trainer/Admin boleh melihat semua
        if ($user->isTrainee() && $certificate->user_id !== $user->id) {
            abort(403, 'Akses sertifikat ditolak.');
        }

        return view('evaluasi-1.certificates.simper-l1-view', compact('certificate'));
    }

    public function print($id)
    {
        $certificate = Eval1Certificate::with(['user.equipmentCategory', 'attempt.exam'])->findOrFail($id);
        $user = Auth::user();

        if ($user->isTrainee() && $certificate->user_id !== $user->id) {
            abort(403, 'Akses cetak sertifikat ditolak.');
        }

        return view('evaluasi-1.certificates.simper-l1-print', compact('certificate'));
    }
}
