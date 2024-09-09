<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TransactionExport;
use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::all();
        $vehiclesWithAllOption = $vehicles->prepend((object) [
            'id' => 0,
            'name' => 'Semua Kendaraan',
            'type' => '',
            'created_at' => null,
            'updated_at' => null,
        ]);

        return Inertia::render('Report/Index', [
            'title' => 'Laporan',
            'desc'  => 'Laporan Parkir & Pendapatan',
            'vehicles'  => $vehiclesWithAllOption,
        ]);
    }

    public function process_excell(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicles' => 'required',
            'vehicles.*' => 'integer|exists:vehicles,id',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'reportType' => 'required|string|in:rekap,detail',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $vehicles = $request->vehicles;
        $type = $request->reportType;

        $export = new TransactionExport($startDate, $endDate, $vehicles, $type);
        return Excel::download($export, "transactions_{$startDate}_$endDate.xlsx");
    }

    public function process_pdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicles' => 'required',
            'vehicles.*' => 'integer|exists:vehicles,id',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'reportType' => 'required|string|in:rekap,detail',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $vehicles = $request->vehicles;
        $type = $request->reportType;

        $export = new TransactionExport($startDate, $endDate, $vehicles, $type);
        $transactions = $export->query()->get();

        if ($type === 'detail') {
            $totalPendapatan = $transactions->sum(function ($transaction) {
                return $transaction['payment'] - $transaction['change_pay'];
            });
        }
        
        $mappedTransactions = $transactions->map(function ($transaction) use ($export) {
            return $export->map($transaction);
        })->toArray();

        $load_view = match ($type) {
            'rekap' => 'reports.parkingrekap',
            'detail' => 'reports.parkingdetail',
            default => 'reports.parkingdefault',
        };
        
        $pdf = Pdf::loadView($load_view, [
            'transactions' => $mappedTransactions,
            'headings' => $export->headings(),
            'startDate' => $startDate,
            'endDate' => $endDate,
            'type' => $type,
            'totalPendapatan' => $totalPendapatan ?? 0,
        ])->setPaper('a4', 'landscape');
        

        return $pdf->download("transactions_{$startDate}_$endDate.pdf");
    }
}
