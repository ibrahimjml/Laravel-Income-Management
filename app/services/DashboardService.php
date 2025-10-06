<?php

namespace App\Services;

use App\Models\Client;
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

       return [
         'financial' => $this->getFinancialCard($firstDayOfMonth, $currentDay),
         'chart_data' => $this->getChartData($firstDayOfMonth),
         'current_month' => date('F Y')
       ];
    }
    protected function getFinancialCard(Carbon $firstDayOfMonth, Carbon $currentDay)
    {
         $totalIncome = $this->getTotalIncome($firstDayOfMonth, $currentDay);
         $totalOutcome = $this->getTotalOtcome($firstDayOfMonth, $currentDay);
         $totalClients = $this->getTotalClients();

         return [
           'total_income' => $totalIncome,
           'total_outcome' => $totalOutcome,
           'total_clients' => $totalClients,
           'profit' => $totalIncome - $totalOutcome
         ];
    }
    protected function getTotalIncome(Carbon $startDate, Carbon $endDate)
    {
         return Payment::with(['income.client'])
            ->where('is_deleted', 0)
            ->whereHas('income', function($query) {
                $query->where('is_deleted', 0)
                    ->whereHas('client', function($q) {
                        $q->where('is_deleted', 0);
                    });
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('payment_amount');
    }
    protected function getTotalOtcome(Carbon $startDate, Carbon $endDate)
    {
       return Outcome::where('is_deleted', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
    }
    protected function getTotalClients()
    {
        return Client::whereHas('types', function($query) {
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
            'labels' => $labels,
            'income_data' => $incomeData,
            'outcome_data' => $outcomeData,
            'profit_data' => $profitData
         ];
    }
}
