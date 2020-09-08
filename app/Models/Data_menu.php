<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data_menu extends Model
{
    protected $guarded=[];
    
    public function data_menu()
    {
        return $this->hasMany(' App\Models\Type_user_detail','data_menu_id','id');
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
        return $query->where('status', $status);
    }
}
