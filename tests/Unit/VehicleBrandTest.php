<?php

use App\Models\Vehicle;
use App\Models\VehicleBrand;
use Illuminate\Database\QueryException;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('creates a vehicle-brand with a unique name', function () {

    $vehicle = Vehicle::factory()->create();

    $vehicleBrand = VehicleBrand::create([
        'name' => 'test-name',
        'brand' => 'test-brand',
        'vehicle_id' => rand(1, $vehicle->count())
    ]);

    expect(function () use ($vehicleBrand) {
        VehicleBrand::create([
            'name' => $vehicleBrand->name,
            'brand' => $vehicleBrand->brand,
            'vehicle_id' => $vehicleBrand->vehicle_id,
        ]);
    })->toThrow(QueryException::class)
        ->and($vehicleBrand)
        ->toBeInstanceOf(VehicleBrand::class)
        ->and($vehicleBrand->name)->toBe($vehicleBrand->name)
        ->and($vehicleBrand->brand)->toBe($vehicleBrand->brand)
        ->and($vehicleBrand->vehicle_id)->toBe($vehicleBrand->vehicle_id);

    assertDatabaseCount(VehicleBrand::class, 1);

    assertDatabaseHas(VehicleBrand::class, [
        'id' => $vehicleBrand->id,
        'name' => $vehicleBrand->name,
        'brand' => $vehicleBrand->brand,
        'vehicle_id' => $vehicleBrand->vehicle_id,
    ]);

});


test('update vehicle-brand', function () {

    $vehicle = Vehicle::factory()->create();

    $vehicleBrand = VehicleBrand::create([
        'name' => fake()->unique()->word,
        'brand' => fake()->unique()->word,
        'vehicle_id' => $vehicle->id,
    ]);

    $newName = fake()->unique()->word;
    $newBrand = fake()->unique()->word;

    $vehicleBrand->update([
        'name' => $newName,
        'brand' => $newBrand,
    ]);

    expect(function () use ($vehicleBrand, $newBrand, $newName) {
        $vehicleBrand->update([
            'name' => $newName,
            'brand' => $newBrand,
        ]);
    })->toBeObject(VehicleBrand::class)
        ->and($vehicleBrand)
        ->toBeInstanceOf(VehicleBrand::class)
        ->and($vehicleBrand->name)->toBe($newName)
        ->and($vehicleBrand->brand)->toBe($newBrand);

    assertDatabaseCount(VehicleBrand::class, 1);

});


test('delete vehicle', function () {

    $vehicle = Vehicle::factory()->create();

    $vehicleBrand = VehicleBrand::create([
        'name' => fake()->unique()->word,
        'brand' => fake()->unique()->word,
        'vehicle_id' => $vehicle->id,
    ]);

    $vehicleBrand->delete();

    assertDatabaseCount(VehicleBrand::class, 0);

});
