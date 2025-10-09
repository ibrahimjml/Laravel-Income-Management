<?php

namespace App\Traits;

use App\Models\OutcomeTranslations;

trait OutcomeTranslation
{
      public function translations()
    {
        return $this->hasMany(OutcomeTranslations::class, 'outcome_id');
    }
    public function translation($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        return $this->hasOne(OutcomeTranslations::class, 'outcome_id')
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
