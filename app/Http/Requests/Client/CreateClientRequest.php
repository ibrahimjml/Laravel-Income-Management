<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
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
              'client_fname' => 'required|string|max:20',
              'client_lname' => 'required|string|max:20',
              'client_phone' => 'required|numeric',
              'email'        => 'nullable|email|unique:clients,client_id',
              'type_id'      => 'required|array',
              'type_id.*'    => 'exists:client_type,type_id', 
              'lang'         => 'required|in:en,ar',
        ];
    }
    public function messages(): array
    {
        return [
            'client_fname.required' => 'First name is required.',
            'client_fname.max'      => 'First name may not be greater than 20 characters.',
            'client_lname.required' => 'Last name is required.',
            'client_lname.max'      => 'Last name may not be greater than 20 characters.',
            'email.unique'          => 'Email is already in used.',
            'client_phone.required' => 'Phone number is required.',
            'client_phone.numeric'  => 'Phone number must be numeric.',
            'type_id.required'      => 'At least one client type is required.',
            'type_id.array'         => 'Client types must be provided as an array.',
            'type_id.min'           => 'At least one client type must be selected.',
            'type_id.*.exists'      => 'Selected client type is invalid.'
        ];
    }
}
