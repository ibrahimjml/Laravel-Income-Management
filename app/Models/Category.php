<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $primaryKey = 'category_id';
  public $timestamps = false;

  protected $fillable = [
      'category_name', 'category_type', 'is_deleted',
  ];

  public function subcategories()
  {
      return $this->hasMany(Subcategory::class, 'category_id');
  }
}
