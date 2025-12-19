<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Outcome;
use App\Models\Payment;
use App\Services\Analytics\BarChartService;
use Carbon\Carbon;

class DashboardService
{
    protected $barChartService;
    public function __construct(BarChartService $barChartService){
      $this->barChartService = $barChartService;
    }
    public function getDashboardData()
    {
       $firstDayOfMonth = Carbon::now()->startOfMonth();
       $currentDay = Carbon::now()->addDay();
       $thisYear = Carbon::now()->year;
       $yearBefore = $thisYear - 1;


       return [
         'financial'              => $this->getFinancialCard($firstDayOfMonth, $currentDay),
         'chart_data'             => $this->getChartData($firstDayOfMonth),
         'yearly_chart_data'      => $this->getYearlyChartData($thisYear, $yearBefore),
         'upcoming_payments'      => $this->getUpcomingPayments(),
         'outdated_payments'      => $this->getTotalOutdated(),
         'current_month'          => date('F Y'),
         'total_paid_invoices'    => Invoice::where('status','paid')->count(),
         'total_unpaid_invoices'  => Invoice::where('status','unpaid')->count(),
       ];
    }
    protected function getFinancialCard(Carbon $firstDayOfMonth, Carbon $currentDay)
    {
         $totalIncome = $this->getTotalIncome($firstDayOfMonth, $currentDay);
         $totalOutcome = $this->getTotalOtcome($firstDayOfMonth, $currentDay);
         $totalClients = $this->getTotalClients();


         return [
           'total_income'    => $totalIncome,
           'total_outcome'   => $totalOutcome,
           'total_clients'   => $totalClients,
           'profit'          => $totalIncome - $totalOutcome
         ];
    }
    protected function getTotalIncome(Carbon $startDate, Carbon $endDate)
    {
         return Payment::with(['income.client'])
                     ->notDeleted()
                     ->where('status', 'paid')
                     ->whereHas('income', function($query)use ($startDate, $endDate) {
                         $query->notDeleted()
                               ->whereBetween('created_at', [$startDate, $endDate])
                         ->whereHas('client', function($q) {
                                 $q->notDeleted();
                          });
                     })
                     ->sum('payment_amount');
    }
    protected function getTotalOtcome(Carbon $startDate, Carbon $endDate)
    {
       return Outcome::notDeleted()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
    }
    protected function getTotalClients()
    {
        return Client::notDeleted()->whereHas('types', function($query) {
                         $query->where('type_name', 'student');
                     })->count();
    }
    protected function getChartData(Carbon $firstDayOfMonth)
    {
         $labels = range(1, date('t')); 
         $incomeData = $this->barChartService->getDailyIncomeData($firstDayOfMonth);
         $outcomeData = $this->barChartService->getDailyOutcomeData($firstDayOfMonth);

         $profitData = array_map(function($i) use ($incomeData, $outcomeData) {
             return $incomeData[$i] - $outcomeData[$i];
         }, array_keys($labels));

         return [
            'labels'       => $labels,
            'income_data'  => $incomeData,
            'outcome_data' => $outcomeData,
            'profit_data'  => $profitData
         ];
    }
    protected function getYearlyChartData($thisYear, $yearBefore)
    {
        $incomeData = $this->barChartService->getYearlyIncomeData($thisYear, $yearBefore);

        $thisYearIncome = array_fill(0, 12, 0);
        $yearBeforeIncome = array_fill(0, 12, 0);
  
       foreach ($incomeData['thisYear'] as $month => $total) {
            $thisYearIncome[$month - 1] = (float) $total;
        }

        foreach ($incomeData['yearBefore'] as $month => $total) {
            $yearBeforeIncome[$month - 1] = (float) $total;
        }

        $totalThisYear = array_sum($thisYearIncome);
        $totalYearBefore = array_sum($yearBeforeIncome);
        $totalYearlyIncome = $totalThisYear + $totalYearBefore;

         $percentageChange = 0;
        if ($totalYearBefore > 0) {
            $percentageChange = (($totalThisYear - $totalYearBefore) / $totalYearBefore) * 100;
        } else {
            $percentageChange = 100;
        }

        return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'this_year_income' => $thisYearIncome,
            'year_before_income' => $yearBeforeIncome,
            'total_yearly_income' => $totalYearlyIncome,
            'income_percentage_change' => $percentageChange,
        ];
    }

    protected function getUpcomingPayments()
    {
       $today = Carbon::today();
        return Income::notDeleted()
                           ->with(['client', 'payments'=> function ($q){
                             $q->where('status','!=','paid');
                           }])
                           ->whereDate('next_payment', '>', $today)
                           ->orwhereDate('next_payment', $today)
                           ->where('status','!=','complete')
                           ->whereHas('client', function($query) {
                               $query->notDeleted();
                               })
                           ->get();
    }
    protected function getTotalOutdated()
    {
       $today = Carbon::today();
        return Income::notDeleted()
                           ->with(['client', 'payments'=> function ($q){
                             $q->where('status','!=','paid');
                           }])
                           ->whereDate('next_payment', '<', $today)
                           ->where('status','!=','complete')
                           ->whereHas('client', function($query) {
                               $query->notDeleted();
                               })
                           ->get();
    }
}
