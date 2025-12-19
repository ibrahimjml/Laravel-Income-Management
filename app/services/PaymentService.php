<?php

namespace App\Services;

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
        $paginate = null;

        $outdatedPayments = $this->getOutDatedPayments($today,$paginate);
        $todayPayments    = $this->getTodayPayments($today, $paginate);
        $upcomingPayments = $this->getUpComingPayments($today, $paginate);

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
            'status'         => $data['status'] ?? 'unpaid',
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
         $totalPaid = Payment::where('income_id', $incomeId)
                           ->where('status', 'paid')
                           ->sum('payment_amount');

          $amount =  $income->final_amount && $income->final_amount > 0
                           ? $income->final_amount
                           : $income->amount;

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


             $payment->update([
               'income_id'      => $incomeId,
               'payment_amount' => $data['payment_amount'],
               'status'         => $data['status'] ?? 'unpaid',
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
                                     ->where('status', 'paid')
                                     ->sum('payment_amount');
                   
          $amount = $income->final_amount && $income->final_amount > 0
                           ? $income->final_amount
                           : $income->amount;
          
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
    public function getOutDatedPayments(string $today, ?int $paginate = null)
    {
      $query =  Income::notDeleted()
                    ->with(['client', 'payments'])
                    ->where('status','!=','complete')
                    ->whereDate('next_payment', '<', $today)
                    ->whereHas('client', fn($q)=> $q->notDeleted());
      // with Pagination 
       if ($paginate) {
        $result = $query->paginate($paginate);
        $result->getCollection()->transform(function ($income) {
            $nextPayment = $income->payments
                ->where('status', '!=', 'paid')
                ->first();

            $income->next_payment_amount = $nextPayment ? $nextPayment->payment_amount : 0;

            return $income;
        });

        return $result;
    }            
      // without pagination   
      return $query->get()->map(function ($income) {
        $nextPayment = $income->payments
            ->where('status', '!=', 'paid')
            ->first();

        $income->next_payment_amount = $nextPayment ? $nextPayment->payment_amount : 0;

        return $income;
    });
    }
    public function getTodayPayments(string $today, ?int $paginate = null)
    {
      $query = Income::notDeleted()
                    ->with(['client', 'payments'])
                    ->whereDate('next_payment', $today)
                    ->where('status','!=','complete')
                    ->whereHas('client', fn($q)=> $q->notDeleted());
      // with Pagination 
       if ($paginate) {
        $result = $query->paginate($paginate);
        $result->getCollection()->transform(function ($income) {
            $nextPayment = $income->payments
                ->where('status', '!=', 'paid')
                ->first();

            $income->next_payment_amount = $nextPayment ? $nextPayment->payment_amount : 0;

            return $income;
        });

        return $result;
    }            
      // without pagination   
      return $query->get()->map(function ($income) {
        $nextPayment = $income->payments
            ->where('status', '!=', 'paid')
            ->first();

        $income->next_payment_amount = $nextPayment ? $nextPayment->payment_amount : 0;

        return $income;
    });
                  
    }
    public function getUpComingPayments(string $today, ?int $paginate = null)
    {
      $query =  Income::notDeleted()
                    ->with(['client', 'payments'])
                    ->whereDate('next_payment', '>', $today)
                    ->where('status','!=','complete')
                    ->whereHas('client', fn($q)=> $q->notDeleted());
        // with Pagination 
       if ($paginate) {
        $result = $query->paginate($paginate);
        $result->getCollection()->transform(function ($income) {
            $nextPayment = $income->payments
                ->where('status', '!=', 'paid')
                ->first();

            $income->next_payment_amount = $nextPayment ? $nextPayment->payment_amount : 0;

            return $income;
        });

        return $result;
    }            
      // without pagination   
      return $query->get()->map(function ($income) {
        $nextPayment = $income->payments
            ->where('status', '!=', 'paid')
            ->first();

        $income->next_payment_amount = $nextPayment ? $nextPayment->payment_amount : 0;

        return $income;
    });          
                  
    }
}
