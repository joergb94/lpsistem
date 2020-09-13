<?php

use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $games = [
            ['name'=> 'Tris extra'],
            ['name'=> 'Tris clásico'],
            ['name'=> 'Lotería Nacional'],
            ['name'=> 'Lotería Nacional 2do'],
            ['name'=> 'Lotería Nacional Gallo'],
      ];

      
      foreach($games as $game){
            DB::table('games')->insert($game);
                        }
    }
    
}
