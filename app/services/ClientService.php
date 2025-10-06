<?php

namespace App\Services;

use App\Models\{Client, ClientType, ClientTypesRelation, Income};
use Illuminate\Support\Facades\DB;

class ClientService
{
    
    public function getClientsData()
    {
        $clienttype = ClientType::where('is_deleted',0)->get();
        $clients = Client::with('types') 
                     ->where('is_deleted', 0)
                     ->paginate(6);
            return [
               'clients' => $clients,
               'clients_type' => $clienttype
            ];         
    }
    public function createClient(array $data)
    {
       $client = Client::create([
            'client_fname' => $data['client_fname'],
            'client_lname' => $data['client_lname'],
            'email'        => $data['email'],
            'client_phone' => $data['client_phone'],
            'created_at'   => now(),
        ]);

        foreach ($data['type_id'] as $typeId) {
            ClientTypesRelation::create([
                'type_id'   => $typeId,
                'client_id' => $client->client_id,
                'created_at'=> now(),
            ]);
        }

        return $client;
   
    }
    public function updateClient(int $clientId, array $data)
    {   
       return DB::transaction(function() use($clientId, $data){

         $client = Client::findOrFail($clientId);
         $client->update($data);

        $selectedTypeIds = $data['type_id'] ?? [];
        $existingRelations = ClientTypesRelation::where('client_id', $clientId)->get();

        foreach ($existingRelations as $relation) {
            if (!in_array($relation->type_id, $selectedTypeIds)) {
                $relation->update(['is_deleted' => 1]);
            }
        }

        foreach ($selectedTypeIds as $typeId) {
            $relation = ClientTypesRelation::where('client_id', $clientId)
                ->where('type_id', $typeId)
                ->first();

            if ($relation) {
                $relation->update(['is_deleted' => 0]);
            } else {
                ClientTypesRelation::create([
                    'client_id'  => $clientId,
                    'type_id'    => $typeId,
                    'created_at' => now(),
                ]);
            }
        }
    });
  }
    public function deleteClient(int $clientId)
    {
          DB::transaction(function () use ($clientId) {
            $client = Client::findOrFail($clientId);
            $client->update(['is_deleted' => 1]);

            Income::where('client_id', $clientId)->update(['is_deleted' => 1]);
            ClientTypesRelation::where('client_id', $clientId)->update(['is_deleted' => 1]);
        });
    
    }
    
}
