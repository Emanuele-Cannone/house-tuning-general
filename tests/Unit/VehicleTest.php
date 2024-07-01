<?php

use App\Models\Vehicle;
use Illuminate\Database\QueryException;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;


test('creates a vehicle with a unique name', function () {

    $vehicle = Vehicle::factory()->create();

    expect(function () use ($vehicle) {
        Vehicle::create(['name' => $vehicle->name]);
    })->toThrow(QueryException::class)
        ->and($vehicle)
        ->toBeInstanceOf(Vehicle::class)
        ->and($vehicle->name)
        ->toBe($vehicle->name);

    assertDatabaseCount(Vehicle::class, 1);
    assertDatabaseHas(Vehicle::class, [
        'id' => $vehicle->id,
        'name' => $vehicle->name,
        'active' => $vehicle->active,
    ]);

});


test('updates vehicle', function () {

    $vehicle = Vehicle::factory()->create();

    $newName = fake()->word;

    $vehicle->update(['name' => $newName]);

    expect(function () use ($vehicle) {
        $vehicle->update(['name' => fake()->word]);
    })->toBeObject(Vehicle::class)
        ->and($vehicle)
        ->toBeInstanceOf(Vehicle::class)
        ->and($vehicle->name)
        ->toBe($newName);

    assertDatabaseCount(Vehicle::class, 1);

});


test('delete vehicle', function () {

    $vehicle = Vehicle::factory()->create();

    $vehicle->delete();

    assertDatabaseCount(Vehicle::class, 0);

});
