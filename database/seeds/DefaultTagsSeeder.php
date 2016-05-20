<?php

use app\libraries\tags\DataTag;
use app\libraries\types\Types;
use Illuminate\Database\Seeder;

class DefaultTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $excel = new DataTag("excel", 0, Types::get_type_folder(), 1);
        $excel->create();
        $facilities = new DataTag("facilities", $excel->get_id(), Types::get_type_folder(), 0);
        $facilities->create();
        $reports = new DataTag("reports", $excel->get_id(), Types::get_type_folder(), 1 );
        $reports->create();
        $templates = new DataTag("templates", $excel->get_id(), Types::get_type_folder(), 1 );
        $templates->create();

    }
}
