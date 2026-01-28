<?php

namespace App\Services;

use App\Services\Gateways\CreditCardGateway;
use App\Services\Gateways\PaymentGatewayInterface;
use App\Services\Gateways\PixGateway;
use App\Services\Gateways\WalletGateway;
use Exception;

class PaymentGatewayFactory
{
    public static function make(string $method): PaymentGatewayInterface
    {
        return match ($method) {
            'pix' => new PixGateway(),
            'credit_card' => new CreditCardGateway(),
            'wallet' => new WalletGateway(),
            default => throw new Exception("Método de pagamento não suportado: {$method}"),
        };
    }
}
