<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\ParkingRate;
use App\Http\Requests\StoreParkingRateRequest;
use App\Http\Requests\UpdateParkingRateRequest;
use App\Http\Resources\ParkingRateResource;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParkingRateController extends Controller
{
    protected $permissions = [
        'index' => 'view-parking-rates',
        'create' => 'create-parking-rate',
        'store' => 'create-parking-rate',
        'show' => 'view-parking-rate',
        'edit' => 'edit-parking-rate',
        'update' => 'edit-parking-rate',
        'destroy' => 'delete-parking-rate',
    ];

    public function __construct(Request $request)
    {
        $action = $request->route()?->getActionMethod();
        
        if ($action && isset($this->permissions[$action])) {
            $requiredPermission = $this->permissions[$action];
            if (!$request->user()->hasPermission($requiredPermission)) {
                abort(403, 'Role Anda Tidak Punya Akses');
            }
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $parkingRates = ParkingRate::query()
            ->with('vehicle')
            ->when($request->input('search'), function ($query, $search) {
                $query->whereHas('vehicle', function ($vehicleQuery) use ($search) {
                    $vehicleQuery->where('name', 'ilike', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formattedParkingRate = $parkingRates->map(function ($parkingRate) {
            return new ParkingRateResource($parkingRate);
        });

        return Inertia::render('Master/Parkingrate/Index', [
            'title' => 'Setting Parkir',
            'desc'  => 'Setting Parkir',
            'parking_rates' => [
                'data'  => $formattedParkingRate,
                'links' => PaginationHelper::formatPaginationLinks($parkingRates),
                'current_page'  => $parkingRates->currentPage(),
                'per_page'  => $parkingRates->perPage(),
                'total' => $parkingRates->total()
            ],
            'filters'   => $request->only(['search']) ?: ['search' => '']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehicles = Vehicle::all();
        return Inertia::render('Master/Parkingrate/Add', [
            'title' => 'Setting Parkir',
            'desc'  => 'Tambah Setting Parkir',
            'vehicles'  => $vehicles
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
        $vehicles = Vehicle::all();
        return Inertia::render('Master/Parkingrate/Edit', [
            'title' => 'Setting Parkir',
            'desc'  => 'Ubah Setting Parkir',
            'parking_rate'  => $parkingRate,
            'vehicles'  => $vehicles
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
