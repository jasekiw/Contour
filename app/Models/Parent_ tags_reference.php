<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Parent_tags_reference
 *
 * @package App\Models
 * @property int $tag_id
 * @property int $parent_tag_id
 */
class Parent_tags_reference extends \Eloquent
{
    protected $table = 'parent_tags_reference';
    protected $casts = [
        'tag_id' => 'integer',
        'parent_tag_id' => 'integer'
    ];



}
