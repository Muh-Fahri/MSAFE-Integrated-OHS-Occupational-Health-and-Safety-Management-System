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
        Schema::create('mine_personels', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pengajuan')->nullable();
            $table->string('no')->nullable();
            $table->unsignedBigInteger('company')->nullable();
            $table->string('company_name')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->enum('appointment', [
                'PENGAWAS OPERASIONAL',
                'PENGAWAS TEKNIS',
            ]);
            $table->string('attachments')->nullable();
            $table->string('status')->nullable();
            $table->string('next_action')->nullable();
            $table->unsignedBigInteger('next_user_id')->nullable();
            $table->string('next_user_name')->nullable();
            $table->string('last_action')->nullable();
            $table->unsignedBigInteger('last_user_id')->nullable();
            $table->string('last_user_name')->nullable();
            $table->integer('approval_level')->nullable();
            $table->integer('list_approval_level')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mine_personels');
    }
};
