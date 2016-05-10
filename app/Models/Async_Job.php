<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\Async_Job
 *
 * @property integer $id
 * @property string $name
 * @property string $className
 * @property string $token
 * @property string $parent_task
 * @property integer $progressCurrent
 * @property integer $progressMax
 * @property boolean $complete
 * @property boolean $error
 * @property string $started
 * @property string $completed
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereClassName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereParentTask($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereProgressCurrent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereProgressMax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereComplete($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereError($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereStarted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Async_Job whereCompleted($value)
 * @mixin \Eloquent
 */
class Async_Job extends Model
{
    protected $table = 'async_jobs';
    public $timestamps = false;
}
