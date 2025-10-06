<?php

namespace App\Services;

use App\Models\Income;
use Carbon\Carbon;

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
