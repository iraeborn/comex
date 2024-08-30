<style>
    body {
        font-family: sans-serif;
        font-size: 9px
    }

    .border {
        border: 1px solid black;
    }
    .border-bottom {
        border-bottom: 1px solid black;
    }

    table {
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 0px solid black;
        padding: 3px;
    }

    .section-title {
        background-color:#ffeb3bc2;
        color: black;
        text-align: center;
    }

    .multi-cols {
        text-align: left;
    }

    .row-separator {
        column-span: 6;
        height: 2rem;
    }

    .footer > td {
        font-weight: bold;
        text-align: right;
    }

    .page-break { 
        page-break-before: always; 
    }
</style>

<table width="100%">

    <tr>
        <td width="10%">
            @if(isset($data->exporter) && $data->exporter->profile_picture)
                <img width="100px" src="{{$data->exporter->profile_picture}}" alt="">
            @else 
                <img src="@include('documents.logo')" alt="AgricolaLucas" width="100px">
            @endif
        </td>

        <td width="40%">
            <p style="font-weight: bold">
                @if(isset($data->exporter))
                    {{strtoupper($data->exporter->name)}}<br />
                    {!! $data->exporter->addressString() !!}
                    {!! $data->exporter->regString() !!}
                    {!! $data->exporter->contactString() !!}
                @endif
            </p>
        </td>

    </tr>

</table>

<table width="100%">

    <tr>
        <td colspan="2" width="70%"></td>
        <td colspan="2" width="30%">
            <p class="header-secondary-text">
                DATE: {{ ($data->loadings && $data->loadings->date_ptax ? date("m/d/Y", strtotime($data->loadings->date_ptax)) : '') }}
                <br>
                COMMERCIAL INVOICE {{ $data->number }}
            </p>
        </td>
    </tr>

</table>

<table width="100%">

    @include("documents.stuffing_loading._exportation_info")

    <tr>
        <th class="border">FORM OF PAYMENT:</th>
        <td class="border" colspan="5"> {{ $data->payment_conditions }} </td>
    </tr>

    @if($data->bank['beneficiary_company'])
    <tr>
        <th class="border">COMPANY:</th>
        <td class="border" colspan="5">{{ $data->bank['beneficiary_company'] }}</td>
    </tr>
    @endif

    @if($data->bank['beneficiary_name'])
    <tr>
        <th class="border">NAME:</th>
        <td class="border" colspan="5">{{ $data->bank['beneficiary_name'] }}</td>
    </tr>
    @endif

    @if($data->bank['beneficiary_iban'])
    <tr>
        <th class="border">IBAN:</th>
        <td class="border" colspan="5">{{ $data->bank['beneficiary_iban'] }}</td>
    </tr>
    @endif

    @if($data->bank['beneficiary_swift'])
    <tr>
        <th class="border">SWIFT:</th>
        <td class="border" colspan="5">{{ $data->bank['beneficiary_swift'] }}</td>
    </tr>
    @endif

    @if($data->bank['beneficiary_branch'])
    <tr>
        <th class="border">BRANCH:</th>
        <td class="border" colspan="5">{{ $data->bank['beneficiary_branch'] }}</td>
    </tr>
    @endif

    @if($data->bank['beneficiary_agency'])
    <tr>
        <th class="border">AGENCY:</th>
        <td class="border" colspan="5">{{ $data->bank['beneficiary_agency'] }}</td>
    </tr>
    @endif

    @if($data->bank['beneficiary_account'])
    <tr>
        <th class="border">ACCOUNT:</th>
        <td class="border" colspan="5">{{ $data->bank['beneficiary_account'] }}</td>
    </tr>
    @endif

    @if($data->bank['beneficiary_clearing'])
    <tr>
        <th class="border">CLEARING:</th>
        <td class="border" colspan="5">{{ $data->bank['beneficiary_clearing'] }}</td>
    </tr>
    @endif

    @if($data->bank['beneficiary_chips'])
    <tr>
        <th class="border">CHIPS:</th>
        <td class="border" colspan="5">{{ $data->bank['beneficiary_chips'] }}</td>
    </tr>
    @endif

</table>

<table width="100%">

    <tr>
        <th class="border">INCOTERM:</th>
        <td class="border"> {{ $data->incoterm}} </td>

        <th class="border">PACKING:</th>
        <td class="border"> {{ $data->full_packing }} </td>

        <th class="border">PORT ORIGIN:</th>
        <td class="border"> {{ $data->port_origin }} </td>

        <th class="border">PORT DESTINY:</th>
        <td class="border"> {{ $data->port_destiny }} </td>
        
        <th class="border">DATE OF SHIPMENT:</th>
        <td class="border"> {{ ($data->shipping->first() && $data->shipping->first()->etd ? date("m/d/Y", strtotime($data->shipping->first()->etd)) : '') }} </td>
    </tr>

    <tr>
        <th class="border">TRANSPORTATION:</th>
        <td class="border"> {{ $data->transportion }} </td>

        <th class="border">VESSEL:</th>
        <td class="border"> {{ ($data->shipping->first() ? $data->shipping->first()->vessel : '') }} </td>

        <th class="border">BILL OF LADING:</th>
        <td class="border"> {{ ($data->mapa ? $data->mapa->bill_of_lading : '') }} </td>

        <th class="border">BOOKING:</th>
        <td class="border"> {{ ($data->shipping->first() ? $data->shipping->first()->booking : '') }} </td>

        <th class="border">VESSEL ARRIVAL:</th>
        <td class="border"> {{ ($data->shipping->first() && $data->shipping->first()->eta ? date("m/d/Y", strtotime($data->shipping->first()->eta)) : '') }} </td>
    </tr>

</table>
@if($data->mapa->observation)
<table width="100%">
    <tr>
        <td class="border" colspan="5"> OBSERVATION:{{ $data->mapa->observation }} </td>
    </tr>
</table>
@endif
<table width="100%">

    <tr>
        <th class="border">QUANTITY</th>
        <th class="border">TEU(s)</th>
        <th class="border">CROP</th>
        <th class="border">DESCRIPTION OF GOODS</th>
        <th class="border">UNIT PRICE US$ / TON</th>
        <th class="border">TOTAL PRICE US$</th>
    </tr>

    @php
        $items = $data->items ?? null;
        $advance = $data->transactions ? $data->transactions->where('transaction_type_id', 2)->sum('value') : 0;
    @endphp

    @if($items)
        @foreach($data->items as $item)
        <tr>
            <td class="border"> {{ ($data->loadings && $data->loadings->vehicles ? $data->loadings->vehicles->sum('wheight') / 1000 : '') }} </td>
            <td class="border"> {{ ($data->container_stuffing ? $data->container_stuffing->qtd_container : '') }} </td>
            <td class="border"> {{ $item->crop_year }} </td>
            <td class="border"> {{ $item->description }} </td>
            <td class="border" style="text-align:right"> {{ number_format($item->unit_price, 2, ',', '.') }} </td>
            <td class="border" style="text-align:right"> {{ ($data->loadings && $data->loadings->vehicles ? number_format($data->loadings->vehicles->sum('wheight') / 1000 * floatval($item->unit_price), 2, ',', '.') : '') }} </td>
        </tr>
        @endforeach

        <tr>
            <td colspan="4"></td>
            <td style="border-bottom:1px solid black"> ADVANCE </td>
            <td style="border-bottom:1px solid black; text-align:right"> {{ number_format($advance, 2, ',', '.') }} </td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td style="border-bottom:1px solid black"> BALANCE </td>
            <td style="border-bottom:1px solid black; text-align:right">
                {{ ($data->loadings && $data->loadings->vehicles ? number_format( ($data->loadings->vehicles->sum('wheight') / 1000 * floatval($item->unit_price)) - $advance, 2, ',', '.') : '') }}
            </td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <th style="border-bottom:1px solid black; text-align:left"> TOTAL </th>
            <td style="border-bottom:1px solid black; text-align:right">
                {{ ($data->loadings && $data->loadings->vehicles ? number_format($data->loadings->vehicles->sum('wheight') / 1000 * floatval($item->unit_price), 2, ',', '.') : '') }}
            </td>
        </tr>
    </table>

    @if($data->shipping->first() && $data->shipping->first()->containers && $data->shipping->first()->containers->count() > 8)
        <div class="page-break"></div>
    @endif

    <table width="100%">
        <tr>
            <th class="border" style="text-align: left"> TOTAL BAGS </th>
            <th class="border" style="text-align: left"> TOTAL NET WEIGHT (KG)</th>
            <th class="border" style="text-align: left"> TOTAL GROSS WEIGHT (KG) </th>
            <th class="border" style="text-align: left"> CONTAINERS </th>
            <th class="border" style="text-align: left"> TARE </th>
            <th class="border" style="text-align: left"> SEAL </th>
        </tr>
        @if ($data->shipping->first() && $data->shipping->first()->containers)
            @foreach($data->shipping->first()->containers as $container)
                <tr>
                    @if($loop->first)
                    <td style="border-bottom: 1px solid black" rowspan="{{$data->shipping->first()->containers->count()}}"> {{ $data->bills->sum('bags') }} </td>
                    <td style="border-bottom: 1px solid black" rowspan="{{$data->shipping->first()->containers->count()}}"> {{ $data->loadings->vehicles->sum('wheight') }} </td>
                    <td style="border-bottom: 1px solid black" rowspan="{{$data->shipping->first()->containers->count()}}"> {{  $data->loadings->vehicles->sum(function($vehicle) use ($data) {
                    return round(floatval($vehicle->wheight) + floatval($vehicle->wheight) / floatval($data->packing) * floatval($vehicle->tara_bags), 2);
                })  }} </td>
                    @endif
                    <td style="border-bottom: 1px solid black"> {{ $container->name }} </td>
                    <td style="border-bottom: 1px solid black"> {{ $container->tare }} </td>
                    <td style="border-bottom: 1px solid black"> {{ $container->seal }} </td>
                </tr>
            @endforeach
        @endif
    @endif

    <tr> <td colspan="6" style="height: 2rem"></td></tr>
    <tr> <td colspan="6" style="height: 2rem"></td></tr>

    <tr class="footer">
        <td colspan="6">
        <p> Commercial Invoice {{ $data->number }} </p>
        </td>
    </tr>
</table>
