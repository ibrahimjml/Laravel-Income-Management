<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\ClientService;
use App\Services\IncomeService;
use App\Services\OutcomeService;
use App\Services\PaymentService;
use App\Services\ReportService;


class AdminController extends Controller
{
  public function index(DashboardService $dashboardService){

  $data = $dashboardService->getDashboardData();

    return view('admin.dashboard',[

      'labels'         => $data['chart_data']['labels'],
      'incomeData'     => $data['chart_data']['income_data'],
      'outcomeData'    => $data['chart_data']['outcome_data'],
      'profitData'     => $data['chart_data']['profit_data'],
      'currentMonth'   => $data['current_month'],
      'totalIncome'    => $data['financial']['total_income'],
      'totalOutcome'   => $data['financial']['total_outcome'],
      'totalStudents'  => $data['financial']['total_clients'],
      'profit'         => $data['financial']['profit']
    ]);
  }
  public function clients_page(ClientService $clientService)
  {
    $data = $clientService->getClientsData();

    return view('admin.clients.clients',[

      'clienttype' => $data['clients_type'],
      'clients'    => $data['clients']
    ]);
  }
  public function incomes_page(IncomeService $incomeService)
  {
    $data = $incomeService->getIcomeData();
    
    return view('admin.incomes.incomes',[

      'categories'    => $data['categories'],
      'subcategories' => $data['sub_categories'],
      'clients'       => $data['clients'],
      'incomes'       => $data['incomes']
    ]);
  }
  public function outcomes_page(OutcomeService $outcomeService)
  {
    $data = $outcomeService->getOutcomesData();

    return view('admin.outcomes.outcomes',[

      'categories'    => $data['categories'],
      'subcategories' => $data['sub_categories'],
      'outcomes'      => $data['outcomes']
    ]);
  }

  public function reports_page(ReportService $reportService)
  {
     $filters = request()->only(['dateFrom', 'dateTo', 'month', 'year']);
     $data = $reportService->getReportsData($filters);

    return view('admin.reports.reports',[
      
      'date_range'             => $filters,
      'total_income'           => $data['summary']['total_income'],
      'total_outcome'          => $data['summary']['total_outcome'],
      'total_profit'           => $data['summary']['profit'],
      'total_students'         => $data['summary']['total_clients'],
      'incomes'                => $data['transactions']['incomes'],
      'outcomes'               => $data['transactions']['outcomes'],
      'incomeCategoryData'     => $data['charts']['income_category'],
      'incomeSubcategoryData'  => $data['charts']['income_sub_category'],
      'outcomeCategoryData'    => $data['charts']['outcome_category'],
      'outcomeSubcategoryData' => $data['charts']['outcome_sub_category']

    ]);
  }
  
  
}

