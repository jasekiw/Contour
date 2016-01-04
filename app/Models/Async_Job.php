<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class App\Models\Async_Jobs
 * @package App\Models
 * @property int $progressCurrent
 * @property int $progressMax
 * @property string $className
 * @property int $id
 * @property string $name
 * @property string $token
 * @property boolean $started
 * @property boolean $complete
 */
class Async_Job extends \Eloquent
{
    protected $table = 'async_jobs';
    public $timestamps = false;
}
