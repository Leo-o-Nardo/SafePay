<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessTopupJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1|max:100000', // Mínimo R$ 1,00
        ]);

        $user = Auth::user() ?? \App\Models\User::first();

        ProcessTopupJob::dispatch($user->id, $request->amount)->onQueue('wallet_topups');

        return response()->json([
            'message' => 'Solicitação de depósito recebida!',
            'status' => 'processing'
        ]);
    }

    public function getBalance()
    {
         $user = Auth::user() ?? \App\Models\User::first();
         return response()->json($user->wallet);
    }
}
