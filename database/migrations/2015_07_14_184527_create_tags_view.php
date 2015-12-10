<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsView extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
//		Schema::create('tags_view', function(Blueprint $table)
//		{
//			$table->increments('id');
//			$table->timestamps();
//		});
        DB::statement( 'CREATE VIEW tags_view AS
        SELECT
            tag.id AS ID,
            tag.name as name,
            types.name as type,
            tag.parent_tag_id AS parent_id,
            parent.name AS parent_name,
            tag.sort_number,
            tag.created_at,
            tag.updated_at
        FROM tags AS tag
        LEFT JOIN tags AS parent
            ON tag.parent_tag_id = parent.id
        LEFT JOIN types as types
            ON tag.type_id = types.id' );
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement( 'DROP VIEW tags_view' );
	}

}
