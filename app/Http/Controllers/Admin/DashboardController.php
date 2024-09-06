<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Transaction;
use App\Models\ParkingArea;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $parkingData = [
            'totalSpots' => $this->getTotalSpots(),
            'occupied' => $this->getOccupiedSpots(),
            'available' => $this->getAvailableSpots(),
            'revenue' => [
                'today' => $this->getTodayRevenue(),
                'yesterday' => $this->getYesterdayRevenue(),
            ],
            'averageDuration' => $this->getAverageDuration(),
            'peakHour' => $this->getPeakHour(),
            'monthlySubscribers' => $this->getMonthlySubscribers(),
        ];

        return Inertia::render('Dashboard', [
            'title' => 'Dashboard',
            'desc' => 'Dashboard Parkir',
            'parkingData' => $parkingData
        ]);
    }

    private function getTotalSpots()
    {
        return ParkingArea::sum('capacity');
    }

    private function getOccupiedSpots()
    {
        return Transaction::whereNull('exit_time')->count();
    }

    private function getAvailableSpots()
    {
        return $this->getTotalSpots() - $this->getOccupiedSpots();
    }

    private function getTodayRevenue()
    {
        return Transaction::whereDate('exit_time', Carbon::today())->sum('total_cost');
    }

    private function getYesterdayRevenue()
    {
        return Transaction::whereDate('exit_time', Carbon::yesterday())->sum('total_cost');
    }

    private function getAverageDuration()
    {
        return round(Transaction::whereNotNull('exit_time')->avg('duration'));
    }

    private function getPeakHour()
    {
        $peakHour = Transaction::selectRaw('EXTRACT(HOUR FROM entry_time) as hour, COUNT(*) as count')
            ->whereNull('deleted_at')
            ->groupBy('hour')
            ->orderByDesc('count')
            ->first();
    
        if ($peakHour) {
            $startHour = str_pad((int)$peakHour->hour, 2, '0', STR_PAD_LEFT);
            $endHour = str_pad((($peakHour->hour + 1) % 24), 2, '0', STR_PAD_LEFT);
            return "{$startHour}:00 - {$endHour}:00";
        }
    
        return 'N/A';
    }
    private function getMonthlySubscribers()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        return Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
    }
}