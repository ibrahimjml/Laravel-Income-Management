<?php

namespace App\Services;

use App\Models\{Client, ClientType, ClientTypesRelation, Income};
use App\Models\ClientTranslations;
use Illuminate\Support\Facades\DB;

class ClientService
{
    
    public function getClientsData()
    {
        $clienttype = ClientType::notDeleted()->get();
        $clients = Client::notDeleted()->with('types') ->paginate(5);

            return [
               'clients' => $clients,
               'clients_type' => $clienttype
            ];         
    }
    public function createClient(array $data)
    {
       $lang = $data['lang'] ?? 'en';

       if ($lang === 'en') {
        $client = Client::create([
            'client_fname' => $data['client_fname'],
            'client_lname' => $data['client_lname'],
            'email'        => $data['email'] ?? null,
            'client_phone' => $data['client_phone'] ?? null,
            'created_at'   => now(),
        ]);
     }else {
        
        $client = Client::create([
            'client_fname' => $data['client_fname'] ?? $data['client_lname'] ?? 'N/A', 
            'client_lname' => $data['client_lname'] ?? $data['client_fname'] ?? 'N/A',
            'email'        => $data['email'] ?? null,
            'client_phone' => $data['client_phone'] ?? null,
            'created_at'   => now(),
        ]);
       
      $client->translations()->create([
            'lang_code'    => 'ar',
            'client_fname' => $data['client_fname'],
            'client_lname' => $data['client_lname'],
            'created_at'   => now(),
        ]);
      }
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
          $lang = $data['lang'] ?? 'en';

         if ($lang === 'en') {
            $client->update([
                'client_fname' => $data['client_fname'],
                'client_lname' => $data['client_lname'],
                'email'        => $data['email'] ?? $client->email,
                'client_phone' => $data['client_phone'] ?? $client->client_phone,
            ]);
        } else {
  
            $translation = $client->translations()->where('lang_code', $lang)->first();

            if ($translation) {
                $translation->update([
                    'client_fname' => $data['client_fname'],
                    'client_lname' => $data['client_lname'],
                ]);
            } else {
                $client->translations()->create([
                    'lang_code'    => $lang,
                    'client_fname' => $data['client_fname'],
                    'client_lname' => $data['client_lname'],
                ]);
            }
        }

         $selectedTypeIds = $data['type_id'] ?? [];
        
        // delete old relation
        ClientTypesRelation::where('client_id', $clientId)->delete();    
        // Create new 
        foreach ($selectedTypeIds as $typeId) {
            ClientTypesRelation::create([
                'client_id'  => $clientId,
                'type_id'    => $typeId,
                'created_at' => now(),
            ]);
        }
    });
  }
    public function deleteClient(int $clientId)
    {
        return  DB::transaction(function () use ($clientId) {
            $client = Client::findOrFail($clientId);
            $client->update(['is_deleted' => 1]);
             $client->translations()->update(['is_deleted' => 1]);
             
            Income::where('client_id', $clientId)->update(['is_deleted' => 1]);
            ClientTypesRelation::where('client_id', $clientId)->update(['is_deleted' => 1]);
        });
    
    }
    public function handleForceDelete(int $clientId)
    {
     return DB::transaction(function () use ($clientId) {
        $client = Client::findOrFail($clientId);
        
        Income::where('client_id', $clientId)->delete();
        ClientTypesRelation::where('client_id', $clientId)->delete();
        ClientTranslations::where('client_id', $clientId)->delete();
        
        $client->delete();
    });
    }
    public function handleRecovery(int $clientId)
    {
        return  DB::transaction(function () use ($clientId) {
             $client = Client::findOrFail($clientId);
             $client->update(['is_deleted' => 0]);
             $client->translations()->update(['is_deleted' => 0]);
             
            Income::where('client_id', $clientId)->update(['is_deleted' => 0]);
            ClientTypesRelation::where('client_id', $clientId)->update(['is_deleted' => 0]);
        });
    }
    
}
