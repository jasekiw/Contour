<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;





/**
 * App\Models\Revision
 *
 * @property integer $id
 * @property string $revisionable_type
 * @property integer $revisionable_id
 * @property integer $user_id
 * @property string $key
 * @property string $old_value
 * @property string $new_value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereRevisionableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereRevisionableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereOldValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereNewValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Revision whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Revision extends Model {
    protected $fillable = [];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

}