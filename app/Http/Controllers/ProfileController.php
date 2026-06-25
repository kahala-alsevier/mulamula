<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $transactions = \App\Models\Transaction::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('profile.index', compact('user', 'transactions'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $user->update([
            'name' => $request->name,
            'whatsapp_number' => $request->whatsapp_number,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
