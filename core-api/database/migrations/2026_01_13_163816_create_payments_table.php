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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id')->index();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('method');

            // Status do pagamento: PENDING, PAID, FAILED, REFUNDED
            $table->string('status')->default('PENDING')->index();

            // Campos de auditoria
            $table->string('transaction_id')->nullable();
            $table->text('gateway_error')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
