<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Outcome;
use App\Models\Subcategory;

class OutcomeService
{
  public function getOutcomesData()
  {
      $categories = $this->getCategories();
      $subcategories = $this->getSubCategories();
      $outcomes = $this->getOutcomes();

        return [
           'categories'     => $categories,
           'sub_categories' => $subcategories,
           'outcomes'       => $outcomes
        ];         
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
