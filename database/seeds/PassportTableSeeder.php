<?php

use Illuminate\Database\Seeder;

class PassportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \Laravel\Passport\Client::create([
            'name' => 'Laravel ClientCredentials Grant Client',
            'secret' => '44mOB9p7eCHggYNlvPxuF7RnLV1TnNx6ee2Qn1IH',
            'redirect' => '',
            'personal_access_client' => 0,
            'password_client' => 0,
            'revoked' => 0,
        ]);
    }
}
