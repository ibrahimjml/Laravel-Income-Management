<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ClientTypeTranslation;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientType extends Model
{ 
  use ClientTypeTranslation, LogsActivity;
  protected $table = 'client_type';
  protected $primaryKey = 'type_id';
  protected $activitySubjectName = 'type_name';
  public $timestamps = false;

  protected $fillable = [
      'type_name', 'is_deleted',
  ];

  public function clients()
  {
      return $this->belongsToMany(Client::class, 'client_types_relation', 'type_id', 'client_id')
                  ->withPivot('created_at', 'is_deleted');
  }
    public function scopeNotDeleted($query)
  {
    return $query->where('is_deleted',0);
  }
   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['type_name','is_deleted'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "client type {$eventName}");
      }
    public function getActivitySubjectNameAttribute()
    {
        $field = $this->activitySubjectName;
        return $this->$field;
    }

}
