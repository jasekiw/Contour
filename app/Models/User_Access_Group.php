<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class User_Access_level
 *
 * @property String $name
 * @property String $permission_ids
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Access_Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Access_Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Access_Group wherePermissionIds($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Access_Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User_Access_Group whereUpdatedAt($value)
 */
class User_Access_Group extends \Eloquent {
	use RevisionableTrait;
	protected $fillable = [];
	protected $table = 'user_access_groups';
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	public static function boot()
	{
		parent::boot();
	}
}