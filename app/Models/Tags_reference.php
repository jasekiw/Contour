<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Tags_reference
 *
 * @property int $data_block_id
 * @property int $tag_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_reference whereTagId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_reference whereDataBlockId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_reference whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tags_reference whereUpdatedAt($value)
 */
class Tags_reference extends \Eloquent {
	protected $fillable = [];
	protected $table = "tags_reference";
}