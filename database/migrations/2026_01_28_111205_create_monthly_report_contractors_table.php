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
        Schema::create('monthly_report_contractors', function (Blueprint $table) {
            $table->id();
            $table->string('report_no')->nullable();
            $table->date('report_date')->nullable();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('company_name')->nullable();
            $table->integer('operational_employee_total')->nullable();
            $table->integer('supervision_employee_total')->nullable();
            $table->integer('subcon_operational_employee_total')->nullable();
            $table->integer('local_employee_total')->nullable();
            $table->integer('national_employee_total')->nullable();
            $table->integer('foreign_employee_total')->nullable();
            $table->float('machine_working_hours')->nullable();
            $table->string('business_field')->nullable();
            $table->string('operational_person_name')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            // baru
            $table->integer('administration_operational_total');
            $table->double('operational_hours')->nullable();
            $table->double('administration_hours')->nullable();
            $table->double('supervision_hours')->nullable();
            $table->text('remarks')->nullable();
            // subcon
            $table->double('subcon_operational_hours')->nullable();
            $table->double('subcon_admin_hours')->nullable();
            $table->double('subcon_supervision_hours')->nullable();
            $table->double('subcon_operational_employee_total')->nullable();
            $table->double('subcon_operational_hours')->nullable();
            $table->double('subcon_admin_total')->nullable();
            $table->double('subcon_admin_hours')->nullable();
            $table->double('subcon_supervision_total')->nullable();
            $table->double('subcon_supervision_hours')->nullable();

            // approval
            $table->string('status')->nullable();
            $table->integer('approval_level');
            $table->foreignId('last_user_id')->nullable()->constrained('id')->onDelete('cascade');
            $table->string('last_user_name')->nullable();
            $table->foreignId('next_user_id')->nullable()->constrained('id')->onDelete('cascade');
            $table->string('next_user_name')->nullable();
            $table->string('next_action')->nullable();
            $table->string('last_action')->nullable();

            // request
            $table->foreignId('requestor_id')->nullable()->constraned('id')->onDelete('cascade');
            $table->string('requestor_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_report_contractors');
    }
};
