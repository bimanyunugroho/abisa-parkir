<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingRate;
use App\Http\Requests\StoreParkingRateRequest;
use App\Http\Requests\UpdateParkingRateRequest;
use Inertia\Inertia;

class ParkingRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Master/Parkingrate/Index', [
            'title' => 'Master Parkir Rate',
            'desc'  => 'Data parkir rate'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Master/Parkingrate/Add', [
            'title' => 'Master Parkir Rate',
            'desc'  => 'Tambah data parkir rate'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParkingRateRequest $request)
    {
        $parkir_rate = ParkingRate::create($request->validated());

        return redirect()->route('parking_rates.index')->with('success', 'Data parkir rate berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParkingRate $parkingRate)
    {
        return Inertia::render('Master/Parkingrate/Edit', [
            'title' => 'Master Parkir Rate',
            'desc'  => 'Ubah data parkir rate',
            'parking_rate'  => $parkingRate
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParkingRateRequest $request, ParkingRate $parkingRate)
    {
        $parkingRate->update($request->validated());

        return redirect()->route('parking_rates.index')->with('success', 'Data parkir rate berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParkingRate $parkingRate)
    {
        $parkingRate->delete();

        return redirect()->route('parking_rates.index')->with('success', 'Data parkir rate berhasil dihapus!');
    }
}
