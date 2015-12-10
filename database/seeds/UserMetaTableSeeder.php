<?php
use App\Models\User_Meta;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;


class UserMetaTableSeeder extends Seeder {

	public function run()
	{
		DB::table('user_meta')->truncate();

		User_Meta::create(array(
			'user_id'	=> 1,
			'key' => 'first_name',
			'value'    => 'Jason',
		));
		User_Meta::create(array(
			'user_id'	=> 1,
			'key' => 'last_name',
			'value'    => 'Gallavin',
		));

	}

}