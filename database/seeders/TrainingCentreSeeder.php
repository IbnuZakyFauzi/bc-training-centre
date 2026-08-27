<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainingCentreSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'training.centre@beraucoal.co.id'],
            ['sid' => 'BC-10001', 'name' => 'Rudi Hartono (Kabag Training Centre)', 'password' => Hash::make('password'), 'role' => 'admin', 'phone' => '+62 812-1000-2000']
        );
    }
}
