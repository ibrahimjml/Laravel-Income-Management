<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class IncomeReportService
{
    public function getIncomeByCategory()
    {
        $query = DB::table('income as i')
            ->join('subcategories as s', 'i.subcategory_id', '=', 's.subcategory_id')
            ->join('categories as c', 's.category_id', '=', 'c.category_id')
            ->select('c.category_name as category', DB::raw('SUM(i.amount) as total_amount'))
            ->where('i.is_deleted', 0)
            ->groupBy('c.category_name');

        return $query->get();
    }

    public function getIncomeBySubcategory()
    {
        $query = DB::table('income as i')
            ->join('subcategories as s', 'i.subcategory_id', '=', 's.subcategory_id')
            ->select('s.sub_name as subcategory', DB::raw('SUM(i.amount) as total_amount'))
            ->where('i.is_deleted', 0)
            ->groupBy('s.sub_name');

        return $query->get();
    }
}
