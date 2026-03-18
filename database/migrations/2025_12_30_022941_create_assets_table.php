<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;
use function Symfony\Component\String\s;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('company_name')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->date('register_date')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('category')->nullable();
            $table->mediumText('specification')->nullable();
            $table->integer('assembly_year')->nullable();
            $table->string('maintenance_period')->nullable();
            $table->string('ownership')->nullable();
            $table->date('commissioning_date')->nullable();
            $table->string('status')->nullable();
            $table->mediumText('remarks')->nullable();
            $table->unsignedBigInteger('requestor_id')->nullable();
            $table->string('requestor_name')->nullable();
            $table->unsignedBigInteger('requestor_department_id')->nullable();
            $table->string('requestor_department_name')->nullable();
            $table->string('last_action')->nullable();
            $table->unsignedBigInteger('last_user_id')->nullable();
            $table->string('last_user_name')->nullable();
            $table->integer('last_approval_level')->nullable();
            $table->string('next_action')->nullable();
            $table->unsignedBigInteger('next_user_id')->nullable();
            $table->string('next_user_name')->nullable();
            $table->string('approval_status')->nullable();
            $table->integer('approval_level')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('requestor_department_id')->references('id')->on('departments');
            $table->foreign('requestor_id')->references('id')->on('users');
            $table->foreign('last_user_id')->references('id')->on('users');
            $table->foreign('next_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
