<?php

namespace App\Models;

use App\Traits\OutcomeTranslation;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Outcome extends Model
{
   use OutcomeTranslation, LogsActivity;
  protected $table = 'outcome';
  protected $primaryKey = 'outcome_id';
  protected $appends = ['trans_description'];
  protected $activitySubjectName = 'outcome_id';
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
    public function scopeIsDeleted($query)
  {
    return $query->where('is_deleted',1);
  }
  public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['amount', 'description','is_deleted'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "outcome {$eventName}");
      }
        public function getActivitySubjectNameAttribute()
    {
        $field = $this->activitySubjectName;
        return $this->$field;
    }
}
