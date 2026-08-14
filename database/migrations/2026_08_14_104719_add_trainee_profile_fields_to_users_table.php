<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('certification', ['Green', 'Skill-up', 'Experience'])->nullable()->after('phone');
            $table->string('company')->nullable()->after('certification');
            $table->foreignId('equipment_category_id')->nullable()->constrained()->nullOnDelete()->after('company');
            $table->string('equipment_number')->nullable()->after('equipment_category_id');
            $table->date('sticker_expired_at')->nullable()->after('equipment_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['certification', 'company', 'equipment_category_id', 'equipment_number', 'sticker_expired_at']);
        });
    }
};
