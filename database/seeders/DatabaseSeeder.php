<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'firstname' => 'Brian',
            'lastname' => 'Rabin',
            'email' => 'brianrabin@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('1234'),
        ]);

        User::create([
            'firstname' => 'Jake',
            'lastname' => 'Relampagos',
            'email' => 'jakerelampagos@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('1234')
        ]);

        User::create([
            'firstname' => 'Shielo',
            'lastname' => 'Arong',
            'email' => 'sheiloarong@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('1234')
        ]);

        User::create([
            'firstname' => 'Rey Manuel',
            'lastname' => 'Ferolino',
            'email' => 'reymanuelferolino@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('1234')
        ]);

        User::create([
            'firstname' => 'Kemberlie',
            'lastname' => 'Sabellano',
            'email' => 'kemberliesabellano@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Franc',
            'lastname' => 'Sanders',
            'email' => 'qrtabares@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('1234')
        ]);
        User::create([
            'firstname' => 'Test',
            'lastname' => 'User',
            'email' => 'test@elink.com.ph',
            'email_verified_at' => now(),
            'password' => Hash::make('qwe123123')
        ]);
    }
}
