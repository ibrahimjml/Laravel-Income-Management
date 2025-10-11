<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::all();
        return view('admin.discounts.index',['discounts'=>$discounts]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
          'name' => 'required|string',
          'rate' => 'required|numeric|min:0|max:90',
          'type' => 'required|in:manual,loyalty,early'
        ]);
        Discount::create([
          'name' => $fields['name'],
          'rate' => $fields['rate'],
          'type' => $fields['type'],
        ]) ;
        return back()->with('success','discount added sucessfuly');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {    $discount = Discount::findOrFail($id);
          $fields = $request->validate([
          'name' => 'required|string',
          'rate' => 'required|numeric|min:0|max:90',
          'type' => 'required|in:manual,loyalty,early'
        ]);
        try{
          $discount->update([
          'name' => $fields['name'],
          'rate' => $fields['rate'],
          'type' => $fields['type'],
        ]) ;
        return response()->json([
          'success' => true,
          'message' => 'discount updated successfully'
        ],200);
        }catch(\Exception $e){
          return response()->json([
            'success' => false,
            'message' => 'error ' . $e->getMessage(),
          ]);

        }
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
        return back();

    }
}
