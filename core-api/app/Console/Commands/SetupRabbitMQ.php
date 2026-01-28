<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
use PhpAmqpLib\Wire\AMQPTable;

class SetupRabbitMQ extends Command
{
    protected $signature = 'rabbitmq:setup';
    protected $description = 'Cria as filas e configura Dead Letter Queues (DLQ) no RabbitMQ';

    public function handle()
    {
        $this->info('Iniciando configuração do RabbitMQ...');

        $connection = Queue::connection('rabbitmq');
        $channel = $connection->getChannel();

        $dlqName = 'payments_dlq';
        $this->info("Declarando fila DLQ: {$dlqName}");

        $channel->queue_declare(
            queue: $dlqName,
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false
        );

        $queueName = 'payments_processing';
        $this->info("Declarando fila Principal: {$queueName}");

        $args = new AMQPTable([
            'x-dead-letter-exchange' => '',
            'x-dead-letter-routing-key' => $dlqName
        ]);

        $channel->queue_declare(
            queue: $queueName,
            passive: false,
            durable: true,
            exclusive: false,
            auto_delete: false,
            nowait: false,
            arguments: $args
        );

        $channel->basic_qos(null, 1, null);

        $this->info('Configuração concluída com sucesso.');
    }
}
