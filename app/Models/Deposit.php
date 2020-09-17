<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Model
{
    protected $guarded=[];
    use SoftDeletes;
    
    public function sellers()
    {
        return $this->belongsTo('App\Models\User','seller_id','id');
    }
    public function clients()
    {
        return $this->belongsTo('App\Models\Type_user','user_id','id');
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
