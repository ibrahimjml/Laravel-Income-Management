<?php

namespace App\Models;

use App\Traits\PaymentTranslation;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  use PaymentTranslation;
  protected $primaryKey = 'payment_id';
  protected $appends = ['trans_description'];
  public $timestamps = false;

  protected $fillable = [
      'income_id', 'payment_amount', 'description', 'is_deleted',
  ];

  public function income()
  {
      return $this->belongsTo(Income::class, 'income_id');
  }

  public function scopeDateBetween($query, $from, $to)
{
    if ($from && $to) {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    return $query;
}
    public function scopeNotDeleted($query)
  {
    return $query->where('is_deleted',0);
  }
}
