<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessTopupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $onQueue = 'wallet_topups';

    public function __construct(
        protected int $userId,
        protected float $amount
    ) {}

    public function handle(): void
    {
        Log::info("Iniciando Top-up de R$ {$this->amount} para o User {$this->userId}");

        DB::transaction(function () {
            $user = User::findOrFail($this->userId);

            $user->wallet()->increment('balance', $this->amount);

            $user->wallet->transactions()->create([
                'type' => WalletTransaction::TYPE_DEPOSIT,
                'amount' => $this->amount,
                'description' => 'Depósito via PIX (Simulado)',
                'reference_id' => 'TOPUP-' . uniqid() // Simulando um ID externo
            ]);
        });

        Log::info("Top-up concluído com sucesso!");

        \App\Events\WalletUpdated::dispatch($this->userId);
    }
}
