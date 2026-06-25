<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $subtotal = 0;
        $totalGreetingCardFee = 0;
        $recipientName = null;
        $greetingCardTexts = [];

        foreach ($cart as $id => $item) {
            $cardFee = !empty($item['has_card']) ? 15000 : 0;
            $subtotal += ($item['price'] * $item['quantity']) + $cardFee;

            if (!empty($item['has_card'])) {
                $totalGreetingCardFee += 15000;
                if (!$recipientName) {
                    $recipientName = $item['recipient_name'] ?? null;
                }
                if (!empty($item['greeting_text'])) {
                    $greetingCardTexts[] = $item['name'] . ": " . $item['greeting_text'];
                }
            }
        }

        $finalGreetingText = !empty($greetingCardTexts) ? implode(" | ", $greetingCardTexts) : null;

        $invoiceNumber = 'MULA-' . time() . '-' . Str::upper(Str::random(4));
        $user = auth()->user();

        $payload = [
            'transaction_details' => [
                'order_id' => $invoiceNumber,
                'gross_amount' => $subtotal,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->whatsapp_number ?? '08123456789',
            ],
            'item_details' => collect($cart)->map(function ($item, $id) {
                return [
                    'id' => $id,
                    'price' => (int)$item['price'],
                    'quantity' => (int)$item['quantity'],
                    'name' => Str::limit($item['name'], 45),
                ];
            })->values()->toArray()
        ];

        if ($totalGreetingCardFee > 0) {
            $payload['item_details'][] = [
                'id' => 'CARD-PREMIUM',
                'price' => $totalGreetingCardFee,
                'quantity' => 1,
                'name' => 'Kartu Ucapan Premium MulaMula',
            ];
        }

        $secretKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        $base64Key = base64_encode($secretKey . ':');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $base64Key,
        ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $payload);

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Gagal terhubung ke server Midtrans. Coba lagi.');
        }

        $snapToken = $response->json()['token'];
        $redirectUrl = $response->json()['redirect_url'];

        DB::transaction(function () use ($user, $invoiceNumber, $subtotal, $snapToken, $recipientName, $finalGreetingText, $totalGreetingCardFee, $cart) {

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'buyer_name' => $user->name,
                'buyer_phone' => $user->whatsapp_number,
                'invoice_number' => $invoiceNumber,
                'type' => 'in',
                'source' => 'midtrans',
                'total_amount' => $subtotal,
                'status' => 'pending',
                'midtrans_snap_token' => $snapToken,
                'recipient_name' => $recipientName,
                'greeting_card_text' => $finalGreetingText,
                'greeting_card_fee' => $totalGreetingCardFee,
                'shipping_address' => 'Self-Pickup (Ambil di Toko)',
                'notes' => 'Pesanan Web'
            ]);

            foreach ($cart as $productId => $item) {
                DB::table('transaction_items')->insert([
                    'transaction_id' => $transaction->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return response()->json([
            'snap_token' => $snapToken
        ]);
    }

    public function finish(Request $request)
    {
        session()->forget('cart');

        return view('cart.success', [
            'order_id' => $request->order_id,
            'status' => $request->transaction_status
        ]);
    }

    public function callback(Request $request)
    {
        $json = json_decode($request->getContent(), true);

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $signatureKey = hash("sha512", $json['order_id'] . $json['status_code'] . $json['gross_amount'] . $serverKey);

        if ($signatureKey !== $json['signature_key']) {
            return response()->json(['message' => 'Tanda tangan tidak valid'], 400);
        }

        $transaction = Transaction::where('invoice_number', $json['order_id'])->first();
        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 444);
        }

        $status = $json['transaction_status'];

        if ($status == 'settlement' || $status == 'capture') {
            if ($transaction->status !== 'completed') {
                $transaction->update(['status' => 'completed']);

                $items = DB::table('transaction_items')->where('transaction_id', $transaction->id)->get();

                foreach ($items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->decrement('stock', $item->quantity);
                    }
                }
            }
        } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
            $transaction->update(['status' => 'failed']);
        }

        return response()->json(['message' => 'Callback Berhasil Diproses']);
    }
}
