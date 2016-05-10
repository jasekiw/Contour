<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Tag_meta
 *
 * @property integer $id
 * @property integer $tag_id
 * @property string $name
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag_meta whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag_meta whereTagId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag_meta whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag_meta whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag_meta whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag_meta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag_meta whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Tag_meta extends Model
{
    use SoftDeletes;
    protected  $table = 'tags_meta';
    protected $dates = ['deleted_at'];
}
