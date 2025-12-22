<?php

namespace App\Http\Requests\Income;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
               'payment_type'   =>  ['required',new Enum(PaymentType::class)],
               'paid'           => ['nullable', 'numeric', 'min:0', 'lte:amount'],
               'payment_status' => ['required',new Enum(PaymentStatus::class)],
               'discount_id'    => 'nullable|exists:discounts,discount_id',
               'description'    => 'sometimes|nullable|string|max:200',
               'next_payment'   => ['nullable','required_if:payment_status,'.PaymentStatus::UNPAID->value,'date','after_or_equal:today'],
               'lang'           => 'required|in:en,ar'
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
            'paid.min'                    => 'Paid amount cannot be negative.',
            'paid.lte'                    => 'Paid amount cannot exceed the total amount.',
            'description.required'        =>'Description is required',
            'description.max'             => 'Description may not be greater than 200 characters.',
            'next_payment.required'       => 'Next payment date is required.',
            'next_payment.after_or_equal' => 'Next payment date must be today or in the future.',

       ];
    }
}
