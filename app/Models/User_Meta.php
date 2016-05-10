<?php
namespace App\Models;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\User_Meta
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $key
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereDeletedAt($value)
 * @mixin \Eloquent
 */
class User_Meta extends Model {
	protected  $table = 'user_meta';
	protected $fillable = [];
	use SoftDeletes;
	protected $dates = ['deleted_at'];


}