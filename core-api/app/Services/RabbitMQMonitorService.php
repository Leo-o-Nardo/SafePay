<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RabbitMQMonitorService
{
    protected string $baseUrl;
    protected string $user;
    protected string $pass;

    public function __construct()
    {
        $host = env('RABBITMQ_HOST', 'safepay_rabbitmq');
        $port = 15672;

        $this->baseUrl = "http://{$host}:{$port}/api";

        $this->user = env('RABBITMQ_USER', 'user');
        $this->pass = env('RABBITMQ_PASSWORD', 'password');
    }

    public function getQueueStats(string $queueName = 'payments_processing'): array
    {
        try {
            // A API requer o VHost. O padrão é "/" que na URL vira "%2F"
            $url = "{$this->baseUrl}/queues/%2F/{$queueName}";

            $response = Http::withBasicAuth($this->user, $this->pass)
                ->timeout(2) // Timeout rápido para não travar o dashboard
                ->get($url);

            if ($response->failed()) {
                return $this->defaultStats();
            }

            $data = $response->json();

            return [
                'ready' => $data['messages_ready'] ?? 0,          // Paradas esperando
                'unacked' => $data['messages_unacknowledged'] ?? 0, // Sendo processadas AGORA
                'total' => $data['messages'] ?? 0,                // Total
                'consumers' => $data['consumers'] ?? 0,           // Quantos workers ativos
                'state' => $data['state'] ?? 'down'               // running/idle
            ];

        } catch (\Exception $e) {
            Log::error("Erro ao conectar no RabbitMQ API: " . $e->getMessage());
            return $this->defaultStats();
        }
    }

    private function defaultStats(): array
    {
        return [
            'ready' => '-', 'unacked' => '-', 'total' => '-',
            'consumers' => '-', 'state' => 'error'
        ];
    }
}
