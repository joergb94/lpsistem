<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Model
{
    protected $guarded=[];
    use SoftDeletes;
    
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
