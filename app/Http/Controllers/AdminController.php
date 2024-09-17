<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function listUsers()
    {
        $users = User::with('roles', 'wallets')->get();
        return response()->json($users);
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
    public function banUser($id)
    {
        $user = User::findOrFail($id);
        // return $user;
        $user->update(['banned' => 1]); // You can use a 'banned' field in the users table
        return response()->json(['ban user' => $user, 'message' => 'User banned successfully']);
    }
    public function listWallets()
    {
        $wallets = Wallet::with('user')->get();
        return response()->json($wallets);
    }
    public function createWallet(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
        ]);

        $wallet = Wallet::create([
            'user_id' => $request->user_id,
            'account_id' => $request->account_id,
            'type' => $request->type,
            'balance' => $request->balance,
        ]);

        return response()->json($wallet, 201);
    }
    public function deleteWallet($id)
    {
        $wallet = Wallet::findOrFail($id);
        $wallet->delete();
        return response()->json(['message' => 'Wallet deleted successfully']);
    }
    public function allTransactions()
    {
        $transactions = Transaction::with(['senderWallet.user', 'receiverWallet.user'])->get();
        return response()->json($transactions);
    }
    public function dashboard()
    {
        $totalTransactions = Transaction::sum('amount');
        $userCount = User::count();
        $walletCount = Wallet::count();

        return response()->json([
            'total_transactions' => $totalTransactions,
            'total_users' => $userCount,
            'total_wallets' => $walletCount,
        ]);
    }
}