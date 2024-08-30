<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\SiscomexService;

class SiscomexApiController extends Controller
{
    const VALIDATION_URL_PRIVATE = 'https://val.portalunico.siscomex.gov.br';
    const VALIDATION_URL_PUBLIC = 'https://val.anuentes.portalunico.siscomex.gov.br';

    const PRODUCTION_URL_PRIVATE = 'https://portalunico.siscomex.gov.br';
    const PRODUCTION_URL_PUBLIC = 'https://anuentes.portalunico.siscomex.gov.br';

    public function index (\Request $awesome_service) {
        dd(self::authentication());
    }

    protected static function authentication () {
        $custom_header = array(
            'Role-Type' => 'IMPEXP',
        );

        return self::requestURI('/portal/api/autenticar', $custom_header);
    }

    protected static function requestURI ($uri, $custom_header = array()) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::VALIDATION_URL_PRIVATE . $uri,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => $custom_header,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
        ));

        $return = curl_exec($curl);

        curl_close($curl);

        return $return;
    }
}
