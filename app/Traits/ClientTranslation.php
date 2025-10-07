<?php

namespace App\Traits;

use App\Models\ClientTranslations;

trait ClientTranslation
{
      public function translations()
    {
        return $this->hasMany(ClientTranslations::class, 'client_id');
    }
  public function translation($lang = null)
    {
        $lang = $lang ?? app()->getLocale();

        return $this->hasOne(ClientTranslations::class, 'client_id')
                    ->where('lang_code', $lang);
    }
    public function getFNameAttribute($locale = null)
  {
      $locale = $locale ?? app()->getLocale();

    if ($locale === 'en') return $this->client_fname;

    $translation = $this->translation($locale)->first();
    return $translation ? $translation->client_fname  : $this->client_fname;
  }
    public function getLNameAttribute($locale = null)
  {
      $locale = $locale ?? app()->getLocale();

    if ($locale === 'en') return $this->client_lname;

    $translation = $this->translation($locale)->first();
    return $translation ? $translation->client_lname  : $this->client_lname;
  }
    public function getFullNameAttribute($locale = null)
  {
    $locale = $locale ?? app()->getLocale();

    if ($locale === 'en') return $this->client_fname . ' ' . $this->client_lname;

    $translation = $this->translation($locale)->first();
    return $translation ? $translation->client_fname . ' ' . $translation->client_lname : $this->client_fname . ' ' . $this->client_lname;
  }
}
