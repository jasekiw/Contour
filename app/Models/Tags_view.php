<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Models\Tags_view
 *
 * @property integer $ID
 * @property string $name
 * @property string $type
 * @property integer $parent_id
 * @property string $parent_name
 * @property integer $sort_number
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_view whereID($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_view whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_view whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_view whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_view whereParentName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_view whereSortNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_view whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_view whereUpdatedAt($value)
 */
class Tags_view extends \Eloquent {
    use RevisionableTrait;
	protected $fillable = [];
    protected $table = 'tags_view';

    public static function boot()
    {
        parent::boot();
    }
}