<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\LogbookHistory;
use App\Models\OjtLogbook;
use App\Models\User;
use Illuminate\Database\Seeder;

class TrainerReviewSampleSeeder extends Seeder
{
    public function run(): void
    {
        $trainee = User::where('role', 'trainee')->firstOrFail();
        $trainer = User::where('role', 'trainer')->firstOrFail();
        $supervisor = User::where('role', 'supervisor')->first();
        $category = EquipmentCategory::where('code', 'DZ')->firstOrFail();
        $equipment = Equipment::where('equipment_category_id', $category->id)->firstOrFail();
        $department = Department::where('code', 'MIN-OPS')->first() ?? $trainee->department;

        $groups = [
            ['Teknik Pengoperasian', 'Dozing & digging untuk Bulldozer', [['1.1','Skl','Cara memposisikan blade pada saat mendorong / grading'],['1.2','Kwn','Penggunaan tilt blade'],['1.3','Skl','Cara pengoperasian blade untuk mendorong / ditching'],['1.4','Skl','Cara pengoperasian blade untuk menggali / sloping'],['1.5','Skl','Penyesuaian beban dengan RPM / posisi transmisi'],['1.6','Skl','Teknik dozing / grading / digging']]],
            ['Spreading & Leveling', 'Pengoperasian untuk meratakan dan membentuk area kerja', [['2.1','Kwn','Penggunaan speed / transmisi saat bergerak'],['2.2','Kwn','Cara leveling menggunakan tilt'],['2.3','Skl','Cara menghampar material untuk membuat jalan / menimbun lubang'],['2.4','Skl','Filling pada saat melewatkan area kerja'],['2.5','Skl','Penggunaan steering'],['2.6','Skl','Penggunaan articulated (khusus unit GR)'],['2.7','Skl','Teknik spreading / leveling']]],
            ['Ripping', 'Khusus pekerjaan ripping dan pembukaan material keras', [['3.1','Skl','Cara memposisikan ripper'],['3.2','Kwn','Teknik penetrasi ripping'],['3.3','Skl','Penyesuaian posisi ripper dengan kekerasan material']]],
            ['Finishing', 'Finishing grading dan koreksi permukaan kerja', [['4.1','Skl','Kesesuaian penggunaan speed'],['4.2','Skl','Hasil akurasi / kualitas pekerjaan']]],
        ];

        $payloadGroups = collect($groups)->map(fn ($group) => [
            'title' => $group[0], 'subtitle' => $group[1],
            'items' => collect($group[2])->map(fn ($item) => ['code' => $item[0], 'kind' => $item[1], 'label' => $item[2], 'status' => 'K', 'note' => 'Dilaksanakan sesuai instruksi dan kondisi area kerja.'])->all(),
        ])->all();

        $logbook = OjtLogbook::updateOrCreate(
            ['logbook_number' => 'LOG-202608-DZ01'],
            [
                'trainee_id' => $trainee->id, 'trainer_id' => $trainer->id, 'supervisor_id' => $supervisor?->id,
                'department_id' => $department?->id, 'equipment_category_id' => $category->id, 'equipment_id' => $equipment->id,
                'date' => now()->toDateString(), 'shift' => 'day', 'location' => 'Disposal Area Block 4 North',
                'start_time' => '07:00', 'finish_time' => '17:00', 'hm_start' => 3112.5, 'hm_end' => 3120.0, 'total_hm' => 7.5,
                'daily_activity' => "P2H unit DZ dilakukan sebelum operasi.\nMelaksanakan dozing, spreading overburden, dan perapihan safety crest di Disposal Block 4 North.\nMelakukan ripping pada material keras sesuai arahan supervisor serta housekeeping akhir shift.",
                'sop_payload' => ['meta' => ['company' => 'PT BERAU COAL / PT MTL', 'category_code' => 'DZ', 'unit_family' => 'track', 'certification' => 'Green', 'assessment_mode' => 'pendampingan', 'assessment_stage' => 'bulanan'], 'track' => ['groups' => $payloadGroups]],
                'status' => 'submitted', 'revision_notes' => null, 'submitted_at' => now(), 'verified_at' => null, 'approved_at' => null,
            ]
        );

        LogbookHistory::firstOrCreate(
            ['ojt_logbook_id' => $logbook->id, 'action' => 'Submitted to Trainer'],
            ['user_id' => $trainee->id, 'from_status' => 'draft', 'to_status' => 'submitted', 'comment' => 'Contoh logbook Dozer dikirim untuk review Trainer.']
        );
    }
}
