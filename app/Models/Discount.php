<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $primaryKey = 'discount_id';
     protected $fillable = [
        'name',
        'rate',
        'type',
        'is_deleted',
    ];
}
