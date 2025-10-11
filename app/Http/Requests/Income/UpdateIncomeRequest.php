<?php

namespace App\Http\Requests\Income;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncomeRequest extends FormRequest
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
            'client_id'      => 'required|integer|exists:clients,client_id',
            'category_id'    => 'required|integer|exists:categories,category_id',
            'subcategory_id' => 'required|integer|exists:subcategories,subcategory_id',
            'amount'         => 'required|numeric|min:0.01',
            'description'    => 'sometimes|string',
            'next_payment'   => 'required|date|after_or_equal:today',
            'lang'           => 'required|in:en,ar'
        ];
    }
}
