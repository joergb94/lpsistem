<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type_user_detail extends Model
{
    protected $guarded=[];
    use SoftDeletes;

    public function type_users()
    {
        return $this->belongsTo('App\Models\Type_user','type_user_id','id');
    }

    public function data_menu()
    {
        return $this->belongsTo('App\Models\Data_menu','data_menu_id','id');
    }


   /**
     * @return bool
     */
    public function isActive()
    {
        return $this->status;
    }

    /**
     * @param $query
     * @param bool $status
     *
     * @return mixed
     */
    public function scopeActive($query, $status = true)
    {
        return $query->where('active', $status);
    }
}
