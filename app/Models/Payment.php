<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  protected $primaryKey = 'payment_id';
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
}
