<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
      protected $table = 'invoices';

    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'income_id',
        'payment_id',
        'payment_amount',
        'amount',
        'status',
        'description',
        'issue_date',
        'created_at',
    ];

    public function income()
    {
        return $this->belongsTo(Income::class, 'income_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
