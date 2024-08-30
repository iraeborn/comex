@include("documents.header")

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        @if($data['status'] == 'first' || !$data['status'])
            <th style="font-size: 10px; padding-left: 2px">PO</th>
            <th style="font-size: 10px; padding-left: 2px">BOOKING</th>
            <th style="font-size: 10px; padding-left: 2px">LOADING LOCATION</th>
            <th style="font-size: 10px; padding-left: 2px">UNLOADING LOCATION</th>
            <th style="font-size: 10px; padding-left: 2px">PRODUCT</th>
            <th style="font-size: 10px; padding-left: 2px">QUANTITY KG</th>
            <th style="font-size: 10px; padding-left: 2px">BILL NUMBER</th>
            <th style="font-size: 10px; padding-left: 2px">DU-E</th>
            <th style="font-size: 10px; padding-left: 2px">ACCESS KEY</th>
        @endif

        @if($data['status'] == 'second')
            <th style="font-size: 10px; padding-left: 2px">PO</th>
            <th style="font-size: 10px; padding-left: 2px">BOOKING</th>
            <th style="font-size: 10px; padding-left: 2px">LOADING LOCATION</th>
            <th style="font-size: 10px; padding-left: 2px">UNLOADING LOCATION</th>
            <th style="font-size: 10px; padding-left: 2px">UNLOADING DATE</th>
            <th style="font-size: 10px; padding-left: 2px">PRODUCT</th>
            <th style="font-size: 10px; padding-left: 2px">WEIGHT</th>
            <th style="font-size: 10px; padding-left: 2px">CARRIER</th>
            <th style="font-size: 10px; padding-left: 2px">DRIVER</th>
            <th style="font-size: 10px; padding-left: 2px">LICENSE PLATE</th>
            <th style="font-size: 10px; padding-left: 2px">PHONE NUMBER</th>
        @endif

        @if($data['status'] == 'third')
            <th style="font-size: 10px; padding-left: 2px">PO</th>
            <th style="font-size: 10px; padding-left: 2px">IMPORTER</th>
            <th style="font-size: 10px; padding-left: 2px">QUANTITY KG</th>
            <th style="font-size: 10px; padding-left: 2px">NFE</th>
            <th style="font-size: 10px; padding-left: 2px">PTAX DATE</th>
            <th style="font-size: 10px; padding-left: 2px">DU-E</th>
            <th style="font-size: 10px; padding-left: 2px">ACCESS KEY</th>
            <th style="font-size: 10px; padding-left: 2px">VALUE (R$)</th>
            <th style="font-size: 10px; padding-left: 2px">VALUE (US$)</th>
            <th style="font-size: 10px; padding-left: 2px">ETD</th>
        @endif
    </tr>

    @if ($data['status'] != 'second')
        @foreach($data['orders'] as $order)
            <tr>
                @if ($data['status'] == 'first' || !$data['status'])
                    <td style="font-size: 10px; padding-left: 2px">{{ $order->number }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->shipping()->first() ? $order->shipping()->first()->booking : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->loadings ? $order->loadings->loading_location : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->loadings ? $order->loadings->unloading_location : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ (count($order->items) ? $order->items[0]->description : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ (count($order->items) ? number_format($order->items[0]->net_weight, 2, '.', ',') : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->mapa ? $order->mapa->bill_of_lading : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->mapa ? $order->mapa->due_code : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->mapa ? $order->mapa->access_key : '') }}</td>
                @endif

                @if ($data['status'] == 'third')
                    <td style="font-size: 10px; padding-left: 2px">{{ $order->number }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->owner ? $order->owner->name : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ (count($order->items) ? number_format($order->items[0]->net_weight, 2, '.', ',') : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->mapa ? $order->mapa->nfe_key : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->loadings ? (new Carbon\Carbon($order->loadings->date_ptax))->format('m/d/Y') : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->mapa ? $order->mapa->due_code : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->mapa ? $order->mapa->access_key : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ (count($order->items) ? number_format($order->items[0]->total_price * ($order->loadings && $order->loadings->tax_ptax ? $order->loadings->tax_ptax : 1), 2, '.', ',') : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ (count($order->items) ? number_format($order->items[0]->total_price, 2, '.', ',') : '') }}</td>
                    <td style="font-size: 10px; padding-left: 2px">{{ ($order->shipping()->first() ? (new Carbon\Carbon($order->shipping()->first()->etd))->format('m/d/Y') : '') }}</td>
                @endif
            </tr>                                    
        @endforeach
    @endif

    @if ($data['status'] == 'second')
        @foreach ($data['orders'] as $order)
            @if ($order->loadings && $order->loadings->vehicles && count($order->loadings->vehicles))
                @foreach ($order->loadings->vehicles as $truck)
                    <tr>
                        <td style="font-size: 10px; padding-left: 2px">{{ $order->number }}</td>
                        <td style="font-size: 10px; padding-left: 2px">{{ ($order->shipping()->first() ? $order->shipping()->first()->booking : '') }}</td>
                        <td style="font-size: 10px; padding-left: 2px">{{ ($truck->note ? $truck->note : $order->loadings->loading_location) }}</td>
                        <td style="font-size: 10px; padding-left: 2px">{{ ($order->loadings ? $order->loadings->unloading_location : '') }}</td>
                        <td style="font-size: 10px; padding-left: 2px">{{ (new Carbon\Carbon($truck->truck_unloading_datetime))->format('m/d/Y H:i:s') }}</td>
                        <td style="font-size: 10px; padding-left: 2px">{{ (count($order->items) ? $order->items[0]->description : '') }}</td>
                        <td style="font-size: 10px; padding-left: 2px">{{ number_format($truck->wheight, 2, '.', ',') }}</td>
                        <td style="font-size: 10px; padding-left: 2px">{{ ($truck->carriers ? $truck->carriers->name : '') }}</td>
                        <td style="font-size: 10px; padding-left: 2px">{{ ($truck->driver ? $truck->driver : ($truck->drivers ? $truck->drivers->name : '')) }}</td>
                        <td style="font-size: 10px; padding-left: 2px">{{ $truck->plate }}</td>
                        <td style="font-size: 10px; padding-left: 2px">{{ ($truck->drivers ? $truck->drivers->phone : '') }}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    @endif

    @if (count($data['orders']))
        <tr>
            @if ($data['status'] == 'first' || !$data['status'])
                <th colspan="5" style="font-size: 10px; padding-left: 2px"> Total </th>
                <th style="font-size: 10px; padding-left: 2px">
                    {{ 
                        number_format($data['orders']->sum(function ($order) {
                            return intval(count($order->items) ? $order->items[0]->net_weight : 0); 
                        }), 2, '.', ',') 
                    }} 
                </th>
                <th colspan="3"> &nbsp; </th>
            @endif
            @if ($data['status'] == 'second')
                <th colspan="6" style="font-size: 10px; padding-left: 2px"> Total </th>
                <th style="font-size: 10px; padding-left: 2px">
                    {{ 
                        number_format($data['orders']->sum(function ($order) {
                            return intval($order->loadings && $order->loadings->vehicles && count($order->loadings->vehicles) ? $order->loadings->vehicles->sum(function ($truck) {
                                    return intval($truck->wheight);
                            }) : 0); 
                        }), 2, '.', ',') 
                    }} 
                </th>
                <th colspan="4"> &nbsp; </th>
            @endif
            @if ($data['status'] == 'third')
                <th colspan="2" style="font-size: 10px; padding-left: 2px"> Total </th>
                <th style="font-size: 10px; padding-left: 2px">
                    {{ 
                        number_format($data['orders']->sum(function ($order) { 
                            return intval(count($order->items) ? $order->items[0]->net_weight : 0); 
                        }), 2, '.', ',') 
                    }} 
                </th>
                <th colspan="4" style="font-size: 10px; padding-left: 2px">&nbsp;</th>
                <th style="font-size: 10px; padding-left: 2px">
                    {{ 
                        number_format($data['orders']->sum(function ($order) { 
                            return intval((count($order->items) ? $order->items[0]->total_price : 0) * ($order->loadings && $order->loadings->tax_ptax ? $order->loadings->tax_ptax : 1)); 
                        }), 2, '.', ',') 
                    }}
                </th>
                <th style="font-size: 10px; padding-left: 2px">
                    {{ 
                        number_format($data['orders']->sum(function ($order) { 
                            return intval(count($order->items) ? $order->items[0]->total_price : 0); 
                        }), 2, '.', ',') 
                    }}
                </th>
                <th style="font-size: 10px; padding-left: 2px"> &nbsp; </th>
            @endif
        </tr>
    @endif
</table>
