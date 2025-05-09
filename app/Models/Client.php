<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
  protected $table = "clients";
  protected $primaryKey = 'client_id';
  public $timestamps = false;

  protected $fillable = [
      'client_fname', 'client_lname', 'client_phone', 'created_at', 'is_deleted',
  ];

  public function types()
  {
      return $this->belongsToMany(ClientType::class, 'client_types_relation', 'client_id', 'type_id')
                  ->withPivot('created_at', 'is_deleted');
  }

  public function income()
  {
      return $this->hasMany(Income::class, 'client_id');
  }

  public function scopeDateBetween($query, $from, $to)
{
    if ($from && $to) {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    return $query;
}
}
