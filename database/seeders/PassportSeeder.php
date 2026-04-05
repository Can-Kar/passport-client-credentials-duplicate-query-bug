<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Laravel\Passport\Passport;

class PassportSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Passport::client()->forceFill([
            'name' => 'ClientCredentials Grant Client',
            'secret' => bcrypt('client-credentials-secret'),
            'redirect_uris' => [],
            'grant_types' => ['client_credentials'],
            'revoked' => false,
        ])->save();
    }
}
