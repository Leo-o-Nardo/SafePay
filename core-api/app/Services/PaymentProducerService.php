<?php

namespace App\Services;

use App\DTO\PaymentDTO;
use App\Jobs\ProcessPaymentJob;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;

class PaymentProducerService
{
    public function process(PaymentDTO $dto): void
    {
        Payment::create([
            'id' => $dto->orderId,
            'user_id' => $dto->userId,
            'amount' => $dto->amount,
            'currency' => $dto->currency,
            'method' => $dto->method,
            'status' => 'PENDING',
            'gateway_error' => null
        ]);

        ProcessPaymentJob::dispatch($dto->toArray())
            ->onConnection('rabbitmq')
            ->onQueue('payments_processing');

        Log::info("Enviado para RabbitMQ: Order {$dto->orderId}");

        $this->sendToKafka($dto);
    }

    private function sendToKafka(PaymentDTO $dto): void
    {
        try {
            $message = new Message(
                headers: ['source' => 'api-gateway'],
                body: $dto->toArray(),
                key: $dto->userId
            );

            Kafka::publish()->onTopic('payment-events')->withMessage($message)->send();

            Log::info("Enviado para Kafka: Order {$dto->orderId}");

        } catch (\Exception $e) {
            Log::error("Falha ao enviar para Kafka: " . $e->getMessage());
        }
    }
}
