<?php

namespace App\Console\Commands;

use App\Models\FinalEvaluation;
use App\Models\TraineePhaseHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanEvaluations extends Command
{
    protected $signature = 'eval:clean {--all : Hapus semua data evaluasi final} {--status= : Filter status tertentu, misal hse_approved, submitted, tc_approved, pjo_approved}';
    protected $description = 'Bersihkan data Form Evaluasi Final di database (termasuk orphan atau status selesai)';

    public function handle(): void
    {
        $all = $this->option('all');
        $status = $this->option('status');

        $query = FinalEvaluation::query();

        if (!$all && $status) {
            $query->where('status', $status);
        } elseif (!$all && !$status) {
            // Default: bersihkan evaluasi yang trainee-nya sudah tidak ada di users
            $activeTraineeNames = \App\Models\User::where('role', 'trainee')->pluck('name')->toArray();
            $query->whereNotIn('nama_operator', $activeTraineeNames);
        }

        $count = $query->count();

        if ($count === 0) {
            $this->info("Tidak ada data evaluasi yang cocok untuk dibersihkan.");
            return;
        }

        DB::transaction(function () use ($query) {
            $evalIds = $query->pluck('id')->toArray();
            TraineePhaseHistory::whereIn('evaluation_id', $evalIds)->delete();
            $query->delete();
        });

        $this->info("Berhasil menghapus {$count} data evaluasi final.");
    }
}
