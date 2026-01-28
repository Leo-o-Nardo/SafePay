<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'teste@safepay.com'],
            [
                'name' => 'Safepay Tester',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        Wallet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance' => 0,
                'cashback_balance' => 0
            ]
        );

        $this->command->info("Usuário criado: teste@safepay.com / password");
        $this->command->info("Carteira inicializada com R$ 0,00");
    }
}
