<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParkingSession;
use App\Http\Requests\StoreParkingSessionRequest;
use App\Http\Requests\UpdateParkingSessionRequest;
use Inertia\Inertia;

class ParkingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Transaction/Parkingsession/Index', [
            'title' => 'Master Parkir Session',
            'desc'  => 'Data parkir session'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Transaction/Parkingsession/Add', [
            'title' => 'Master Parkir Session',
            'desc'  => 'Tambah parkir session'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParkingSessionRequest $request)
    {
        $parkir_session = ParkingSession::create($request->validated());

        return redirect()->route('parking_sessions.index')->with('success', 'Data parkir session berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function show(ParkingSession $parkingSession)
    {
        return Inertia::render('Transaction/Parkingsession/Show', [
            'title' => 'Master Parkir Session',
            'desc'  => 'Detail parkir session',
            'parking_session' => $parkingSession
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParkingSession $parkingSession)
    {
        return Inertia::render('Transaction/Parkingsession/Edit', [
            'title' => 'Master Parkir Session',
            'desc'  => 'Ubah parkir session',
            'parking_session'   => $parkingSession
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParkingSessionRequest $request, ParkingSession $parkingSession)
    {
        $parkingSession->update($request->validated());

        return redirect()->route('parking_sessions.index')->with('success', 'Data parkir session berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParkingSession $parkingSession)
    {
        $parkingSession->delete();

        return redirect()->route('parking_sessions.index')->with('success', 'Data parkir session berhasil dihapus!');
    }
}
