<?php

use Illuminate\Database\Seeder;

class TypeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
                      ['mat'=> 'TYU','name'=> 'Administrador Dueño','active'=> 1,],
                      ['mat'=> 'TYU','name'=> 'Administrador','active'=> 1,],
                      ['mat'=> 'TYU','name'=> 'Vendedor','active'=> 1,],
                      ['mat'=> 'TYU','name'=> 'Usuario','active'=> 1,],             ];

        foreach($types as $type){
                DB::table('type_users')->insert($type);
            }
            $tu_prof = [
                //Admin
                  ['type_user_id'=> 1,'data_menu_id'=> 1,'active'=> 1,],
                  ['type_user_id'=> 1,'data_menu_id'=> 2,'active'=> 1,],
                  ['type_user_id'=> 1,'data_menu_id'=> 3,'active'=> 1,],
                  ['type_user_id'=> 1,'data_menu_id'=> 4,'active'=> 1,],
                  ['type_user_id'=> 1,'data_menu_id'=> 5,'active'=> 1,],
                  ['type_user_id'=> 1,'data_menu_id'=> 6,'active'=> 1,],
                  ['type_user_id'=> 1,'data_menu_id'=> 7,'active'=> 1,],
                  ['type_user_id'=> 1,'data_menu_id'=> 8,'active'=> 1,],

                //Vendedor
                  ['type_user_id'=> 2,'data_menu_id'=> 1,'active'=> 1,],
                  ['type_user_id'=> 2,'data_menu_id'=> 2,'active'=> 1,],
                  ['type_user_id'=> 2,'data_menu_id'=> 3,'active'=> 1,],
                  ['type_user_id'=> 2,'data_menu_id'=> 4,'active'=> 1,],
                  ['type_user_id'=> 2,'data_menu_id'=> 5,'active'=> 1,],
                  ['type_user_id'=> 2,'data_menu_id'=> 6,'active'=> 1,],
                  ['type_user_id'=> 2,'data_menu_id'=> 7,'active'=> 1,],
                  ['type_user_id'=> 2,'data_menu_id'=> 8,'active'=> 1,],
      
      
                //Vendedor
                  ['type_user_id'=> 3,'data_menu_id'=> 1,'active'=> 1,],
                  ['type_user_id'=> 3,'data_menu_id'=> 8,'active'=> 1,],
         
      
                ];
      
            foreach($tu_prof as $tu_prof){
              DB::table('type_user_details')->insert($tu_prof);
            }
    }
}
