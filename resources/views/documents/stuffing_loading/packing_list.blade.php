<style>
    body {
        font-family: sans-serif;
        font-size: 9px
    }

    table {
        border-collapse: collapse;
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

    .document-title {
        border: 0;
        font-size: 2rem;
        text-align: center;
    }

    .border {
        border: 1px solid black;
    }

    .border-bottom {
        border-bottom: 1px solid black;
    }
    .item-align-left {
        text-align: left;
    }
    .highlight {
        font-weight: bold;
        background-color: lightgrey;
    }

</style>

<table width="100%" cellpadding="0">

<tr>
        <td colspan="3" width="30%">
            
            @if(isset($data->exporter) && $data->exporter->profile_picture)
                <img width="150px" src="{{$data->exporter->profile_picture}}" alt="">
            @else 
                <img height="150" src="@include('documents.logo')" alt="">
            @endif
        </td>
        <td colspan="2" width="0%">
            <p style="font-weight: bold">
                @if(isset($data->exporter))
                    {{strtoupper($data->exporter->name)}}<br />
                    {!! $data->exporter->addressString() !!}
                    {!! $data->exporter->regString() !!}
                    {!! $data->exporter->contactString() !!}
                @endif
            </p>
        </td>
        <td align="right" style="vertical-align: bottom"> DATE: {{ ($data->shipping->first() && $data->shipping->first()->draft_deadline ? date("m/d/Y", strtotime($data->shipping->first()->draft_deadline)) : '') }} </td>
    </tr>

    <tr>
        <td colspan="2" width="30%">
        </td>
        <td colspan="2" width="40%">
        </td>
        <td colspan="2" width="30%">
        <p class="header-secondary-text">
                CONTRACT, {{ $data->number }}
            </p>
        </td>
    </tr>

    <tr> <td colspan="6" style="height: 2rem"></td></tr>

    <tr class="row-separator">
        <th colspan="6" class="document-title">
            PACKING LIST
        </th>
    </tr>

    <tr> <td colspan="6" style="height: 2rem"></td></tr>
    <tr> <td colspan="6" style="height: 2rem"></td></tr>

    @include("documents.stuffing_loading._exportation_info")

    <tr>
        <th class="border-bottom item-align-left" style="width:20%">INCOTERM:</th>
        <td colspan="5"> {{ $data->incoterm}} </td>
    </tr>
    <tr>
        <th class="border-bottom item-align-left">HS:</th>
        <td colspan="5"> {{ $data->hs_code}} </td>
    </tr>
    <tr>
        <th class="border-bottom item-align-left">PACKING:</th>
        <td colspan="5"> {{ $data->full_packing }} </td>
    </tr>
    <tr>
        <th class="border-bottom item-align-left">PORT ORIGIN:</th>
        <td colspan="5"> {{ $data->port_origin }} </td>
    </tr>
    <tr>
        <th class="border-bottom item-align-left">PORT DESTINY:</th>
        <td colspan="5"> {{ $data->port_destiny }} </td>
    </tr>
    <tr>
        <th class="border-bottom item-align-left">DATE OF SHIPMENT:</th>
        <td colspan="5"> {{ ($data->shipping->first() && $data->shipping->first()->etd ? date("m/d/Y", strtotime($data->shipping->first()->etd)) : '') }} </td>
    </tr>
    <tr>
        <th class="border-bottom item-align-left">TRANSPORTATION:</th>
        <td colspan="5"> {{ $data->transportion }} </td>
    </tr>
    <tr>
        <th class="border-bottom item-align-left">VESSEL:</th>
        <td colspan="5"> {{ ($data->shipping->first() ? $data->shipping->first()->vessel : '') }} </td>
    </tr>
    <tr>
        <th class="border-bottom item-align-left">BOOKING:</th>
        <td colspan="5"> {{ ($data->shipping->first() ? $data->shipping->first()->booking : '') }} </td>
    </tr>
    <tr>
        <th class="border-bottom item-align-left">BL:</th>
        <td colspan="5"> {{ ($data->mapa ? $data->mapa->bill_of_lading : '') }} </td>
    </tr>

    <tr> <td></td> <td colspan="5" style="height: 2rem"></td></tr>
    <tr> <td colspan="6" style="height: 2rem"></td></tr>

    <tr>
        <th colspan="2" class="border"> CONTAINERS </th>
        <th class="border"> TARE </th>
        <th class="border"> SEAL </th>
        <th class="border"> WEIGHT </th>
        <th class="border"> BAGS </th>
    </tr>
    @if($data->shipping->first())
        @foreach($data->shipping->first()->containers as $container)
            <tr>
                <td colspan="2" class="border" align="center"> {{ $container->name }} </td>
                <td class="border" align="center"> {{ $container->tare }} </td>
                <td class="border" align="center"> {{ $container->seal }} </td>
                <td class="border" align="center"> {{ ($container->bills ? number_format($container->bills->sum('weight'), 2, '.', ',') : '') }} </td>
                <td class="border" align="center"> {{ ($container->bills ? number_format($container->bills->sum('bags'), 2, '.', ',') : '') }} </td>
            </tr>
        @endforeach
    @endif
    <tr>
        <td colspan="3"> </td>
        <td class="border highlight" align="center"> Total </td>
        <td class="border highlight" align="center"> {{ ($data->shipping->first() && $data->shipping->first()->containers ? number_format($data->shipping->first()->containers->sum(function ($container) {
                return $container->bills->sum('weight');
            }), 2, '.', ',') : '') }} </td>
        <td class="border highlight" align="center"> {{ ($data->shipping->first() && $data->shipping->first()->containers ? number_format($data->shipping->first()->containers->sum(function ($container) {
                return $container->bills->sum('bags');
            }), 2, '.', ',') : '') }} </td>
    </tr>

    <tr> <td colspan="6" style="height: 2rem"></td></tr>
    <tr> <td colspan="6" style="height: 2rem"></td></tr>

</table>
