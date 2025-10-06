<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Outcome;
use App\Models\Subcategory;

class OutcomeService
{
  public function getOutcomesData()
  {
      $categories    = $this->getCategories();
      $subcategories = $this->getSubCategories();
      $outcomes      = $this->getOutcomes();

        return [
           'categories'     => $categories,
           'sub_categories' => $subcategories,
           'outcomes'       => $outcomes
        ];         
  }
  public function addOutcome(array $data)
  {
       if ($data['amount'] <= 0) {
        return back()->with('error', 'Amount must be greater than 0');
        }

    $category = Category::find($data['category_id']);
    if (!$category || $category->category_type !== 'Outcome') {
        return back()->with('error', 'Invalid category type');
    }
    $outcome =  Outcome::create([
        'subcategory_id' => $data['subcategory_id'],
        'amount'         => $data['amount'],
        'description'    => $data['description'],
      ]);
      return $outcome;
  }
  public function deleteOutcome(int $outcomeId)
  {
      $outcome = Outcome::where('outcome_id',$outcomeId)->firstOrFail();
      $outcome->update(['is_deleted'=>1]);
      return $outcome;
  }
  protected function getCategories()
  {
    return Category::where('is_deleted',0)
                ->where('category_type','Outcome')
                ->get();
  }
  protected function getSubCategories()
  {
    return  Subcategory::whereHas('category', function($query) {
                        $query->where('category_type', 'Outcome')
                              ->where('is_deleted', 0);
                              })
                       ->where('is_deleted', 0)
                       ->get();
  }
  protected function getOutcomes()
  {
    return Outcome::with('subcategory.category')
                       ->where('is_deleted',0)
                       ->get();
  }
}
