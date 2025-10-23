<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Discount extends Model
{
    use LogsActivity;
    protected $table = 'discounts';
    protected $primaryKey = 'discount_id';
    protected $activitySubjectName = 'name';
     protected $fillable = [
        'name',
        'rate',
        'type',
        'is_deleted',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['name', 'type','is_deleted','rate'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "discount {$eventName}");
      }
        public function getActivitySubjectNameAttribute()
    {
        $field = $this->activitySubjectName;
        return $this->$field;
    }
}
