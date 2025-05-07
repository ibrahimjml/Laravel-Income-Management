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
use Illuminate\Support\Carbon;


class AdminController extends Controller
{
  public function index(){

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

    $labels = range(1, date('t')); // Days of current month
    $incomeData = $this->getDailyIncomeData($firstDayOfMonth);
    $outcomeData = $this->getDailyOutcomeData($firstDayOfMonth);
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
  public function report(){

    $totalIncome = Payment::where('is_deleted', 0)
        ->whereHas('income', function($query) {
            $query->where('is_deleted', 0)
                  ->whereHas('client', function($q) {
                      $q->where('is_deleted', 0);
                  });
        })
        ->sum('payment_amount');

  $totalOutcome = Outcome::where('is_deleted', 0)->sum('amount');
  $totalStudents = Client::whereHas('types', function($query) {
    $query->where('type_name','student');
    })->count();
  $totalProfit = $totalIncome - $totalOutcome;

  $incomes = Income::with(['client', 'subcategory.category', 'payments'])
  ->where('is_deleted', 0)
  ->get()
  ->each(function ($income) {
      $income->paid = $income->payments->sum('payment_amount');
  });
  $outcomes = Outcome::with('subcategory.category')
  ->where('is_deleted',0)
  ->get();

    return view('admin.reports',[
      'total_income' => $totalIncome,
      'total_outcome' => $totalOutcome,
      'total_profit' => $totalProfit,
      'total_students' => $totalStudents,
      'incomes'=>$incomes,
      'outcomes' => $outcomes,

    ]);
  }

  protected function getDailyIncomeData($firstDayOfMonth)
  {
      $daysInMonth = date('t');
      $dailyData = [];
      
      for ($day = 1; $day <= $daysInMonth; $day++) {
          $date = $firstDayOfMonth->copy()->addDays($day - 1);
          
          $amount = Payment::where('is_deleted', 0)
              ->whereDate('created_at', $date)
              ->sum('payment_amount');
              
          $dailyData[] = $amount ?? 0;
      }
      
      return $dailyData;
  }
  

  protected function getDailyOutcomeData($firstDayOfMonth)
  {
      $daysInMonth = date('t');
      $dailyData = [];
      
      for ($day = 1; $day <= $daysInMonth; $day++) {
          $date = $firstDayOfMonth->copy()->addDays($day - 1);
          
          $amount = Outcome::where('is_deleted', 0)
              ->whereDate('created_at', $date)
              ->sum('amount');
              
          $dailyData[] = $amount ?? 0;
      }
      
      return $dailyData;
  }
}

