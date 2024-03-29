<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = [
            ['type_user_id' => 1,
            'name'=> 'LP',
            'last_name'=>'AdminDueño',
            'email'=>'admin@LP.com',
            'password'=> bcrypt('admin'),
            'percentage'=>100,],

            ['type_user_id' => 2,
            'name'=> 'LP',
            'last_name'=>'Admin',
            'email'=>'adminB@LP.com',
            'percentage'=>40,
            'password'=> bcrypt('example'),],

            ['type_user_id' => 3,
            'name'=> 'LP',
            'last_name'=>'vendendor',
            'email'=>'vendendor@LP.com',
            'percentage'=>25,
            'password'=> bcrypt('example'),],

            ['type_user_id' => 4,
            'name'=> 'LP',
            'last_name'=>'Corredor',
            'email'=>'Corredor@LP.com',
            'percentage'=>25,
            'password'=> bcrypt('example'),],

            ['type_user_id' =>5,
            'name'=> 'LP',
            'last_name'=>'usuario',
            'email'=>'usuario@LP.com',
            'password'=> bcrypt('example'),],
        ];
      
      foreach($adminUser as $admin)
      {
            DB::table('users')->insert($admin);
        }

        DB::table('coin_purses')->insert(['user_id' => 5,'coins' => 1000]);
    }
}
