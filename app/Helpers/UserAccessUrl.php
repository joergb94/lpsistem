<?php

use App\Models\Data_menu;
use App\Models\Type_user_detail;
use App\Models\Coin_purse;

if (!function_exists('accesUrl')) {
 
   
    function accesUrl($user,$menu_id)
    {
   
        //validate access of user     
        $access=Type_user_detail::where('type_user_id',$user->type_user_id)
                                ->where('data_menu_id',$menu_id)
                                ->where('active',1)
                                ->exists();
        if($access == true){
          //get all data for user menu
          $menuU = Type_user_detail::where('type_user_id',  $user->type_user_id);

          $coins = (Coin_purse::where('user_id',$user->id)->exists())?Coin_purse::where('user_id',$user->id)->first():0;

          $menuU->with(['data_menu' => function($query) {
              $query->orderby('prioridad');
            }]);

          $data_menu=$menuU->get();
          $type_user = $user->type_user_id;
        
        }else{
          
           $data_menu=[];
           $type_user =0;
           $coins =0;
        }
       

        $menu=[
          'data_menu'=>$data_menu,
          'access'=>$access,
          'type_user'=>$type_user,
          'coins'=>$coins,
        ];  
   
      return $menu;
    }
}
