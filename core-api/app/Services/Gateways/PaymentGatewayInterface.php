<?php

namespace App\Services\Gateways;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    public function charge(Payment $payment): bool;
}
