<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;


/**
 * App\Models\Tags_reference
 *
 * @property integer $data_block_id
 * @property integer $tag_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_reference whereDataBlockId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_reference whereTagId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_reference whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_reference whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_reference whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Tags_reference extends Model {
    use RevisionableTrait;
    use SoftDeletes;
	protected $fillable = [];
	protected $table = "tags_reference";
	protected $dates = ['deleted_at'];
    protected $revisionCreationsEnabled = true;
    public static function boot()
    {
        parent::boot();
    }
}