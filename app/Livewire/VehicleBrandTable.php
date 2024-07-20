<?php

namespace App\Livewire;

use App\Exceptions\VehicleException;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class VehicleBrandTable extends PowerGridComponent
{
    use WithExport;

    public array $name;

    public array $brand;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    public $listeners = [
        'refreshTable' => 'render'
    ];

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
        return VehicleBrand::query()->with(['vehicle']);
    }

    public function relationSearch(): array
    {
        return [
            'vehicle' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('brand')
            ->add('vehicle_name', fn(VehicleBrand $model) => e($model->vehicle->name))
            ->add('created_at');
    }

    public function columns(): array
    {
        return [

            Column::make('Brand', 'brand')
                ->editOnClick()
                ->searchable()
                ->sortable(),

            Column::make('Name', 'name')
                ->sortable()
                ->editOnClick()
                ->searchable(),

            Column::make('Vehicle Name', 'vehicle_name')
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            //
        ];
    }


    public function actions(VehicleBrand $vehicleBrand): array
    {
        return [
            Button::add('delete')
                ->slot('Elimina')
                ->id()
                ->class('pg-btn-white dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-700 dark:ring-offset-pg-primary-800 dark:text-pg-primary-300 dark:bg-pg-primary-700')
                ->dispatch('showDeleteModal', ['model' => 'VehicleBrand', 'modelId' => $vehicleBrand->id])
        ];
    }




    protected function rules(): array
    {
        return [
            'name.*' => 'required|string:max:255',
            'brand.*' => 'required|string:max:255',
        ];
    }

    public function onUpdatedEditable(string|int $id, string $field, string $value): void
    {

        $this->validate();

        $vehicle = VehicleBrand::query()->find($id);

        try {
            DB::beginTransaction();

            $vehicle->update([$field => $value]);

            noty()->success('Azione eseguita con successo!');

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error on update vehicle brand', [$e->getMessage()]);
            throw new VehicleException();
        }

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
