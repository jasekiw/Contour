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
							UNIQUE KEY (tag_id, data_block_id),
							FOREIGN KEY (data_block_id) REFERENCES data_blocks (id)
						);');
		DB::statement("ALTER TABLE `tags_reference` ADD `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ;");
		DB::statement("ALTER TABLE `tags_reference` ADD `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ;");
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
