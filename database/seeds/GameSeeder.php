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


        Schema::disableForeignKeyConstraints();
        DB::table('game_details')->truncate();
        DB::table('games')->truncate();
        Schema::enableForeignKeyConstraints();
        
        $games = [
            ['name'=> 'Tris extra','time_end'=>'16:31:00'],
            ['name'=> 'Tris clásico','time_end'=>'20:21:00'],
            ['name'=> 'Lotería Nacional','time_end'=>'19:31:00'],
            ['name'=> 'Lotería Nacional 2do','time_end'=>'19:31:00'],
            ['name'=> 'Lotería Nacional Gallo','time_end'=>'19:31:00'],
            ['name'=> 'Tris de las 12','time_end'=>'11:31:00'],
            ['name'=> 'Tris de las 3','time_end'=>'14:31:00'],
            ['name'=> 'Tris de las 7','time_end'=>'18:31:00'],
      ];

      $gamesD = [
                    ['game_id'=> 1,'figures'=>3,'prize'=>'500'],
                    ['game_id'=> 1,'figures'=>2,'prize'=>'50'],
                    ['game_id'=> 1,'figures'=>1,'prize'=>'5'],
                    ['game_id'=> 2,'figures'=>3,'prize'=>'500'],
                    ['game_id'=> 2,'figures'=>2,'prize'=>'50'],
                    ['game_id'=> 2,'figures'=>1,'prize'=>'5'],
                    ['game_id'=> 3,'figures'=>3,'prize'=>'500'],
                    ['game_id'=> 3,'figures'=>2,'prize'=>'50'],
                    ['game_id'=> 3,'figures'=>1,'prize'=>'5'],
                    ['game_id'=> 4,'figures'=>3,'prize'=>'500'],
                    ['game_id'=> 4,'figures'=>2,'prize'=>'50'],
                    ['game_id'=> 4,'figures'=>1,'prize'=>'5'],
                    ['game_id'=> 5,'figures'=>1,'prize'=>'400'],
                    ['game_id'=> 5,'figures'=>2,'prize'=>'100'],
                    ['game_id'=> 6,'figures'=>3,'prize'=>'500'],
                    ['game_id'=> 6,'figures'=>2,'prize'=>'50'],
                    ['game_id'=> 6,'figures'=>1,'prize'=>'5'],
                    ['game_id'=> 7,'figures'=>3,'prize'=>'500'],
                    ['game_id'=> 7,'figures'=>2,'prize'=>'50'],
                    ['game_id'=> 7,'figures'=>1,'prize'=>'5'],
                    ['game_id'=> 8,'figures'=>3,'prize'=>'500'],
                    ['game_id'=> 8,'figures'=>2,'prize'=>'50'],
                    ['game_id'=> 8,'figures'=>1,'prize'=>'5'],
      
                ];

      
        foreach($games as $game){
            DB::table('games')->insert($game);
                        }

        foreach($gamesD as $gameD){
            DB::table('game_details')->insert($gameD);
                        }
    }
    
}
