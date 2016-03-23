<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataBlockMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datablock_meta', function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('datablock_id');
            $table->string('name');
            $table->string('value');
            $table->timestamps();
            $table->softDeletes();
            $table->index('datablock_id');
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
        Schema::drop("datablock_meta");
    }
}
