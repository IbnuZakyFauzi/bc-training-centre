<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainingCentreSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'training.centre@beraucoal.co.id'],
            ['nrp' => 'BC-10001', 'name' => 'Rudi Hartono (Kabag Training Centre)', 'password' => Hash::make('password'), 'role' => 'admin', 'department_id' => Department::where('code', 'SHE-TC')->value('id'), 'phone' => '+62 812-1000-2000']
        );
    }
}
