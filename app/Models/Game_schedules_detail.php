<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;;

class Game_schedules_detail extends Model
{
    protected $guarded=[];
    use SoftDeletes;
}
