<?php

namespace App\Services;

use App\Jobs\ProcessPaymentJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class QueueMaintenanceService
{
    public function replayDlq(): int
    {
        $connection = Queue::connection('rabbitmq');
        $count = 0;

        while (true) {
            $job = $connection->pop('payments_dlq');

            if (!$job) {
                break; // Fila vazia, acabou
            }

            try {
                $payload = $job->payload();
                $command = unserialize($payload['data']['command']);

                if (isset($command->paymentData['force_fail'])) {
                    $command->paymentData['force_fail'] = false; // Remove a flag para forçar a falha
                }

                ProcessPaymentJob::dispatch($command->paymentData)->onQueue('payments_processing');

                $job->delete();
                $count++;
            } catch (\Exception $e) {
                Log::error("Erro ao reprocessar item da DLQ: " . $e->getMessage());
                $job->release();
                break;
            }
        }

        return $count;
    }
}
