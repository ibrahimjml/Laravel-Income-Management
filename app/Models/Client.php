<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClientTranslation;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{ 

  use ClientTranslation, LogsActivity;
  protected $table = "clients";
  protected $primaryKey = 'client_id';
  protected $activitySubjectName = ['client_fname', 'client_lname'];
  public $timestamps = false;

  protected $fillable = [
          'client_fname',
          'client_lname',
          'email',
          'client_phone',
          'created_at',
          'is_deleted',
  ];

  public function types()
  {
      return $this->belongsToMany(ClientType::class, 'client_types_relation', 'client_id', 'type_id')
                  ->withPivot('created_at', 'is_deleted')
                  ->wherePivot('is_deleted', 0);
  }
  public function trashedTypes()
{
    return $this->belongsToMany(ClientType::class, 'client_types_relation', 'client_id', 'type_id')
                ->withPivot('created_at', 'is_deleted')
                ->wherePivot('is_deleted', 1);
}
  public function income()
  {
      return $this->hasMany(Income::class, 'client_id');
  }
    public function scopeNotDeleted($query)
  {
    return $query->where('is_deleted',0);
  }
  public function scopeIsDeleted($query)
{
    return $query->where('is_deleted', 1);
}
  public function scopeDateBetween($query, $from, $to)
{
    if ($from && $to) {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    return $query;
}
   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['client_fname', 'client_lname', 'email', 'client_phone','is_deleted'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "client {$eventName}");
      }
     public function getActivitySubjectNameAttribute()
    {
        $fields = $this->activitySubjectName;
        $values = [];

        foreach ($fields as $field) {
            if (isset($this->$field)) {
                $values[] = $this->$field;
            }
        }

        return implode(' ', $values);
    }
}
