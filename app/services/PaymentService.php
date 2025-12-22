<?php

namespace App\Services;

use App\Enums\IncomeStatus;
use App\Enums\PaymentStatus;
use App\Models\Income;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function getPaymentsData()
    {
        $today = Carbon::today()->toDateString();

        $outdatedPayments = $this->getPaymentsByDate($today, '<');
        $todayPayments    = $this->getPaymentsByDate($today, '=');
        $upcomingPayments = $this->getPaymentsByDate($today, '>');

        return [

           'outdated_payments' => $outdatedPayments,
           'today_payments'    => $todayPayments,
           'upcoming_payments' => $upcomingPayments
        ];
    }
    public function addPayment(int $incomeId, array $data)
    {
        return DB::transaction(function() use($incomeId, $data){

        $payment = Payment::create([
            'income_id'      => $incomeId,
            'payment_amount' => $data['payment_amount'],
            'next_payment'   => $data['next_payment'] ?? null,
            'status'         => PaymentStatus::from($data['status'])->value,
            'description'    => $data['description'] ?? null
        ]);

        if (!empty($data['description']) && $data['lang'] === 'ar') {
            $payment->translations()->create([
                'lang_code'   => $data['lang'] ?? 'ar',
                'description' => $data['description'],
                'created_at'  => now()
            ]);
        }

        if (!empty($data['next_payment'])) {
            Income::where('income_id', $incomeId)
                ->update(['next_payment' => $data['next_payment']]);
        }

         $income = Income::with('payments')->findOrFail($incomeId);
         $totalPaid = $income->total_paid;

          $amount =  $income->final_amount && $income->final_amount > 0
                           ? $income->final_amount
                           : $income->amount;

        $income->update([
          'status' => match (true) {
              $totalPaid <= 0        => IncomeStatus::PENDING->value,
              $totalPaid <  $amount  => IncomeStatus::PARTIAL->value,
               default               => IncomeStatus::COMPLETE->value,
             }
        ]);
        return $payment;
    });
    }
    public function editPayment(int $paymentId,int $incomeId, array $data)
    {
       return DB::transaction(function() use($paymentId,$incomeId, $data){
        $payment = Payment::findOrFail($paymentId);
            $lang = $data['lang'] ?? 'en';


             $payment->update([
               'income_id'      => $incomeId,
               'payment_amount' => $data['payment_amount'],
               'status'         => PaymentStatus::from($data['status']),
               'next_payment'   => $data['next_payment'] ?? null,
               'description'    => $data['description'] ?? null
             ]);

             if(!empty($data['description']) && $lang == 'ar'){
                 $payment->translations()->update([
                'lang_code' => 'ar',
                'description' => $data['description'],
            ]);
             }
          
           if (isset($data['next_payment'])) {
               Income::where('income_id', $incomeId)
                   ->update(['next_payment' => $data['next_payment']]);
           }

           $income    = Income::findOrFail($incomeId);
          $totalPaid =  Payment::where('income_id', $incomeId)
                                     ->where('status', PaymentStatus::PAID->value)
                                     ->sum('payment_amount');
                   
          $amount = $income->final_amount && $income->final_amount > 0
                           ? $income->final_amount
                           : $income->amount;
          
            $status = IncomeStatus::PENDING->value;
        if ($totalPaid >= $amount) {
            $status = IncomeStatus::COMPLETE->value;
        } elseif ($totalPaid > 0 && $totalPaid < $amount) {
            $status = IncomeStatus::PARTIAL->value;
        }

           $income->update(['status' => $status]);
           return $payment;
       });
    }
    public function deletePayment(Payment $payment, Income $income)
    {
        return DB::transaction(function() use($payment, $income){

            $payment->update([
              'is_deleted' => 1
            ]);

            $totalPaid = $income->total_paid;

            $income->update([
              'status' => match (true) {
                      $totalPaid <= 0 => IncomeStatus::PENDING,
                      ($totalPaid > 0 && $totalPaid < $income->amount) => IncomeStatus::PARTIAL,
                      default => IncomeStatus::COMPLETE,
                  }
            ]);
            return $payment;
        });
    }
public function getPaymentsByDate(string $today, string $operator = '=', ?int $paginate = null)
{
    $query = Income::notDeleted()
        ->with(['client', 'unpaidPayments'])
        ->withSum('paidPayments', 'payment_amount')
        ->where('status', '!=', IncomeStatus::COMPLETE->value)
        ->whereDate('next_payment', $operator, $today)
        ->whereHas('client', fn($q) => $q->notDeleted())
        ->whereHas('unpaidPayments');

    // with pagination
    $addNextPaymentAmount = function ($collection) {
        return $collection->map(function ($income) {
            $nextPayment = $income->unpaidPayments->first();
            $income->next_payment_amount = $nextPayment ? $nextPayment->payment_amount : 0;
            return $income;
        });
    };

    if ($paginate) {
        $result = $query->paginate($paginate);

        $result->getCollection()->transform(function ($income) {
            $nextPayment = $income->unpaidPayments->first();
            $income->next_payment_amount = $nextPayment ? $nextPayment->payment_amount : 0;
            return $income;
        });
        return $result;
    }

    return $addNextPaymentAmount($query->get());
}

}
