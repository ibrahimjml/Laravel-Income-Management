<?php

namespace App\Enums;

enum PaymentType:string
{
    case ONETIME = 'onetime';
    case RECURRING = 'recurring';

    public function label(): string
    {
        return __('income.payment_type.' . $this->value);
    }


}
