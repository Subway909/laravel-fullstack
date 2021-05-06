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
                'email' => env("LOGIN_EMAIL"),
                'name' => 'Usuário Teste',
                'cpf' => '422.150.530-38',
                'data_nascimento' => '2000-01-01',
                'email_verified_at' => now(),
                'password' => Hash::make(env("LOGIN_PASS"))
            ]);
        }
        else {
            \App\Models\User::factory(1)->create();
        }
    }
}
