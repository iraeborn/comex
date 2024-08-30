<?php

namespace App\Http\Controllers\API;

use App\ContractProvision;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProvisionPostingRequest;
use App\ProvisionPosting;
use Request;

class ContractServicesController extends Controller
{

    public function factorTypes ()
    {
        return [
            [
                'id' => 1,
                'text' => 'per conteiner'
            ],
            [
                'id' => 2,
                'text' => 'per conteiner (dolar day)'
            ],
            [
                'id' => 3,
                'text' => 'per ton'
            ],
            [
                'id' => 4,
                'text' => 'per order'
            ],
            [
                'id' => 5,
                'text' => 'per order (imbued)'
            ]
        ];
    }
}
