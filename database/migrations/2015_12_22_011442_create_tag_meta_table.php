<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTagMetaTable
 */
class CreateTagMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags_meta', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('tag_id');
            $table->string('name');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
            $table->index('tag_id');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("tags_meta");
    }
}
