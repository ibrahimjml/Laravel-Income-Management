<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientType;
use App\Models\ClientTypesRelation;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{

    public function add_type(Request $request){
     $fields =  $request->validate([
        'type_name'=>'required'
      ]);
      ClientType::create([
        'type_name' => $fields['type_name']
      ]);

      return back()->with('success',"client type {$request->type_name} added !");
    }
    public function edit_type(Request $request, $id)
    {
  
            $validated = $request->validate([
                'type_name' => 'required|string|max:255'
            ]);
    
            $clientType = ClientType::findOrFail($id);
            $clientType->update($validated);
    
            return response()->json([
                'success' => true,
                'message' => 'Client type updated successfully',
                'data' => $clientType
            ]);
    }
    public function delete_type(Request $request,$id)
    {
        $clientType = ClientType::findOrFail($id);

        $clientType->update([
          'is_deleted' =>1
        ]);
        $clientType->save();
        return response()->json([
          'success' => true,
          'message' => 'Client type updated successfully',
          'data' => $clientType
      ]);
    }
    public function add( Request $request)
    {
      $fields = $request->validate([
        'client_fname' => 'required|string|max:20',
        'client_lname' => 'required|string|max:20',
        'client_phone' => 'required|numeric',
        'type_id' => 'required|array',
        'type_id.*' => 'exists:client_type,type_id', 
    ]);
    $client =  Client::create([
      'client_fname' => $fields['client_fname'],
      'client_lname' => $fields['client_lname'],
      'client_phone' => $fields['client_phone']
   ]);
   foreach ($fields['type_id'] as $typeId) {
    ClientTypesRelation::create([
        'type_id' => $typeId,
        'client_id' => $client->client_id,
        'created_at' => now(),
        'is_deleted' => 0
    ]);
}
      return back()->with('success','client added !');
  }

  public function edit(Request $request, $id) {
    $fields = $request->validate([
        'client_fname' => 'required|string|max:20',
        'client_lname' => 'required|string|max:20',
        'client_phone' => 'required|numeric',
        'type_id' => 'required|array',
        'type_id.*' => 'exists:client_type,type_id', 
    ]);

        $client = Client::findOrFail($id);
        
        $client->update([
            'client_fname' => $fields['client_fname'],
            'client_lname' => $fields['client_lname'],
            'client_phone' => $fields['client_phone'],
        ]);

        ClientTypesRelation::where('client_id', $id)->delete();

        foreach ($fields['type_id'] as $typeId) {
            $existingRelation = ClientTypesRelation::where('client_id', $id)
                ->where('type_id', $typeId)
                ->where('is_deleted', 0)
                ->first();

            if (!$existingRelation) {
      
                ClientTypesRelation::create([
                    'client_id' => $id,
                    'type_id' => $typeId,
                    'created_at' => now(),
                    'is_deleted' => 0,  
                ]);
            }
        }
        return response()->json(['message' => 'Client updated successfully.']);
}

    public function delete($id)
    {
      DB::beginTransaction();
      try {
        $client = Client::findOrFail($id);
        $client->where('client_id',$id)->update(['is_deleted' => 1]);
        
        Income::where('client_id', $id)->update(['is_deleted' => 1]);
        ClientTypesRelation::where('client_id', $id)->update(['is_deleted' => 1]);

        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Client and all associated records deleted successfully.'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
    }
}

