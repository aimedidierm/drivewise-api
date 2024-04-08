<?php

namespace App\Http\Requests;

use App\Enums\MaintenanceTimeUnit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ReflectionClass;

class MaintenanceRequest extends FormRequest
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
        return [
            'title' => 'required|string',
            'notification' => 'required|string',
            'interval' => 'required|integer',
            'unit' => ['required', 'string', Rule::in(array_values((new ReflectionClass(MaintenanceTimeUnit::class))->getConstants())),],
            'vehicle_id' => 'required|integer|exists:vehicles,id'
        ];
    }
}
