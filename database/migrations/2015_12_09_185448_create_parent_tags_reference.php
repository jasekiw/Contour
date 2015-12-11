<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParentTagsReference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('parent_tags_reference', function(Blueprint $table)
//        {
//            DB::statement('
//                            CREATE TABLE parent_tags_reference (
//							tag_id INT UNSIGNED,
//							parent_tag_id INT UNSIGNED,
//							FOREIGN KEY (tag_id) REFERENCES tags (id),
//							FOREIGN KEY (parent_tag_id) REFERENCES tags (id)
//                            );
//                            ');
//            DB::statement("ALTER TABLE `parent_tags_reference` ADD `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ;");
//            DB::statement("ALTER TABLE `parent_tags_reference` ADD `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' ;");
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::drop('parent_tags_reference');
    }
}
