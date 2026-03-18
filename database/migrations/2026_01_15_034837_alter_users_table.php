<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('department_id')->nullable()->after('email');
            $table->integer('company_id')->nullable()->after('department_id');
            $table->integer('hod')->nullable()->after('company_id');
            $table->integer('hod2')->nullable()->after('hod');
            $table->string('phone', 15)->nullable()->after('password');
            $table->string('employee_id', 20)->nullable()->after('phone');
            $table->string('status', 10)->nullable()->after('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->dropColumn('company_id');
            $table->dropColumn('hod');
            $table->dropColumn('hod2');
            $table->dropColumn('phone');
            $table->dropColumn('employee_id');
            $table->dropColumn('status');
        });
    }
};
