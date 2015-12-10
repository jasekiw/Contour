<?php
namespace App\Models;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
/**
 * Class User_Meta
 *
 * @property string $key
 * @property String $value
 * @property integer $id
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Meta whereUpdatedAt($value)
 */
class User_Meta extends \Eloquent {
	protected  $table = 'user_meta';
	protected $fillable = [];


}