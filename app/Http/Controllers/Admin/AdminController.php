<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\Income;
use App\Models\Outcome;
use App\Models\Payment;
use App\Models\Subcategory;
use App\Services\BarChartService;
use App\Services\IncomeReportService;
use App\Services\OutcomeReportService;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
  public function index(BarChartService $barChart){

   $firstDayOfMonth = Carbon::now()->startOfMonth();
   $currentDay = Carbon::now()->addDay();

    $totalIncome = Payment::with(['income.client'])
                          ->where('is_deleted', 0)
                          ->whereHas('income', function($query) {
                          $query->where('is_deleted', 0)
                          ->whereHas('client', function($q) {
                          $q->where('is_deleted', 0);
                              });
                           })
                         ->whereBetween('created_at', [$firstDayOfMonth, $currentDay])
                         ->sum('payment_amount');

    $totalOutcome = Outcome::where('is_deleted', 0)
                           ->whereBetween('created_at', [$firstDayOfMonth, $currentDay])
                           ->sum('amount');
    $totalStudents = Client::whereHas('types', function($query) {
                           $query->where('type_name','student');
                           })->count();
    $profit = $totalIncome - $totalOutcome;
    // Bar chart data
    $labels = range(1, date('t')); 
    $incomeData = $barChart->getDailyIncomeData($firstDayOfMonth);
    $outcomeData = $barChart->getDailyOutcomeData($firstDayOfMonth);
    $profitData = array_map(function($i) use ($incomeData, $outcomeData) {
        return $incomeData[$i] - $outcomeData[$i];
    }, array_keys($labels));

    return view('admin.dashboard',[
      'labels' => $labels,
      'incomeData' => $incomeData,
      'outcomeData' => $outcomeData,
      'profitData' => $profitData,
      'currentMonth' => date('F Y'),
      'totalIncome' => $totalIncome,
      'totalOutcome' => $totalOutcome,
      'totalStudents' => $totalStudents,
      'profit' => $profit
    ]);
  }
  public function client(){
    $clienttype = ClientType::where('is_deleted',0)->get();
    $clients = Client::with('types') 
                     ->where('is_deleted', 0)
                     ->paginate(6);
    return view('admin.clients.clients',['clienttype'=>$clienttype,'clients'=>$clients]);
  }
  public function income(){

    $categories = Category::where('is_deleted',0)->where('category_type','Income')->get();
    $subcategories = Subcategory::where('is_deleted',0)->get();
    $clients = Client::with('types')->where('is_deleted',0)->get();
    $incomes = Income::with(['client', 'subcategory.category', 'payments'])
                     ->where('is_deleted', 0)
                     ->get()
                     ->each(function ($income) {
                    $income->paid = $income->payments->sum('payment_amount');
                    });
  
    return view('admin.incomes.incomes',[
      'categories' => $categories,
      'subcategories' => $subcategories,
      'clients' => $clients,
      'incomes' => $incomes
    ]);
  }
  public function outcome(){
    $categories = Category::where('is_deleted',0)->where('category_type','Outcome')->get();
    $subcategories = Subcategory::whereHas('category', function($query) {
                                $query->where('category_type', 'Outcome')
                                      ->where('is_deleted', 0);
                                      })
                               ->where('is_deleted', 0)
                               ->get();
    $outcomes = Outcome::with('subcategory.category')
                       ->where('is_deleted',0)
                       ->get();
    return view('admin.outcomes.outcomes',['categories'=>$categories,'subcategories'=>$subcategories,'outcomes'=>$outcomes]);
  }
  public function payment(){
    $today = Carbon::today()->toDateString();

    $outdatedPayments = Income::with(['client', 'payments'])
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

    $todayPayments = Income::with(['client', 'payments'])
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

    $upcomingPayments = Income::with(['client', 'payments'])
                              ->whereDate('next_payment', '>', $today)
                              ->where('is_deleted', 0)
                              ->where('status','!=','complete')
                              ->whereHas('client', function($query) {
                                  $query->where('is_deleted', 0);
                              })
                              ->get()
                              ->map(function($income) {
                                $totalPaid = (float)$income->payments->sum('payment_amount');
                                $income->total_paid = $totalPaid;
                                return $income;
                               });
    return view('admin.payments',[
    'outdated_payments' => $outdatedPayments,
    'today_payments' => $todayPayments,
    'upcoming_payments' => $upcomingPayments
    ]);
  }
  public function report(IncomeReportService $incomeReport,OutcomeReportService $outcomeReport){

    $selectedMonth = request()->input('month') ?? '';
    $selectedYear = request()->input('year') ?? '';
    $incomeDateFrom = request()->input('dateFrom') ?? '';
    $incomeDateTo = request()->input('dateTo') ?? '';

    $dateFrom = $incomeDateFrom;
    $dateTo = $incomeDateTo;

    if ($selectedMonth && $selectedYear) {
      $dateFrom = "$selectedYear-$selectedMonth-01";
      $dateTo = date("Y-m-t", strtotime($dateFrom));
  };

  $totalIncome = Payment::where('is_deleted', 0)
                        ->whereHas('income', function($query) use ($dateFrom, $dateTo) {
                        $query->where('is_deleted', 0)
                        ->dateBetween($dateFrom, $dateTo)
                        ->whereHas('client', function($q) {
                        $q->where('is_deleted', 0);
                        });
                        })->sum('payment_amount');

  $totalOutcome = Outcome::where('is_deleted', 0)
                         ->dateBetween($dateFrom, $dateTo)
                         ->sum('amount');
  $totalStudents = Client::whereHas('types', function($query) {
                          $query->where('type_name','student');
                          })
                          ->dateBetween($dateFrom, $dateTo)
                          ->count();
  $totalProfit = $totalIncome - $totalOutcome;

  $incomes = Income::with(['client', 'subcategory.category', 'payments'])
                    ->where('is_deleted', 0)
                    ->dateBetween($dateFrom, $dateTo)
                    ->get()
                    ->each(function ($income) {
                    $income->paid = $income->payments->sum('payment_amount');
                        });
  $outcomes = Outcome::with('subcategory.category')
                      ->where('is_deleted',0)
                      ->dateBetween($dateFrom, $dateTo)
                      ->get();
// doughnut chart data
$incomeCategoryData = $incomeReport->getIncomeByCategory($dateFrom, $dateTo);
$incomeSubcategoryData = $incomeReport->getIncomeBySubcategory($dateFrom, $dateTo);
$outcomeCategoryData = $outcomeReport->getOutcomeByCategory($dateFrom, $dateTo);
$outcomeSubcategoryData = $outcomeReport->getOutcomeBySubcategory($dateFrom, $dateTo);

    return view('admin.reports.reports',[
      'total_income' => $totalIncome,
      'total_outcome' => $totalOutcome,
      'total_profit' => $totalProfit,
      'total_students' => $totalStudents,
      'incomes'=>$incomes,
      'outcomes' => $outcomes,
      'incomeCategoryData' => $incomeCategoryData,
      'incomeSubcategoryData' => $incomeSubcategoryData,
      'outcomeCategoryData' => $outcomeCategoryData,
      'outcomeSubcategoryData' =>$outcomeSubcategoryData

    ]);
  }
  
}

