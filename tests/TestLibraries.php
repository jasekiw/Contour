<?php

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TestLibraries extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testDataBlock()
    {

        $this->assertNotNull(new \app\libraries\datablocks\DataBlock());
    }
    public function testDataTag() {

        $this->assertNotNull(new \app\libraries\tags\DataTag());
    }
    public function testTypes() {

        $this->assertNotNull( new \app\libraries\types\Type("test"));
    }
    public function testTypeCategories() {

        $this->assertNotNull( new \app\libraries\types\TypeCategory());
    }

}
