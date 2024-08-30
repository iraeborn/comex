@include('documents._header')
<div>
    <p style="text-align: center;  font-weight: bold;">
        <span style="margin-right: 40px; font-size: 10px">PROFORMA INVOICE Nº</span>
        <span>{{$data['number']}}</span>
    </p>
</div>

    <table width="100%" class="pb5">
        <tr valign="top">
            <th class="p5 border">
                EXPORTER/SHIPPER
            </th>
            <th class="p5 border">
                IMPORTER
            </th>
            <th class="p5 border">
                NOTIFY
            </th>
        </tr>
        <tr valign="top">
            <td class="p5 border">
                @if(isset($data['exporter']))
                {{strtoupper($data['exporter']['name'])}}<br />
                {!! $data['exporter']->addressString() !!}
                {!! $data['exporter']->regString() !!}
                {!! $data['exporter']->contactString() !!}
                @endif
            </td>
            <td class="p5 border">
                {{strtoupper($data['buyer']['name'])}}<br />
                {!! $data['buyer']->addressString() !!}
                {!! $data['buyer']->regString() !!}
                {!! $data['buyer']->contactString() !!}

            </td>
            <td class="p5 border">
                {{strtoupper($data['notify']['name'])}}<br />
                {!! $data['notify']->addressString() !!}
                {!! $data['notify']->regString() !!}
                {!! $data['notify']->contactString() !!}

                @foreach($data['phones'] as $phone)
                {{$phone}}
                @endforeach
                <div>
                    <hr />
                    <b>CONSIGNEE</b>
                    <hr /><br />
                    <p>

                        {{strtoupper($data['consignee']['name'])}}<br />
                        {!! $data['consignee']->addressString() !!}
                        {!! $data['consignee']->regString() !!}
                        {!! $data['consignee']->contactString() !!}
                    </p>
                </div>
            </td>
        </tr>
    </table>

<table width="100%">
    <tr style="background-color: #C0C0C0;">
        <th class="p5 border">PAYMENT CONDITIONS:</th>
        <td colspan="2" class="p5 border">{{$data['payment_conditions']}}</td>
    </tr>
    <tr valign="top">
        <th width="34%" class="p5 border">BANK DATA:</th>
        <td colspan="2" class="p5 border">
            <b>INTERMEDIARY BANK: {{$data['bank']['intermediary_name']}}</b><br />
            @if($data['bank']['intermediary_company']) Company: {{$data['bank']['intermediary_company']}}<br />@endif
            @if($data['bank']['intermediary_iban']) Iban: {{$data['bank']['intermediary_iban']}}<br />@endif
            @if($data['bank']['intermediary_swift']) Swift: {{$data['bank']['intermediary_swift']}}<br />@endif
            @if($data['bank']['intermediary_branch']) Branch: {{$data['bank']['intermediary_branch']}}<br />@endif
            @if($data['bank']['intermediary_agency']) Agency: {{$data['bank']['intermediary_agency']}}<br />@endif
            @if($data['bank']['intermediary_account']) Account: {{$data['bank']['intermediary_account']}}<br />@endif
            @if($data['bank']['intermediary_clearing']) Clearing: {{$data['bank']['intermediary_clearing']}}<br />@endif
            @if($data['bank']['intermediary_chips']) Chips: {{$data['bank']['intermediary_chips']}}<br />@endif

            <b>BENEFICIARY BANK: {{$data['bank']['beneficiary_name']}}</b><br />
            @if($data['bank']['beneficiary_company']) Company: {{$data['bank']['beneficiary_company']}}<br />@endif
            @if($data['bank']['beneficiary_iban']) Iban: {{$data['bank']['beneficiary_iban']}}<br />@endif
            @if($data['bank']['beneficiary_swift']) Swift: {{$data['bank']['beneficiary_swift']}}<br />@endif
            @if($data['bank']['beneficiary_branch']) Branch: {{$data['bank']['beneficiary_branch']}}<br />@endif
            @if($data['bank']['beneficiary_agency']) Agency: {{$data['bank']['beneficiary_agency']}}<br />@endif
            @if($data['bank']['beneficiary_account']) Account: {{$data['bank']['beneficiary_account']}}<br />@endif
            @if($data['bank']['beneficiary_clearing']) Clearing: {{$data['bank']['beneficiary_clearing']}}<br />@endif
            @if($data['bank']['beneficiary_chips']) Chips: {{$data['bank']['beneficiary_chips']}}<br />@endif

            *all banker commission expenses will be the importer's responsibilities
        </td>
    </tr>
</table>

<table width="100%" class="mt5" style="text-align: left;">
    <tr>
        <th style="background-color: #C0C0C0;" colspan="3">
            CONDITIONS:
        </th>
    </tr>

    <tr>
        <th>
            <table width="100%">
                <tr>
                    <th class="p5 border tal">INCOTERM:</th>
                    <td class="p5 border tal">{{$data['incoterm']}}</td>
                </tr>
                {{-- <tr>
                    <th class="p5 border tal">HS Code:</th>
                    <td class="p5 border tal">{{$data['hs_code']}}</td>
                </tr> --}}
                <tr>
                    <th class="p5 border tal">PACKING:</th>
                    <td class="p5 border tal">{{$data['full_packing']}}</td>
                </tr>
                <tr>
                    <th class="p5 border tal">PORT OF ORIGIN:</th>
                    <td class="p5 border tal">{{$data['port_origin']}}</td>
                </tr>
                <tr>
                    <th class="p5 border tal">PORT OF DISCHARGE:</th>
                    <td class="p5 border tal">{{$data['port_destiny']}}</td>
                </tr>
            </table>
        </th>
        <th></th>
        <th>
            <table width="100%" class="tal">
                <tr>
                    <th class="p5 border tal">SHIPMENT:</th>
                    <td class="p5 border tal">
                        @if(isset($data['shipment']))
                        {{$data['shipment']}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="p5 border tal">TRANSPORTATION:</th>
                    <td class="p5 border tal">
                        {{$data['transportion']}}
                    </td>
                </tr>
                <tr>
                    <th class="p5 border tal">CONTAINERS TYPE</th>
                    <td class="p5 border tal">{!! $data['container_type'] !!}</td>
                </tr>
                <tr>
                    <th class="p5 border tal">QUANTITY CONTAINERS</th>
                    <td class="p5 border tal">{!! $data['qtdTotalContainers'] !!}</td>
                </tr>
                <tr>
                    <th class="p5 border tal"></th>
                    <td class="p5 border tal"></td>
                </tr>
            </table>
        </th>
    </tr>
</table>

<table cellspacing="0" width="100%" cellpadding="1" style="margin-top: 20px">
    <tr>
        <th align="left" width="10%">
            QUANTITY
        </th>
        <th align="left" width="10%">
            QTY. CONTAINER
        </th>
        <th width="10%" align="left">
            CROP
        </th>
        <th width="40%" align="left">
            DESCRIPTION OF GOODS
        </th>
        <th align="left">
            HS CODE
        </th>
        <th align="left">
            UNIT PRICE US$ / TON
        </th>
        <th align="left">
            TOTAL PRICE US$
        </th>
    </tr>
    @foreach($data['items'] as $item)
    <tr>
        <td align="left">
            @if(isset($item['quantity']))
                {{ $item['quantity'] }} TON
            @endif
        </td>
        <td align="left">
            @if(isset($item['container']))
                {{ $item['container'] }}
            @endif
        </td>
        <td align="left">
            @if(isset($item['crop_year']))
                {{ $item['crop_year'] }}
            @endif
        </td>
        <td width="10%" align="left">
            @if(isset($item['description']))
                {{ $item['description'] }}
            @endif
        </td>
        <td align="left">
            @if(isset($item['hs_code']))
                {{ $item['hs_code'] }}
            @endif
        </td>
        <td align="left">
            @if(isset($item['unit_price']))
                $ {{ number_format($item['unit_price'],2,",",".") }}
            @endif
        </td>
        <td align="left">
            @if(isset($item['total_price']))
            $ {{ number_format($item['total_price'],2,",",".") }}
            @endif
        </td>
    </tr>
    @endforeach
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <th align="left">
            ADVANCE
        </th>

        <td align="left">
            $ {{number_format($data['totalAdvancePayment'],2,",",".")}}
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <th align="left">
            BALANCE
        </th>
        <td align="left">
            $ {{number_format($data['totalPriceItems']-$data['totalAdvancePayment'],2,",",".")}}
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <th align="left">
            TOTAL
        </th>
        <td align="left">
            $ {{number_format($data['totalPriceItems'],2,",",".")}}
        </td>
    </tr>
    <tr>
        <th th colspan="7" style="border-top: 1px solid black; border-bottom: 1px solid black">
            VALUE:
            @foreach($data['items'] as $item)
            {{$item['value']}},
            @endforeach
        </th>
    </tr>
</table>

<table cellspacing="0" cellpadding="0" style="margin-top: 20px; margin-bottom: 20%" align="left">
    <tr>
        <th valign="top">
            SPECIFICATIONS:
        </th>
        <td>
            @foreach($data['specificationsLst'] as $specification)
            {!! $specification !!}<br />
            @endforeach
        </td>
    </tr>
</table>
<br /><br />

<div class="signature">
    <table width="100%" border="0">
        <tr>
            <th align="left" valign="bottom">
                @if(isset($data['signature']['content']))
                <table width="100%" border="0">>
                    <tr>
                        <td>
                            <img width="280" src="data:image/png;base64, {{$data['signature']['content']}}">
                        </td>
                    </tr>
                    <tr>
                        <td style="border-bottom:solid 1px"></td>
                    </tr>
                </table>
                @else
                <table width="100%">
                    <tr>
                        <td style="border-bottom:solid 1px"></td>
                    </tr>
                </table>
                @endif
            </th>
            <th align="left" valign="bottom">
                <table width="100%" border="0">
                    <tr>
                        <td style="border-bottom:solid 1px"></td>
                    </tr>
                </table>
            </th>
        </tr>
        <tr>
            <th width="50%" valign="top" align="left">
                @if(isset($data['signature']['content']))
                {{$data['signature']['text']}}<br>
                {{date('d/m/Y')}}
                @endif
            </th>
            <th align="left">{{strtoupper($data['buyer']['name'])}}.<br />DESCRIBE YOUR FULL NAME</th>
        </tr>
    </table>
</div>
</div>