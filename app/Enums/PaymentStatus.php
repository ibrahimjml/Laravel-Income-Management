<?php

namespace App\Enums;

enum PaymentStatus:string
{
    case PAID = 'paid';
    case UNPAID = 'unpaid';

    public function label(): string
    {
        return __('income.payment_status.' . $this->value);
    }


}
