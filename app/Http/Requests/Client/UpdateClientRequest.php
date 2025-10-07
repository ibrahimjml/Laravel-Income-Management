<?php

namespace App\Http\Requests\Client;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
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
         $clientId = $this->route('id');

          return [
              'client_fname' => 'sometimes|required|string|max:20',
              'client_lname' => 'sometimes|required|string|max:20',
              'client_phone' => ['sometimes','required','numeric', Rule::unique('clients')->ignore($clientId,'client_id')],
              'email'        => ['sometimes','nullable','email', Rule::unique('clients')->ignore($clientId,'client_id')],
              'type_id'      => 'sometimes|required|array',
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
              'email.unique'          => 'Email is already used.',
              'client_phone.required' => 'Phone number is required.',
              'client_phone.numeric'  => 'Phone number must be numeric.',
              'client_phone.unique'   => 'Phone number is already used.',
              'type_id.required'      => 'At least one client type is required.',
              'type_id.array'         => 'Client types must be provided as an array.',
              'type_id.min'           => 'At least one client type must be selected.',
              'type_id.*.exists'      => 'Selected client type is invalid.'
    ];
    }
}
