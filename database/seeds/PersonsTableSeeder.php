<?php

use Illuminate\Database\Seeder;

class PersonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Person::class, 100)->create()->each(function ($person) {
            factory(\App\Phone::class, rand(1, 3))->create(['person_id' => $person->id]);
        });
    }
}
