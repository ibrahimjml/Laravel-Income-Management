<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Outcome;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class OutcomesController extends Controller
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

    public function add_outcome(Request $request)
    {
      $fields = $request->validate([
        'category_id' => 'required|integer|exists:categories,category_id',
        'subcategory_id' => 'required|integer|exists:subcategories,subcategory_id',
        'amount' => 'required|numeric|min:0.01',
        'description' => 'required|string',
      ]);
      if ($fields['amount'] <= 0) {
        return back()->with('error', 'Amount must be greater than 0');
    }

    $category = Category::find($fields['category_id']);
    if (!$category || $category->category_type !== 'Outcome') {
        return back()->with('error', 'Invalid category type');
    }
      Outcome::create([
        'subcategory_id' => $fields['subcategory_id'],
        'amount' => $fields['amount'],
        'description' => $fields['description'],
      ]);
      return back()->with('success','outcome added successfuly');
    }

    public function delete($outcome_id)
    {
      $outcome = Outcome::where('outcome_id',$outcome_id)->firstOrFail();
      $outcome->update(['is_deleted'=>1]);
      return response()->json([
        'success' => true,
        'message' => 'Outncome  deleted successfully.'
    ], 200);
    }
}
