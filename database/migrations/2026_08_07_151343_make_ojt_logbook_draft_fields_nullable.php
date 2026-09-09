<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL cannot add an enum CHECK constraint through ALTER COLUMN TYPE.
        // The existing enum constraint is retained; only nullability/default change.
        if (DB::getDriverName() === 'pgsql') {
            Schema::table('ojt_logbooks', function (Blueprint $table) {
                $table->date('date')->nullable()->change();
                $table->string('location')->nullable()->change();
                $table->longText('daily_activity')->nullable()->change();
            });

            DB::statement('ALTER TABLE "ojt_logbooks" ALTER COLUMN "shift" DROP NOT NULL');
            DB::statement("ALTER TABLE \"ojt_logbooks\" ALTER COLUMN \"shift\" SET DEFAULT 'day'");

            return;
        }

        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->date('date')->nullable()->change();
            $table->enum('shift', ['day', 'night'])->nullable()->default('day')->change();
            $table->string('location')->nullable()->change();
            $table->longText('daily_activity')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            Schema::table('ojt_logbooks', function (Blueprint $table) {
                $table->date('date')->nullable(false)->change();
                $table->string('location')->nullable(false)->change();
                $table->longText('daily_activity')->nullable(false)->change();
            });

            DB::statement('ALTER TABLE "ojt_logbooks" ALTER COLUMN "shift" SET NOT NULL');
            DB::statement("ALTER TABLE \"ojt_logbooks\" ALTER COLUMN \"shift\" SET DEFAULT 'day'");

            return;
        }

        Schema::table('ojt_logbooks', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
            $table->enum('shift', ['day', 'night'])->nullable(false)->default('day')->change();
            $table->string('location')->nullable(false)->change();
            $table->longText('daily_activity')->nullable(false)->change();
        });
    }
};
