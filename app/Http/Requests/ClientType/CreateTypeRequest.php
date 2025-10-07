<?php

namespace App\Http\Requests\ClientType;

use Illuminate\Foundation\Http\FormRequest;

class CreateTypeRequest extends FormRequest
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
            'type_name' => 'required|string|min:3|max:40',
            'lang'      => 'required|in:en,ar',
        ];
    }
     public function messages(): array
    {
        return [
            'type_name.required' => 'The client type name is required.',
            'type_name.string' => 'The client type name must be a string.',
            'type_name.max' => 'The client type name may not be greater than 40 characters.',
            'lang.in' => 'The language must be either en or ar.',
        ];
    }
}
