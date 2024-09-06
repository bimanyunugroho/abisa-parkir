<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ApiParkirController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'no_ticket' => 'nullable|string',
            'license_plate' => 'nullable|string',
        ]);
    
        $query = Transaction::query();
    
        if ($request->has('no_ticket')) {
            $query->where('no_ticket', $request->input('no_ticket'));
        }
    
        if ($request->has('license_plate')) {
            $query->where('license_plate', $request->input('license_plate'));
        }
    
        $transactions = $query->with(['parkingArea', 'parkingRate.vehicle', 'user'])->get();
    
        if ($transactions->isEmpty()) {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }
    
        $activeTransactions = $transactions->where('status', 'ACTIVE');
        $completedTransactions = $transactions->where('status', 'COMPLETE');
    
        if ($activeTransactions->isNotEmpty()) {
            return TransactionResource::collection($activeTransactions);
        }
    
        if ($completedTransactions->isNotEmpty()) {
            return response()->json([
                'message' => 'Transaksi ini sudah selesai'
            ], 404);
        }
    
        return response()->json([
            'message' => 'Transaksi tidak ditemukan'
        ], 404);
    }
}
