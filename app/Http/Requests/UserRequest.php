<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            $passwordRule = 'required';
            $emailUnique = 'unique:users,email';
        } elseif (request()->isMethod('put') || request()->isMethod('patch')) {
            $passwordRule = 'sometimes';
            $emailUnique = Rule::unique('users')->ignore($this->driver);
        }

        return [
            'name' => 'required|string',
            'email' => 'required', 'string', 'email', $emailUnique,
            'phone' => 'required|string',
            'password' => [$passwordRule, 'required', 'string', 'min:6']
        ];
    }
}
