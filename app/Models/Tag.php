<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Tag
 *
 * @property integer $id
 * @property string $name
 * @property integer $type_id
 * @property integer $parent_tag_id
 * @property integer $sort_number
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereParentTagId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereSortNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Tag extends Model {
	use RevisionableTrait;
	use SoftDeletes;
	protected $dates = ['deleted_at'];
    protected $revisionCreationsEnabled = true;
	protected $fillable = [];

	public static function boot()
	{
		parent::boot();
	}



}