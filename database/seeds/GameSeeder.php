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
            ['name'=> 'Tris extra','time_end'=>'16:30:00'],
            ['name'=> 'Tris clásico','time_end'=>'20:00:00'],
            ['name'=> 'Lotería Nacional','time_end'=>'19:30:00'],
            ['name'=> 'Lotería Nacional 2do','time_end'=>'19:30:00'],
            ['name'=> 'Lotería Nacional Gallo','time_end'=>'19:30:00'],
      ];

      $gamesD = [
                    ['game_id'=> 1,'figures'=>3,'prize'=>'500'],
                    ['game_id'=> 1,'figures'=>2,'prize'=>'50'],
                    ['game_id'=> 1,'figures'=>2,'prize'=>'5'],
                    ['game_id'=> 2,'figures'=>3,'prize'=>'500'],
                    ['game_id'=> 2,'figures'=>2,'prize'=>'50'],
                    ['game_id'=> 2,'figures'=>2,'prize'=>'5'],
      
                ];

      
        foreach($games as $game){
            DB::table('games')->insert($game);
                        }

        foreach($gamesD as $gameD){
            DB::table('game_details')->insert($gameD);
                        }
    }
    
}
