<?php

namespace App\Http\Requests;

use App\Enums\FuelType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ReflectionClass;

class VehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if (request()->isMethod('post')) {
            $plateUnique = 'unique:vehicles,plate';
        } elseif (request()->isMethod('put') || request()->isMethod('patch')) {
            $plateUnique = Rule::unique('plate')->ignore($this->vehicle);
        }

        return [
            'name' => 'required|string',
            'plate' => 'required', 'string', 'email', $plateUnique,
            'load' => 'required|numeric',
            'fuel' => 'required|numeric',
            'fuel_type' => ['required', Rule::in(array_values((new ReflectionClass(FuelType::class))->getConstants())),],
            'group_id' => 'required|exists:groups,id',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
