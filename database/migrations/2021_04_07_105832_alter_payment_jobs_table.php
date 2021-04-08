<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AlterPaymentJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_jobs', function (Blueprint $table) {
            //$table->renameColumn('result', 'order_result');
            DB::statement("ALTER TABLE `payment_jobs` CHANGE `result` `order_result` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
            $table->text('capture_result')->after('order_result')->nullable()->after("order_result");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_jobs', function (Blueprint $table) {
            DB::statement("ALTER TABLE `payment_jobs` CHANGE `order_result` `result` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
            $table->dropColumn('capture_result');
        });
    }
}
