<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
        ]);

        $wallet = Wallet::create([
            'user_id' => auth()->id(),
            'account_id' => $request->account_id,
            'type' => $request->type,
            'balance' => $request->balance,
        ]);

        return response()->json($wallet, 201);
    }
}