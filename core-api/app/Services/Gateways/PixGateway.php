<?php

namespace App\Services\Gateways;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PixGateway implements PaymentGatewayInterface
{
    public function charge(Payment $payment): bool
    {
        Log::info("Pagamento PIX: Gerando QRCode para {$payment->id}");

        sleep(rand(1, 10));
        // Teste para Pix ser aprovado 90% das vezes
        return rand(1, 100) <= 90;
    }
}
