<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('amount');
            $table->string('category')->nullable();
            $table->string('description')->nullable();
            $table->date('transaction_date');
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->index(['type', 'transaction_date']);
        });

        $now = now();

        DB::table('orders')->orderBy('id')->get(['id', 'table_number', 'total_amount', 'created_at'])->each(function ($order) use ($now) {
            DB::table('transactions')->insert([
                'type' => 'income',
                'amount' => $order->total_amount,
                'category' => 'Penjualan',
                'description' => 'Pesanan #'.$order->id.' - Meja '.$order->table_number,
                'transaction_date' => substr($order->created_at, 0, 10),
                'order_id' => $order->id,
                'created_at' => $order->created_at ?? $now,
                'updated_at' => $order->created_at ?? $now,
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
