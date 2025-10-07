<?php

namespace App\Traits;

use App\Models\SubcategoryTranslations;

trait SubcategoryTranslation
{
      public function translations()
    {
        return $this->hasMany(SubcategoryTranslations::class, 'subcategory_id');
    }
    public function translation($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        return $this->hasOne(SubcategoryTranslations::class, 'subcategory_id')
                    ->where('lang_code', $lang);
    }
      public function getNameAttribute($locale = null)
  {
      $locale = $locale ?? app()->getLocale();

    if ($locale === 'en') return $this->sub_name;

    $translation = $this->translation($locale)->first();
    return $translation ? $translation->sub_name  : $this->sub_name;
  }
}
