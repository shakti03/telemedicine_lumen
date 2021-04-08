<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAppointmentsTableAddPaymentStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum("payment_status", ["PENDING", "PAID", "FAILED", "NA"])->default("NA")->after("status");
            $table->string("meeting_location")->nullable()->after("fee");
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
            $table->dropColumn("payment_status");
            $table->dropColumn("meeting_location");
        });
    }
}
