<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * Class Tag
 *
 * @property int $id
 * @property String $name
 * @property int $type_id
 * @property int $parent_tag_id
 * @property int $sort_number
 * @property String $created_at
 * @property String $updated_at
 * @method Tag static first() first() returns
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property mixed type_category_id
 * @property mixed sort_number
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereParentTagId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereSortNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tag whereUpdatedAt($value)
 */
class Tag extends \Eloquent {
	use RevisionableTrait;
	protected $fillable = [];

	public static function boot()
	{
		parent::boot();
	}


}