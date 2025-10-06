<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientType;

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
}
