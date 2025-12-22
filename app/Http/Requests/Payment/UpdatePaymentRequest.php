<?php

namespace App\Http\Requests\Payment;

use App\Models\Income;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentRequest extends FormRequest
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
            'payment_amount' => 'required|numeric|min:0.01|lte:remaining',
            'status'         => 'required|in:paid,unpaid',
            'description'    => 'nullable|string',
            'next_payment'   => 'nullable|date',
            'remaining'      => 'required|numeric|min:0',
            'lang'           => 'required|in:en,ar'
        ];
    }
}
