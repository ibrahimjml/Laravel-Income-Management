<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientType extends Model
{
  protected $table = 'client_type';
  protected $primaryKey = 'type_id';
  public $timestamps = false;

  protected $fillable = [
      'type_name', 'is_deleted',
  ];

  public function clients()
  {
      return $this->belongsToMany(Client::class, 'client_types_relation', 'type_id', 'client_id')
                  ->withPivot('created_at', 'is_deleted');
  }
}
