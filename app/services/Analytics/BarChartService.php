<?php
namespace App\Services\Analytics;

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
  public function getYearlyIncomeData($thisYear, $yearBefore)
  {
        $thisYearIncomes = Income::notDeleted()
                    ->where('status','complete')
                    ->whereYear('created_at', $thisYear)
                    ->select(
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('SUM(amount) as total')
                    )
                    ->groupBy('month')
                    ->pluck('total', 'month');

        $yearBeforeIncomes = Income::notDeleted()
                    ->whereYear('created_at', $yearBefore)
                    ->select(
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('SUM(amount) as total')
                    )
                    ->groupBy('month')
                    ->pluck('total', 'month');

        return [
            'thisYear' => $thisYearIncomes->all(),
            'yearBefore' => $yearBeforeIncomes->all(),
        ];
  }

}