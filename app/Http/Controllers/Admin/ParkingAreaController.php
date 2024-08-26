<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingArea;
use App\Http\Requests\StoreParkingAreaRequest;
use App\Http\Requests\UpdateParkingAreaRequest;
use Inertia\Inertia;

class ParkingAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('', [
            'title' => 'Master Parkir Area',
            'desc'  => 'Data master parkir area'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('', [
            'title' => 'Master Parkir Area',
            'desc'  => 'Tambah master parkir area'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParkingAreaRequest $request)
    {
        $parking_area = ParkingArea::create($request->validated());

        return redirect()->route('parking_areas.index')->with('success', 'Data parkir area berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParkingArea $parkingArea)
    {
        return Inertia::render('', [
            'title' => 'Master Parkir Area',
            'desc'  => 'Ubah master parkir area'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParkingAreaRequest $request, ParkingArea $parkingArea)
    {
        $parkingArea->update($request->validated());

        return redirect()->route('parking_areas.index')->with('success', 'Data parkir area berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParkingArea $parkingArea)
    {
        $parkingArea->delete();

        return redirect()->route('parking_areas.index')->with('success', 'Data parkir area berhasil dihapus!');
    }
}
