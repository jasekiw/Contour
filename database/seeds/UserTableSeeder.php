<?php
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/4/2015
 * Time: 10:11 AM
 */
class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->truncate();
        User::create([
            'username' => 'jasekiw',
            'email' => 'jasong@lougeek.com',
            'password' => Hash::make('k1w1k1w1'),
            'user_access_group_id' => 1
            ]);
    }

}