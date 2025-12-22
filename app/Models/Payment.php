<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Traits\PaymentTranslation;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
  use PaymentTranslation, LogsActivity;
  protected $primaryKey = 'payment_id';
  protected $appends = ['trans_description'];
  protected $activitySubjectName = 'income_id';
  public $timestamps = false;
  protected $fillable = [
       'income_id',
       'payment_amount',
       'description',
       'status',
       'next_payment',
       'is_deleted',
       'discount_id',
  ];
  protected $casts = [
    'next_payment' => 'date',
    'status'     => PaymentStatus::class,
    'is_deleted' => 'boolean',
    'payment_amount' => 'decimal:2',
  ];
  public function income()
  {
      return $this->belongsTo(Income::class, 'income_id');
  }

  public function scopeDateBetween($query, $from, $to)
{
    if ($from && $to) {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    return $query;
}
    public function scopeNotDeleted($query)
  {
    return $query->where('is_deleted',0);
  }
    public function scopeIsDeleted($query)
  {
    return $query->where('is_deleted',1);
  }
  public function discount()
{
    return $this->belongsTo(Discount::class, 'discount_id');
}
   public function invoices()
 {
        return $this->hasMany(Invoice::class, 'payment_id');
  }
  public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['payment_amount', 'income_id', 'status','next_payment','is_deleted'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "payment {$eventName}");
      }
        public function getActivitySubjectNameAttribute()
    {
        $field = $this->activitySubjectName;
        return $this->$field;
    }
}
