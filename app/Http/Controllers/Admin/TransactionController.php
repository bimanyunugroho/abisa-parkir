<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Transaction/Parking/Index', [
            'title' => 'Transaksi',
            'desc'  => 'Transaksi parkir'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Transaction/Parking/Add', [
            'title' => 'Transaksi',
            'desc'  => 'Tambah transaksi parkir'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $transaksi = Transaction::create($request->validated());

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dilakukan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return Inertia::render('Transaction/Parking/Show', [
            'title' => 'Transaksi',
            'desc'  => 'Detail transaksi',
            'transaction' => $transaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        return Inertia::render('Transaction/Parking/Edit', [
            'title' => 'Transaksi',
            'desc'  => 'Ubah transaksi',
            'transaction'   => $transaction
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus!');
    }
}
