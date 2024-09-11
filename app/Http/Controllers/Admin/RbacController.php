<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\RbacResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class RbacController extends Controller
{
    protected $permissions = [
        'index' => 'view-rbacs',
        'create' => 'create-rbac',
        'store' => 'create-rbac',
        'show' => 'view-rbac',
        'edit' => 'edit-rbac',
        'update' => 'edit-rbac',
        'destroy' => 'delete-rbac',
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

        $roles = Role::query()
            ->whereHas('permissions')
            ->with('permissions')
            ->when($request->input('search'), function ($query, $search) {
                return $query->where('name', 'ilike', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();


        $formattedRolesPermission = $roles->map(function ($role) {
            return new RbacResource($role);
        });

        return Inertia::render('Access/RBAC/Index', [
            'title' => 'RBAC',
            'desc'  => 'Role Based Access Control',
            'roles' => [
                'data'  => $formattedRolesPermission,
                'links' => PaginationHelper::formatPaginationLinks($roles),
                'current_page'  => $roles->currentPage(),
                'per_page'  => $roles->perPage(),
                'total' => $roles->total()
            ],
            'filters'    => $request->only(['search']) ?: ['search' => '']
        ]);
    }

    public function create()
    {
        $permissions = Permission::all();
        $roles = Role::all();

        return Inertia::render('Access/RBAC/Create', [
            'title' => 'Create Role',
            'desc'  => 'Create New Role',
            'permissions' => $permissions,
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role_id = $request->input('role_id');

        $existingRole = DB::table('role_permissions')
            ->where('role_id', $role_id)
            ->first();

        if ($existingRole) {
            return redirect()->back()->withErrors(['role_id' => 'The role_id is already assigned to a permission.']);
        }

        DB::transaction(function () use ($request) {
            $role = Role::findOrFail($request->input('role_id'));
            $role->permissions()->sync($request->input('permissions'));
        });

        return redirect()->route('rbacs.index')->with('success', 'RBAC berhasil disimpan!');
    }
    
    public function show(Role $role)
    {
        $role->load('permissions');
        $userCount = $role->users()->count();
        return Inertia::render('Access/RBAC/Show', [
            'title' => 'Role Details',
            'desc'  => 'Role Details',
            'role' => new RbacResource($role),
            'user_count'    => $userCount
        ]);
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::all();

        return Inertia::render('Access/RBAC/Edit', [
            'title' => 'Edit Role',
            'desc'  => 'Edit Role',
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($request->permissions);

        return redirect()->route('rbacs.index')->with('message', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('rbacs.index')->with('message', 'Role deleted successfully.');
    }
}