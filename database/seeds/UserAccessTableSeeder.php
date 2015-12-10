<?php
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/4/2015
 * Time: 1:23 PM
 */

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User_Access_Group;

class UserAccessTableSeeder extends Seeder {

    public function run()
    {
        DB::table('user_access_groups')->truncate();
        User_Access_Group::create(array(
            'name'     => 'Administrator',
            'permission_ids'     => ''

        ));
    }

}