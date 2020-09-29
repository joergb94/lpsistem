<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Day_ticket extends Model
{
    protected $guarded=[];
    use SoftDeletes;

    public function tickets()
    {
        return $this->belongsTo('App\Models\Ticket','ticket_id','id');
    }
}
