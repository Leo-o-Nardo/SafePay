<?php

namespace App\Services\Gateways;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function charge(Payment $payment): bool
    {
        Log::info("Pagamento Cartão: Validando limite para {$payment->id}");

        // Simulação: Cartão demora mais e aprova 50% das vezes
        sleep(rand(10, 20));
        return rand(1, 100) <= 50;
    }
}
