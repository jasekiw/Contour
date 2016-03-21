<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Configuration
 *
 * @property String $key
 * @property String $value
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Configuration whereUpdatedAt($value)
 */
class Configuration extends \Eloquent {
	protected $table = 'config';
	protected $fillable = [];
	use SoftDeletes;
	protected $dates = ['deleted_at'];
}