<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');

            // Tipos: 'deposit', 'payment', 'cashback_in', 'cashback_out'
            $table->string('type');

            // Sempre positivo no banco, o 'type' define se soma ou subtrai
            $table->decimal('amount', 15, 2);

            // Rastrear qual pagamento gerou essa movimentação (pode ser null se for depósito)
            $table->string('reference_id')->nullable();

            // Descrição para exibir na tela ("Compra no Cartão", "Depósito via PIX")
            $table->string('description')->nullable();

            $table->timestamps();
            $table->index(['wallet_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
