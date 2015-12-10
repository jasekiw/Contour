<?php
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/2/2015
 * Time: 3:23 PM
 */

use App\Models\Type_category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TypeCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        DB::table('type_categories')->truncate();
        Type_category::create(array(
            'name' => 'tag',
        ));
        Type_category::create(array(
            'name' => 'datablock',
        ));
    }
}