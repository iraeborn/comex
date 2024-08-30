@include("documents.header")

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th style="font-size: 10px; padding-left: 2px">PO</th>
        <th style="font-size: 10px; padding-left: 2px">IMPORTER</th>
        <th style="font-size: 10px; padding-left: 2px">LOADING LOCATION</th>
        <th style="font-size: 10px; padding-left: 2px">START END TRUCK LOADING DATE</th>
        <th style="font-size: 10px; padding-left: 2px">END TRUCK LOADING DATE</th>
        <th style="font-size: 10px; padding-left: 2px">PACKING</th>
        <th style="font-size: 10px; padding-left: 2px">LABEL</th>
        <th style="font-size: 10px; padding-left: 2px">QUANTITY CONT.</th>
        <th style="font-size: 10px; padding-left: 2px">CONT. TYPE</th>
        <th style="font-size: 10px; padding-left: 2px">PRODUCT</th>
        <th style="font-size: 10px; padding-left: 2px">CROSS DOCKING TERMINAL</th>
        <th style="font-size: 10px; padding-left: 2px">STUFFING START DATE</th>
        <th style="font-size: 10px; padding-left: 2px">STUFFING END DATE</th>
        <th style="font-size: 10px; padding-left: 2px">TOTAL ORDER</th>
        <th style="font-size: 10px; padding-left: 2px">SHIPPED TOTAL</th>
        <th style="font-size: 10px; padding-left: 2px">BALANCE</th>
        <th style="font-size: 10px; padding-left: 2px">QUALITY OBSERVATIONS</th>
    </tr>
    
    @foreach($data as $order)
        <tr>
            <th style="font-size: 10px; padding-left: 2px">
                {{ $order->number }}
            </th>
            <td style="font-size: 10px; padding-left: 2px">
                {{ ($order->owner ? $order->owner->name : '') }}{{ ($order->owner && $order->owner->country ? ' - ' . $order->owner->country : '') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{ ($order->loadings ? $order->loadings->loading_location : '') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{ ($order->loadings ? (new Carbon\Carbon($order->loadings->start_truck_loading_date))->format('d/m/Y') : '') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{ ($order->loadings ? (new Carbon\Carbon($order->loadings->end_truck_loading_date))->format('d/m/Y') : '') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{ $order->full_packing }}
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{ (intval($order->label) == 1 ? 'Yes' : 'No') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px" align="center">
                {{ ($order->container_stuffing ? $order->container_stuffing->qtd_container : '') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px" >
                {{ ($order->container_stuffing ? $order->container_stuffing->container_type : '') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{ ($order->items[0] ? $order->items[0]->description : '') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{ ($order->container_stuffing ? $order->container_stuffing->terminal : '') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{ ($order->container_stuffing ? (new Carbon\Carbon($order->container_stuffing->stuffing_starting_date))->format('d/m/Y') : '') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{ ($order->container_stuffing ? (new Carbon\Carbon($order->container_stuffing->stuffing_ending_date))->format('d/m/Y') : '') }}
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{$order->container_stuffing->quantity_total}} KG
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{$order->total_shipped}} KG
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                {{$order->balance}} KG
            </td>
            <td style="font-size: 10px; padding-left: 2px">
                @foreach ($order->specifications as $spec)
                    {{ ($spec->specification ? $spec->specification->description : '')}}<br/>
                @endforeach
            </td>
        </tr>
    @endforeach

    <tr>
        <th colspan="7" style="font-size: 10px; padding-left: 2px"> Total </th>
        <th style="font-size: 10px; padding-left: 2px"> 
            {{ 
                $data->sum(function ($order) {
                    $container = ($order->items[0] ? $order->items[0]->container : 0);
                    return intval($container); 
                }) 
            }} 
        </th>
        <th colspan="5" style="font-size: 10px; padding-left: 2px"></th>
        <th style="font-size: 10px; padding-left: 2px"> {{ number_format($data->sum(function ($order) { return ($order->container_stuffing ? $order->container_stuffing->quantity_total : 0); }), 2, '.', ',') }} KG </th>
        <th style="font-size: 10px; padding-left: 2px"> {{ number_format($data->sum('total_shipped'), 2, '.', ',') }} KG </th>
        <th colspan="2" style="font-size: 10px; padding-left: 2px"> {{ number_format($data->sum('balance'), 2, '.', ',') }} KG </th>
    </tr>
</table>
