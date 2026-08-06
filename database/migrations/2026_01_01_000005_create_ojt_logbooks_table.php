<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ojt_logbooks', function (Blueprint $table) {
            $table->id();
            $table->string('logbook_number')->unique(); // LOG-YYYYMM-XXXX
            $table->foreignId('trainee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('trainer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('equipment_category_id')->nullable()->constrained('equipment_categories')->nullOnDelete();
            $table->foreignId('equipment_id')->nullable()->constrained('equipments')->nullOnDelete();
            
            $table->date('date');
            $table->enum('shift', ['day', 'night'])->default('day');
            $table->string('location'); // Pit H1 East, Pit 3 West, ROM 2, etc.
            
            $table->time('start_time')->nullable();
            $table->time('finish_time')->nullable();
            $table->decimal('hm_start', 8, 1)->default(0);
            $table->decimal('hm_end', 8, 1)->default(0);
            $table->decimal('total_hm', 8, 1)->default(0);
            
            $table->longText('daily_activity');
            $table->enum('status', ['draft', 'submitted', 'revision', 'verified', 'supervisor_approved', 'final_approved'])->default('draft');
            $table->text('revision_notes')->nullable();
            
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ojt_logbooks');
    }
};
