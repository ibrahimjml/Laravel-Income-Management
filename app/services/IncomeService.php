<?php

namespace App\Services;

use App\Models\{Category, Client, Income, Payment, Subcategory};
use Illuminate\Support\Facades\DB;

class IncomeService
{
    public function getIcomeData()
    {
      $categories    = $this->getCategories();
      $subcategories = $this->getSubCategories();
      $clients       = $this->getClients();
      $incomes       = $this->getIncomes();

      return [
         'categories'     => $categories,
         'sub_categories' => $subcategories,
         'clients'        => $clients,
         'incomes'        => $incomes
      ];
    }
    public function createIncome(array $data)
    {
      return DB::transaction(function() use($data){
          $data['date'] = now()->format('Y-m-d');

         $income = Income::create([
             'client_id'      => $data['client_id'],
             'subcategory_id' => $data['subcategory_id'],
             'amount'         => $data['amount'],
             'description'    => $data['description'],
             'next_payment'   => $data['next_payment'],
             'status'         => 'Pending' 
         ]);
          $income->translations()->create([
            'lang_code' => 'ar',
            'description' => $data['description'],
            'created_at' => now()
          ]);

         if (isset($data['paid']) && $data['paid'] > 0) {
             if ($data['paid'] > $data['amount']) {
                 return back()->with('error',"Paid amount cannot be greater than total income amount");
             }
 
             Payment::create([
                 'income_id' => $income->income_id,
                 'payment_amount' => $data['paid']
             ]);
 
             $totalPaid = Payment::where('income_id', $income->income_id)->sum('payment_amount');
 
             $status = 'pending';
             if ($totalPaid == $data['amount']) {
                 $status = 'complete';
             } elseif ($totalPaid > 0) {
                 $status = 'partial';
             }
 
             $income->update(['status' => $status]);
         }
         return $income;
      });
    }
    public function updateIncome(int $incomeId, array $data)
    { 
       return DB::transaction(function() use($incomeId, $data){
            $income = Income::where('income_id',$incomeId)->firstOrFail();
            $data['date'] = now()->format('Y-m-d');
            $lang = $data['lang'] ?? 'en';
         
            if($lang == 'en'){
             $income->update([
             'client_id'      => $data['client_id'],
             'subcategory_id' => $data['subcategory_id'],
             'amount'         => $data['amount'],
             'description'    => $data['description'],
             'next_payment'   => $data['next_payment'],
               ]);
            }elseif ($lang == 'ar'){
              $income->translations()->update([
                    'lang_code' => 'ar',
                    'description' => $data['description']
                 ]);
            }
        
         return $income;
      });
    }
    public function deleteIncome(int $incomeId)
    {
      return DB::transaction(function() use($incomeId){
          $income = Income::where('income_id', $incomeId)->firstOrFail();
          $income->update(['is_deleted' => 1]);
          
          Payment::where('income_id', $incomeId)
                ->update(['is_deleted' => 1]);

          return true;      
      });
    }
    public function getIcomeDetails(int $incomeId)
    {
         $income = Income::with(['client','subcategory.category','payments'])
                      ->withSum('payments as paid', 'payment_amount')
                      ->where('income_id', $incomeId)
                      ->notDeleted()
                      ->firstOrFail();
      if ($income->client->is_deleted == 1) {
            abort(404, 'Client not found');
            };
        $payments = Payment::notDeleted()->where('income_id',$incomeId)->get();
        $clients = Client::notDeleted()->get();
        $categories = Category::notDeleted()->get();
        $subcategories = Subcategory::notDeleted()->get();

        return [
           'income'        => $income,
           'payments'      => $payments,
           'clients'       => $clients,
           'categories'    => $categories,
           'subcategories' => $subcategories
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
    return Income::with(['client', 'subcategory.category'])
                   ->withSum('payments', 'payment_amount')
                   ->notDeleted()
                   ->paginate(7)
                   ->through(function ($income) {
                       $income->paid = $income->payments_sum_payment_amount;
                       return $income;
                   });
    }
}
