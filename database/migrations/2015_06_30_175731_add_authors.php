<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAuthors extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        DB::table('authors')->insert(array(
            'name' => 'Jason Gallavin',
            'bio'   =>  'Jason is a great author',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ));
        DB::table('authors')->insert(array(
            'name' => 'Author 2',
            'bio'   =>  'Author 2 is a great author',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ));
        DB::table('authors')->insert(array(
            'name' => 'James Patterson',
            'bio'   =>  'James Patterson is a great author',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ));
        DB::table('authors')->insert(array(
            'name' => 'Gary Paulson',
            'bio'   =>  'Gary Paulson is a great author',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ));
        DB::table('authors')->insert(array(
            'name' => 'Paula Hawkins',
            'bio'   =>  'Paula Hawkins is a great author',
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
        DB::table('authors')->delete(1);
        DB::table('authors')->delete(2);
        DB::table('authors')->delete(3);
        DB::table('authors')->delete(4);
        DB::table('authors')->delete(5);

	}

}
