<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
      use LogsActivity;
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    protected $activitySubjectName = 'invoice_id';
    protected $fillable = [
        'income_id',
        'payment_id',
        'payment_amount',
        'amount',
        'status',
        'description',
        'issue_date',
        'created_at',
    ];

    public function income()
    {
        return $this->belongsTo(Income::class, 'income_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['income_id', 'payment_id', 'description'])
        ->logOnlyDirty()
        ->setDescriptionForEvent(fn(string $eventName) => "invoice {$eventName}");
      }
        public function getActivitySubjectNameAttribute()
    {
        $field = $this->activitySubjectName;
        return $this->$field;
    }
}
