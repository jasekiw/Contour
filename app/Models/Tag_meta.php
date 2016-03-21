<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Tag_meta
 * @package App\Models
 * @property mixed tag_id
 * @property mixed id
 * @property mixed name
 * @property mixed value
 * @property mixed created_at
 * @property mixed updated_at
 */
class Tag_meta extends \Eloquent
{
    use SoftDeletes;
    protected  $table = 'tags_meta';
    protected $dates = ['deleted_at'];
}
