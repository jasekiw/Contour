<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Data_block_meta
 *
 * @property integer $id
 * @property integer $datablock_id
 * @property string $name
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block_meta whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block_meta whereDatablockId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block_meta whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block_meta whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block_meta whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block_meta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block_meta whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Data_block_meta extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected  $table = 'datablock_meta';
    protected $revisionCreationsEnabled = true;

}
