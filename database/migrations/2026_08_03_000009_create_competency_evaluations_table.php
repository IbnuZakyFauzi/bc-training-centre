<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('competency_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ojt_logbook_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('overall_score')->nullable();
            $table->enum('competency_status', ['competent', 'not_yet_competent'])->nullable();
            $table->json('assessment_payload')->nullable();
            $table->text('trainer_comment')->nullable();
            $table->text('revision_instruction')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamp('sent_to_pjo_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competency_evaluations');
    }
};
