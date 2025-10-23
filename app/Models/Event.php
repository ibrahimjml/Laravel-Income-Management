<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model
{
    use LogsActivity;
    protected $table = 'events';
    protected $primaryKey = 'event_id';
    protected $activitySubjectName = 'event_name';
    protected $fillable = ['event_name','color','start_date','end_date'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['event_name'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "event {$eventName}");
      }
        public function getActivitySubjectNameAttribute()
    {
        $field = $this->activitySubjectName;
        return $this->$field;
    }
}
