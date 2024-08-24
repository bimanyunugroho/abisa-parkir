<?php

namespace App\Http\Controllers;

use App\Models\ParkingSession;
use App\Http\Requests\StoreParkingSessionRequest;
use App\Http\Requests\UpdateParkingSessionRequest;

class ParkingSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParkingSessionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ParkingSession $parkingSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParkingSession $parkingSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParkingSessionRequest $request, ParkingSession $parkingSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParkingSession $parkingSession)
    {
        //
    }
}
