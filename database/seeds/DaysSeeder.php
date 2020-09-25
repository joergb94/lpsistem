<?php

use Illuminate\Database\Seeder;

class DaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
            $days = [
                ['name'=> 'Domingo','value'=>6,'carbon'=>0],
                ['name'=> 'Lunes','value'=>0,'carbon'=>1],
                ['name'=> 'Martes','value'=>1,'carbon'=>2],
                ['name'=> 'Miercoles','value'=>2,'carbon'=>3],
                ['name'=> 'Jueves','value'=>3,'carbon'=>4],
                ['name'=> 'Viernes','value'=>4,'carbon'=>5],
                ['name'=> 'Sabado','value'=>5,'carbon'=>6],
            
        ];
        
        foreach($days as $day){
                DB::table('days')->insert($day);
                            }
        }
    
}
