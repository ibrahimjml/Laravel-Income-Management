<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Outcome;
use App\Models\Subcategory;
use Illuminate\Support\Facades\DB;

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
      $lang = $data['lang'] ?? 'en';
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
          'description'    => ($lang == 'en') ? $data['description'] : $data['description'],
        ]);

        $outcome->translations()->create([
          'lang_code' => 'ar',
          'description' => $data['description'],
          'created_at' => now()
        ]);
    
      return $outcome;
  }
public function updateOutcome(int $outcomeId, array $data)
{
    return DB::transaction(function() use($outcomeId, $data) {
        
        $lang = $data['lang'] ?? 'en';
        
        if ($data['amount'] <= 0) {
            throw new \Exception('Amount must be greater than 0');
        }

        $category = Category::find($data['category_id']);
        if (!$category || $category->category_type !== 'Outcome') {
            throw new \Exception('Invalid category type');
        }

        $outcome = Outcome::findOrFail($outcomeId);
        
        if($lang == 'en') {
            $outcome->update([
                'subcategory_id' => $data['subcategory_id'],
                'amount'         => $data['amount'],
                'description'    => $data['description'],
            ]);
        

        } elseif ($lang == 'ar') {    
             $outcome->translations()->update([
                'lang_code' => 'ar',
                'description' => $data['description'],
            ]);
            
        }
        
        return $outcome;
    });
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
    return  Subcategory::notDeleted()
                       ->whereHas('category', function($query) {
                        $query->where('category_type', 'Outcome')
                              ->notDeleted();
                            })->get();
  }
  protected function getOutcomes()
  {
    return Outcome::notDeleted()->with('subcategory.category')->get();
  }
}
