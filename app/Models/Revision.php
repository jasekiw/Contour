<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Created by PhpStorm.
 * User: Jason
 * Date: 8/12/2015
 * Time: 3:00 PM
 */


/**
 * Class Revision
 *
 * @property int $id
 * @property String $revisionable_type
 * @property int $revisionable_id
 * @property int $user_id
 * @property String $key
 * @property String $old_value
 * @property String $new_value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereRevisionableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereRevisionableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereOldValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereNewValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereUpdatedAt($value)
 */
class Revision extends \Eloquent {
    protected $fillable = [];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

}