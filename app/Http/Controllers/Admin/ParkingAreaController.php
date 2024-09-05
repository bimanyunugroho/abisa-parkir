<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ParkingAreaResource;
use App\Models\ParkingArea;
use App\Http\Requests\StoreParkingAreaRequest;
use App\Http\Requests\UpdateParkingAreaRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ParkingAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $parkingAreas = ParkingArea::query()
            ->when($request->input('search'), function($query, $search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formaatedParkingArea = $parkingAreas->map(function($parkingArea) {
            return new ParkingAreaResource($parkingArea);
        });

        return Inertia::render('Master/Parkingarea/Index', [
            'title' => 'Master Parkir Area',
            'desc'  => 'Data Master Parkir Area',
            'parking_areas' => [
                'data'  => $formaatedParkingArea,
                'links' => PaginationHelper::formatPaginationLinks($parkingAreas),
                'current_page'  => $parkingAreas->currentPage(),
                'per_page'  => $parkingAreas->perPage(),
                'total' => $parkingAreas->total()
            ],
            'filters'    => $request->only(['search']) ?: ['search' => '']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Master/Parkingarea/Add', [
            'title' => 'Master Parkir Area',
            'desc'  => 'Tambah Master Parkir Area'
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
        return Inertia::render('Master/Parkingarea/Edit', [
            'title' => 'Master Parkir Area',
            'desc'  => 'Ubah Master Parkir Area',
            'parking_area'  => $parkingArea
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
