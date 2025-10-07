<?php

namespace App\Traits;

use App\Models\CategoryTranslations;

trait CategoryTranslation
{
    public function translations()
    {
        return $this->hasMany(CategoryTranslations::class, 'category_id');
    }
    public function translation($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        return $this->hasOne(CategoryTranslations::class, 'category_id')
                    ->where('lang_code', $lang);
    }
      public function getNameAttribute($locale = null)
  {
      $locale = $locale ?? app()->getLocale();

    if ($locale === 'en') return $this->category_name;

    $translation = $this->translation($locale)->first();
    return $translation ? $translation->category_name  : $this->category_name;
  }
}
