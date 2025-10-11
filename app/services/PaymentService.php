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

        $payment = Payment::create([
            'income_id'      => $incomeId,
            'payment_amount' => $data['payment_amount'],
            'description'    => $data['description'] ?? null
        ]);

        if (!empty($data['description'])) {
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
        $totalPaid = $income->payments->sum('payment_amount');
        $amount = $income->final_amount ?? $income->amount;

        $status = 'pending';
        if ($totalPaid >= $amount) {
            $status = 'complete';
        } elseif ($totalPaid > 0 && $totalPaid < $amount) {
            $status = 'partial';
        }

        $income->update(['status' => $status]);

        return $payment;
    });
    }
    public function editPayment(int $paymentId,int $incomeId, array $data)
    {
       return DB::transaction(function() use($paymentId,$incomeId, $data){
        $payment = Payment::findOrFail($paymentId);
            $lang = $data['lang'] ?? 'en';

          if($lang == 'en'){
             $payment->update([
               'income_id'      => $incomeId,
               'payment_amount' => $data['payment_amount'],
               'description'    => $data['description'] 
             ]);
          }elseif($lang == 'ar'){
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
    public function getOutDatedPayments(string $today)
    {
      return  Income::notDeleted()
                    ->with(['client', 'payments'])
                    ->whereDate('next_payment', '<', $today)
                    ->whereHas('client', function($query) {
                        $query->notDeleted();
                       })
                    ->get();
                  
    }
    public function getTodayPayments(string $today)
    {
      return Income::notDeleted()
                    ->with(['client', 'payments'])
                    ->whereDate('next_payment', $today)
                    ->where('status','!=','complete')
                    ->whereHas('client', function($query) {
                        $query->notDeleted();
                    })
                    ->get();
                  
    }
    public function getUpComingPayments(string $today)
    {
      return Income::notDeleted()
                    ->with(['client', 'payments'])
                    ->whereDate('next_payment', '>', $today)
                    ->where('status','!=','complete')
                    ->whereHas('client', function($query) {
                        $query->notDeleted();
                        })
                    ->get();
                  
    }
}
