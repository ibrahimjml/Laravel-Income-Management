<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function getPaymentsData()
    {
        $today = Carbon::today()->toDateString();

        $outdatedPayments = $this->getOutDatedPayments($today);
        $todayPayments    = $this->getTodayPayments($today);
        $upcomingPayments = $this->getUpComingPayments($today);

        return [

           'outdated_payments' => $outdatedPayments,
           'today_payments'    => $todayPayments,
           'upcoming_payments' => $upcomingPayments
        ];
    }
    public function addPayment(int $incomeId, array $data)
    {
       return DB::transaction(function() use($incomeId, $data){
          $payment =  Payment::create([
               'income_id'      => $incomeId,
               'payment_amount' => $data['payment_amount'],
               'description'    => $data['description'] ?? null
             ]);

           if (isset($data['next_payment'])) {
               Income::where('income_id', $incomeId)
                   ->update(['next_payment' => $data['next_payment']]);
           }

           $income    = Income::findOrFail($incomeId);
           $totalPaid = Payment::where('income_id', $incomeId)->sum('payment_amount');
           
           $status = 'pending';
           if ($totalPaid > 0 && $totalPaid < $income->amount) {
               $status = 'partial';
           } elseif ($totalPaid >= $income->amount) {
               $status = 'complete';
           }
   
           $income->update(['status' => $status]);
           return $payment;
       });
    }
    protected function getOutDatedPayments(string $today)
    {
      return  Income::with(['client', 'payments'])
                    ->whereDate('next_payment', '<', $today)
                    ->where('is_deleted', 0)
                    ->whereHas('client', function($query) {
                        $query->where('is_deleted', 0);
                       })
                    ->get()
                    ->map(function($income) {

                        $income->total_paid = $income->payments->sum('payment_amount');
                        return $income;
                    });
    }
    protected function getTodayPayments(string $today)
    {
      return Income::with(['client', 'payments'])
                    ->whereDate('next_payment', $today)
                    ->where('is_deleted', 0)
                    ->where('status','!=','complete')
                    ->whereHas('client', function($query) {
                        $query->where('is_deleted', 0);
                    })
                    ->get()
                    ->map(function($income) {
                          
                          $income->total_paid = $income->payments->sum('payment_amount');
                          return $income;
                    });
    }
    protected function getUpComingPayments(string $today)
    {
      return Income::with(['client', 'payments'])
                    ->whereDate('next_payment', '>', $today)
                    ->where('is_deleted', 0)
                    ->where('status','!=','complete')
                    ->whereHas('client', function($query) {
                        $query->where('is_deleted', 0);
                        })
                    ->get()
                    ->map(function($income) {

                          $totalPaid = (float) $income->payments->sum('payment_amount');
                          $income->total_paid = $totalPaid;
                          return $income;
                     });
    }
}
