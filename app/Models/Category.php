<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CategoryTranslation;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
  use CategoryTranslation, LogsActivity;
  protected $primaryKey = 'category_id';
  protected $activitySubjectName = 'category_name';
  public $timestamps = false;

  protected $fillable = [
      'category_name', 'category_type', 'is_deleted',
  ];

  public function subcategories()
  {
      return $this->hasMany(Subcategory::class, 'category_id');
  }
  public function scopeNotDeleted($query)
  {
    return $query->where('is_deleted',0);
  }
   public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['category_name', 'category_type','is_deleted'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "category {$eventName}");

    }
      public function getActivitySubjectNameAttribute()
    {
        $field = $this->activitySubjectName;
        return $this->$field;
    }
}
