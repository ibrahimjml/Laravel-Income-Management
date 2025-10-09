<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\CreateClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Requests\ClientType\CreateTypeRequest;
use App\Models\Client;
use App\Models\ClientType;
use App\Services\ClientService;


class ClientsController extends Controller
{   
    protected $clientService;
    public function __construct(ClientService $clientService)
    {
       $this->clientService = $clientService;
    }
    public function add_client_type(CreateTypeRequest $request)
    {
      $fields =  $request->validated();
       $lang = $fields['lang'] ?? 'en';

  
       $clientType = ClientType::create([
        'type_name' => $lang === 'en' ? $fields['type_name'] : $fields['type_name'] ,
    ]);

    $clientType->translations()->create([
        'lang_code' => 'ar',
        'type_name' => $fields['type_name'],
    ]);
      return back()->with('success',"client type {$request->type_name} added !");
    }
    public function edit_client_type(CreateTypeRequest $request, $id)
    {
            $clientType = ClientType::findOrFail($id);
            $validated = $request->validated();
             $lang = $validated['lang'] ;

        if($lang === 'en'){
           $clientType->update([
          'type_name' =>  $validated['type_name']]);
        }
        
        if ($lang === 'ar') {
        $translation = $clientType->translations()->where('lang_code', 'ar')->first();

        if ($translation) {
            $translation->update([
                'type_name' => $validated['type_name'],
            ]);
        } else {
            $clientType->translations()->create([
                'lang_code' => 'ar',
                'type_name' => $validated['type_name'],
            ]);
        }
    }

            return response()->json([
                'success' => true,
                'message' => 'Client type updated successfully',
                'data' => $clientType
            ]);
    }
    public function delete_type($id)
    {
        $clientType = ClientType::findOrFail($id);

        $clientType->update(['is_deleted' => 1]);

        return response()->json([
          'success' => true,
          'message' => 'Client type updated successfully',
          'data' => $clientType
      ]);
    }
    public function add_client(CreateClientRequest $request)
    {
      $this->clientService->createClient($request->validated());
      return back()->with('success','client added !');
  }

  public function edit_client(UpdateClientRequest $request, $id)
  {
        $this->clientService->updateClient($id, $request->validated());
        return response()->json(['message' => 'Client updated successfully.']);
  }

    public function delete_client($id)
    {
      try {
            $this->clientService->deleteClient($id);
            return response()->json([
                'success' => true,
                'message' => 'Client and all associated records deleted successfully.'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    
    }
    public function trashed_clients()
    {
      $clients = Client::isDeleted()->with('types')->paginate(7);
      return view('admin.clients.trashed',['clients' => $clients]);
    }
    public function recover($id)
    {
        try {
        $this->clientService->handleRecovery($id);
        return response()->json([
            'success' => true,
            'message' => 'Client recovered successfully'
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
        $this->clientService->handleForceDelete($id);
        return response()->json([
            'success' => true,
            'message' => 'Client permanently deleted successfully'
        ]);
        
      } catch (\Exception $e) {  
         return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
         ], 500);
     }
    }
}

