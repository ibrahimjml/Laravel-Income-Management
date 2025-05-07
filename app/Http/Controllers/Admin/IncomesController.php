<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Income;
use App\Models\Payment;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomesController extends Controller
{
    public function add_category(Request $request)
    {
      $fields = $request->validate([
        'category_name' => 'required|string|max:20',
        'category_type' => 'nullable|in:Income,Outcome'
      ]);
      Category::create([
        'category_name' =>$fields['category_name'],
        'category_type' => $fields['category_type']
      ]);
      return back()->with('success','category added successfuly !');
    }

    public function add_subcategory(Request $request)
    {
      $fields = $request->validate([
        'category_id' => 'required|integer|exists:categories,category_id',
        'sub_name' => 'required|string|max:20'
      ]);
      Subcategory::create([
        'category_id' =>$fields['category_id'],
        'sub_name' => $fields['sub_name']
      ]);
      return back()->with('success','Subcategory added successfuly !');
    }

    public function add_income(Request $request)
    {
      $fields = $request->validate([
        'client_id' => 'required|integer|exists:clients,client_id',
        'category_id' => 'required|integer|exists:categories,category_id',
        'subcategory_id' => 'required|integer|exists:subcategories,subcategory_id',
        'amount' => 'required|numeric|min:0.01',
        'paid' => 'nullable|numeric|min:0',
        'description' => 'required|string',
        'next_payment' => 'required|date'
      ]);
  
     DB::beginTransaction();

     try {
      $fields['date'] = now()->format('Y-m-d');

         $income = Income::create([
             'client_id' => $fields['client_id'],
             'subcategory_id' => $fields['subcategory_id'],
             'amount' => $fields['amount'],
             'description' => $fields['description'],
             'next_payment' => $fields['next_payment'],
             'status' => 'Pending' 
         ]);
 

         if ($request->filled('paid') && $fields['paid'] > 0) {
             if ($fields['paid'] > $fields['amount']) {
                 return back()->with('error',"Paid amount cannot be greater than total income amount");
             }
 
             Payment::create([
                 'income_id' => $income->income_id,
                 'payment_amount' => $fields['paid']
             ]);
 
             $totalPaid = Payment::where('income_id', $income->income_id)->sum('payment_amount');
 
             $status = 'pending';
             if ($totalPaid == $fields['amount']) {
                 $status = 'complete';
             } elseif ($totalPaid > 0) {
                 $status = 'partial';
             }
 
             $income->update(['status' => $status]);
         }
 
         DB::commit();
 
         return back()->with('success', 'Income added successfully!');
 
     } catch (\Exception $e) {
         DB::rollBack();
         return back()->with('error', 'Error: ' . $e->getMessage());
     }

    }
    public function delete($income_id)
    {

      try {
          $income = Income::where('income_id', $income_id)->firstOrFail();
          $income->update(['is_deleted' => 1]);
          
          Payment::where('income_id', $income_id)
                ->update(['is_deleted' => 1]);
          
          return response()->json([
              'success' => true,
              'message' => 'Income and associated records deleted successfully.'
          ], 200);
          
      } catch (\Exception $e) {
          return response()->json([
              'success' => false,
              'message' => 'Failed to delete income: ' . $e->getMessage()
          ], 500);
      }
    }
    
    public function update(Request $request,$income_id)
    {
      $fields = $request->validate([
        'client_id' => 'required|integer|exists:clients,client_id',
        'category_id' => 'required|integer|exists:categories,category_id',
        'subcategory_id' => 'required|integer|exists:subcategories,subcategory_id',
        'amount' => 'required|numeric|min:0.01',
        'description' => 'required|string',
        'next_payment' => 'required|date'
      ]);
      $income = Income::where('income_id',$income_id)->firstOrFail();


     try {
      $fields['date'] = now()->format('Y-m-d');

         $income->update([
             'client_id' => $fields['client_id'],
             'subcategory_id' => $fields['subcategory_id'],
             'amount' => $fields['amount'],
             'description' => $fields['description'],
             'next_payment' => $fields['next_payment'],
         ]);
 
         return back()->with('success', 'Income updated successfully!');
 
     } catch (\Exception $e) {
         return back()->with('error', 'Error: ' . $e->getMessage());
     }

    }

    public function add_payment(Request $request,$income_id)
    {
      $fields = $request->validate([
        'payment_amount' => 'required|numeric|min:0.01',
        'description' => 'required|string',
        'next_payment' => 'nullable|date'
    ]);

    DB::beginTransaction();

    try {
            Payment::create([
            'income_id' => $income_id,
            'payment_amount' => $fields['payment_amount'],
            'description' => $fields['description'] ?? null
             ]);

        if ($request->filled('next_payment')) {
            Income::where('income_id', $income_id)
                ->update(['next_payment' => $fields['next_payment']]);
        }

        $income = Income::findOrFail($income_id);
        $totalPaid = Payment::where('income_id', $income_id)->sum('payment_amount');
        
        $status = 'pending';
        if ($totalPaid > 0 && $totalPaid < $income->amount) {
            $status = 'partial';
        } elseif ($totalPaid >= $income->amount) {
            $status = 'complete';
        }

        $income->update(['status' => $status]);

        DB::commit();

        return back()->with('success','payment updated !');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error','Error: ' . $e->getMessage());
    }

    }
    public function show($income_id)
    {
      $income = Income::with(['client','subcategory.category','payments'])
                      ->withSum('payments as paid', 'payment_amount')
                      ->where('income_id', $income_id)
                      ->where('is_deleted', 0)
                      ->firstOrFail();
      if ($income->client->is_deleted == 1) {
            abort(404, 'Client not found');
            };
        $payments = Payment::where('income_id',$income_id)->where('is_deleted',0)->get();
        $clients = Client::where('is_deleted',0)->get();
        $categories = Category::where('is_deleted',0)->get();
        $subcategories = Subcategory::where('is_deleted',0)->get();
        return view('admin.incomes.details',[
          'income' => $income,
          'payments'=>$payments,
          'clients' => $clients,
          'categories' => $categories,
          'subcategories' => $subcategories
        ]);
    }
}
