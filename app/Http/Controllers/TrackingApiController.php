<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingApiController extends Controller
{
    const API_KEY = 'DEMO';

    const GAPI = "https://api.dhlecommerce.com/account/v1/auth/accesstoken";
    const FULFILLMENT = "https://api.dhlecommerce.com/efulfillment/v1/auth/accesstoken";

    public function index (\Request $request, $code ) {
        $dhl = $this->dhl($code);
        $dhlParsed = $this->dhlParse($dhl);

        return response()->json($dhlParsed);
    }

    function getLloyd ($code) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://www.hapag-lloyd.com/en/online-business/tracing/tracing-by-container.html?container=BMOU++2728050');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Connection: keep-alive';
        $headers[] = 'Cache-Control: max-age=0';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36';
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
        $headers[] = 'Referer: https://www.hapag-lloyd.com/en/online-business/tracing/tracing-by-container.html?container=BMOU++2728050';
        $headers[] = 'Accept-Encoding: gzip, deflate, br';
        $headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
        $headers[] = 'Cookie: isSdEnabled=false; _gcl_au=1.1.295247375.1556633276; _ga=GA1.2.396010875.1556633276; _mkto_trk=id:793-XLJ-305&token:_mch-hapag-lloyd.com-1556633276103-43214; _et_coid=b0ca05bfa32841117a4ea6c8344a5186; _et_coid=b0ca05bfa32841117a4ea6c8344a5186; _gid=GA1.2.1072652192.1556730606; TS01f2bb67=01541c804ac0a449ee1a7820625cf37873f6cfd02fb351811adce37e5e153ee4098cc4bc3249523289ac54eb1f572882a20d84702993710f85c5f1afdafb01653244a1726f; BT_ctst=101; _gcl_aw=GCL.1556802081.CjwKCAjwqqrmBRAAEiwAdpDXtAbR0rBdX9hkb0YVeNeZgbBo6VI7LhCEZKIx_vNWC3eSvPO-dLap8hoCIGwQAvD_BwE; BT_sdc=eyJldF9jb2lkIjoiTkEiLCJyZnIiOiJodHRwczovL3d3dy5nb29nbGUuY29tLyIsInRpbWUiOjE1NTY4MDIwODEwNDIsInBpIjowLCJldXJsIjoiaHR0cHM6Ly93d3cuaGFwYWctbGxveWQuY29tL2VuL29ubGluZS1idXNpbmVzcy9xdW90YXRpb24vcXVpY2stcXVvdGVzLmh0bWw%2FZ2NsaWQ9Q2p3S0NBandxcXJtQlJBQUVpd0FkcERYdEFiUjByQmRYOWhrYjBZVmVOZVpnYkJvNlZJN0xoQ0VaS0l4X3ZOV0MzZVN2UE8tZExhcDhob0NJR3dRQXZEX0J3RSIsInJldHVybmluZyI6MCwiZXRjY19jbXAiOiJOQSIsInNtcyI6bnVsbCwibm9XUyI6InZQM2JucyJ9; _gac_UA-111931200-1=1.1556802081.CjwKCAjwqqrmBRAAEiwAdpDXtAbR0rBdX9hkb0YVeNeZgbBo6VI7LhCEZKIx_vNWC3eSvPO-dLap8hoCIGwQAvD_BwE; TSa4b927ad_27=081ecde62cab200032bf740b897cb1711e659406b702b23b4e7edba699c1c08db6c87da8e68ecd7d08ebdb2cc51120008342f1f2362aea037b5521c09d2b73adf80dd71f4887f72d29222b9fc5fd2363; JSESSIONID=0000AQ5DEPnQC8RqIBf82EFbq2S:1b25u3q9u; TS01a3c52a=01541c804a2f2192a58660256d8273eb1cc6a6e312f2c5585d1b202e4a53945b3326962289d5b44e4a1e0ad4187965176e7b42186c00f745da2bf5b78ad477d1666f59630137c07ac6e63b38a7dbd7586745b7f9c0; TSa4b927ad_76=081ecde62cab28000d4480cd56cb8e912f8a3a7409a1e651381df9f22f8583cac1dda46fca1062c8a4e4bb6a49e58923083addc0d709e80083f8e934ad8622f641b8bbaeb3a06a8520a431362281d550738b3c7472208539268a46989c25781df097c624c7fecc57876bf536586ce87af2c596213612dcc611ed270496f386d9b9a780bb4517c3afba6242db46c5366cd2cd9919d11b91f5d2d1984522a4171a4197a86fe0b0e3de9c45a9893b6b384b9d39fb07b010d9aa531da7ecbe600aacb135868fcbf9bc3efcea238977ffcfd40f85fe6d18cdd8e1777f927e9002311507e911774d7aa9cd023d17dd0db346f671af9947ae2f946ec954340baa56761c38379e7047cafa19ef25244d43537f691963191ebb2fcbc29de92b28faf7a542; TSPD_101=081ecde62cab28000d4480cd56cb8e912f8a3a7409a1e651381df9f22f8583cac1dda46fca1062c8a4e4bb6a49e58923:081ecde62cab28000d4480cd56cb8e912f8a3a7409a1e651381df9f22f8583cac1dda46fca1062c8a4e4bb6a49e58923083addc0d70638003faade63055c79ff449f4d0ce20095d4436b2e99eb1d1dd8c3304670107bdc9d84ef174d9df03050767056802765b4a02072ed0b8b9b8157; TS01a3c52a_77=081ecde62cab2800b8cca85b82b886d02fa4fead14cb436f29c9a65808e00b753208c58f37c6939cbdc72fe0053ce84208e76ce4c4824000ed0547c1a00a570783f9e7d006a1d5249c907eb2e811a994756c31757d775893a2485a518a5080a6e12138a48d18e80d2e9fed585fffabf142e0b6fe5954ba9a; dtPC=-; dtLatC=1; TSff5ac71e_27=081ecde62cab20003ddae0d6b2ea7528f5cf5210717843114796847c93274a30c5b3574f5bfaf690081d5d73961120002db5ac3f5eead8be78e7c0767596ace280eb43159aaa051eb3aac686a5f77046; dtCookie=B22422599489EBAD1597C62A128A305D|d3d3LmhhcGFnLWxsb3lkLmNvbXwx; dtSa=true%7CKD116%7C-1%7CPage%3A%20tracing-by-container.html%3Fcontainer%3DBMOU2728050%7C-%7C1556825192965%7C425026698_853%7Chttps%3A%2F%2Fwww.hapag-lloyd.com%2Fen%2Fonline-business%2Ftracing%2Ftracing-by-container.html%3Fcontainer%3DBMOU2728050%7CTracing%20by%20Container%20-%20Hapag-Lloyd%7C1556825043785%7C';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        return $result;
    }

    public function getTrackingLinks () {
        return response()->json(\App\TrakingLink::all());
    }

    public function postTrackingLinks (\Request $request, $order_id) {
        $order = \App\Order::find($order_id);
        $order->tracking_link_id = \Request::input('traking_link_id');
        $order->dhl_tracking_number = \Request::input('dhl_tracking_code');

        return response()->json(['success' => $order->save()]);
    }

    function dhl ($code) {
        // https://api.dhl.com/dgff/transportation/shipment-tracking
        // https://developer.dhl/api-reference/shipment-tracking#/default/get_shipments
        $key = 'SQ20BZhWJ36Q2YCA7k2r1FKW6KhGdQr8';
        $secret = 'U4VYBysw2Ar3MfhV';


        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api-eu.dhl.com/track/shipments?trackingNumber={$code}&service=express&requesterCountryCode=DE&originCountryCode=DE&language=en");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Dhl-Api-Key: ' . $key;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        return $result;
    }

    function dhlParse ($dhl) {
        return json_decode($dhl);
    }
}
