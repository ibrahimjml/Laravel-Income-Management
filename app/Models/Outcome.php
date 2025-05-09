<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
  protected $table = 'outcome';
  protected $primaryKey = 'outcome_id';
  public $timestamps = false;

  protected $fillable = [
      'subcategory_id', 'amount', 'description', 'date', 'is_deleted',
  ];

  public function subcategory()
  {
      return $this->belongsTo(Subcategory::class, 'subcategory_id');
  }

  public function scopeDateBetween($query, $from, $to)
{
    if ($from && $to) {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    return $query;
}
}
