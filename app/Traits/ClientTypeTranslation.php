<?php

namespace App\Traits;

use App\Models\ClientTypeTranslations;

trait ClientTypeTranslation
{
      public function translations()
    {
        return $this->hasMany(ClientTypeTranslations::class, 'type_id');
    }
    public function translation($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        return $this->hasOne(ClientTypeTranslations::class, 'type_id')
                    ->where('lang_code', $lang);
    }
      public function getTypesNameAttribute($locale = null)
    {
      $locale = $locale ?? app()->getLocale();

    if ($locale === 'en') return $this->type_name;

    $translation = $this->translation($locale)->first();
    return $translation ? $translation->type_name  : $this->type_name;
    }
}
