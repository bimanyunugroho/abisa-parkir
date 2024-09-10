<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\MonitoringParkingResource;
use App\Http\Resources\TransactionResource;
use App\Models\MonitoringParking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MonitoringParkingController extends Controller
{
    protected $permissions = [
        'index' => 'view-monitoring-parkings',
        'monitoring_vehicle' => 'view-monitoring-vehicles'
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

    public function monitoring_vehicle(Request $request)
    {
        $transactions = Transaction::query()
            ->when($request->input('search'), function ($query, $search) {
                return $query->where('license_plate', 'like', "%{$search}%")
                    ->orWhere('no_ticket', 'like', "%{$search}%");
            })
            ->with('user', 'parkingArea', 'parkingRate', 'parkingRate.vehicle')
            ->where('entry_time', '<=', now()->subDays(2))
            ->whereNull('exit_time')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formattedTransaction = $transactions->map(function ($transaction) {
            return new TransactionResource($transaction);
        });

        return Inertia::render('Monitoring/Vehicle/Index', [
            'title' => 'Monitoring Kendaraan',
            'desc'  => 'Monitoring Kendaraan > 3 Hari',
            'transactions' => [
                'data' => $formattedTransaction,
                'links' => PaginationHelper::formatPaginationLinks($transactions),
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total()
            ],
            'filters' => $request->only(['search']) ?: ['search' => '']
        ]);
    }
}
