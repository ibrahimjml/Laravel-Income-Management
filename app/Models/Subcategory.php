<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\SubcategoryTranslation;
class Subcategory extends Model
{
  use SubcategoryTranslation;
  protected $primaryKey = 'subcategory_id';
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
}
