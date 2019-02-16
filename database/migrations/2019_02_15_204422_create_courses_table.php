<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->unsignedInteger('group_id');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

            $table->unsignedInteger('lecture_teacher_id')->nullable();
            $table->foreign('lecture_teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedInteger('lab_teacher_id')->nullable();
            $table->foreign('lab_teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedInteger('practice_teacher_id')->nullable();
            $table->foreign('practice_teacher_id')->references('id')->on('teachers')->onDelete('cascade');

            $table->unsignedInteger('tmp_course_id')->nullable();
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
        Schema::dropIfExists('courses');
    }
}
