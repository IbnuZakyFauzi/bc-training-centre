<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbook_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ojt_logbook_id')->constrained('ojt_logbooks')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_name');
            $table->enum('file_type', ['image', 'video', 'document'])->default('image');
            $table->string('file_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbook_evidences');
    }
};
