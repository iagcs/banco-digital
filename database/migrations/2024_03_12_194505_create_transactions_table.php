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
        Schema::create('transactions', static function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('payer_id')->constrained('users');
            $table->foreignUuid('payee_id')->constrained('users');

            $table->float('value');
            $table->enum('status', \Arr::pluck(\App\Enums\TransactionStatus::cases(), 'value'))->default(\App\Enums\TransactionStatus::WAITING->value);

            $table->timestamps(3);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_operations');
    }
};
