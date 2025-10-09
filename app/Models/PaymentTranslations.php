<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTranslations extends Model
{
    protected $table = 'payment_translations';
    protected $fillable = ['payment_id','lang_code','description','is_deleted'];
}
