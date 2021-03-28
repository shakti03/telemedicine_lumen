<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGotoMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goto_meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subject');
            $table->string('starttime');
            $table->string('endtime');
            $table->bigInteger('appointment_id');
            $table->string('goto_meetingid');
            $table->string('join_url');
            $table->text('other')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goto_meetings');
    }
}
