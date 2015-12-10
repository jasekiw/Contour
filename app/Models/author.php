<?php
/**
 * Created by PhpStorm.
 * User: jasong
 * Date: 7/1/2015
 * Time: 9:06 AM
 */
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\author
 *
 * @property integer $id
 * @property string $name
 * @property string $bio
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\author whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\author whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\author whereBio($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\author whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\author whereUpdatedAt($value)
 */
class author extends \Eloquent {

    public static $rules = array(
        'name'  => 'required|min:2',
        'bio'   => 'required|min:10'
    );
    public static $accessible = array('name' , 'bio');
    public static function validate($data)
    {
        return \Validator::make($data, static::$rules);
    }


}