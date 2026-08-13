<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainee_trainer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained('users')->cascadeOnDelete();
            $table->enum('trainer_type', ['instruktur', 'pengawas', 'operator_pendamping']);
            $table->timestamps();

            $table->unique(['trainee_id', 'trainer_id', 'trainer_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_trainer');
    }
};
