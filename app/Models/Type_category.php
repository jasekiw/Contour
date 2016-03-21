<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\Type_category
 *
 * @property integer $id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Type_category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Type_category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Type_category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Type_category whereUpdatedAt($value)
 */
class Type_category extends \Eloquent {
	use RevisionableTrait;
	use SoftDeletes;
	protected $fillable = [];
	protected $dates = ['deleted_at'];

	public static function boot()
	{
		parent::boot();
	}
}