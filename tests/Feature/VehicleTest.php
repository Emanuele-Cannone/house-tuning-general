<?php

use App\Http\Requests\VehicleUpdateRequest;
use App\Livewire\CreateVehicleModal;
use App\Livewire\VehicleTable;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\VehicleService;
use Livewire\Livewire;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

test('vehicle create page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/vehicle/create');

    $response->assertOk();
});

test('open and close modal', function () {


    Livewire::test(CreateVehicleModal::class)
        ->call('showCreateVehicleModal')
        ->assertSet('show', true)
        ->call('closeModal')
        ->assertSet('show', false);

});


test('create vehicle from modal', function () {


    $name = fake()->word;

    Livewire::test(CreateVehicleModal::class)
        ->call('showCreateVehicleModal')
        ->set('name', $name)
        ->call('create')
        ->assertDispatched('refreshTable')
        ->assertSet('show', false);

    $this->assertDatabaseHas('vehicles', [
        'id' => 1,
        'name' => $name
    ]);

});

test('exception to create a non unique vehicle', function () {


    Vehicle::create(['name' => 'Non-Unique Vehicle Name']);

    Livewire::test(CreateVehicleModal::class)
        ->call('showCreateVehicleModal')
        ->set('name', 'Non-Unique Vehicle Name')
        ->call('create')
        ->assertHasErrors(['name' => 'unique']);

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
