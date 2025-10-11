<?php
namespace App\Services\Analytics;

use Illuminate\Support\Facades\DB;

class IncomeReportService
{
    public function getIncomeByCategory($dateFrom = null, $dateTo = null)
    {
        $query = DB::table('income as i')
            ->join('subcategories as s', 'i.subcategory_id', '=', 's.subcategory_id')
            ->join('categories as c', 's.category_id', '=', 'c.category_id')
            ->select('c.category_name as category', DB::raw('SUM(COALESCE( i.amount)) as total_amount'))
            ->where('i.is_deleted', 0)
            ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
              $query->whereBetween('i.created_at', [$dateFrom, $dateTo]);
          })
            ->groupBy('c.category_name');

        return $query->get();
    }

    public function getIncomeBySubcategory($dateFrom = null, $dateTo = null)
    {
        $query = DB::table('income as i')
            ->join('subcategories as s', 'i.subcategory_id', '=', 's.subcategory_id')
            ->select('s.sub_name as subcategory', DB::raw('SUM(COALESCE( i.amount)) as total_amount'))
            ->where('i.is_deleted', 0)
            ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
              $query->whereBetween('i.created_at', [$dateFrom, $dateTo]);
            })
            ->groupBy('s.sub_name');

        return $query->get();
    }
}
