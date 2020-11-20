<?php

use Illuminate\Database\Seeder;

class DataMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menus = [
            ['name'=> 'Inicio','icon'=> 'ti-home','link'=>'/home','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Usuarios','icon'=> 'ti-user','link'=>'/users','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Tickets Vendidos','icon'=> 'ti-receipt','link'=>'/tickets','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Ganadores','icon'=> 'ti-star','link'=>'/winners','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Depositos','icon'=> 'ti-money','link'=>'/deposits','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Calendario de Juegos','icon'=> 'ti-calendar','link'=>'/schedule','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Reportes','icon'=> 'ti-bookmark-alt','link'=>'/reports','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Mis Tickets','icon'=> 'ti-notepad','link'=>'/my-tickets','prioridad'=> '3','active'=> 1,],
      ];
      
      foreach($menus as $menu){
            DB::table('data_menus')->insert($menu);
                        }
    }
}
