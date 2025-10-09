<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Income\{CreateIncomeRequest, UpdateIncomeRequest};
use App\Http\Requests\Subcategory\CreateSubcategoryRequest;
use App\Models\{Category, Subcategory};
use App\Services\{IncomeService, PaymentService};

class IncomesController extends Controller
{   
    protected $incomeService;
    protected $paymentService;
    public function __construct(IncomeService $incomeService, PaymentService $paymentService){
      $this->incomeService = $incomeService;
      $this->paymentService = $paymentService;
    }
    public function add_category(CreateCategoryRequest $request)
    {
      $fields = $request->validated();

        $category = Category::create([
          'category_name' => $fields['name_en'],
          'category_type' => $fields['category_type']
        ]);
    
        $category->translations()->create([
          'lang_code'     => 'ar',
          'category_name' => $fields['name_ar'],
          'created_at'    => now()
        ]);
    
      return back()->with('success','category added successfuly !');
    }

    public function add_subcategory(CreateSubcategoryRequest $request)
    {
      $fields = $request->validated();
      
      $sub =  Subcategory::create([
        'category_id' =>$fields['category_id'],
        'sub_name' => $fields['name_en']
      ]);
      $sub->translations()->create([
        'lang_code' => 'ar',
        'sub_name'  => $fields['name_ar'],
        'created_at' => now()
      ]);
      return back()->with('success','Subcategory added successfuly !');
    }

    public function add_income(CreateIncomeRequest $request)
    {
      $fields = $request->validated();

     try {
         $this->incomeService->createIncome($fields);
         return back()->with('success', 'Income added successfully!');
 
     } catch (\Exception $e) {

         return back()->with('error', 'Error: ' . $e->getMessage());
     }

    }
    public function delete($income_id)
    {

      try {
          $this->incomeService->deleteIncome($income_id);
          
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
    
    public function update(UpdateIncomeRequest $request,$income_id)
    {
      $fields = $request->validated();
      
     try {
          $this->incomeService->updateIncome($income_id, $fields); 
          return back()->with('success', 'Income updated successfully!');
 
     } catch (\Exception $e) {
         return back()->with('error', 'Error: ' . $e->getMessage());
     }

    }
  
    public function show($income_id)
    {
        $data = $this->incomeService->getIcomeDetails($income_id);

        return view('admin.incomes.details',[
          'income'        => $data['income'],
          'payments'      =>$data['payments'],
          'clients'       => $data['clients'],
          'categories'    => $data['categories'],
          'subcategories' => $data['subcategories']
        ]);
    }
}
