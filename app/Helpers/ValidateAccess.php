<?php

use App\Models\Data_menu;
use App\Models\Type_user_detail;

if (!function_exists('validateAccess')) {
 
   
    function validateAccess($user,$menu_id)
    {
        if($user && $menu_id){
            //validate access of user     
            $access=Type_user_detail::where('type_user_id',$user->type_user_id)
                                    ->where('data_menu_id',$menu_id)
                                    ->where('active',1)
                                    ->exists();
        }else{

            $access=false;
        }

      return $access;
    }
}
