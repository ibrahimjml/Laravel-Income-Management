<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutcomeTranslations extends Model
{
    protected $table = 'outcome_translations';
    protected $fillable = ['outcome_id','lang_code','description','is_deleted'];
}
