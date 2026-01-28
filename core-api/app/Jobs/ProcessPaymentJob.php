<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\PaymentGatewayFactory;
use Illuminate\Support\Facades\Cache;
use App\Events\PaymentUpdated;
use App\Models\WalletTransaction;
use App\Events\WalletUpdated;

class ProcessPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [2, 10, 30];

    public function __construct(
        public array $paymentData
    ) {}

    public function handle(): void
    {
        $orderId = $this->paymentData['id'];
        Log::info("Worker: Iniciando pedido {$orderId}");

        if (isset($this->paymentData['force_fail']) && $this->paymentData['force_fail']) {
            Log::error("Worker: Erro forçado via Dashboard para {$orderId}!");
            throw new \Exception("Simulação de Falha Crítica (Dashboard)");
        }

        $payment = Payment::find($orderId);
        if ($payment && $payment->status !== 'PENDING') {
            Log::warning("Worker: Pedido {$orderId} já foi processado anteriormente.");
            return;
        }

        $payment = Payment::firstOrCreate(
            ['id' => $orderId],
            [
                'user_id' => $this->paymentData['user_id'],
                'amount' => $this->paymentData['amount'],
                'currency' => $this->paymentData['currency'],
                'method' => $this->paymentData['method'],
                'status' => 'PENDING'
            ]
        );

        if ($payment->status === 'PAID') {
            return;
        }

        try {
            $gateway = PaymentGatewayFactory::make($payment->method);
            $isApproved = $gateway->charge($payment);

            if ($isApproved) {
                $payment->update(['status' => 'PAID', 'transaction_id' => 'tx_' . bin2hex(random_bytes(10))]);
                Log::info("Worker: Pagamento {$orderId} APROVADO via {$payment->method}.");

                if ($payment->method == 'pix') {
                    $cashbackAmount = $payment->amount * 0.05;
                    $payment->user->wallet()->increment('cashback_balance', $cashbackAmount);

                    $payment->user->wallet->transactions()->create([
                        'type' => 'cashback_in',
                        'amount' => $cashbackAmount,
                        'reference_id' => $payment->id,
                        'description' => 'Cashback (1%)'
                    ]);

                    Log::info("Cashback de R$ {$cashbackAmount} gerado para {$orderId}");
                }
            } else {
                $payment->update(['status' => 'FAILED']);
                Log::warning("Worker: Pagamento {$orderId} RECUSADO via {$payment->method}.");
            }

            PaymentUpdated::dispatch($payment);
            WalletUpdated::dispatch($payment->user_id);

        } catch (\Exception $e) {
            Log::error("Worker: Erro no processamento: " . $e->getMessage());
            // Lança a exceção para o RabbitMQ fazer o retry/DLQ
            throw $e;
        }
    }
}
