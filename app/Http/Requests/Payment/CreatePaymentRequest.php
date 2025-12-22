<?php

namespace App\Http\Requests\Payment;

use App\Enums\PaymentStatus;
use App\Models\Income;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Enum;

class CreatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation(): void
{
    if (! $this->income_id) {
        return;
    }

    $income = Income::find($this->income_id);

    if (! $income) {
        return;
    }

    // Use your accessor directly
    $this->merge([
        'remaining' => $income->remaining,
    ]);
}
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'income_id'      => 'required|exists:income,income_id',
            'payment_amount' => 'required|numeric|min:0|lte:remaining',
            'status'         => ['required',new Enum(PaymentStatus::class)],
            'description'    => 'nullable|string',
            'next_payment'   => ['nullable','required_if:status,'.PaymentStatus::UNPAID->value,'date','after_or_equal:today'],
            'remaining'      => 'required|numeric|min:0',
            'lang'           => 'required|in:en,ar'

        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
{
    Log::error('Payment validation failed', [
        'errors' => $validator->errors()->toArray(),
        'input'  => $this->all(),
    ]);

    parent::failedValidation($validator);
}

}
