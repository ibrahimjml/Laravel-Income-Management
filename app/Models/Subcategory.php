<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SubcategoryTranslation;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Subcategory extends Model
{
  use SubcategoryTranslation, LogsActivity;
  protected $primaryKey = 'subcategory_id';
  protected $activitySubjectName = 'sub_name';
  public $timestamps = false;

  protected $fillable = [
      'sub_name', 'category_id', 'is_deleted',
  ];

  public function category()
  {
      return $this->belongsTo(Category::class, 'category_id');
  }

  public function income()
  {
      return $this->hasMany(Income::class, 'subcategory_id');
  }

  public function outcome()
  {
      return $this->hasMany(Outcome::class, 'subcategory_id');
  }
    public function scopeNotDeleted($query)
  {
    return $query->where('is_deleted',0);
  }
  public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['sub_name', 'category_id','is_deleted'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "subcategory {$eventName}");
      }
        public function getActivitySubjectNameAttribute()
    {
        $field = $this->activitySubjectName;
        return $this->$field;
    }
}
