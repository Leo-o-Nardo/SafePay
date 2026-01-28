<?php

namespace App\Console\Commands;

use App\Events\QueueStatsPulse;
use App\Services\RabbitMQMonitorService;
use Illuminate\Console\Command;

class MonitorStream extends Command
{
    protected $signature = 'monitor:stream';
    protected $description = 'Lê o RabbitMQ e transmite via Reverb em tempo real';

    public function handle()
    {
        $this->info("Iniciando Streaming de Monitoramento...");

        $service = new RabbitMQMonitorService();

        while (true) {
            try {
                // 1. Busca dados (Main e DLQ)
                $main = $service->getQueueStats('payments_processing');
                $dlq = $service->getQueueStats('payments_dlq');

                // 2. Transmite via WebSocket
                QueueStatsPulse::dispatch($main, $dlq);

                // 3. Aguarda 1seg
                usleep(1000000);

            } catch (\Exception $e) {
                $this->error("Erro no stream: " . $e->getMessage());
                sleep(2);
            }
        }
    }
}
