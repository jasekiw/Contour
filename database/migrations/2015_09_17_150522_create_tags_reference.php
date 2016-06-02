<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsReference extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tags_reference', function(Blueprint $table)
		{
			$table->increments('id');
			$table->unsignedInteger('data_block_id');
			$table->unsignedInteger('tag_id');
			$table->unique(['tag_id', 'data_block_id']);
			$table->timestamps();
			$table->softDeletes();
			$table->index('data_block_id');
			$table->index('tag_id');
		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tags_reference');
	}

}
