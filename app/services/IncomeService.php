<?php

namespace App\Services;

use App\Models\{Category, Client, Income, Subcategory};

class IncomeService
{
    public function getIcomeData()
    {
      $categories = $this->getCategories();
      $subcategories = $this->getSubCategories();
      $clients = $this->getClients();
      $incomes = $this->getIncomes();

      return [
         'categories'     => $categories,
         'sub_categories' => $subcategories,
         'clients'        => $clients,
         'incomes'        => $incomes
      ];
    }
    protected function getCategories()
    {
        return Category::where('is_deleted',0)
                   ->where('category_type','Income')
                   ->get();
    }
    protected function getSubCategories()
    {
        return Subcategory::where('is_deleted',0)->get();
    }
    protected function getClients()
    {
      return Client::with('types')->where('is_deleted',0)->get();
    }
    protected function getIncomes()
    {
      return Income::with(['client', 'subcategory.category', 'payments'])
                     ->where('is_deleted', 0)
                     ->get()
                     ->each(function ($income) {
                    $income->paid = $income->payments->sum('payment_amount');
                    });
    }
}
