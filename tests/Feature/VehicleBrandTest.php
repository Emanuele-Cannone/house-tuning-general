<?php

use App\Http\Requests\VehicleUpdateRequest;
use App\Livewire\CreateVehicleBrandModal;
use App\Livewire\CreateVehicleModal;
use App\Livewire\VehicleTable;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Services\VehicleService;
use Livewire\Livewire;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('vehicle-brand create page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/vehicle-brand/create');

    $response->assertOk();
});

test('open and close modal', function () {


    Livewire::test(CreateVehicleBrandModal::class)
        ->call('showCreateVehicleBrandModal')
        ->assertSet('show', true)
        ->call('closeModal')
        ->assertSet('show', false);

});


test('create vehicle-brand from modal', function () {

    $vehicle = Vehicle::factory()->create();

    $name = fake()->unique()->word;
    $brand = fake()->unique()->word;

    Livewire::test(CreateVehicleBrandModal::class)
        ->call('showCreateVehicleBrandModal')
        ->set('name', $name)
        ->set('brand', $brand)
        ->set('vehicle_id', $vehicle->id)
        ->call('create')
        ->assertDispatched('refreshTable')
        ->assertSet('show', false);

    $this->assertDatabaseHas(VehicleBrand::class, [
        'id' => 1,
        'name' => $name,
        'brand' => $brand,
        'vehicle_id' => $vehicle->id,
    ]);

});

test('exception to create a non unique vehicle-brand', function () {

    $vehicle = Vehicle::factory()->create();

    VehicleBrand::create([
        'name' => 'Non-Unique Vehicle Name',
        'brand' => 'Non-Unique Vehicle Brand',
        'vehicle_id' => $vehicle->id,
    ]);

    Livewire::test(CreateVehicleBrandModal::class)
        ->call('showCreateVehicleBrandModal')
        ->set('name', 'Non-Unique Vehicle Name')
        ->set('brand', 'Non-Unique Vehicle Brand')
        ->set('vehicle_id', $vehicle->id)
        ->call('create')
        ->assertHasErrors([
            'name' => 'unique',
            'brand' => 'unique',
        ]);

});

test('vehicle update', function () {

    $user = User::factory()->create();
    $vehicle = Vehicle::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/vehicle/' . $vehicle->id .'/edit');

    $response->assertOk();

    $request = mock(VehicleUpdateRequest::class);

    $request->shouldReceive('validated')
        ->withNoArgs()
        ->andReturn([
            'name' => 'updated name'
        ]);

    $service = new VehicleService();

    $service->update($request, $vehicle);

    assertDatabaseCount('vehicles', 1);
    assertDatabaseHas('vehicles', [
        'id' => 1,
        'name' => 'updated name'
    ]);

});


test('updates a vehicle field on editable update to livewire powergrid', function () {

    $vehicle = Vehicle::create(['name' => 'Old Name']);

    Livewire::test(VehicleTable::class)
        ->set('selectedRow', $vehicle->id)
        ->call('onUpdatedEditable', (string) $vehicle->id, 'name', 'New Name');

    $this->assertDatabaseHas('vehicles', [
        'id' => $vehicle->id,
        'name' => 'New Name'
    ]);
});
