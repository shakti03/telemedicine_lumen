<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->uuid('uuid')->after('id');
            $table->decimal('fee', 10, 2)->default(0)->after('summary');
            $table->string('phone', 20)->nullable()->after('patient_email');
            $table->integer('duration')->default(0)->after('appointment_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->dropColumn('fee');
            $table->dropColumn('phone');
            $table->dropColumn('duration');
        });
    }
}
