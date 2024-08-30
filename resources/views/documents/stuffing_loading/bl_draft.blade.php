<style>
    @page { margin-top: 15px; }
    body {
        font-family: sans-serif;
        font-size: 11px
    }

    .border-strong {
        border: 2px solid black;
    }

    .border {
        border: 1px solid black;
    }

    .border-none {
        border: none;
    }

    .border-top {
        border-top: 1px solid black;
    }

    .border-bottom {
        border-bottom: 1px solid black;
    }

    .border-left {
        border-left: 1px solid black;
    }

    .border-right {
        border-right: 1px solid black;
    }

    table {
        border-collapse: collapse;
    }

    .italic {
        font-style: italic;
    }

    .pmin {
        padding: 0.1rem;
    }

    .p1 {
        padding: 0.5rem;
    }

    .p2 {
        padding: 1rem;
    }

    .p3 {
        padding: 1.5rem;
    }

    .p4 {
        padding: 2rem;
    }

    .p5 {
        padding: 2.5rem;
    }

    .pt {
        padding-left: 0;
        padding-bottom: 0;
        padding-right: 0;
    }

    .pb {
        padding-left: 0;
        padding-top: 0;
        padding-right: 0;
    }

    .pl {
        padding-top: 0;
        padding-bottom: 0;
        padding-right: 0;
    }

    .pr {
        padding-left: 0;
        padding-bottom: 0;
        padding-top: 0;
    }

    .px {
        padding-top: 0;
        padding-bottom: 0;
    }

    .py {
        padding-left: 0;
        padding-right: 0;
    }

    .mt {
        margin-right: 0;
        margin-left: 0;
        margin-bottom: 0;
    }

    .f8 {
        font-size: 5px;
    }

    .f10 {
        font-size: 8px;
    }

    .f12 {
        font-size: 10px;
    }

    .f14 {
        font-size: 12px;
    }

    .f16 {
        font-size: 14px;
    }

    .f24 {
        font-size: 22px;
    }

    .upper {
        text-transform: uppercase;
    }

    .underline {
        text-decoration: underline;
    }

    .highlight {
        font-weight: bold;
        background-color: lightgrey;
    }

    .page-break { 
        page-break-before: always; 
    }
</style>

<table width="100%" cellpadding="0">
    <tr>
        <td colspan="2" align="center">
            <h2 style="margin: 0 0 5px 0;">
                DRAFT FOR THE BILL OF LADING
            </h2>
        </td>
    </tr>

    <tr>
        <td width="50%" align="center">
            <strong class="f12">
                NOT NEGOTIABLE UNLESS CONSIGNED " TO ORDER "
            </strong>
        </td>
        <td width="50%">&nbsp;</td>
    </tr>

    <tr>
        <td width="50%" valign="top" class="f12 border pmin">
            Shipper<br/>
            @if(isset($data->exporter))
                {{strtoupper($data->exporter->name)}}<br />
                {!! $data->exporter->addressString() !!}
                {!! $data->exporter->regString() !!}
                {!! $data->exporter->contactString() !!}
            @endif
        </td>

        <td width="50%" rowspan="2" class="border-bottom" valign="top">
            <table width="100%">
                <tr>
                    <td width="40%" align="center" valign="center" class="border-none">
                        @if(isset($data->exporter) && $data->exporter->profile_picture)
                            <img width="80px" src="{{$data->exporter->profile_picture}}" alt="">
                        @else 
                            <img src="@include('documents.logo')" alt="AgricolaLucas" width="80px">
                        @endif
                    </td>

                    <td width="60%" valign="top">
                        <table width="100%">
                            <tr>
                                <td class="f12 border pmin">
                                    B/L No.<br/><br/>
                                    <strong class="upper">
                                        {{ ($data->mapa ? $data->mapa->bill_of_lading : '') }}
                                    </strong>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" valign="bottom" class="f12 pmin pt p5">
                        <strong class="p1">
                            Booking No. 
                            <span class="upper">
                                {{ ($data->shipping->first() ? $data->shipping->first()->booking : '') }}
                            </span>
                        </strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td width="50%" valign="top" class="f12 border pmin">
            Consignee (if 'To Order' so indicate)<br/>
            <span class="upper">
                {{$data->consignee->name}} <br/>
                {!! $data->consignee->addressString() !!}
                {!! $data->consignee->contactString(true) !!}
            </span>
        </td>
    </tr>

    <tr>
        <td width="50%" class="border">
            <table width="100%" cellpadding="0">
                <tr>
                    <td colspan="2" valign="top" class="f12 border-bottom pmin">
                        Notify party (No claim shall attach for failure to notify)<br/>
                        <span class="upper">
                            {{$data->notify->name}} <br/>
                            {!! $data->notify->addressString() !!}
                            {!! $data->notify->contactString(true) !!}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td width="50%" valign="top" class="f12 pmin">
                        Place of Receipt<br/>
                        <span class="upper">
                            {{ ($data->shipping->first() && $data->shipping->first() && $data->shipping->first()->loading_port ? $data->shipping->first()->loading_port->name : '') }}
                        </span>
                    </td>
                    <td width="50%" valign="top" class="f12 border-left pmin">
                        Port of Loading<br/>
                        <span class="upper">
                            {{ ($data->shipping->first() && $data->shipping->first() && $data->shipping->first()->loading_port ? $data->shipping->first()->loading_port->name : '') }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" valign="top" class="f12 border-top border-bottom pmin">
                        Ocean vessel / Voy No.<br/>
                        <span class="upper">
                            {{ ($data->shipping->first() ? $data->shipping->first()->vessel : '') }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td width="50%" valign="top" class="f12 pmin">
                        Port of Discharge<br/>
                        <span class="upper">
                            {{ ($data->shipping->first() && $data->shipping->first() && $data->shipping->first()->discharge_port ? $data->shipping->first()->discharge_port->name : '') }}
                        </span>
                    </td>
                    <td width="50%" valign="top" class="f12 border-left pmin">
                        Place of Delivery<br/>
                        <span class="upper">
                            {{ ($data->shipping->first() && $data->shipping->first() && $data->shipping->first()->discharge_port ? $data->shipping->first()->discharge_port->name : '') }}
                        </span>
                    </td>
                </tr>
            </table>
        </td>

        <td width="50%" class="border" valign="top">
            <table width="100%" cellpadding="0">
                <tr>
                    <td class="f12 border-bottom pmin" valign="top">
                        For delivery of goods please apply to:
                        <br/><br/><br/><br/>
                        <strong>NÃO PREENCHER</strong>
                        <br/><br/><br/><br/>
                    </td>
                </tr>

                <tr>
                    <td class="f12 pmin">
                        <strong>Local de Emissão dos Originais</strong><br/>
                        Origem ( X ) PREPAID<br/>
                        Destino ( )<br/>
                        Express Release ( )<br/>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="2" class="f14 border-strong p1" align="center">
            <strong>- PARTICULARS FURNISHED BY SHIPPER -</strong>
        </td>
    </tr>

    <tr>
        <td colspan="2" class="border" valign="top">
            <table width="100%">
                <tr>
                    <td width="20%" class="f12 border-bottom" align="center">
                        Marks and Numbers
                    </td>
                    <td width="15%" class="f12 border-bottom border-left" align="center">
                        Quantity and Type of Packages
                    </td>
                    <td width="30%" class="f12 border-bottom border-left" align="center">
                        Kind of Packages/Description of
                    </td>
                    <td width="15%" class="f12 border-bottom border-left" align="center">
                        Gross Weight
                    </td>
                    <td width="20%" class="f12 border-bottom border-left" align="center">
                        Measurement
                    </td>
                </tr>

                <tr>                    
                    <td width="20%" class="f12 border-bottom" align="center" valign="top">
                        <strong class="upper">
                            @if ($data->shipping->first() && $data->shipping->first()->containers)
                                @foreach ($data->shipping->first()->containers as $container)
                                    {{ $container->name }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $container->seal }}<br/>
                                @endforeach
                            @endif
                        </strong>
                    </td>
                    <td width="15%" class="f12 border-bottom border-left" align="center" valign="top">
                        <strong class="upper">
                            @if ($data->shipping->first() && $data->shipping->first()->containers)
                                @foreach ($data->shipping->first()->containers as $container)
                                    {{ $container->bills ? $container->bills->sum('bags') : '' }} BAGS<br/>
                                @endforeach
                            @endif
                        </strong>
                    </td>
                    <td width="30%" class="f12 border-bottom border-left" valign="top">
                        <strong class="upper">
                            {!! ($data->container_stuffing ? $data->container_stuffing->qtd_container . ' X ' . $data->container_stuffing->container_type . '<br/>' : '') !!}
                            {!! ($data->items && $data->items[0] ? $data->items[0]->description . ($data->items[0]->crop_year ? ', CROP ' . $data->items[0]->crop_year : '') . '<br/>' : '') !!}
                            {!! ($data->shipping->first() && $data->shipping->first()->containers ? $data->shipping->first()->containers->sum(function ($container) {
                                return ($container->bills ? $container->bills->sum('bags') : 0);
                            }) . ' BAGS (' . $data->full_packing . ')<br/>' : '')  !!}
                            <br/>
                            {!! ($data->mapa ? 'DU-E NO. ' . $data->mapa->due_code . '<br/>' : '') !!}
                            {!! ($data->mapa ? 'LPCO ' . $data->mapa->lpco_key . '<br/>' : '') !!}
                            {!! ($data->mapa ? 'RUC ' . $data->mapa->ruc_code . '<br/>' : '') !!}
                            <br/>
                            {!! ($data->shipping->first() && $data->shipping->first()->containers ? 'GROSS WEIGHT TOTAL: ' . number_format($data->shipping->first()->containers->sum(function($container) use ($data) {
                                return $container->bills->sum(function($bill) use ($data) {
                                    $weight = floatval($bill->weight);
                                        $packing = floatval($data->packing);
                                        $tara_bags = floatval($bill->vehicle->tara_bags);

                                        return round($weight + $weight / $packing * $tara_bags, 2);
                                    });
                                }), 3, '.', ',')  . ' KGS<br/>' : '')  !!}
                            {!! ($data->shipping->first() && $data->shipping->first()->containers ? 'NET WEIGHT TOTAL: ' . number_format($data->shipping->first()->containers->sum(function($container) use ($data) {
                                return $container->bills->sum('weight');
                                }), 3, '.', ',')  . ' KGS<br/>' : '')  !!}                                
                            <br/>
                            PO {{ $data->number }}<br/>
                            {!! ($data->hs_code ? 'NCM/HS CODE ' . $data->hs_code . '<br/>' : '') !!}
                            <br/>
                            CLEAN ON BOARD<br/>
                            SHIPPED ON BOARD<br/>
                            {!! ($data->shipping->first() ? 'FREIGHT ' . $data->shipping->first()->freight . '<br/>' : '') !!}
                            <br/>
                            {!! ($data->shipping->first() ? (int)$data->shipping->first()->free_time_destination . ' DAYS FREE DETENTION AT DESTINATION PORT<br/>' : '') !!}
                            <br/>
                            NOTE: EXPORTER'S INSURANCE POLICY RESTRICTION FOR LOADING CONTAINERS ON VESSEL DECK.<br/>
                            <br/>
                        </strong>
                    </td>
                    <td width="15%" class="f12 border-bottom border-left" align="center" valign="top">
                        <strong class="upper">
                            @if ($data->shipping->first() && $data->shipping->first()->containers)
                                @foreach ($data->shipping->first()->containers as $container)
                                    {{ $container->bills ? number_format($container->bills->sum(function($bill) use ($data) {
                                        $weight = floatval($bill->weight);
                                        $packing = floatval($data->packing);
                                        $tara_bags = floatval($bill->vehicle->tara_bags);

                                        return round($weight + $weight / $packing * $tara_bags, 2);
                                    }), 3, '.', ',') : '' }} KG<br/>
                                @endforeach
                            @endif
                        </strong>
                    </td>
                    <td width="20%" class="f12 border-bottom border-left" align="center" valign="top">
                        <strong class="upper">
                            @if ($data->shipping->first() && $data->shipping->first()->containers)
                                @foreach ($data->shipping->first()->containers as $container)
                                    {{ number_format($container->cbm, '2', '.', ',') }} CBM<br/>
                                @endforeach
                            @endif
                        </strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="2" class="border" valign="top">
            <table width="100%">
                <tr>
                    <td width="35%" valign="top" class="f12">
                        Total number of Container or other packages<br/>
                        or units received by the Carrier (in words)
                    </td>
                    <td width="30%" valign="top" align="center">
                        <strong class="f12 upper">
                            {!! ($data->container_stuffing ? $data->container_stuffing->qtd_container . 'X' . $data->container_stuffing->container_type . '<br/>' : '') !!}/{{ ($data->shipping->first() && $data->shipping->first()->containers ? $data->shipping->first()->containers->sum(function ($container) {
                                return ($container->bills ? $container->bills->sum('bags') : 0);
                            }) . ' BAGS' : '')  }}
                        </strong>
                    </td>
                    <td width="35%">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="2" valign="top">
            <table width="100%">
                <tr>
                    <td width="50%" valign="top" class="f12 border-strong pmin">
                        <strong>FREIGHT AND CHARGES</strong>
                    </td>
                    <td width="15%" valign="top" class="f12 border-strong pmin">
                        <strong>PREPAID</strong>
                    </td>
                    <td rowspan="3" width="35%" class="f8 border">
                        RECEIVED by Carrier the Goods as specified above or apparent good order and condition unless otherwise stated, to be transported to such place as agreed, authorized or permitted herein and subject to all the terms and conditions appearing accepting this Bill of Lading.<br/>
                        any local privileges and customes notwithstanding.<br/>
                        <br/>
                        The particulars given above as stated by shipper and the weight, measure, quantity, condition, contents and value of the Goods are unknown to the Carrier.<br/>
                        <br/>
                        In WITNESS where of one(1) original Bill of Lading has been signed if not otherwise stated above, the same being accomplished the other(s), if any, to be void, if required by the Carrier one(1) original Bill of Lading must be surrendered duly endorsed in exchange of the Goods or delivery order. 
                    </td>
                </tr>

                <tr>
                    <td width="50%" class="f12 border">
                        <br/>
                        <strong>NÃO PREENCHER</strong>
                        <br/><br/>
                    </td>
                    <td width="15%" valign="top" class="f12 border">
                    </td>
                </tr>

                <tr>
                    <td width="50%" align="right" class="f12 border p1">
                        <strong>TOTAL: </strong>
                    </td>
                    <td width="15%" valign="top" class="f12 border">
                    </td>
                </tr>

                <tr>
                    <td colspan="2" width="65%" class="f12 border pmin" valign="top">
                        Freight payable at
                    </td>
                    <td width="35%" class="f12 border pmin" valign="top">
                        Number of original B/L(s)
                    </td>
                </tr>

                <tr>
                    <td colspan="2" width="65%" class="f12 border pmin" valign="top">
                        <strong>
                            LADEN ON BOARD THE VESSEL<br/>
                            Date
                        </strong>

                    </td>
                    <td width="35%" class="f12 border pmin" valign="top">
                        Place and Date of Issue
                    </td>
                </tr>

                <tr>
                    <td colspan="2" width="65%" class="f24 pmin" align="right" valign="top">
                        <strong class="p5 pr">
                            - DRAFT -
                        </strong>

                    </td>
                    <td width="35%" class="f12 border pmin" valign="top">
                        Signed on behalf of the Carrier
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="page-break"></div>

<table width="100%" cellpadding="0">
    <tr>
        <th class="highlight border">
            CONTAINER NO.
        </th>
        <th class="highlight border">
            CARRIER SEAL NO.
        </th>
        <th class="highlight border">
            OTHER SEAL NO.
        </th>
        <th class="highlight border">
            PACKING
        </th>
        <th class="highlight border">
            NO. OF ITEMS
        </th>
        <th class="highlight border">
            GROSS WEIGHT
        </th>
        <th class="highlight border">
            CBM
        </th>
        <th class="highlight border">
            NET WEIGHT
        </th>
        <th class="highlight border">
            TARE
        </th>
        <th class="highlight border">
            COMMENTS
        </th>
    </tr>

    @if ($data->shipping->first())
        @foreach ($data->shipping->first()->containers as $container)
            <tr>
                <td class="upper border" align="center">{{ $container->name }}</td>
                <td class="upper border" align="center">{{ $container->seal }}</td>
                <td class="upper border" align="center">N/D</td>
                <td class="upper border" align="center">BAGS</td>
                <td class="upper border" align="center">{{ $container->bills ? $container->bills->sum('bags') : '' }}</td>
                <td class="upper border" align="center">{{ $container->bills ? number_format($container->bills->sum(function ($bill) {
                    return $bill->weight + $bill->vehicle->tara_bags * $bill->bags;
                }), 3, '.', ',') : '' }}</td>
                <td class="upper border" align="center">{{ number_format($container->cbm, 2, '.', ',') }} CBM</td>
                <td class="upper border" align="center">{{ $container->bills ? number_format($container->bills->sum('weight'), 3, '.', ',') : '' }}</td>
                <td class="upper border" align="center">{{ $container->tare }}</td>
                <td class="upper border" align="center">{{ $data->number }}</td>
            </tr>
        @endforeach
    @endif
</table>
