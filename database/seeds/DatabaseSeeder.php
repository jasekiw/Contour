<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);
        $this->call('UserTableSeeder');
        $this->call('UserMetaTableSeeder');
        $this->call('TypeCategoriesTableSeeder');
        $this->call('TypesTableSeeder');
        $this->call('ConfigurationSeeder');
        Model::reguard();
    }
}
