<?php

namespace App\Livewire;

use App\Exceptions\VehicleException;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class VehicleTable extends PowerGridComponent
{
    use WithExport;

    public $listeners = [
        'vehicleUpdated' => 'render',
        'refreshTable' => 'render'
    ];

    public array $name;

    public bool $showErrorBag = true;

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),

            Header::make()->showSearchInput(),

            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Vehicle::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('active')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [

            Column::make('Name', 'name')
                ->sortable()
                ->editOnClick()
                ->searchable(),

            Column::make('active', 'active')
                ->toggleable()
                ->sortable()
                ->bodyAttribute('flex flex-col items-start mt-3'),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

    #[\Livewire\Attributes\On('delete')]
    public function delete($rowId): void
    {
        // $this->js('alert('.$rowId.')');

        try {
            Vehicle::destroy($rowId);

        } catch (\Exception $e){

            dd($e->getMessage());
        }

        noty()->success('Azione eseguita con successo!');
        // $this->dispatch('openModal', component: 'delete-vehicle', arguments:  ['vehicle' =>  $rowId]  );
    }

    public function actions(Vehicle $vehicle): array
    {
        return [
            Button::add('delete')
                ->slot('Elimina')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('showDeleteModal', ['model' => 'Vehicle', 'modelId' => $vehicle->id])
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        try {
            DB::beginTransaction();

            Vehicle::query()->find($id)->update([
                $field => e($value),
            ]);

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Error on update of vehicle', [$e->getMessage()]);
            throw new VehicleException();

        }

        $this->skipRender();
    }


    protected function rules()
    {
        return [
            'name.*' => 'required|string:max:255|unique:vehicles,name',
        ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {

        $this->validate();

        if($field === 'name'){

            $vehicle = Vehicle::query()->find($id);

            try {
                DB::beginTransaction();

                $vehicle->update(['name' => $value]);

                noty()->success('Azione eseguita con successo!');

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error on update vehicle', [$e->getMessage()]);
                throw new VehicleException();
            }
        }


        /*
        if ($field === 'price_in_eur') {
            $field = 'price';

            $value = (new \NumberFormatter('pt-PT', \NumberFormatter::CURRENCY))
                ->parse(preg_replace('/\s+/', "\u{A0}", $value));
        }

        Icecream::query()->find($id)->update([
            $field => e($value),
        ]);

        */
    }

    protected function messages()
    {
        return [
            'name.*.unique' => 'Nome veicolo già utilizzato.',
        ];
    }

    /*
    public function actionRules($row): array
    {
       return [
            // Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($row) => $row->id === 1)
                ->hide(),
        ];
    }
    */
}
