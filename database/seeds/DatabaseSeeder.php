<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            PersonsTableSeeder::class,
            OrdersTableSeeder::class,
            PassportTableSeeder::class,
        ]);
    }
}
