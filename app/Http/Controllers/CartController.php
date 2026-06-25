<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image,
                "has_card" => false,
                "recipient_name" => "",
                "greeting_text" => ""
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Bunga berhasil dimasukkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            if($request->has('quantity')) {
                $cart[$id]['quantity'] = max(1, (int)$request->quantity);
            }

            $cart[$id]['has_card'] = $request->has('has_card');
            $cart[$id]['recipient_name'] = $request->recipient_name ?? '';
            $cart[$id]['greeting_text'] = $request->greeting_text ?? '';

            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Keranjang berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}
