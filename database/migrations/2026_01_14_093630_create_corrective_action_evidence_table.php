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
        Schema::create('corrective_action_evidence', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id')->nullable();
            $table->string('remark')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();

            $table->foreign('action_id')->references('id')->on('corrective_actions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corrective_action_evidence');
    }
};
