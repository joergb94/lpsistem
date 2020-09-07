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
            ['name'=> 'Inicio','icon'=> 'fas fa-tachometer-alt','link'=>'/home','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Usuarios','icon'=> 'fa fa-users','link'=>'/users','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Categorias','icon'=> 'fa fa-qrcode','link'=>'/categories','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Proveedores','icon'=> 'fa fa-truck','link'=>'/providers','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Inventario','icon'=> 'fa fa-barcode','link'=>'/inventories','prioridad'=> '3','active'=> 1,],
            ['name'=> 'Clientes','icon'=> 'fa fa-user','link'=>'/clients','prioridad'=> '4','active'=> 1,],
            ['name'=> 'Compras','icon'=> 'fa fa-book','link'=>'/purchases','prioridad'=> '4','active'=> 1,],
            ['name'=> 'Ventas','icon'=> 'fa fa-dollar','link'=>'/sales','prioridad'=> '4','active'=> 1,],
      ];
      
      foreach($menus as $menu){
            DB::table('data_menus')->insert($menu);
                        }
    }
}
