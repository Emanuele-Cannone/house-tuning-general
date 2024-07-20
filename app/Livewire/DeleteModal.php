<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DeleteModal extends Component
{

    public $model;
    public $modelId;
    public $show = false;


    protected $listeners = ['showDeleteModal', 'closeModal'];

    public function showDeleteModal($model, $modelId): void
    {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->show = true;
    }

    public function closeModal(): void
    {
        $this->show = false;
    }

    public function delete(): void
    {
        $modelClass = "App\\Models\\" . $this->model;
        $modelInstance = $modelClass::find($this->modelId);

        if ($modelInstance) {

            try {

                $modelInstance->delete();
                noty()->success('Azione eseguita con successo!');

            } catch (\Exception $e) {

                Log::error($e->getMessage());
            }
        }

        $this->show = false;
        $this->dispatch('refreshTable');
    }

    public function render()
    {
        return view('livewire.delete-modal');
    }
}
