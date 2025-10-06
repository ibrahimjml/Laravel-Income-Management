<?php

namespace App\Http\Requests\Income;

use Illuminate\Foundation\Http\FormRequest;

class CreateIncomeRequest extends FormRequest
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
               'paid'           => 'nullable|numeric|min:0',
               'description'    => 'required|string',
               'next_payment'   => 'required|date'
        ];
    }
    public function messages(): array
    {
       return [
            'client_id.exists'          =>'Selected client is invalid.',
            'client_id.required'        =>'Client is required.',
            'category_id.required'      =>'Category is required',
            'subcategory_id.required'   =>'Subcategory is required',
            'amount.required'           =>'Amount is required',
            'description.required'      =>'Description is required',
            'next_payment.required'     =>'Next payment is required',

       ];
    }
}
