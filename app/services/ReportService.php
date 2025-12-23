<?php

namespace App\Services;


use App\Enums\PaymentType;
use App\Models\{Client, Income, Invoice, Outcome, Payment};
use App\Services\Analytics\BarChartService;
use App\Services\Analytics\DoughnutService;
use App\Services\Analytics\IncomeReportService;
use App\Services\Analytics\OutcomeReportService;

class ReportService
{
    protected $barChartService;
    protected $doughnutService;
    protected $dashboardService;
    public function __construct(
      BarChartService $barChartService,
      DoughnutService $doughnutService,
      DashboardService $dashboardService,)
    {
        $this->barChartService = $barChartService;
        $this->doughnutService = $doughnutService;
        $this->dashboardService = $dashboardService;
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
       $totalIncome            = $this->getTotalIncome($dateFrom, $dateTo);
       $totalOutcome           = $this->getTotalOutcome($dateFrom, $dateTo);
       $totalClients           = $this->getTotalClients($dateFrom, $dateTo);
       $totalInvoices          = $this->getTotalInvoices($dateFrom, $dateTo);
       $totalIncomeRemaining   = $this->barChartService->getTotalRemainingIncome($dateFrom, $dateTo);
       $totalRecurringPayments = $this->getTotalRecurringPayments($dateFrom, $dateTo);
       $totalOneTimePayments   = $this->getTotalOneTimePayments($dateFrom, $dateTo);



        return [
         'total_income'             => $totalIncome,
         'total_outcome'            => $totalOutcome,
         'total_clients'            => $totalClients,
         'profit'                   => $totalIncome - $totalOutcome,
         'total_invoices'           => $totalInvoices,
         'total_income_remaining'   => $totalIncomeRemaining,
         'total_recurring_payments' => $totalRecurringPayments,
         'total_onetime_payments'  => $totalOneTimePayments,
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
                   ->withWhereHas('types', function($query) {
                         $query->selectRaw('COUNT(type_name) as count');
                         })   
                   ->dateBetween($dateFrom, $dateTo)
                   ->count();
    }
    protected function getTotalInvoices(?string $dateFrom, ?string $dateTo)
    {
       return Invoice::dateBetween($dateFrom, $dateTo)->count();
    }
    protected function getTotalRecurringPayments(?string $dateFrom, ?string $dateTo)
    {
       return Income::from('income as i')
               ->where('i.is_deleted',0)
               ->where('i.payment_type', PaymentType::RECURRING->value)
               ->when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
                  $q->whereBetween('i.created_at', [$dateFrom, $dateTo]);
                 })
               ->count();
    }
    protected function getTotalOneTimePayments(?string $dateFrom, ?string $dateTo)
    {
       return Income::from('income as i')
               ->where('i.is_deleted',0)
               ->where('i.payment_type', PaymentType::ONETIME->value)
               ->when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
                  $q->whereBetween('i.created_at', [$dateFrom, $dateTo]);
                 })
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
                    ->with(['client.types', 'subcategory.category'])
                    ->withSum('paidPayments', 'payment_amount')
                    ->dateBetween($dateFrom, $dateTo)
                    ->get();
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
          'income_category'       => $this->doughnutService->getIncomeByCategory($dateFrom, $dateTo),
          'income_sub_category'   => $this->doughnutService->getIncomeBySubcategory($dateFrom, $dateTo),
          'outcome_category'      => $this->doughnutService->getOutcomeByCategory($dateFrom, $dateTo),
          'outcome_sub_category'  => $this->doughnutService->getOutcomeBySubcategory($dateFrom, $dateTo),
          'payments_stats'        => $this->doughnutService->getPaymentsStats($dateFrom,$dateTo),
          'income_stats'          => $this->doughnutService->getIcomeStats($dateFrom, $dateTo),
          'clients_stats'         => $this->doughnutService->getTotalCLientsStats($dateFrom, $dateTo),

        ];
    }
}
