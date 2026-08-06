<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_category_id')->constrained('equipment_categories')->cascadeOnDelete();
            $table->string('unit_code')->unique(); // e.g. EX-2001
            $table->string('model_name'); // e.g. Komatsu PC2000-8
            $table->string('serial_number')->nullable();
            $table->string('status')->default('active'); // active, maintenance
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
