<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    const TYPE_DEPOSIT = 'deposit';           // Entrada (Add Saldo)
    const TYPE_PAYMENT = 'payment';           // Saída (Pagamento com Saldo)
    const TYPE_CASHBACK_IN = 'cashback_in';   // Entrada (Ganhou Cashback)
    const TYPE_CASHBACK_OUT = 'cashback_out'; // Saída (Pagou usando Cashback)
    const TYPE_CASHBACK_REDEEM = 'cashback_redeem';

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'reference_id',
        'description'
    ];
}
