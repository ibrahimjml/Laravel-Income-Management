<?php
namespace App\Services\Analytics;

use App\Enums\IncomeStatus;
use App\Enums\PaymentStatus;
use App\Models\Income;
use App\Models\Outcome;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class BarChartService
{
  public function getDailyIncomeData($firstDayOfMonth)
  {
        $daysInMonth = $firstDayOfMonth->daysInMonth;
        $endOfMonth = $firstDayOfMonth->copy()->endOfMonth();

        $payments = Payment::notDeleted()
                  ->whereBetween('created_at', [$firstDayOfMonth, $endOfMonth])
                  ->select(DB::raw('DAY(created_at) as day'), DB::raw('SUM(payment_amount) as total'))
                  ->groupBy('day')
                  ->pluck('total', 'day')
                  ->all();

        $dailyData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dailyData[] = (float) ($payments[$day] ?? 0);
        }

        return $dailyData;
  }
  public function getDailyOutcomeData($firstDayOfMonth)
  {
        $daysInMonth = $firstDayOfMonth->daysInMonth;
        $endOfMonth = $firstDayOfMonth->copy()->endOfMonth();

        $outcomes = Outcome::notDeleted()
                   ->whereBetween('created_at', [$firstDayOfMonth, $endOfMonth])
                   ->select(DB::raw('DAY(created_at) as day'), DB::raw('SUM(amount) as total'))
                   ->groupBy('day')
                   ->pluck('total', 'day')
                   ->all();

        $dailyData = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dailyData[] = (float) ($outcomes[$day] ?? 0);
        }

        return $dailyData;
  }
  // sum income this year vs year before
  public function getYearlyIncomeData($thisYear, $yearBefore)
  {
          $thisYearIncomes   = $this->getIncome($thisYear);
          $yearBeforeIncomes = $this->getIncome($yearBefore);

        return [
            'thisYear'   => $thisYearIncomes,
            'yearBefore' => $yearBeforeIncomes,
        ];
  }
  // sum payments paid vs unpaid        
  public function getSumPaymentsData()
  {
    $paidStatus = PaymentStatus::PAID;
    $unpaidStatus = PaymentStatus::UNPAID;

    return [
      'sum_paid' => $this->getSumPayments($paidStatus),
      'sum_unpaid' => $this->getSumPayments($unpaidStatus),
    ];
  
  }

public function getTotalRemainingIncome($dateFrom, $dateTo): float
{
    $subQuery = DB::table('income as i')
        ->leftJoin('payments as p', function ($join) {
            $join->on('p.income_id', '=', 'i.income_id')
                 ->where('p.is_deleted', 0)
                 ->where('p.status', PaymentStatus::PAID->value);
        })
        ->when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('i.created_at', [$dateFrom, $dateTo]);
        })
        ->where('i.is_deleted', 0)
        ->whereYear('i.created_at', now()->year)
        ->groupBy('i.income_id', 'i.amount', 'i.final_amount')
        ->selectRaw('
            GREATEST( 0,(
                    CASE
                        WHEN i.final_amount IS NOT NULL AND i.final_amount > 0
                        THEN i.final_amount
                        ELSE i.amount
                    END ) - COALESCE(SUM(p.payment_amount), 0)
              ) AS remaining
        ');

    return (float) DB::query()
        ->fromSub($subQuery, 't')
        ->sum('remaining');
}

    private function getIncome($year)
  {
    return  Income::from('income as i')
                     ->join('payments as p', function ($q) {
                         $q->on('p.income_id', '=', 'i.income_id')
                              ->where('p.status', PaymentStatus::PAID->value)
                              ->where('p.is_deleted', 0);
                     })
                     ->where('i.is_deleted', 0)
                     ->whereYear('i.created_at', $year)
                     ->selectRaw('MONTH(i.created_at) as month, SUM(p.payment_amount) as total')
                     ->groupBy('month')
                     ->pluck('total', 'month');
  }
  private function getSumPayments(PaymentStatus $status)
  {
    return (float) DB::table('payments as p')
                ->join('income as i', function ($q) {
                         $q->on('p.income_id', '=', 'i.income_id')
                           ->where('i.is_deleted', 0);
                     })
                ->where('p.is_deleted', 0)
                ->where('p.status', $status)
                ->whereYear('p.created_at', now()->year)
                ->sum('p.payment_amount');
  }
  
}