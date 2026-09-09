<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legacy_sqlite_archives', function (Blueprint $table) {
            $table->id();
            $table->string('source_table');
            $table->string('source_id')->nullable();
            $table->string('source_column')->nullable();
            $table->json('payload');
            $table->timestamps();

            $table->unique(['source_table', 'source_id', 'source_column']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legacy_sqlite_archives');
    }
};
