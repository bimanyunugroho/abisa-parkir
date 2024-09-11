<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AccessUserController extends Controller
{
    protected $permissions = [
        'index' => 'view-access-users',
        'edit' => 'edit-access-user',
        'update' => 'edit-access-user',
        'non_active' => 'edit-access-user',
        'active' => 'edit-access-user',
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
        $users = User::query()
            ->with('role')
            ->when($request->input('search'), function ($query, $search) {
                return $query->where('name', 'ilike', "%{$search}%");
            })
            ->whereNot('id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $formattedUsers = $users->map(function ($user) {
            return new UserResource($user);
        });

        return Inertia::render('Access/User/Index', [
            'title' => 'Akses User',
            'desc'  => 'Setting Akses User',
            'users' => [
                'data'  => $formattedUsers,
                'links' => PaginationHelper::formatPaginationLinks($users),
                'current_page' => $users->currentPage(),
                'per_page'  => $users->perPage(),
                'total' => $users->total()
            ],
            'filters'   => $request->only(['search']) ?: ['search' => '']
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $access_user)
    {
        $access_user->load('role');
        $roles = Role::all();
        return Inertia::render('Access/User/Verify', [
            'title' => 'Akses User',
            'desc'  => 'Setting Verify Akses User',
            'user'  => new UserResource($access_user),
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $access_user)
    {
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $roleId = $request->input('role_id');

        $access_user->update(['role_id' => $roleId]);
        return redirect()->route('access_users.index')->with('success', 'User berhasil dinonaktifkan.');
    }


    public function non_active(User $access_user)
    {
        $access_user->update(['status' => 0]);
        return redirect()->route('access_users.index')->with('success', 'User berhasil dinonaktifkan.');
    }


    public function active(User $access_user)
    {
        $access_user->update(['status' => 1]);
        return redirect()->route('access_users.index')->with('success', 'User berhasil diaktifkan.');
    }
    
}
