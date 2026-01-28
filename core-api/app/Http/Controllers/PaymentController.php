<?php

namespace App\Http\Controllers;

use App\DTO\PaymentDTO;
use App\Services\PaymentProducerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentProducerService $producer
    ) {}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'method' => 'required|string',
            'user_id' => 'required|string'
        ]);

        $dto = new PaymentDTO(
            userId: $validated['user_id'],
            orderId: (string) Str::uuid(),
            amount: (float) $validated['amount'],
            currency: $validated['currency'],
            method: $validated['method']
        );

        $this->producer->process($dto);

        return response()->json([
            'message' => 'Pagamento recebido e em processamento.',
            'order_id' => $dto->orderId,
            'status' => 'queued'
        ], 202);
    }
}
