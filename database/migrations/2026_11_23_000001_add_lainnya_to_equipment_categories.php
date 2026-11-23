<?php

use App\Models\EquipmentCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('equipment_categories')) {
            EquipmentCategory::firstOrCreate(
                ['code' => 'OTH'],
                [
                    'name' => 'Lainnya',
                    'description' => 'Tipe Unit / Alat Lainnya',
                ]
            );
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('equipment_categories')) {
            EquipmentCategory::where('code', 'OTH')->delete();
        }
    }
};
