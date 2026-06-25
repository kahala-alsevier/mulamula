<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createManualTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $transaction = Transaction::create([
                'user_id' => null,
                'invoice_number' => 'INV-MANUAL-' . time(),
                'type' => $data['type'],
                'source' => 'manual',
                'total_amount' => $data['total_amount'],
                'status' => 'completed',
                'notes' => $data['notes'] ?? null,
            ]);

            if ($data['type'] === 'in' && isset($data['items'])) {
                foreach ($data['items'] as $item) {
                    $transaction->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);

                    $product = Product::find($item['product_id']);
                    if ($product) {
                        $product->decrement('stock', $item['quantity']);
                    }
                }
            }

            return $transaction;
        });
    }
}