<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslations extends Model
{
    protected $table = 'categories_translations';
    protected $fillable = ['category_id', 'lang_code','category_name'];
}
