<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDataBlocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('data_blocks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('value');
			$table->unsignedInteger('type_id');
            $table->integer('sort_number');
			$table->timestamps();
			$table->softDeletes();
			$table->index('type_id');
		});
		//DB::statement('ALTER TABLE posts ADD FULLTEXT full(name, content)');

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('data_blocks');
	}

}
