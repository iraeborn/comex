<style>
    body {
        font-family: sans-serif;
        font-size: 11px;
    }

    .border {
        border: 1px solid black;
    }

    table {
        border-collapse: collapse;
    }

    .italic {
        font-style: italic;
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

    .f10 {
        font-size: 10px;
    }

    .upper {
        text-transform: uppercase;
    }

    .underline {
        text-decoration: underline;
    }
</style>

<table width="100%" cellpadding="0">
    <tr>
        <td class="border p4 px" colspan="2">
            <table width="100%" cellpadding="0">
                <tr>
                    <td width="15%" rowspan="2" class="p2 pt">
                        @if(isset($data->exporter) && $data->exporter->profile_picture)
                            <img width="90px" src="{{$data->exporter->profile_picture}}" alt="">
                        @else
                            <img src="@include('documents.logo')" alt="AgricolaLucas" width="90px">
                        @endif
                    </td>
                    <td class="p5 pt">
                        @if(isset($data->exporter))
                            <span class="p3 pl">{{ strtoupper($data->exporter->name) }}</span><br />
                        @endif
                    </td>
                </tr>

                <tr>
                    <td align="center" class="p3 pt">
                        <h2 class="italic mt">FUMIGATION DECLARATION - ORIGINAL</h2>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" align="center" class="p1 pb">
                        <strong class="f10">NO. {{ $data->number }}</strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="border p1" colspan="2">
            <table>
                <tr>
                    <td colspan="2" align="center">
                        <strong style="font-size: 7px">We hereby certify that the mentioned goods below, installations and accommodations are treated according to the international regulations. We assure that all cargo has been well fumigated before loading.</strong>
                    </td>
                </tr>

                <tr>
                    <td class="p3 pt">&nbsp;</td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>OCEAN VESSEL:</strong>
                    </td>
                    <td class="f10 upper">
                        {{ ($data->shipping->first() ? $data->shipping->first()->vessel : '') }}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>SHIPPER:</strong>
                    </td>
                    <td class="f10 upper">
                        @if(isset($data->exporter))
                            {{ strtoupper($data->exporter->name) }}<br />
                            {!! $data->exporter->addressString() !!}
                            {!! $data->exporter->regString() !!}
                            {!! $data->exporter->contactString() !!}
                        @endif
                    </td>
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>DESCRIPTION OF GOODS:</strong>
                    </td>
                    <td class="f10 upper">
                        {{ ($data->items->first() ? $data->items->first()->description : '') }}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>TOTAL BAGS:</strong>
                    </td>
                    <td class="f10 upper">
                        {{ ($data->bills ? $data->bills->sum('bags') : '0') }} BAGS ({{ $data->full_packing }})
                    </td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>NET WEIGHT:</strong>
                    </td>
                    <td class="f10 upper">
                        {{
                            $data->loadings && $data->loadings->vehicles && is_numeric($data->loadings->vehicles->sum('wheight'))
                                ? number_format($data->loadings->vehicles->sum('wheight'), 3, ',', '.') . ' KGS'
                                : ''
                        }}
                    </td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>GROSS WEIGHT:</strong>
                    </td>
                    <td class="f10 upper">
                        {{
                            $data->loadings && $data->loadings->vehicles
                                ? number_format($data->loadings->vehicles->sum(function ($vehicle) use ($data) {
                                    return is_numeric($vehicle->wheight) && is_numeric($data->packing) && is_numeric($vehicle->tara_bags)
                                        ? round($vehicle->wheight + $vehicle->wheight / $data->packing * $vehicle->tara_bags, 2)
                                        : 0;
                                }), 3, ',', '.') . ' KGS'
                                : ''
                        }}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>PORT OF LOADING:</strong>
                    </td>
                    <td class="f10 upper">
                        {{ $data->port_origin }}
                    </td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>PORT OF DESTINY:</strong>
                    </td>
                    <td class="f10 upper">
                        {{ $data->port_destiny }}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>CONSIGNEE:</strong>
                    </td>
                    <td class="f10 upper">
                        {{ $data->consignee->name }} <br />
                        {!! $data->consignee->addressString() !!}
                        {!! $data->consignee->regString(true) !!}
                        {!! $data->consignee->contactString(true) !!}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>NOTIFY:</strong>
                    </td>
                    <td class="f10 upper">
                        {{ $data->notify->name }} <br />
                        {!! $data->notify->addressString() !!}
                        {!! $data->notify->regString(true) !!}
                        {!! $data->notify->contactString(true) !!}
                    </td>
                </tr>

                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>

                <tr>
                    <td width="32%" class="p4 pl f10" valign="top">
                        <strong>BL / CONTAINERS:</strong>
                    </td>
                    <td class="f10 upper">
                        {{ ($data->mapa ? $data->mapa->bill_of_lading : '-') }} /
                        @if ($data->shipping->first() && $data->shipping->first()->containers)
                            @php $separator = ''; @endphp
                            @foreach ($data->shipping->first()->containers as $container)
                                {{ $separator . strtoupper($container->container) }} <br />
                                @php $separator = ', '; @endphp
                            @endforeach
                        @else
                            {{ '-' }}
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
