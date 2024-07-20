<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\VehicleBrand;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateVehicleBrandModal extends Component
{

    public string $name;
    public string $brand;
    public collection $vehicles;
    public int $vehicle_id;
    public bool $show = false;

    protected $listeners = ['showCreateVehicleBrandModal', 'closeModal'];

    public function mount()
    {
        $this->vehicles = Vehicle::all();
    }

    public function showCreateVehicleBrandModal()
    {
        $this->reset(['name','brand','vehicle_id']);
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false; // Nasconde la modale
    }

    public function create()
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                Rule::unique(VehicleBrand::class)->where(function ($query) {
                    return $query->where('brand', $this->brand);
                })
            ],
            'brand' => [
                'required',
                'string',
                Rule::unique(VehicleBrand::class)->where(function ($query) {
                    return $query->where('name', $this->name);
                })
            ],
            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
            ],
        ]);

        try {

            DB::beginTransaction();
            VehicleBrand::create($validated);
            DB::commit();

            noty()->success('Azione eseguita con successo!');
        } catch (\Exception $exception) {

            DB::rollBack();
            Log::error($exception->getMessage());

        }


        $this->closeModal(); // Chiude la modale dopo la creazione
        $this->dispatch('refreshTable'); // Emette un evento per aggiornare la tabella
    }

    public function render()
    {
        return view('livewire.create-vehicle-brand-modal');
    }
}
