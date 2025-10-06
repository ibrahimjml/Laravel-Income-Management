<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Outcome\CreateOutcomeRequest;
use App\Http\Requests\Subcategory\CreateSubcategoryRequest;
use App\Models\Category;
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
      Category::create([
        'category_name' =>$fields['category_name'],
        'category_type' => $fields['category_type']
      ]);
      return back()->with('success','category added successfuly !');
    }

    public function add_subcategory(CreateSubcategoryRequest $request)
    {
      $fields = $request->validated();
      Subcategory::create([
        'category_id' =>$fields['category_id'],
        'sub_name' => $fields['sub_name']
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
}
