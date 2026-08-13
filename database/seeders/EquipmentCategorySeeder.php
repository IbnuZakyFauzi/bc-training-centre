<?php

namespace Database\Seeders;

use App\Models\EquipmentCategory;
use Illuminate\Database\Seeder;

class EquipmentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['code' => 'EXC', 'name' => 'Excavator', 'description' => 'Hydraulic Excavator Loading Units'],
            ['code' => 'DZ', 'name' => 'Bulldozer', 'description' => 'Crawler Bulldozers'],
            ['code' => 'MG', 'name' => 'Motor Grader', 'description' => 'Haul Road Maintenance Graders'],
            ['code' => 'HDT', 'name' => 'Heavy Dump Truck', 'description' => 'Off-Highway Heavy Dump Trucks'],
            ['code' => 'LDT', 'name' => 'Light Dump Truck', 'description' => 'Off-Highway Light Dump Trucks'],
            ['code' => 'SDT', 'name' => 'Semi Dump Trailler', 'description' => 'Semi Dump Trailler Units'],
            ['code' => 'ADT', 'name' => 'Articulated Dump Truck', 'description' => 'Articulated Dump Truck Units'],
            ['code' => 'WL', 'name' => 'Wheel Loader', 'description' => 'Wheel Loading Units'],
        ];

        foreach ($categories as $cat) {
            EquipmentCategory::firstOrCreate(['code' => $cat['code']], $cat);
        }
    }
}
