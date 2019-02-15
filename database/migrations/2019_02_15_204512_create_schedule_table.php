<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('course_id')->nullable();
            $table->foreign('course_id')->references('id')
                                        ->on('courses')
                                        ->onDelete('cascade');

            $table->string('subject_type')->nullable();
            $table->string('room')->nullable();
            $table->unsignedTinyInteger('day')->nullable();
            $table->json('time')->nullable();
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
        Schema::dropIfExists('schedule');
    }
}
