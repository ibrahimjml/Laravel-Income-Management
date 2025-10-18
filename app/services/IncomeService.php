<?php

namespace App\Services;

use App\Models\{Category, Client, Income, Payment, Penalty, Subcategory};
use App\Models\Discount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncomeService
{
    public function getIcomeData()
    {
      $categories    = $this->getCategories();
      $subcategories = $this->getSubCategories();
      $discountRates = $this->getDiscounts();  
      $clients       = $this->getClients();
      $incomes       = $this->getIncomes();

      return [
         'categories'     => $categories,
         'sub_categories' => $subcategories,
         'discounts'      => $discountRates,
         'clients'        => $clients,
         'incomes'        => $incomes
      ];
    }
public function createIncome(array $data)
{
    return DB::transaction(function () use ($data) {

        $data['date'] = now()->format('Y-m-d');

        $amount = $data['amount'];
        $discountAmount = 0;
        $finalAmount = 0;

        if (!empty($data['discount_id'])) {
            $discount = Discount::find($data['discount_id']);

            if ($discount) {
                $discountAmount = ($discount->rate / 100) * $amount;
                $finalAmount = $amount - $discountAmount;
            }
        }

        $income = Income::create([
            'client_id'       => $data['client_id'],
            'subcategory_id'  => $data['subcategory_id'],
            'amount'          => $amount,
            'discount_id'     => $data['discount_id'] ?? null,
            'discount_amount' => $discountAmount,
            'final_amount'    => $finalAmount,
            'description'     => $data['description'] ?? null,
            'next_payment'    => $data['next_payment'] ?? null,
            'status'          => 'pending'
        ]);

        if (!empty($data['description']) && $data['lang'] === 'ar') {
            $income->translations()->create([
                'lang_code'   => 'ar',
                'description' => $data['description'],
                'created_at'  => now(),
            ]);
        }

        if (!empty($data['paid']) && $data['paid'] > 0) {

          $executeamount = !empty($data['discount_id']) ? $finalAmount : $amount;
           if ($data['paid'] > $executeamount) {
                throw new \Exception('Paid amount cannot be greater than the total due amount.');
              }

            Payment::create([
                'income_id'      => $income->income_id,
                'payment_amount' => $data['paid'],
                'status'         => $data['payment_status'],
                'next_payment'   => $data['next_payment'] ?? null,
             ]);

            $totalPaid = Payment::where('income_id', $income->income_id)
                                ->where('status', 'paid')
                                ->sum('payment_amount');

             $status = 'pending';
            if ($totalPaid >= $executeamount) {
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
    protected function getDiscounts()
    {
     return Discount::where('is_deleted', 0)
                    ->pluck('rate', 'discount_id');
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
    return Income::notDeleted()
                   ->with(['client', 'subcategory.category'])
                   ->withSum( 'payments','payment_amount')
                   ->paginate(7)
                   ->through(function ($income) {
                       $income->paid = $income->payments_sum_payment_amount;
                       return $income;
                   });
    }
}
