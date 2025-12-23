<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Outcome\CreateOutcomeRequest;
use App\Http\Requests\Outcome\UpdateOutcomeRequest;
use App\Http\Requests\Subcategory\CreateSubcategoryRequest;
use App\Models\Category;
use App\Models\Outcome;
use App\Models\Subcategory;
use App\Services\OutcomeService;

class OutcomesController extends Controller
{
    protected $outcomeService;
    public function __construct(OutcomeService $outcomeService){
      $this->outcomeService = $outcomeService;
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

    public function add_outcome(CreateOutcomeRequest $request)
    {
      $fields = $request->validated();
      $this->outcomeService->addOutcome($fields);
      return back()->with('success','outcome added successfuly');
    }

    public function delete($outcome_id)
    {
      $this->outcomeService->deleteOutcome($outcome_id);
      return response()->json([
        'success' => true,
        'message' => 'Outncome  deleted successfully.'
    ], 200);
    }
    public function edit_outcome($id, UpdateOutcomeRequest $request)
    {
      $fields = $request->validated();
       try {
         $this->outcomeService->updateOutcome($id, $fields); 
         return response()->json([
            'success' => true,
            'message' => 'Outcome updated successfully!'
        ]);
 
     } catch (\Exception $e) {
         return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
     }
    }
    public function trashed_outcomes()
    {
      return view('admin.outcomes.trashed',['outcomes'=> Outcome::isDeleted()->with('subcategory.category')->latest()->paginate('10')]);
    }
    public function recover($id)
    {
       try {
            $outcome = Outcome::findOrFail($id);

            $outcome->update([
                'is_deleted' => 0
             ]);
          
        return response()->json([
            'success' => true,
            'message' => __('message.outcome Recovered')
        ]);
        
      } catch (\Exception $e) {  
         return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
         ], 500);
     }
    }
    public function force_delete($id)
    {
        try {
        Outcome::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => __('message.Outcome Permanently Deleted')
        ]);
        
      } catch (\Exception $e) {  
         return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
         ], 500);
     }
    }
}
