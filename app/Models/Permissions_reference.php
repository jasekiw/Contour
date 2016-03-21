<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Permissions_reference
 *
 */
class Permissions_reference extends \Eloquent {
	protected $fillable = [];
	use SoftDeletes;
	protected $dates = ['deleted_at'];
}