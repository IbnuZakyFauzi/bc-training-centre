<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('role', 'department_ops')->update(['role' => 'trainer']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role_new', ['trainee', 'trainer', 'supervisor', 'admin'])->default('trainee');
        });

        DB::table('users')->update(['role_new' => DB::raw('role')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role_new', 'role');
            $table->enum('trainer_type', ['instruktur', 'pengawas', 'operator_pendamping'])->nullable();
        });

        $pengawasUserId = DB::table('users')->where('name', 'like', '%Pengawas%')->where('role', 'trainer')->value('id');
        if ($pengawasUserId) {
            DB::table('users')->where('id', $pengawasUserId)->update(['trainer_type' => 'pengawas']);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('trainer_type');
            $table->enum('role_new', ['trainee', 'trainer', 'supervisor', 'department_ops', 'admin'])->default('trainee');
        });

        DB::table('users')->update(['role_new' => DB::raw('role')]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role_new', 'role');
        });
    }
};
