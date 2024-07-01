<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'emanuele',
        ]);

        $vehicles = collect([
            'Auto',
            'Moto',
            'Trattori'
        ]);

        $brands = collect([
            [
                'vehicle_id' => 1,
                'brand' => 'Fiat',
                'name' => 'Panda'
            ],
            [
                'vehicle_id' => 1,
                'brand' => 'Fiat',
                'name' => 'Bravo'
            ],
            [
                'vehicle_id' => 1,
                'brand' => 'Fiat',
                'name' => 'Doblò'
            ],
            [
                'vehicle_id' => 1,
                'brand' => 'Bmw',
                'name' => 'X1'
            ],
            [
                'vehicle_id' => 1,
                'brand' => 'Bmw',
                'name' => 'X3'
            ],
            [
                'vehicle_id' => 1,
                'brand' => 'Bmw',
                'name' => 'X5'
            ],
            [
                'vehicle_id' => 2,
                'brand' => 'Ducati',
                'name' => '998'
            ],
            [
                'vehicle_id' => 2,
                'brand' => 'Ducati',
                'name' => '996'
            ],
            [
                'vehicle_id' => 2,
                'brand' => 'Ducati',
                'name' => '1098'
            ],
            [
                'vehicle_id' => 2,
                'brand' => 'Aprilia',
                'name' => 'RS'
            ],
            [
                'vehicle_id' => 2,
                'brand' => 'Aprilia',
                'name' => 'RSV4'
            ],
            [
                'vehicle_id' => 2,
                'brand' => 'Aprilia',
                'name' => 'Tuono'
            ],
            [
                'vehicle_id' => 3,
                'brand' => 'John Deere',
                'name' => '6125'
            ],
            [
                'vehicle_id' => 3,
                'brand' => 'John Deere',
                'name' => '6250'
            ],
            [
                'vehicle_id' => 3,
                'brand' => 'Class',
                'name' => 'Xerion'
            ],
            [
                'vehicle_id' => 3,
                'brand' => 'Class',
                'name' => 'Arion'
            ],
            [
                'vehicle_id' => 3,
                'brand' => 'Class',
                'name' => 'Axion'
            ]
        ]);

        $vehicles->each(function ($item) use ($brands) {
            Vehicle::create(['name' => $item]);
        });

        /*
        $brands->each(function ($item) {
            VehicleBrand::create([
                'vehicle_id' => $item['vehicle_id'],
                'brand' => $item['brand'],
                'name' => $item['name']
            ]);
        });
        */
    }
}
