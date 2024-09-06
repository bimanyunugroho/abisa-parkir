<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MonitoringParkingResource;
use App\Models\MonitoringParking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MonitoringParkingController extends Controller
{
    public function index(Request $request)
    {
        $monitoringParkings = MonitoringParking::query()
            ->with('parkingArea')
            ->when($request->input('search'), function ($query, $search) {
                $query->whereHas('parkingArea', function ($parkingAreaQuery) use ($search) {
                    $parkingAreaQuery->where('name', 'ilike', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formattedMonitoringParking = $monitoringParkings->map(function ($monitoringParking) {
            return new MonitoringParkingResource($monitoringParking);
        });

        return Inertia::render('Monitoring/Index', [
            'title' => 'Monitoring Area Parkir',
            'desc'  => 'Monitoring Area Parkir',
            'monitoring_parkings'   => [
                'data'  => $formattedMonitoringParking,
                'links' => PaginationHelper::formatPaginationLinks($monitoringParkings),
                'current_page'  => $monitoringParkings->currentPage(),
                'per_page' => $monitoringParkings->perPage(),
                'total' => $monitoringParkings->total(),
            ],
            'filters'   => $request->only(['search']) ?: ['search' => '']
        ]);
    }
}
