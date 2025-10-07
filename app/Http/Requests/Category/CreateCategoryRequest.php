<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            'name_en'       => 'required|string|max:20',
            'name_ar'       => 'required|string|max:20',
            'category_type' => 'nullable|in:Income,Outcome'
        ];
    }
    public function messages(): array
{
    return [
        'name_en.required' => 'The English name is required.',
        'name_en.string'   => 'The English name must be a string.',
        'name_en.max'      => 'The English name may not be greater than 20 characters.',
        'name_ar.required' => 'The Arabic name is required.',
        'name_ar.string'   => 'The Arabic name must be a string.',
        'name_ar.max'      => 'The Arabic name may not be greater than 20 characters.',
        'category_type.in' => 'The category type must be either Income or Outcome.',
    ];
}

}
