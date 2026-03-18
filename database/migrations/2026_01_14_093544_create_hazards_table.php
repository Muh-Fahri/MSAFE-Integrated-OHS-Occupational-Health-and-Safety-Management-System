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
        Schema::create('hazards', function (Blueprint $table) {
            $table->id();
            $table->string('no', 30)->unique();
            $table->integer('requestor_id');
            $table->string('requestor_name', 100);
            $table->integer('requestor_department_id');
            $table->string('requestor_department_name', 100);
            $table->integer('reporter_id');
            $table->string('reporter_name', 100);
            $table->integer('reporter_department_id');
            $table->string('reporter_department_name', 100);
            $table->integer('recipient_id');
            $table->string('recipient_name', 100);
            $table->integer('recipient_department_id');
            $table->string('recipient_department_name', 100);
            $table->dateTime('report_datetime');
            $table->string('location', 100);
            $table->string('hazard_source', 100);
            $table->string('hazard_type', 100);
            $table->mediumText('hazard_description')->nullable();
            $table->mediumText('immediate_actions')->nullable();
            $table->mediumText('corrective_action')->nullable();
            $table->mediumText('action_taken')->nullable();
            $table->date('due_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->integer('assignee_id')->nullable();
            $table->string('assignee_name', 100)->nullable();
            $table->integer('assignee_department_id')->nullable();
            $table->string('assignee_department_name', 100)->nullable();
            $table->mediumText('remarks')->nullable();
            $table->string('last_action', 20);
            $table->integer('last_user_id')->nullable();
            $table->string('last_user_name', 100)->nullable();
            $table->string('next_action', 20)->nullable();
            $table->string('status', 30);
            $table->integer('approval_level');
            $table->string('file_1_type', 255)->nullable();
            $table->string('file_1_path', 255)->nullable();
            $table->string('file_2_type', 255)->nullable();
            $table->string('file_2_path', 255)->nullable();
            $table->string('file_3_type', 255)->nullable();
            $table->string('file_3_path', 255)->nullable();
            $table->string('file_4_type', 255)->nullable();
            $table->string('file_4_path', 255)->nullable();
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazards');
    }
};
