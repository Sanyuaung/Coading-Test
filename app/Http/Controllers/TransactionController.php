<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function transfer(Request $request)
    {
        // return $request;
        $request->validate([
            'sender_wallet_id' => 'required|exists:wallets,id',
            'receiver_wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $senderWallet = Wallet::find($request->sender_wallet_id);
        $receiverWallet = Wallet::find($request->receiver_wallet_id);

        if ($senderWallet->balance < $request->amount) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        $senderWallet->balance -= $request->amount;
        $receiverWallet->balance += $request->amount;

        $senderWallet->save();
        $receiverWallet->save();

        $transaction = Transaction::create([
            'sender_wallet_id' => $senderWallet->id,
            'receiver_wallet_id' => $receiverWallet->id,
            'amount' => $request->amount,
        ]);

        return response()->json($transaction, 201);
    }

    public function history($id)
    {
        $wallet = Wallet::findOrFail($id);
        $transactions = $wallet->sentTransactions()->with('receiverWallet')->get();

        return response()->json($transactions);
    }

}