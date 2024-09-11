<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PermissionController extends Controller
{
    protected $permissions = [
        'index' => 'view-permissions',
        'create' => 'create-permission',
        'store' => 'create-permission',
        'show' => 'view-permission',
        'edit' => 'edit-permission',
        'update' => 'edit-permission',
        'destroy' => 'delete-permission',
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
        $permissions = Permission::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formattedPermissions = $permissions->map(function ($role) {
            return new PermissionResource($role);
        });

        return Inertia::render('Access/Permission/Index', [
            'title' => 'Akses Permissions',
            'desc'  => 'Data Akses Permissions',
            'permissions' => [
                'data'  => $formattedPermissions,
                'links' => PaginationHelper::formatPaginationLinks($permissions),
                'current_page'  => $permissions->currentPage(),
                'per_page'  => $permissions->perPage(),
                'total' => $permissions->total()
            ],
            'filters'    => $request->only(['search']) ?: ['search' => '']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Access/Permission/Add', [
            'title' => 'Akses Permissions',
            'desc'  => 'Tambah Akses Permissions',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::create($request->validated());

        return redirect()->route('permissions.index')->with('success', 'Data Akses permission berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return Inertia::render('Access/Permission/Edit', [
            'title' => 'Akses Permissions',
            'desc'  => 'Edit Data Akses Permissions',
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());

        return redirect()->route('permissions.index')->with('success', 'Data Akses Permission Berhasil Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Data Akses Permission Berhasil DiHapus!');
    }
}
