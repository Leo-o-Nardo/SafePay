<?php

namespace App\DTO;

readonly class PaymentDTO
{
    public function __construct(
        public string $userId,
        public string $orderId,
        public float $amount,
        public string $currency,
        public string $method, // ex: 'credit_card', 'pix'
        public ?bool $forceFail = false
    ) {}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'order_id' => $this->orderId,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'method' => $this->method,
            'timestamp' => now()->toIso8601String(),
            'force_fail' => $this->forceFail,
        ];
    }
}
