<?php

namespace App\Http\Requests;

use App\Models\Vehicle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request): array
    {
        switch ($this->method()) {
            case 'PUT':
            case 'PATCH':
                return [
                    'name' => ['required','string',Rule::unique(Vehicle::class)->ignore($request->get('name'), 'name')]
                ];
                break;
            default:
                return [
                    'name' => 'required|string|unique:vehicles,name'
                ];

        }
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'Nome veicolo già utilizzato!',
            'name.required' => 'Nome veicolo necessario!',
        ];
    }
}
