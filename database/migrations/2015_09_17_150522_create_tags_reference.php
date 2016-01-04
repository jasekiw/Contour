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
//		Schema::create('tags_reference', function(Blueprint $table)
//		{
//			$table->increments('id');
//			$table->timestamps();
//		});
		DB::statement('CREATE TABLE tags_reference (
							tag_id INT UNSIGNED,
							data_block_id INT UNSIGNED,
							created_at TIMESTAMP NOT NULL DEFAULT \'0000-00-00 00:00:00\',
							updated_at TIMESTAMP NOT NULL DEFAULT \'0000-00-00 00:00:00\',
							UNIQUE KEY (tag_id, data_block_id)

						);');
//		FOREIGN KEY (data_block_id) REFERENCES data_blocks (id),
//							FOREIGN KEY (tag_id) REFERENCES tags (id)
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
