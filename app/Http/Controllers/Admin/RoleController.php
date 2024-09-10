<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    protected $permissions = [
        'index' => 'view-roles',
        'create' => 'create-role',
        'store' => 'create-role',
        'show' => 'view-role',
        'edit' => 'edit-role',
        'update' => 'edit-role',
        'destroy' => 'delete-role',
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
        $roles = Role::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formattedRoles = $roles->map(function ($role) {
            return new RoleResource($role);
        });

        return Inertia::render('Access/Role/Index', [
            'title' => 'Master Role',
            'desc'  => 'Data Master Role',
            'roles' => [
                'data'  => $formattedRoles,
                'links' => PaginationHelper::formatPaginationLinks($roles),
                'current_page'  => $roles->currentPage(),
                'per_page'  => $roles->perPage(),
                'total' => $roles->total()
            ],
            'filters'    => $request->only(['search']) ?: ['search' => '']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Access/Role/Add', [
            'title' => 'Master Role',
            'desc'  => 'Tambah Master Role'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create($request->validated());

        return redirect()->route('roles.index')->with('success', 'Data role berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return Inertia::render('Access/Role/Edit', [
            'title' => 'Master Role',
            'desc'  => 'Ubah Master Role',
            'role'  => $role
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->validated());

        return redirect()->route('roles.index')->with('success', 'Data role berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Data role berhasil dihapus!');
    }
}
