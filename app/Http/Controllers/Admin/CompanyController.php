<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    protected $permissions = [
        'index' => 'view-companies',
        'create' => 'create-company',
        'store' => 'create-company',
        'show' => 'view-company',
        'edit' => 'edit-company',
        'update' => 'edit-company',
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
        $companies = Company::query()
            ->when($request->input('search'), function($query, $search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formattedCompany = $companies->map(function($company) {
            return new CompanyResource($company);
        });

        return Inertia::render('Setting/Company/Index', [
            'title' => 'Setting Perusahaan',
            'desc'  => 'Data Setting Perusahaan',
            'companies' => [
                'data'  => $formattedCompany,
                'links' => PaginationHelper::formatPaginationLinks($companies),
                'current_page'  => $companies->currentPage(),
                'per_page'  => $companies->perPage(),
                'total' => $companies->total()
            ],
            'filters'    => $request->only(['search']) ?: ['search' => '']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Setting/Company/Create', [
            'title' => 'Setting Perusahaan',
            'desc'  => 'Tambah Setting Perusahaan'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $company = Company::create($request->validated());

        return redirect()->route('companies.index')->with('success', 'Setting Perusahaan Berhasil Ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return Inertia::render('Setting/Company/Show', [
            'title' => 'Setting Perusahaan',
            'desc'  => 'Detail Perusahaan',
            'company'   => $company
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return Inertia::render('Setting/Company/Edit', [
            'title' => 'Setting Perusahaan',
            'desc'  => 'Edit Setting Perusahaan',
            'company'   => $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->update($request->validated());

        return redirect()->route('companies.index')->with('success', 'Setting Perusahaan Berhasil Diubah!');
    }
}
