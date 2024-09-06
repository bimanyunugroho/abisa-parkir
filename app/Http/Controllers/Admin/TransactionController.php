<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NoTicketHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\ParkingArea;
use App\Models\ParkingRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = Transaction::query()
            ->when($request->input('search'), function ($query, $search) {
                return $query->where('license_plate', 'like', "%{$search}%")
                     ->orWhere('no_ticket', 'like', "%{$search}%");
            })
            ->with('user', 'parkingArea', 'parkingRate', 'parkingRate.vehicle')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formattedTransaction = $transactions->map(function ($transaction) {
            return new TransactionResource($transaction);
        });

        $user = Auth::id();
        $parking_rates = ParkingRate::with('vehicle')->get()->map(function ($rate) {
            return [
                'id' => $rate->id,
                'name' => $rate->vehicle->name ?? 'Tidak Ada Kendaraan'
            ];
        });
        $parking_areas = ParkingArea::all();

        $parkingAreas = $parking_areas->map(function ($area) {
            $monitoringParking = $area->monitoringParking;
            $status = $monitoringParking ? ($monitoringParking->available > 0 ? 1 : 0) : 1;
            return [
                'id' => $area->id,
                'name' => $area->name,
                'status' => $status
            ];
        });

        $filteredParkingAreas = $parkingAreas->filter(function ($area) {
            return $area['status'] > 0;
        });
        
        return Inertia::render('Transaction/Parking/Index', [
            'title' => 'Transaksi',
            'desc'  => 'Transaksi Parkir',
            'transactions'  => [
                'data'  => $formattedTransaction,
                'links' => PaginationHelper::formatPaginationLinks($transactions),
                'current_page'  => $transactions->currentPage(),
                'per_page'  => $transactions->perPage(),
                'total' => $transactions->total()
            ],
            'filters'   => $request->only(['search']) ?: ['search' => ''],
            'user'  => $user,
            'parking_areas' => $filteredParkingAreas,
            'parking_rates' => $parking_rates
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validatedData = $request->validated();
        $parkingArea = ParkingArea::findOrFail($validatedData['parking_area_id']);
        $validatedData['no_ticket'] = NoTicketHelper::generateNoTicket($parkingArea);
        $transaksi = Transaction::create($validatedData);

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dilakukan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'parkingArea', 'parkingRate', 'parkingRate.vehicle');

        return Inertia::render('Transaction/Parking/Show', [
            'title' => 'Transaksi',
            'desc'  => 'Detail transaksi',
            'transaction' => new TransactionResource($transaction)
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dilakukan!');
    }
}
