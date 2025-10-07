<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTypeTranslations extends Model
{
    protected $table = 'client_type_translations';
    protected $fillable = ['type_id','type_name', 'lang_code','is_deleted'];
}
