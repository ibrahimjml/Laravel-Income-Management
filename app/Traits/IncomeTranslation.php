<?php

namespace App\Traits;

use App\Models\IncomeTranslations;

trait IncomeTranslation
{
      public function translations()
    {
        return $this->hasMany(IncomeTranslations::class, 'income_id');
    }
    public function translation($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        return $this->hasOne(IncomeTranslations::class, 'income_id')
                    ->where('lang_code', $lang);
    }
      public function getTransDescriptionAttribute($locale = null)
  {
      $locale = $locale ?? app()->getLocale();

    if ($locale === 'en') return $this->description;

    $translation = $this->translation($locale)->first();
    return $translation ? $translation->description  : $this->description;
  }
}
