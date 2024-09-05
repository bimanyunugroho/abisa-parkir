<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'ADMIN',
                'slug' => 'admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'PENGAWAS',
                'slug' => 'pengawas',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'OPERATOR',
                'slug' => 'operator',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
