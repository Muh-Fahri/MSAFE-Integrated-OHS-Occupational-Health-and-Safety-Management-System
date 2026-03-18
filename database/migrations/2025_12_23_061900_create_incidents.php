<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Symfony\Component\String\s;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->nullable();
            $table->string('no')->nullable();
            $table->unsignedBigInteger('reporter_id')->nullable();
            $table->string('reporter_name')->nullable();
            $table->mediumText('event_title')->nullable();
            $table->dateTime('event_dateTime')->nullable();
            $table->string('location_type')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('company_name')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->string('event_type')->nullable();
            $table->string('severity_level_actual')->nullable();
            $table->string('severity_level_acttual_remarks')->nullable();
            $table->string('severity_level_potential')->nullable();
            $table->string('severity_level_potential_remarks')->nullable();
            $table->mediumText('incident_description')->nullable();
            $table->mediumText('incident_actions')->nullable();
            $table->string('work_related')->nullable();
            $table->text('remarks')->nullable();
            $table->string('last_action')->nullable();
            $table->unsignedBigInteger('last_user_id')->nullable();
            $table->string('last_user_name')->nullable();
            $table->integer('last_approval_level')->nullable();
            $table->string('next_action')->nullable();
            $table->unsignedBigInteger('next_user_id')->nullable();
            $table->string('next_user_name')->nullable();
            $table->string('status')->nullable();
            $table->integer('approval_level')->nullable();
            $table->string('photo_1_type')->nullable();
            $table->string('photo_1_path')->nullable();
            $table->string('photo_2_type')->nullable();
            $table->string('photo_2_path')->nullable();
            $table->string('photo_3_type')->nullable();
            $table->string('photo_3_path')->nullable();
            $table->string('photo_4_type')->nullable();
            $table->string('photo_4_path')->nullable();
            $table->string('impact_injury_classification')->nullable();
            $table->mediumText('impact_injury_description')->nullable();
            $table->mediumText('impact_injury_treatment_details')->nullable();
            $table->string('impact_injury_body_injury')->nullable();
            $table->string('impact_environmental_category')->nullable();
            $table->string('impact_environmental_product_name')->nullable();
            $table->string('impact_environmental_quantity_quom')->nullable();
            $table->mediumText('impact_property_damage_plant_type')->nullable();
            $table->mediumText('impact_property_damage_cost')->nullable();
            $table->string('impact_property_damage_asset_involved')->nullable();
            $table->mediumText('impact_property_damage_info')->nullable();
            $table->mediumText('impact_property_damage_asset_number')->nullable();
            $table->mediumText('fact_finding_description_people')->nullable();
            $table->mediumText('fact_finding_description_environment')->nullable();
            $table->mediumText('fact_finding_description_equipment')->nullable();
            $table->mediumText('fact_finding_description_procedure')->nullable();
            $table->mediumText('fact_finding_causal_factor')->nullable();
            $table->mediumText('fact_finding_root_cause')->nullable();
            $table->string('fact_finding_photo_1_type')->nullable();
            $table->string('fact_finding_photo_1_path')->nullable();
            $table->string('fact_finding_photo_2_type')->nullable();
            $table->string('fact_finding_photo_2_path')->nullable();
            $table->string('fact_finding_photo_3_type')->nullable();
            $table->string('fact_finding_photo_3_path')->nullable();
            $table->string('fact_finding_photo_4_type')->nullable();
            $table->string('fact_finding_photo_4_path')->nullable();
            $table->timestamps();

            // Faoreign
            $table->foreign('reporter_id')->references('id')->on('users');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('last_user_id')->references('id')->on('users');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('next_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_notifications');
    }
};
