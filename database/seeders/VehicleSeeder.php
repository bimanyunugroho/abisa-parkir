<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kendaraan = [
            ['name' => 'Mobil', 'slug' => 'mobil'],
            ['name' => 'Motor', 'slug' => 'motor'],
            ['name' => 'Sepeda', 'slug' => 'sepeda'],
            ['name' => 'Truk', 'slug' => 'truk'],
            ['name' => 'Bus', 'slug' => 'bus'],
            ['name' => 'Van', 'slug' => 'van'],
            ['name' => 'Skuter', 'slug' => 'skuter'],
            ['name' => 'Mobil Listrik', 'slug' => 'mobil-listrik'],
            ['name' => 'SUV', 'slug' => 'suv'],
            ['name' => 'Pickup', 'slug' => 'pickup'],
        ];

        foreach ($kendaraan as $item) {
            Vehicle::create($item);
        }
    }
}
