<?php

namespace App\Services;

use App\Models\{Client, Income, Outcome, Payment};
use App\Services\Analytics\IncomeReportService;
use App\Services\Analytics\OutcomeReportService;

class ReportService
{
    protected $incomeReportService;
    protected $outcomeReportService;
    public function __construct(
      IncomeReportService $incomeReportService, 
      OutcomeReportService $outcomeReportService)
    {
        $this->incomeReportService = $incomeReportService;
        $this->outcomeReportService = $outcomeReportService;
    }
    public function getReportsData(array $filters)
    {
       $dateRange = $this->getDateRange($filters);

       return [
          'summary'      => $this->getSummaryData($dateRange['from'], $dateRange['to']),
          'transactions' => $this->getTransactionData($dateRange['from'], $dateRange['to']),
          'charts'       => $this->getChartData($dateRange['from'], $dateRange['to']),
          'date_range'   => $dateRange
       ];

    }
    protected function getDateRange(array $filters)
    {
        if (!empty($filters['month']) && !empty($filters['year'])) {
            $dateFrom = $filters['year'] . '-' . $filters['month'] . '-01';
            $dateTo = date('Y-m-t', strtotime($dateFrom));

        } else {
            $dateFrom = $filters['dateFrom'] ?? null;
            $dateTo = $filters['dateTo'] ?? null;
        }

        return [
           'from' => $dateFrom,
           'to'   => $dateTo
        ];
    }
    protected function getSummaryData(?string $dateFrom, ?string $dateTo)
    {
       $totalIncome = $this->getTotalIncome($dateFrom, $dateTo);
       $totalOutcome = $this->getTotalOutcome($dateFrom, $dateTo);
       $totalClients = $this->getTotalClients($dateFrom, $dateTo);

       return [
         'total_income' => $totalIncome,
         'total_outcome' => $totalOutcome,
         'total_clients' => $totalClients,
         'profit'        => $totalIncome - $totalOutcome
       ];
    }
    protected function getTotalIncome(?string $dateFrom, ?string $dateTo)
    {
      return  Payment::notDeleted()
                      ->where('status','paid')
                      ->whereHas('income', function($query) use ($dateFrom, $dateTo) {
                          $query->notDeleted()
                      ->dateBetween($dateFrom, $dateTo)
                      ->whereHas('client', function($q) {
                          $q->notDeleted();
                            });
                      })->sum('payment_amount');
    }
    protected function getTotalOutcome(?string $dateFrom, ?string $dateTo)
    {
      return Outcome::notDeleted()
                         ->dateBetween($dateFrom, $dateTo)
                         ->sum('amount');
    }
    protected function getTotalClients(?string $dateFrom, ?string $dateTo)
    {
      return Client::notDeleted()
                   ->whereHas('types', function($query) {
                         $query->where('client_type.type_name', 'student');
                         })   
                   ->dateBetween($dateFrom, $dateTo)
                   ->count();
    }
    protected function getTransactionData(?string $dateFrom, ?string $dateTo)
    {
        return [
           'incomes'  => $this->getIncomes($dateFrom, $dateTo),
           'outcomes' => $this->getOutcomes($dateFrom, $dateTo)
        ];
    }
    protected function getIncomes(?string $dateFrom, ?string $dateTo)
    {
      return Income::notDeleted()
                    ->with(['client.types', 'subcategory.category', 'payments'])
                    ->dateBetween($dateFrom, $dateTo)
                    ->get()
                    ->each(function ($income) {
                         $income->paid = $income->payments->where('status','paid')->sum('payment_amount');
                      });      
    }
    protected function getOutcomes(?string $dateFrom, ?string $dateTo)
    {
      return Outcome::notDeleted()->with('subcategory.category')
                      ->dateBetween($dateFrom, $dateTo)
                      ->get();
    }
    // doughnut chart data
    protected function getChartData(?string $dateFrom, ?string $dateTo)
    {
       return [
          'income_category'      => $this->incomeReportService->getIncomeByCategory($dateFrom, $dateTo),
          'income_sub_category'  => $this->incomeReportService->getIncomeBySubcategory($dateFrom, $dateTo),
          'outcome_category'     => $this->outcomeReportService->getOutcomeByCategory($dateFrom, $dateTo),
          'outcome_sub_category' => $this->outcomeReportService->getOutcomeBySubcategory($dateFrom, $dateTo)
       ];
    }
}
