<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'Admin']);
        $managerRole = Role::create(['name' => 'Manager']);
        $pengawasRole = Role::create(['name' => 'Pengawas']);
        $operatorRole = Role::create(['name' => 'Operator']);

        $permissions = [
            // Dashboard
            'view dashboard',

            // Profile
            'edit profile',

            // Roles
            'view roles', 'view role', 'create role', 'edit role', 'delete role',

            // Parking Areas
            'view parking areas', 'view parking area', 'create parking area', 'edit parking area', 'delete parking area',

            // Parking Rates
            'view parking rates', 'view parking rate', 'create parking rate', 'edit parking rate', 'delete parking rate',

            // Vehicles
            'view vehicles', 'view vehicle', 'create vehicle', 'edit vehicle', 'delete vehicle',

            // Transactions
            'view transactions', 'view transaction', 'create transaction', 'edit transaction', 'delete transaction',

            // Permissions
            'view permissions', 'view permission', 'create permission', 'edit permission', 'delete permission',

            // Monitoring
            'view monitoring parkings', 'view monitoring vehicles',

            // Reports
            'view reports', 'generate excel report', 'generate pdf report',

            // RBACS
            'view rbacs', 'view rbac', 'create rbac', 'edit rbac', 'delete rbac',
            
            // Akses User
            'view access users', 'view access user', 'edit access user'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Role Permission Admin
        $adminRole->permissions()->attach(Permission::all());

        // Role Permission Manager
        $managerPermissions = [
            'view dashboard',
            'edit profile',
            'view monitoring parkings', 'view monitoring vehicles',
            'view transactions', 'view transaction',
            'view reports', 'generate excel report', 'generate pdf report',
        ];
        $managerRole->permissions()->attach(Permission::whereIn('name', $managerPermissions)->get());
        
        // Role Permission Pengawas
        $pengawasPermission = [
            'view dashboard',
            'edit profile',
            'view parking areas', 'create parking area', 'edit parking area', 'delete parking area',
            'view parking rates', 'create parking rate', 'edit parking rate', 'delete parking rate',
            'view vehicles', 'create vehicle', 'edit vehicle', 'delete vehicle',
            'view transactions', 'view transaction',
            'view monitoring parkings', 'view monitoring vehicles',
            'view reports', 'generate excel report', 'generate pdf report',
        ];
        $pengawasRole->permissions()->attach(Permission::whereIn('name', $pengawasPermission)->get());


        // Role Permission Operator
        $operatorPermission = [
            'view dashboard',
            'edit profile',
            'view transactions', 'view transaction', 'create transaction', 'edit transaction', 'delete transaction',
            'view monitoring parkings', 'view monitoring vehicles',
            'view reports', 'generate excel report', 'generate pdf report',
        ];
        $operatorRole->permissions()->attach(Permission::whereIn('name', $operatorPermission)->get());

        User::create([
            'name' => 'ADMIN',
            'email' => 'abisa@gmail.com',
            'password' => bcrypt('abisa12345'),
            'role_id' => $adminRole->id,
        ]);
    }
}