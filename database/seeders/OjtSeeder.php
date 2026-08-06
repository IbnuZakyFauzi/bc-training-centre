<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\EquipmentCategory;
use App\Models\Equipment;
use App\Models\User;
use App\Models\OjtLogbook;
use App\Models\LogbookEvidence;
use App\Models\LogbookHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OjtSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Departments
        $deptMining = Department::create([
            'code' => 'MIN-OPS',
            'name' => 'Mining Operations',
            'description' => 'Mine Operation & Production Division',
        ]);
        
        $deptPlant = Department::create([
            'code' => 'PLT-MAINT',
            'name' => 'Plant & Asset Maintenance',
            'description' => 'Heavy Equipment Maintenance Division',
        ]);

        $deptShe = Department::create([
            'code' => 'SHE-TC',
            'name' => 'Safety, Health & Environment Training',
            'description' => 'Mining Competency & Training Division',
        ]);

        // 2. Equipment Categories
        $catExcavator = EquipmentCategory::create([
            'code' => 'EXC',
            'name' => 'Heavy Excavator',
            'description' => 'Hydraulic Excavator Loading Units',
        ]);

        $catHaulTruck = EquipmentCategory::create([
            'code' => 'HT',
            'name' => 'Haul Truck',
            'description' => 'Off-Highway Dump Trucks',
        ]);

        $catDozer = EquipmentCategory::create([
            'code' => 'DZ',
            'name' => 'Track Dozer',
            'description' => 'Crawler Bulldozers',
        ]);

        $catGrader = EquipmentCategory::create([
            'code' => 'MG',
            'name' => 'Motor Grader',
            'description' => 'Haul Road Maintenance Graders',
        ]);

        // 3. Equipment Units
        $eqEx2001 = Equipment::create([
            'equipment_category_id' => $catExcavator->id,
            'unit_code' => 'EX-2001',
            'model_name' => 'Komatsu PC2000-8',
            'serial_number' => 'KMTPC2000-202401',
            'status' => 'active',
        ]);

        $eqHt7042 = Equipment::create([
            'equipment_category_id' => $catHaulTruck->id,
            'unit_code' => 'HT-7042',
            'model_name' => 'Caterpillar 777F',
            'serial_number' => 'CAT0777F-994812',
            'status' => 'active',
        ]);

        $eqDz3015 = Equipment::create([
            'equipment_category_id' => $catDozer->id,
            'unit_code' => 'DZ-3015',
            'model_name' => 'Caterpillar D10T2',
            'serial_number' => 'CAT0D10T-102934',
            'status' => 'active',
        ]);

        $eqMg1002 = Equipment::create([
            'equipment_category_id' => $catGrader->id,
            'unit_code' => 'MG-1002',
            'model_name' => 'Komatsu GD825A-2',
            'serial_number' => 'KMTGD825-554129',
            'status' => 'active',
        ]);

        // 4. Users (Trainee, Trainer, Supervisor)
        $trainee = User::create([
            'nrp' => 'BC-60491',
            'name' => 'Ahmad Rian Syahputra',
            'email' => 'trainee@beraucoal.co.id',
            'password' => Hash::make('password'),
            'role' => 'trainee',
            'department_id' => $deptMining->id,
            'phone' => '+62 812-5543-9901',
        ]);

        $trainer = User::create([
            'nrp' => 'BC-30112',
            'name' => 'Bambang Hermawan (Senior Instructor)',
            'email' => 'trainer@beraucoal.co.id',
            'password' => Hash::make('password'),
            'role' => 'trainer',
            'department_id' => $deptShe->id,
            'phone' => '+62 811-9876-1234',
        ]);

        $supervisor = User::create([
            'nrp' => 'BC-20054',
            'name' => 'Rahmat Hidayat (Pit Superintendent)',
            'email' => 'supervisor@beraucoal.co.id',
            'password' => Hash::make('password'),
            'role' => 'supervisor',
            'department_id' => $deptMining->id,
            'phone' => '+62 813-1122-3344',
        ]);

        // 5. OJT Logbooks (Sample entries covering Draft, Submitted, Revision, Verified, Supervisor Approved)
        
        // Entry 1: Supervisor Approved
        $log1 = OjtLogbook::create([
            'logbook_number' => 'LOG-202607-0001',
            'trainee_id' => $trainee->id,
            'trainer_id' => $trainer->id,
            'supervisor_id' => $supervisor->id,
            'department_id' => $deptMining->id,
            'equipment_category_id' => $catExcavator->id,
            'equipment_id' => $eqEx2001->id,
            'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
            'shift' => 'day',
            'location' => 'Pit H1 East - Bench 45',
            'start_time' => '07:00',
            'finish_time' => '17:00',
            'hm_start' => 4520.5,
            'hm_end' => 4529.0,
            'total_hm' => 8.5,
            'daily_activity' => "1. Pre-Operational Check & P2H Inspection on Komatsu PC2000-8 unit.\n2. Inspection of hydraulic oil levels, bucket teeth wear, and track tension.\n3. Digging and loading overburden into HT-7042 haul trucks (35 passes).\n4. Maintaining clean loading pit layout and bench face angle safety.\n5. End of shift housekeeping and refueling log registration.",
            'status' => 'supervisor_approved',
            'submitted_at' => Carbon::now()->subDays(5)->addHours(10),
            'verified_at' => Carbon::now()->subDays(4)->addHours(2),
            'approved_at' => null,
            'pjo_decided_at' => Carbon::now()->subDays(3)->addHours(4),
        ]);

        LogbookEvidence::create([
            'ojt_logbook_id' => $log1->id,
            'file_path' => 'evidences/p2h_check_001.jpg',
            'file_name' => 'P2H_Inspection_Form_EX2001.jpg',
            'file_type' => 'image',
            'file_size' => '2.4 MB',
        ]);

        LogbookHistory::create([
            'ojt_logbook_id' => $log1->id,
            'user_id' => $trainee->id,
            'action' => 'Created and Submitted Logbook',
            'from_status' => 'draft',
            'to_status' => 'submitted',
            'comment' => 'Logbook completed for Day Shift operation at Pit H1 East.',
        ]);

        LogbookHistory::create([
            'ojt_logbook_id' => $log1->id,
            'user_id' => $trainer->id,
            'action' => 'Verified by Senior Trainer',
            'from_status' => 'submitted',
            'to_status' => 'verified',
            'comment' => 'Good operation technique observed during PC2000 loading cycle. Approved.',
        ]);

        LogbookHistory::create([
            'ojt_logbook_id' => $log1->id,
            'user_id' => $supervisor->id,
            'action' => 'Approved by Supervisor',
            'from_status' => 'verified',
            'to_status' => 'supervisor_approved',
            'comment' => 'Disetujui Pengawas untuk diteruskan ke final approval Training Centre.',
        ]);

        // Entry 2: Revision Requested
        $log2 = OjtLogbook::create([
            'logbook_number' => 'LOG-202607-0002',
            'trainee_id' => $trainee->id,
            'trainer_id' => $trainer->id,
            'supervisor_id' => $supervisor->id,
            'department_id' => $deptMining->id,
            'equipment_category_id' => $catHaulTruck->id,
            'equipment_id' => $eqHt7042->id,
            'date' => Carbon::now()->subDays(3)->format('Y-m-d'),
            'shift' => 'night',
            'location' => 'Pit 3 West to Disposal Block 4',
            'start_time' => '19:00',
            'finish_time' => '05:00',
            'hm_start' => 8910.0,
            'hm_end' => 8918.5,
            'total_hm' => 8.5,
            'daily_activity' => "1. Night shift safety briefing and Golden Rules safety check.\n2. Hauling overburden material from Pit 3 West face to Disposal 4.\n3. Retarder braking testing during down-ramp loaded trips.\n4. Dumping maneuver at tipping point under spotter directions.",
            'status' => 'revision',
            'revision_notes' => "Mohon tambahkan rincian nomor form P2H dan bukti foto lembar checklist P2H shift malam serta konfirmasi catatan retarder brake test.",
            'submitted_at' => Carbon::now()->subDays(3)->addHours(11),
        ]);

        LogbookHistory::create([
            'ojt_logbook_id' => $log2->id,
            'user_id' => $trainer->id,
            'action' => 'Revision Requested by Trainer',
            'from_status' => 'submitted',
            'to_status' => 'revision',
            'comment' => 'Mohon lampirkan foto fisik lembar checklist P2H yang ditandatangani supervisor lapangan.',
        ]);

        // Entry 3: Submitted (Pending Verification)
        $log3 = OjtLogbook::create([
            'logbook_number' => 'LOG-202607-0003',
            'trainee_id' => $trainee->id,
            'trainer_id' => $trainer->id,
            'supervisor_id' => $supervisor->id,
            'department_id' => $deptMining->id,
            'equipment_category_id' => $catExcavator->id,
            'equipment_id' => $eqEx2001->id,
            'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'shift' => 'day',
            'location' => 'Pit H1 East - Bench 48',
            'start_time' => '07:00',
            'finish_time' => '17:00',
            'hm_start' => 4529.0,
            'hm_end' => 4537.5,
            'total_hm' => 8.5,
            'daily_activity' => "1. P2H inspection and fluid level checks on EX-2001.\n2. Selective mining and coal seam cleaning on Seam 14 Highwall.\n3. Loading coal haulers with zero dilution technique.\n4. Bench maintenance and slope stability observation.",
            'status' => 'submitted',
            'submitted_at' => Carbon::now()->subDays(1)->addHours(10),
        ]);

        LogbookHistory::create([
            'ojt_logbook_id' => $log3->id,
            'user_id' => $trainee->id,
            'action' => 'Submitted to Trainer',
            'from_status' => 'draft',
            'to_status' => 'submitted',
            'comment' => 'Logbook submitted for review. Total HM: 8.5 Hours.',
        ]);

        // Entry 4: Draft
        $log4 = OjtLogbook::create([
            'logbook_number' => 'LOG-202607-0004',
            'trainee_id' => $trainee->id,
            'trainer_id' => $trainer->id,
            'supervisor_id' => $supervisor->id,
            'department_id' => $deptMining->id,
            'equipment_category_id' => $catDozer->id,
            'equipment_id' => $eqDz3015->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'shift' => 'day',
            'location' => 'Disposal Area Block 4 North',
            'start_time' => '07:00',
            'finish_time' => '17:00',
            'hm_start' => 3105.0,
            'hm_end' => 3112.5,
            'total_hm' => 7.5,
            'daily_activity' => "Draft activity: Spreading overburden dump, trimming safety crest embankment, and compaction practice on Disposal 4.",
            'status' => 'draft',
        ]);
    }
}
