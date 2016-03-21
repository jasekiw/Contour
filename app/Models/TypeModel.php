<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\TypeModel
 *
 * @property integer $id
 * @property string $name
 * @property integer $type_category_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TypeModel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TypeModel whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TypeModel whereTypeCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TypeModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\TypeModel whereUpdatedAt($value)
 */
class TypeModel extends \Eloquent {
	use RevisionableTrait;
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = [];
	protected $table = 'types';

	public static function boot()
	{
		parent::boot();
	}
}