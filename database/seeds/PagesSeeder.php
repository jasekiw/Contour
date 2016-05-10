<?php

use app\libraries\tags\DataTag;
use app\libraries\tags\DataTags;
use app\libraries\types\Types;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $system = DataTags::get_by_string('system',0);
        $pages = new DataTag('pages',$system->get_id(), Types::get_type_folder());
        $pages->create();
    }
}
