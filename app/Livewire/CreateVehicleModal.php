<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Livewire\Component;

class CreateVehicleModal extends Component
{

    public $name;
    public $show = false;

    protected $listeners = ['showCreateVehicleModal', 'closeModal'];

    public function showCreateVehicleModal()
    {
        $this->reset(['name']);
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false; // Nasconde la modale
    }

    public function create()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:vehicles',
        ]);

        try {
           Vehicle::create($validated);
            noty()->success('Azione eseguita con successo!');
        } catch (\Exception $exception) {

        }


        $this->closeModal(); // Chiude la modale dopo la creazione
        $this->dispatch('refreshTable'); // Emette un evento per aggiornare la tabella
    }

    public function render()
    {
        return view('livewire.create-vehicle-modal');
    }
}
