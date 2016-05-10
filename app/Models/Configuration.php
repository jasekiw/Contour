<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Configuration
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Configuration extends Model {
	protected $table = 'config';
	protected $fillable = [];
	use SoftDeletes;
	protected $dates = ['deleted_at'];
}