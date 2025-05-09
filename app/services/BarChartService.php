<?php
namespace App\Services;

use App\Models\Outcome;
use App\Models\Payment;

class BarChartService
{
  public function getDailyIncomeData($firstDayOfMonth)
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
  public function getDailyOutcomeData($firstDayOfMonth)
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