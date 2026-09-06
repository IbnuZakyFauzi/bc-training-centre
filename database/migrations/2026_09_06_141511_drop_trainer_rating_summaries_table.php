<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('trainer_rating_summaries');
    }

    public function down(): void
    {
        Schema::create('trainer_rating_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('average_rating', 3, 1)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->timestamps();

            $table->unique('user_id');
        });
    }
};
