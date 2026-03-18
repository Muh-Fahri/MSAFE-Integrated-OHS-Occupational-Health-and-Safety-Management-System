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
            Schema::create('corrective_actions', function (Blueprint $table) {
                $table->id();
                $table->string('source', 100);
                $table->integer('source_id');
                $table->string('source_no', 100);
                $table->integer('source_action_id');
                $table->integer('risk_issuer_id');
                $table->string('risk_issuer_name', 100);
                $table->date('risk_issue_date');
                $table->mediumText('risk_description');
                $table->string('location', 100);
                $table->integer('department_id');
                $table->string('department_name', 100);
                $table->integer('responsible_person_id');
                $table->string('responsible_person_name', 100);
                $table->mediumText('corrective_action');
                $table->mediumText('action_taken');
                $table->date('due_date');
                $table->string('status', 30);
                $table->string('last_action', 20);
                $table->integer('last_user_id');
                $table->string('last_user_name', 100);
                $table->string('next_action', 30);
                $table->integer('next_user_id');
                $table->string('next_user_name', 100);
                $table->integer('approval_level');
                $table->string('remarks', 100);
                $table->string('created_by', 100);
                $table->string('updated_by', 100);
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('corrective_actions');
        }
    };
