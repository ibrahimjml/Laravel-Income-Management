<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTypesRelation extends Model
{
  protected $table = 'client_types_relation';
  protected $primaryKey = 'id';
  public $timestamps = false;

  protected $fillable = [
      'client_id', 'type_id', 'is_deleted', 'created_at',
  ];

    public function scopeNotDeleted($query)
  {
    return $query->where('is_deleted',0);
  }
}
