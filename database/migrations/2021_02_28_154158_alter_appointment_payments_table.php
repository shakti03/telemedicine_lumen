<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAppointmentPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointment_payments', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id');
            $table->string('paypal_order_ref');
            $table->string('payee_ref');
            $table->decimal('amount', 10, 2);
            $table->text('result');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
