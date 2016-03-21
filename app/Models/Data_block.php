<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * App\Models\Data_block
 *
 * @property integer $id
 * @property string $value
 * @property integer $type_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Data_block whereUpdatedAt($value)
 */
class Data_block extends \Eloquent {
	use RevisionableTrait;
	use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = [];

	public static function boot()
	{
		parent::boot();
	}



}