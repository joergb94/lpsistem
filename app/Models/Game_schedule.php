<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game_schedule extends Model
{
    protected $guarded=[];
    use SoftDeletes;
    
    public function games()
    {
        return $this->belongsTo('App\Models\Game','game_id','id');
    }

    public function game_schedule_details()
    {
        return $this->hasMany('App\Models\Game_schedules_detail', 'game_schedule_id');
    }
    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param $query
     * @param bool $status
     *
     * @return mixed
     */
    public function scopeActive($query, $status = true)
    {
        //changed 'active' to 'status'
        return $query->where('active', $status);
    }
}
