<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class OutcomeReportService
{
    public function getOutcomeByCategory()
    {
        $query = DB::table('outcome as o')
            ->join('subcategories as s', 'o.subcategory_id', '=', 's.subcategory_id')
            ->join('categories as c', 's.category_id', '=', 'c.category_id')
            ->select('c.category_name as category', DB::raw('SUM(o.amount) as total_amount'))
            ->where('o.is_deleted', 0)
            ->groupBy('c.category_name');

        return $query->get();
    }

    public function getOutcomeBySubcategory()
    {
        $query = DB::table('outcome as o')
            ->join('subcategories as s', 'o.subcategory_id', '=', 's.subcategory_id')
            ->select('s.sub_name as subcategory', DB::raw('SUM(o.amount) as total_amount'))
            ->where('o.is_deleted', 0)
            ->groupBy('s.sub_name');

        return $query->get();
    }
}
