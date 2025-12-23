<?php

namespace App\Services\Analytics;

use App\Enums\IncomeStatus;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;

class DoughnutService
{
  // get income by category stats
   public function getIncomeByCategory($dateFrom = null, $dateTo = null)
  {
    $locale = app()->getLocale();

    return  DB::table('income as i')
      ->join('subcategories as s', 'i.subcategory_id', '=', 's.subcategory_id')
      ->join('categories as c', 's.category_id', '=', 'c.category_id')
      ->leftJoin('categories_translations as ct', function ($join) use ($locale) {
        $join->on('ct.category_id', '=', 'c.category_id')
          ->where('ct.lang_code', $locale);
      })
      ->select(
        DB::raw('COALESCE(ct.category_name, c.category_name) as category'),
        DB::raw('SUM(COALESCE( 
                            CASE
                            WHEN i.final_amount IS NOT NULL AND i.final_amount > 0
                            THEN i.final_amount
                            ELSE i.amount
                            END
                           )) as total_amount')
      )
      ->where('i.is_deleted', 0)
      ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
        $query->whereBetween('i.created_at', [$dateFrom, $dateTo]);
      })
      ->groupBy('category')
      ->get();
  }
  // get income by subcategory stats
    public function getIncomeBySubcategory($dateFrom = null, $dateTo = null)
  {
    $locale = app()->getLocale();

    return DB::table('income as i')
      ->join('subcategories as s', 'i.subcategory_id', '=', 's.subcategory_id')
      ->leftJoin('subcategories_translations as st', function ($join) use ($locale) {
        $join->on('st.subcategory_id', '=', 's.subcategory_id')
          ->where('st.lang_code', $locale);
      })
      ->select(
        DB::raw('COALESCE(st.sub_name, s.sub_name) as subcategory'),
        DB::raw('SUM(COALESCE( 
                            CASE
                            WHEN i.final_amount IS NOT NULL AND i.final_amount > 0
                            THEN i.final_amount
                            ELSE i.amount
                            END
                           )) as total_amount')
      )
      ->where('i.is_deleted', 0)
      ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
        $query->whereBetween('i.created_at', [$dateFrom, $dateTo]);
      })
      ->groupBy('subcategory')
      ->get();
  }
  // get outcome by category stats
    public function getOutcomeByCategory($dateFrom = null, $dateTo = null)
    {
        $locale = app()->getLocale();
        return  DB::table('outcome as o')
                    ->join('subcategories as s', 'o.subcategory_id', '=', 's.subcategory_id')
                    ->join('categories as c', 's.category_id', '=', 'c.category_id')
                    ->leftJoin('categories_translations as ct', function ($join) use ($locale) {
                          $join->on('ct.category_id', '=', 'c.category_id')
                            ->where('ct.lang_code', $locale);
                        })
                    ->select(
                      DB::raw('COALESCE(ct.category_name, c.category_name) as category'),
                     DB::raw('SUM(o.amount) as total_amount'))
                    ->where('o.is_deleted', 0)
                    ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
                    $query->whereBetween('o.created_at', [$dateFrom, $dateTo]);
                        })
                   ->groupBy('category')
                   ->get();
      
    }
  // get outcome by subcategory
    public function getOutcomeBySubcategory($dateFrom = null, $dateTo = null)
    {
        $locale = app()->getLocale();
        return DB::table('outcome as o')
                    ->join('subcategories as s', 'o.subcategory_id', '=', 's.subcategory_id')
                    ->leftJoin('subcategories_translations as st', function ($join) use ($locale) {
                            $join->on('st.subcategory_id', '=', 's.subcategory_id')
                                 ->where('st.lang_code', $locale);
                          })
                    ->select(
                      DB::raw('COALESCE(st.sub_name, s.sub_name) as subcategory'),
                     DB::raw('SUM(o.amount) as total_amount')
                     )
                    ->where('o.is_deleted', 0)
                    ->when($dateFrom && $dateTo, function ($query) use ($dateFrom, $dateTo) {
                    $query->whereBetween('o.created_at', [$dateFrom, $dateTo]);
                         })
                   ->groupBy('subcategory')
                   ->get();


    }
  // get Incomes Statistics
  public function getPaymentsStats(?string $dateFrom = null, ?string $dateTo = null)
  {

    $query = DB::table('payments as p')
      ->select(
        'p.status',
        DB::raw('COUNT(*) as total_count'),
        DB::raw('SUM(p.payment_amount) as total_amount')
      )
      ->join('income as i', function ($q) {
        $q->on('p.income_id', '=', 'i.income_id')
          ->where('i.is_deleted', 0);
      })
      ->when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
        $q->whereBetween('i.created_at', [$dateFrom, $dateTo]);
      })
      ->where('p.is_deleted', 0)
      ->groupBy('p.status')
      ->get()
      ->keyBy('status');

    $total_count = $query->sum('total_count');
    $total_amount = $query->sum('total_amount');

    if ($total_count === 0) {
      return [
        'paid' => ['total' => 0, 'count' => 0, 'percentage' => 0],
        'unpaid' => ['total' => 0, 'count' => 0, 'percentage' => 0],
        'total' => ['count' => 0, 'amount' => 0],
      ];
    }

    return [
      'paid' => [
        'total' => $query[PaymentStatus::PAID->value]->total_amount ?? 0,
        'count' => $query[PaymentStatus::PAID->value]->total_count ?? 0,
        'percentage' => round(($query[PaymentStatus::PAID->value]->total_count ?? 0) / $total_count * 100, 2),
      ],
      'unpaid'  => [
        'total' => $query[PaymentStatus::UNPAID->value]->total_amount ?? 0,
        'count' => $query[PaymentStatus::UNPAID->value]->total_count ?? 0,
        'percentage' => round(($query[PaymentStatus::UNPAID->value]->total_count ?? 0) / $total_count * 100, 2),
      ],
      'total' => [
        'count' => $total_count,
        'amount' => $total_amount,

      ]
    ];
  }
  // get income statistics
  public function getIcomeStats($dateFrom, $dateTo)
  {
    $query = DB::table('income as i')
      ->select(
        'i.status',
        DB::raw('COUNT(*) as total_count'),
        DB::raw('SUM(
                          CASE
                          WHEN i.final_amount IS NOT NULL AND i.final_amount > 0
                          THEN i.final_amount
                          ELSE i.amount
                          END ) as total_amount')
      )
      ->where('i.is_deleted', 0)
      ->when(
        $dateFrom && $dateTo,
        fn($q) =>
        $q->whereBetween('i.created_at', [$dateFrom, $dateTo])
      )
      ->groupBy('i.status')
      ->get()
      ->keyBy('status');

    $total_count = $query->sum('total_count');
    $total_amount = $query->sum('total_amount');

    if ($total_count === 0) {
      return [
        'complete' => ['total' => 0, 'count' => 0, 'percentage' => 0],
        'pending'  => ['total' => 0, 'count' => 0, 'percentage' => 0],
        'partial'  => ['total' => 0, 'count' => 0, 'percentage' => 0],
        'total' => ['count' => 0, 'amount' => 0],
      ];
    }

    return [
      'complete' => [
        'total' => $query[IncomeStatus::COMPLETE->value]->total_amount ?? 0,
        'count' => $query[IncomeStatus::COMPLETE->value]->total_count ?? 0,
        'percentage' => round(($query[IncomeStatus::COMPLETE->value]->total_count ?? 0) / $total_count * 100, 2),
      ],
      'pending'  => [
        'total' => $query[IncomeStatus::PENDING->value]->total_amount ?? 0,
        'count' => $query[IncomeStatus::PENDING->value]->total_count ?? 0,
        'percentage' => round(($query[IncomeStatus::PENDING->value]->total_count ?? 0) / $total_count * 100, 2),
      ],
      'partial' => [
        'total' => $query[IncomeStatus::PARTIAL->value]->total_amount ?? 0,
        'count' => $query[IncomeStatus::PARTIAL->value]->total_count ?? 0,
        'percentage' => round(($query[IncomeStatus::PARTIAL->value]->total_count ?? 0) / $total_count * 100, 2),
      ],
      'total' => [
        'count' => $total_count,
        'amount' => $total_amount,

      ]
    ];
  }
  // get Full CLient statistics
  public function getTotalCLientsStats($dateFrom, $dateTo)
  {
    $locale = app()->getLocale();

    $query = DB::table('client_types_relation as ctr')
               ->join('client_type as ct', 'ct.type_id', '=', 'ctr.type_id')
               ->leftJoin('client_type_translations as ctt', function ($join) use ($locale) {
                      $join->on('ctt.type_id', '=', 'ct.type_id')
                           ->where('ctt.lang_code', $locale);
                           })
              ->join('clients as c', 'c.client_id', '=', 'ctr.client_id')
              ->leftJoin('income as i',fn($q) =>
                    $q->on('i.client_id', '=', 'c.client_id')
                      ->where('i.is_deleted', 0)
                      )
              ->leftJoin('payments as p',fn($q) =>
                    $q->on('p.income_id', '=', 'i.income_id')
                      ->where('p.is_deleted', 0)
                      ->where('p.status', PaymentStatus::PAID->value)
                       )
              ->where('c.is_deleted', 0)
              ->when($dateFrom && $dateTo, function ($q) use ($dateFrom, $dateTo) {
                   $q->whereBetween('i.created_at', [$dateFrom, $dateTo]);
                  })
              ->groupBy(DB::raw('COALESCE(ctt.type_name, ct.type_name)'))
              ->select(
                DB::raw('COALESCE(ctt.type_name, ct.type_name) as type_name'),
                DB::raw('COUNT(DISTINCT c.client_id) as total_clients'),
                DB::raw('COALESCE(SUM(p.payment_amount), 0) as total_payment_amount')
              )->get();
    // total clients / payments                 
    $totalClients = $query->sum('total_clients');
    $totalPayments = $query->sum('total_payment_amount');

    $query = $query->map(function ($row) use ($totalPayments) {
      $row->percentage = $totalPayments > 0
        ? round(($row->total_payment_amount / $totalPayments) * 100, 2)
        : 0;
      return $row;
    })
      ->keyBy('type_name');

    return [
      'totals' => [
        'clients'  => $totalClients,
        'payments' => $totalPayments,
      ],
      'by_type' => $query,
    ];
  }
}
