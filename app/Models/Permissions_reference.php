<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\Permissions_reference
 *
 * @mixin \Eloquent
 */
class Permissions_reference extends Model {
	protected $fillable = [];
	use SoftDeletes;
	protected $dates = ['deleted_at'];
}