<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        $this->call(DataMenuSeeder::class);
        $this->call(TypeUserSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(GameSeeder::class);
    }
}
