<?php
use App\Models\TypeModel;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TypesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		DB::table('types')->truncate();
		TypeModel::create(array(
			'name' => 'row',
			'type_category_id'    => '1',
		));
		TypeModel::create(array(
			'name' => 'column',
			'type_category_id'    => '1',
		));
		TypeModel::create(array(
			'name' => 'sheet',
			'type_category_id'    => '1',
		));
		TypeModel::create(array(
			'name' => 'cell',
			'type_category_id'    => '2',
		));
		TypeModel::create(array(
			'name' => 'folder',
			'type_category_id'    => '1',
		));
	}

}
