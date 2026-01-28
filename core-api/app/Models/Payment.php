<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'user_id', 'amount', 'currency', 'method', 'status', 'transaction_id', 'gateway_error'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
