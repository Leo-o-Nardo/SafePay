<?php

namespace App\Http\Controllers;

use App\DTO\PaymentDTO;
use App\Models\Payment;
use App\Services\PaymentProducerService;
use App\Services\QueueMaintenanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function store(Request $request, PaymentProducerService $service)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'method' => 'required|string',
            'force_error' => 'nullable'
        ]);

        $dto = new PaymentDTO(
            orderId: (string) Str::uuid(),
            userId: 'user_dev',
            amount: (float) $data['amount'],
            currency: 'BRL',
            method: $data['method'],
            forceFail: $request->has('force_error')
        );

        $service->process($dto);

        // Retorna JSON para o JavaScript não recarregar a tela
        return response()->json(['message' => 'Pagamento enviado com sucesso!']);
    }

    public function list()
    {
        $payments = Payment::orderBy('created_at', 'desc')->take(50)->get();

        $stats = [
            'total' => $payments->count(),
            'paid' => $payments->where('status', 'PAID')->count(),
            'failed' => $payments->where('status', 'FAILED')->count(),
            'pending' => $payments->where('status', 'PENDING')->count(),
        ];

        return view('partials.dashboard-content', compact('payments', 'stats'));
    }

    public function reset()
    {
        Payment::truncate(); // Apaga todos os registros da tabela
        return back()->with('success', 'Banco de dados limpo!');
    }

    public function replayDlq(QueueMaintenanceService $service)
    {
        $count = $service->replayDlq();

        if ($count === 0) {
            return back()->with('info', 'A fila DLQ está vazia.');
        }

        return back()->with('success', "{$count} pagamentos reenviados para processamento!");
    }
}
