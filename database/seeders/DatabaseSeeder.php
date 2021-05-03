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
        $user = new User();

        $existeUserPrincipal = $user->where('email', env("LOGIN_EMAIL"))->first();

        if (!$existeUserPrincipal) {

            $user->create([
                'email' => 'henzo.gomes@gmail.com',
                'name' => 'Henzo Gomes',
                'cpf' => '123.456.789-09',
                'data_nascimento' => '2000-01-01',
                'email_verified_at' => now(),
                'password' => Hash::make('password')
            ]);
        }
        else {
            \App\Models\User::factory(1)->create();
        }
    }
}
