<?php

namespace App\Models;

use App\Traits\OutcomeTranslation;
use Illuminate\Database\Eloquent\Model;

class Outcome extends Model
{
   use OutcomeTranslation;
  protected $table = 'outcome';
  protected $primaryKey = 'outcome_id';
  protected $appends = ['trans_description'];
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
    public function scopeNotDeleted($query)
  {
    return $query->where('is_deleted',0);
  }
}
