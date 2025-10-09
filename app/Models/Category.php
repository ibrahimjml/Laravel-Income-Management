<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\CategoryTranslation;
class Category extends Model
{
  use CategoryTranslation;
  protected $primaryKey = 'category_id';
  public $timestamps = false;

  protected $fillable = [
      'category_name', 'category_type', 'is_deleted',
  ];

  public function subcategories()
  {
      return $this->hasMany(Subcategory::class, 'category_id');
  }
  public function scopeNotDeleted()
  {
    return $this->where('is_deleted',0);
  }
}
