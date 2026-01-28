<?php

namespace App\Livewire;

use App\Models\Payment;
use App\Models\User;
use App\Services\RabbitMQMonitorService;
use App\Jobs\ProcessTopupJob;
use App\Jobs\ProcessPaymentJob;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\QueueMaintenanceService;
use App\Jobs\ExchangeCashbackJob;

class DashboardMonitor extends Component
{
    public $payments = [];
    public $stats = [];
    public $rabbitStats = [];
    public $dlqStats = [];

    // --- 2. PROPRIEDADES DO USUÁRIO (WALLET) ---
    public $balance = 0.0;
    public $cashback = 0.0;
    public $userName = '';
    public $topupAmount = '';
    public $paymentAmount = '';
    public $paymentMethod = 'credit_card';
    public $forceError = false;

    public function getListeners()
    {
        return [
            'echo:monitor,.server.pulse'   => 'updateQueueStats',
            'echo:payments,.payment.updated' => 'refreshAllData',
            "echo:user." . Auth::id() . ",.wallet.updated" => 'refreshWalletOnly',
        ];
    }

    public function mount()
    {
        if (!Auth::check()) {
            $devUser = User::where('email', 'teste@safepay.com')->first();
            if ($devUser) Auth::login($devUser);
        }

        $monitor = new RabbitMQMonitorService();
        try {
            $this->rabbitStats = $monitor->getQueueStats('payments_processing');
            $this->dlqStats = $monitor->getQueueStats('payments_dlq');
        } catch (\Exception $e) {
            $this->rabbitStats = ['ready' => 0, 'unacked' => 0];
        }

        $this->refreshAllData();
    }

    public function deposit()
    {
        $this->validate(['topupAmount' => 'required|numeric|min:1']);

        ProcessTopupJob::dispatch(Auth::id(), (float) $this->topupAmount)
            ->onQueue('wallet_topups');

        $this->reset('topupAmount');
        session()->flash('success', 'Depósito enviado para processamento!');
    }

    public function makePayment()
    {
        $this->validate([
            'paymentAmount' => 'required|numeric|min:1',
            'paymentMethod' => 'required|in:credit_card,wallet,pix'
        ]);

        if ($this->paymentMethod === 'wallet' && $this->balance < $this->paymentAmount) {
            $this->addError('paymentAmount', 'Saldo insuficiente.');
            return;
        }

        $payment = Payment::create([
            'id' => (string) Str::uuid(),
            'user_id' => Auth::id(),
            'amount' => $this->paymentAmount,
            'currency' => 'BRL',
            'method' => $this->paymentMethod,
            'status' => 'PENDING'
        ]);

        $payload = $payment->toArray();
        if ($this->forceError) {
            $payload['force_fail'] = true;
        }

        ProcessPaymentJob::dispatch($payload)
            ->onQueue('payments_processing');

        $this->reset('paymentAmount');
        session()->flash('success', 'Pagamento enviado!');
    }

    public function refreshAllData()
    {
        $this->refreshWalletOnly();
        $this->userName = Auth::user()->name;

        $dbPayments = Payment::orderBy('created_at', 'desc')->take(20)->get();
        $this->payments = $dbPayments;

        $this->stats = [
            'total' => $dbPayments->count(),
            'paid' => $dbPayments->where('status', 'PAID')->count(),
            'failed' => $dbPayments->where('status', 'FAILED')->count(),
            'pending' => $dbPayments->where('status', 'PENDING')->count(),
        ];
    }

    public function updateQueueStats($event)
    {
        $dbPayments = Payment::orderBy('created_at', 'desc')->take(20)->get();
        $this->payments = $dbPayments;

        $this->stats = [
            'total' => $dbPayments->count(),
            'paid' => $dbPayments->where('status', 'PAID')->count(),
            'failed' => $dbPayments->where('status', 'FAILED')->count(),
            'pending' => $dbPayments->where('status', 'PENDING')->count(),
        ];

        $this->rabbitStats = $event['mainQueue'];
        $this->dlqStats = $event['dlq'];
    }

    public function refreshWalletOnly()
    {
        $user = Auth::user();
        if ($user) {
            $user->wallet->refresh();
            $this->balance = (float) $user->wallet->balance;
            $this->cashback = (float) $user->wallet->cashback_balance;
        }
    }

    public function resetSystem()
    {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh --seed');
        $this->js("window.location.reload()");
        session()->flash('success', 'Sistema resetado com sucesso!');
    }

    public function retryDlq()
    {
        $service = new QueueMaintenanceService();

        try {
            $count = $service->replayDlq();
            $this->refreshAllData();
            if ($count > 0) {
                session()->flash('success', "Sucesso! {$count} mensagens recuperadas e corrigidas.");
            } else {
                session()->flash('warning', "Nenhuma mensagem encontrada na DLQ.");
            }

        } catch (\Exception $e) {
            session()->flash('error', "Erro ao processar DLQ: " . $e->getMessage());
        }
    }

    public function exchangeCashback()
    {
        if ($this->cashback <= 0) {
            return;
        }

        ExchangeCashbackJob::dispatch(Auth::id())->onQueue('wallet_topups');
        session()->flash('success', 'Solicitação de resgate enviada!');
    }

    public function render()
    {
        return view('livewire.dashboard-monitor');
    }
}
