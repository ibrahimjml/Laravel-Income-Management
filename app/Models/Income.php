<?php

namespace App\Models;

use App\Enums\IncomeStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Traits\IncomeTranslation;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Income extends Model
{
  use IncomeTranslation, LogsActivity;
  protected $table = 'income';
  protected $primaryKey = 'income_id';
  protected $appends = ['trans_description'];
  protected $activitySubjectName = 'income_id';
  public $timestamps = false;
  
  protected $fillable = [
    'subcategory_id',
    'amount',
    'discount_amount',
    'final_amount',
    'status',
    'payment_type',
    'description', 
    'next_payment', 
    'date', 
    'client_id',
    'is_deleted',
    'discount_id',
  ];
  
  protected $casts = [
    'next_payment' => 'date',
    'payment_type' => PaymentType::class,
    'status' => IncomeStatus::class,
    'is_deleted' => 'boolean',
    'amount' => 'decimal:2',
    'discount_amount' => 'decimal:2',
    'final_amount' => 'decimal:2',
  ];
  public function client()
  {
      return $this->belongsTo(Client::class, 'client_id');
  }

  public function subcategory()
  {
      return $this->belongsTo(Subcategory::class, 'subcategory_id');
  }

  public function payments()
  {
      return $this->hasMany(Payment::class, 'income_id');
  }
  public function isRecurring()
  {
    return $this->payment_type == 'recurring';
  }
  public function isOneTime()
  {
    return $this->payment_type == 'onetime';
  }
    public function getPaymentAmountsAttribute()
   {
     return $this->payments->pluck('payment_amount');
   }
   public function getTotalPaidAttribute()
  {
    if (array_key_exists('paid_payments_sum_payment_amount', $this->attributes)) {
        return (float) $this->paid_payments_sum_payment_amount;
    }

     return (float) $this->paidPayments()->sum('payment_amount');
  }

     public function getRemainingAttribute()
    {   
       $amount = $this->final_amount > 0 
             ? $this->final_amount 
             : $this->amount;
       return max(0, $amount - $this->total_paid);
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
    public function unpaidPayments()
    {
        return $this->hasMany(Payment::class, 'income_id')
                    ->where('status', PaymentStatus::UNPAID->value)
                    ->notDeleted();
    }
    public function paidPayments()
    {
        return $this->hasMany(Payment::class, 'income_id')
                    ->where('status', PaymentStatus::PAID->value)
                    ->notDeleted();
    }
       public function discount()
      {
        return $this->belongsTo(Discount::class, 'discount_id');
      }
        public function invoices()
      {
        return $this->hasMany(Invoice::class, 'income_id', 'income_id')->where('is_deleted', 0);
      }
        public function getActivitylogOptions(): LogOptions
      {
        return LogOptions::defaults()
        ->logOnly(['final_amount', 'amount', 'status', 'subcategory_id','description','client_id','discount_id','discount_amount','next_payment','is_deleted'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "income {$eventName}");
      }
        public function getActivitySubjectNameAttribute()
     {
        $field = $this->activitySubjectName;
        return $this->$field;
     }
}
