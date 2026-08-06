<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\CompetencyEvaluation;
use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DepartmentOperationSeeder extends Seeder
{
    public function run(): void
    {
        $operator = User::firstOrCreate(
            ['email' => 'department.operation@beraucoal.co.id'],
            [
                'nrp' => 'BC-10208',
                'name' => 'Dewi Puspitasari (Pengawas Operasional)',
                'password' => Hash::make('password'),
                'role' => 'department_ops',
                'department_id' => Department::where('code', 'MIN-OPS')->value('id'),
                'phone' => '+62 812-2233-4400',
            ]
        );

        // One sample follows the actual approval chain: Trainer → Pengawas → Training Centre.
        OjtLogbook::whereIn('logbook_number', ['LOG-202608-PJO01', 'LOG-202608-TC01'])->delete();
        $logbook = OjtLogbook::where('logbook_number', 'LOG-202608-DZ01')->firstOrFail();
        $trainer = User::where('role', 'trainer')->firstOrFail();
        $trainee = User::where('role', 'trainee')->firstOrFail();
        $logbook->update(['status' => 'verified', 'revision_notes' => null, 'pjo_id' => null, 'pjo_notes' => null, 'pjo_decided_at' => null, 'training_centre_id' => null, 'training_centre_notes' => null, 'training_centre_decided_at' => null, 'verified_at' => now()]);

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
                'trainer_comment' => 'Trainee telah menunjukkan pengoperasian unit yang aman dan konsisten. Direkomendasikan untuk persetujuan Pengawas.',
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
            ['user_id' => $trainer->id, 'from_status' => 'submitted', 'to_status' => 'verified', 'comment' => 'Competency evaluation completed and sent to Supervisor approval.']
        );
    }
}
