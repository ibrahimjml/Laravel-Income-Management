<?php

namespace App\Models;

use App\Traits\IncomeTranslation;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
  use IncomeTranslation;
  protected $table = 'income';
  protected $primaryKey = 'income_id';
  protected $appends = ['trans_description'];
  public $timestamps = false;

  protected $fillable = [
      'subcategory_id', 'amount', 'status', 'description', 'next_payment', 'date', 'client_id', 'is_deleted',
  ];

  public function client()
  {
      return $this->belongsTo(Client::class, 'client_id');
  }

  public function subcategory()
  {
      return $this->belongsTo(Subcategory::class, 'subcategory_id');
  }

  public function payments()
  {
      return $this->hasMany(Payment::class, 'income_id');
  }

  public function getRemainingAttribute()
{
    return $this->amount - $this->paid;
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
