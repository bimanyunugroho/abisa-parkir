<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\NoTicketHelper;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Company;
use App\Models\ParkingArea;
use App\Models\ParkingRate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TransactionController extends Controller
{
    protected $permissions = [
        'index' => 'view-transactions',
        'create' => 'create-transaction',
        'store' => 'create-transaction',
        'show' => 'view-transaction',
        'edit' => 'edit-transaction',
        'update' => 'edit-transaction',
        'destroy' => 'delete-transaction',
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
        $transactions = Transaction::query()
            ->when($request->input('search'), function ($query, $search) {
                return $query->where('license_plate', 'like', "%{$search}%")
                     ->orWhere('no_ticket', 'like', "%{$search}%");
            })
            ->with('user', 'parkingArea', 'parkingRate', 'parkingRate.vehicle')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formattedTransaction = $transactions->map(function ($transaction) {
            return new TransactionResource($transaction);
        });

        $user = Auth::id();
        $parking_rates = ParkingRate::with('vehicle')->get()->map(function ($rate) {
            return [
                'id' => $rate->id,
                'name' => $rate->vehicle->name ?? 'Tidak Ada Kendaraan'
            ];
        });
        $parking_areas = ParkingArea::all();

        $parkingAreas = $parking_areas->map(function ($area) {
            $monitoringParking = $area->monitoringParking;
            $status = $monitoringParking ? ($monitoringParking->available > 0 ? 1 : 0) : 1;
            return [
                'id' => $area->id,
                'name' => $area->name,
                'status' => $status
            ];
        });

        $filteredParkingAreas = $parkingAreas->filter(function ($area) {
            return $area['status'] > 0;
        });
        
        return Inertia::render('Transaction/Parking/Index', [
            'title' => 'Transaksi',
            'desc'  => 'Transaksi Parkir',
            'transactions'  => [
                'data'  => $formattedTransaction,
                'links' => PaginationHelper::formatPaginationLinks($transactions),
                'current_page'  => $transactions->currentPage(),
                'per_page'  => $transactions->perPage(),
                'total' => $transactions->total()
            ],
            'filters'   => $request->only(['search']) ?: ['search' => ''],
            'user'  => $user,
            'parking_areas' => $filteredParkingAreas,
            'parking_rates' => $parking_rates
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validated();
            $parkingArea = ParkingArea::findOrFail($validatedData['parking_area_id']);
            $validatedData['no_ticket'] = NoTicketHelper::generateNoTicket($parkingArea);

            $qrCode = QrCode::size(300)->generate($validatedData['no_ticket']);
            $validatedData['qr_code'] = base64_encode($qrCode);
            
            $transaksi = Transaction::create($validatedData);

            DB::commit();

            // Generate PDF
            $ticketData = [
                'ticket_number' => $transaksi->no_ticket,
                'plat_nomor' => $transaksi->plat_nomor,
                'jenis_kendaraan' => $transaksi->jenis_kendaraan,
                'tanggal_masuk' => $transaksi->created_at->toDateString(),
                'jam_masuk' => $transaksi->created_at->format('H:i:s'),
                'qr_code' => $transaksi->qr_code,
            ];

            return Inertia::render('Transaction/Parking/Index', [
                'message' => 'Transaksi berhasil dilakukan!',
                'transaction' => $transaksi,
                'pdfUrl' => route('transactions.print', $transaksi->slug)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function printTicket(Transaction $transaction)
    {
        $company = Company::first();
        $ticketData = [
            'perusahaan'    => [
                'nama_perusahaan'   => $company->name,
                'alamat'    => $company->address,
                'no_telp'   => $company->phone_number,
                'email' => $company->email
            ],
            'ticket_number' => $transaction->no_ticket,
            'plat_nomor' => $transaction->license_plate,
            'jenis_kendaraan' => $transaction->parkingRate->vehicle->name,
            'tanggal_masuk' => $transaction->created_at->toDateString(),
            'jam_masuk' => $transaction->created_at->format('H:i:s'),
            'qr_code' => $transaction->qr_code,
        ];

        $pdf = PDF::loadView('tickets.ticket', $ticketData);

        return $pdf->stream('parking-ticket.pdf');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'parkingArea', 'parkingRate', 'parkingRate.vehicle');

        return Inertia::render('Transaction/Parking/Show', [
            'title' => 'Transaksi',
            'desc'  => 'Detail transaksi',
            'transaction' => new TransactionResource($transaction)
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction->update($request->validated());
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dilakukan!');
    }
}
