<?php

namespace App\Enums;

enum IncomeStatus:string
{
    case PENDING = 'pending';
    case PARTIAL = 'partial';
    case COMPLETE = 'complete';

    public function label(): string
    {
        return __('income.income_status.' . $this->value);
    }


}
