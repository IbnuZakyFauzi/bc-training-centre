<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\CompetencyEvaluation;
use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainerPengawasSeeder extends Seeder
{
    public function run(): void
    {
        $pengawas = User::firstOrCreate(
            ['email' => 'trainer.pengawas@beraucoal.co.id'],
            [
                'sid' => 'BC-30150',
                'name' => 'Rudi Setiawan (Pengawas Trainer)',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'trainer_type' => 'pengawas',
                'department_id' => Department::where('code', 'CHCPP')->value('id'),
                'phone' => '+62 812-9988-7766',
            ]
        );

        $trainer = User::where('role', 'trainer')->where('trainer_type', 'instruktur')->firstOrFail();
        $trainee = User::where('role', 'trainee')->firstOrFail();
        $logbook = OjtLogbook::where('logbook_number', 'LOG-202608-DZ01')->firstOrFail();

        if ($logbook->status === 'submitted') {
            $logbook->update([
                'status' => 'verified',
                'revision_notes' => null,
                'verified_at' => now(),
                'assigned_pjo_id' => $pengawas->id,
            ]);

            CompetencyEvaluation::updateOrCreate(
                ['ojt_logbook_id' => $logbook->id],
                [
                    'trainer_id' => $trainer->id,
                    'overall_score' => 88,
                    'competency_status' => 'competent',
                    'assessment_payload' => [
                        'safety' => 4,
                        'operation' => 4,
                        'procedure' => 3,
                        'communication' => 3,
                        'training_phase' => 'OJT Operational Assessment',
                    ],
                    'trainer_comment' => 'Trainee telah menunjukkan pengoperasian unit yang aman dan konsisten. Direkomendasikan untuk persetujuan Pengawas Trainer.',
                    'revision_instruction' => null,
                    'evaluated_at' => now(),
                    'sent_to_pjo_at' => now(),
                ]
            );

            LogbookHistory::firstOrCreate(
                ['ojt_logbook_id' => $logbook->id, 'action' => 'Logbook Submitted to Trainer'],
                ['user_id' => $trainee->id, 'from_status' => 'draft', 'to_status' => 'submitted', 'comment' => 'Logbook submitted for Trainer verification.']
            );
            LogbookHistory::firstOrCreate(
                ['ojt_logbook_id' => $logbook->id, 'action' => 'Competency Evaluation Verified'],
                ['user_id' => $trainer->id, 'from_status' => 'submitted', 'to_status' => 'verified', 'comment' => 'Competency evaluation completed and sent to Pengawas Trainer approval.']
            );
        }
    }
}
