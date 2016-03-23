<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsyncJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('async_jobs', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('name');
            $table->string('className');
            $table->string('token');
            $table->string('parent_task');
            $table->integer('progressCurrent');
            $table->integer('progressMax');
            $table->boolean('complete');
            $table->boolean('error');
            $table->dateTime('started');
            $table->dateTime('completed');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('async_jobs');
    }
}
