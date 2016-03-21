<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Data_block_meta
 * @package App\Models
 */
class Data_block_meta extends \Eloquent
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected  $table = 'datablock_meta';
    //
}
