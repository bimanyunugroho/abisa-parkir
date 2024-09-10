<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RbacController extends Controller
{
    public function index()
    {
        return Inertia::render('Access/RBAC/Index', [
            'title' => 'RBAC',
            'desc'  => 'RBAC'
        ]);
    }

    public function create()
    {
        return Inertia::render('Access/RBAC/Index', [
            'title' => 'RBAC',
            'desc'  => 'Setting Tambah RBAC'
        ]);
    }

    public function store()
    {

    }

    public function show()
    {
        return Inertia::render('Access/RBAC/Index', [
            'title' => 'RBAC',
            'desc'  => 'Detail RBAC'
        ]);
    }

    public function edit()
    {
        return Inertia::render('Access/RBAC/Index', [
            'title' => 'RBAC',
            'desc'  => 'Setting Edit RBAC'
        ]);
    }

    public function update()
    {

    }
}
