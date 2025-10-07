<?php

namespace App\Http\Requests\Subcategory;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubcategoryRequest extends FormRequest
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
            'category_id' => 'required|integer|exists:categories,category_id',
            'name_en' => 'required|string|max:20',
            'name_ar' => 'required|string|max:20',
        ];
    }
    public function messages()
{
    return [
        'category_id.required' => 'The category field is required.',
        'category_id.integer'  => 'The category must be a valid selection.',
        'category_id.exists'   => 'The selected category does not exist.',
        'name_en.required'     => 'The English name is required.',
        'name_en.string'       => 'The English name must be a string.',
        'name_en.max'          => 'The English name may not be greater than 20 characters.',
        'name_ar.required'     => 'The Arabic name is required.',
        'name_ar.string'       => 'The Arabic name must be a string.',
        'name_ar.max'          => 'The Arabic name may not be greater than 20 characters.',
    ];
}
}
