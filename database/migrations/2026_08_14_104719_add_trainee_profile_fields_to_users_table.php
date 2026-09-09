<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('certification', ['Green', 'Skill-up', 'Experience'])->nullable();
            $table->string('company')->nullable();
            $table->foreignId('equipment_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('equipment_number')->nullable();
            $table->date('sticker_expired_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['certification', 'company', 'equipment_category_id', 'equipment_number', 'sticker_expired_at']);
        });
    }
};
