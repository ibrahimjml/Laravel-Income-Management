<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTranslations extends Model
{    protected $table = 'clients_translations';
     protected $fillable = ['client_id', 'lang_code', 'client_fname', 'client_lname','is_deleted'];

}
