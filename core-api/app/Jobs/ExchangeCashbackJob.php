<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Events\WalletUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExchangeCashbackJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $onQueue = 'wallet_topups';

    public function __construct(protected int $userId) {}

    public function handle(): void
    {
        Log::info("Iniciando resgate de cashback para User {$this->userId}");

        DB::transaction(function () {
            // Lock for Update para garantir que o saldo não mude durante o processo
            $wallet = User::findOrFail($this->userId)->wallet()->lockForUpdate()->first();

            $amount = $wallet->cashback_balance;

            if ($amount <= 0) {
                Log::warning("Sem cashback para resgatar.");
                return;
            }

            $wallet->balance += $amount;
            $wallet->cashback_balance = 0;
            $wallet->save();

            $wallet->transactions()->create([
                'type' => WalletTransaction::TYPE_CASHBACK_REDEEM,
                'amount' => $amount,
                'description' => 'Resgate de Cashback',
                'reference_id' => 'REDEEM-' . uniqid()
            ]);

            Log::info("Resgate de R$ {$amount} realizado com sucesso.");
        });

        WalletUpdated::dispatch($this->userId)->onQueue('wallet_topups');
    }
}
