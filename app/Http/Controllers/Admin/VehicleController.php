<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Resources\VehicleResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $vehicles = Vehicle::query()
            ->when($request->input('search'), function($query, $search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formattedVehicle = $vehicles->map(function($vehicle) {
            return new VehicleResource($vehicle);
        });

        return Inertia::render('Master/Vehicle/Index', [
            'title' => 'Master Kendaraan',
            'desc'  => 'Data Kendaraan',
            'vehicles'  => [
                'data'  => $formattedVehicle,
                'links' => PaginationHelper::formatPaginationLinks($vehicles),
                'current_page'  => $vehicles->currentPage(),
                'per_page' => $vehicles->perPage(),
                'total' => $vehicles->total()
            ],
            'filters'   => $request->only(['search']) ?: ['search' => '']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Master/Vehicle/Add', [
            'title' => 'Master Kendaraan',
            'desc'  => 'Tambah Kendaraan'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());

        return redirect()->route('vehicles.index')->with('success', 'Data Kendaraan berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        return Inertia::render('Master/Vehicle/Edit', [
            'title' => 'Master Kendaraan',
            'desc'  => 'Ubah Kendaraan',
            'vehicle'   => $vehicle
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return redirect()->route('vehicles.index')->with('success', 'Data Kendaraan berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'Data Kendaraan berhasil dihapus!');
    }
}
