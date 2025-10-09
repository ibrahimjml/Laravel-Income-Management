<?php

namespace App\Http\Requests\Outcome;

use Illuminate\Foundation\Http\FormRequest;

class CreateOutcomeRequest extends FormRequest
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
            'category_id'    => 'required|integer|exists:categories,category_id',
            'subcategory_id' => 'required|integer|exists:subcategories,subcategory_id',
            'amount'         => 'required|numeric|min:0.01',
            'description'    => 'required|string',
            'lang'           => 'required|in:en,ar'
        ];
    }
}
