<?php

namespace App\Services;

use App\Exceptions\VehicleException;
use App\Http\Requests\VehicleStoreRequest;
use App\Http\Requests\VehicleUpdateRequest;
use App\Models\Vehicle;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VehicleService
{

    /**
     * @param VehicleStoreRequest $request
     * @throws VehicleException
     */
    public function create(VehicleStoreRequest $request): void
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            Vehicle::create($validated);

            noty()->success('Azione eseguita con successo!');

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Error on creation of vehicle', [$e->getMessage()]);
            throw new VehicleException();
        }
    }

    /**
     * @param VehicleUpdateRequest $request
     * @param Vehicle $vehicle
     * @return void
     * @throws VehicleException
     */
    public function update(VehicleUpdateRequest $request, Vehicle $vehicle): void
    {
        //
    }

    public function destroy(Vehicle $vehicle): void
    {
        try {
            DB::beginTransaction();

            $vehicle->load('vehicleBrands')->get();

            $vehicle->delete();

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('Error on update vehicle', [$e->getMessage()]);
            throw new VehicleException();
        }

    }
}
