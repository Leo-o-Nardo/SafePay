<?php

namespace App\Services\Gateways;

use App\Models\Payment;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletGateway implements PaymentGatewayInterface
{
    public function charge(Payment $payment): bool
    {
        Log::info("WalletGateway: Tentando pagar Pedido {$payment->id} com Saldo...");

        return DB::transaction(function () use ($payment) {
            $wallet = $payment->user->wallet()->lockForUpdate()->first();

            if (!$wallet) {
                Log::error("Carteira não encontrada para User {$payment->user_id}");
                return false;
            }

            if ($wallet->balance < $payment->amount) {
                Log::warning("Saldo insuficiente: Tem {$wallet->balance}, precisa de {$payment->amount}");
                return false;
            }

            $wallet->decrement('balance', $payment->amount);

            $wallet->transactions()->create([
                'type' => WalletTransaction::TYPE_PAYMENT,
                'amount' => -$payment->amount,
                'reference_id' => $payment->id,
                'description' => "Pagamento Pedido #{$payment->id}"
            ]);

            Log::info("Pagamento com Wallet aprovado!");
            return true;
        });
    }
}
