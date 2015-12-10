<?php
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
/**
 * Created by PhpStorm.
 * User: Jason Gallavin
 * Date: 11/3/2015
 * Time: 9:10 AM
 */


class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Willl fill the database with the proper system tags
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        //DB::table('type_categories')->truncate();
        $systemTagId = Tag::create(array(
            'name' => 'system',
            'type_id' => 5,
            'parent_tag_id' => -1,
            'sort_number' => 1
        ))->id;
      echo $systemTagId;
    }
}