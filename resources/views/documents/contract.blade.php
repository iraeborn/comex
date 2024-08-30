@include('documents._header')
<div>
    <p style="text-align: center;  font-weight: bold;">
        <span style="margin-right: 40px; font-size: 10px">CONTRACT Nº</span>
        <span>{{$data['number']}}</span>
    </p>
</div>

<div class="seller">

    <table cellspacing="0" width="100%" cellpadding="2">
        <tr valign="top">
            <th width="20%" style="margin-top: -50px" align="left">
                SELLER:
            </th>
            <th align="left">
                @if(isset($data['exporter']))
                    {{strtoupper($data['exporter']['name'])}}<br />
                    {!! $data['exporter']->addressString() !!}
                    {!! $data['exporter']->regString() !!}
                    {!! $data['exporter']->contactString() !!}
                @endif
            </th>
            <th width="7%">
                BUYER:
            </th>
            <th align="left">
                {{$data['buyer']->name}} <br/>
                {!! $data['buyer']->addressString() !!}
                {!! $data['buyer']->regString() !!}
                {!! $data['buyer']->contactString() !!}
            </th>
        </tr>
    </table>

    <table cellspacing="0" width="100%" cellpadding="2">
        <tr valign="top">
            <th width="20%" style="margin-top: -50px" align="left">
                COMMODITY:
            </th>
            <th>
                <table cellspacing="0" width="100%" cellpadding="2">
                    <tr>
                        <td>ITEM</td>
                        <td>HS CODE</td>
                        <td>QUANTITY</td>
                        <td>UNIT PRICE</td>
                    </tr>
                    @foreach($data['items'] as $item)
                    <tr>
                        <td>
                            <span style="font-weight: normal">{{ strtoupper($item['description']) }}</span>
                        </td>
                        <td>
                            <span style="font-weight: normal">{{ $item['hs_code'] }}</span>
                        </td>
                        <td>
                            <span style="font-weight: normal">{!! $item['quantity'] !!} TON</span>
                        </td>
                        <td>
                            <span style="font-weight: normal">$ {!! number_format($item['unit_price'], 2, ',', ' ') !!} / TON</span>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </th>
        </tr>
    </table>

    <table cellspacing="0" width="100%" cellpadding="2">
        <tr valign="top">
            <th width="20%" style="margin-top: -50px" align="left">

            </th>
            <th>
                <table cellspacing="0" width="100%" cellpadding="2">
                    <tr>
                        <td>
                            INCOTERM:
                            <span style="font-weight: normal">{!! $data['incoterm'] !!}</span>
                        </td>
                        <td>
                            CONTAINERS TYPE:
                            <span style="font-weight: normal">{!! $data['container_type'] !!}</span>
                        </td>
                        <td>
                            QUANTITY CONTAINERS:
                            <span style="font-weight: normal">{!! $data['qtdTotalContainers'] !!}</span>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>

    <table cellspacing="0" width="100%" cellpadding="2">

        <tr valign="top">
            <td width="20%" style="font-weight: bold">PACKING:</td>
            @if(isset($data['full_packing']))
                <td>{{$data['full_packing']}}</td>
            @endif
        </tr>
        
        <tr valign="top">
            <td style="font-weight: bold">PORT OF DISCHARGE:</td>
            @if(isset($data['port_destiny']))
                <td>{{$data['port_destiny']}}</td>
            @endif
        </tr>

        <tr>
            <td style="font-weight: bold">SHIPMENT:</td>
            <td>
                @if(isset($data['shipment']))
                    {{$data['shipment']}}
                @endif
            </td>
        </tr>

        <tr valign="top">
            <td style="font-weight: bold">PAYMENT CONDITIONS:</td>
            <td>{!! $data['payment_conditions'] !!}<br /></td>
        </tr>

        <tr valign="top">
            <td style="font-weight: bold">DOCUMENTS:</td>
            <td>
                @if(is_array($data['documentName']) && count($data['documentName']) > 0)
                    @foreach($data['documentName'] as $key=> $document)
                        {!! $document !!}<br />
                    @endforeach
                @else
                    No document available.
                @endif
            </td>
        </tr>

        <tr valign="top">
            <td style="font-weight: bold">SPECIFICATIONS:</td>
            <td>
                @if(is_array($data['specificationsLst']) && count($data['specificationsLst']) > 0)
                    @foreach($data['specificationsLst'] as $specification)
                        {!! $specification !!}<br />
                    @endforeach
                @else
                    No specifications available.
                @endif
            </td>
        </tr>

    </table>

    <table cellspacing="0" style="margin-top:10px">
        <tr valign="top">
            <th width="15%">
                <p style="text-align: left">TERMS OF AGREEMENT<br />
                    <span style="font-size: 6px">PART 1:</span></p>
            </th>
            <th style="text-align: justify">
                
                @if(isset($data['signature']['term1']))
                    <ul>
                        @foreach(explode(chr(10), $data['signature']['term1']); as $term)
                            <li>{!! $term !!}</li>
                        @endforeach
                    </ul>
                @endif

            </th>
        </tr>
    </table>
</div>

<div class="signature">
    <table width="100%" border="0">
        <tr>
            <th align="left" valign="bottom">
                @if(isset($data['signature']['content']))
                    <table width="100%"  border="0">>
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
                <table width="100%"  border="0">
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
            <th align="left">{{strtoupper($data['buyer']['name'])}}.<br/>DESCRIBE YOUR FULL NAME</th>
        </tr>
    </table>
</div>

<div class="page-break"></div>

<div class="header" style="padding: 30px;  margin-left:6%;">
    <div>
        @if(isset($data['exporter']))
            <img width="70px" src="data:image/png;base64, {{$data['exporter']['profile_picture']}}" alt="">
        @else 
            <img width="70px" src="@include('documents.logo')" alt="">
        @endif
    </div>

    <div style="margin-top: -180px; margin-left: 100px">
        <p style="font-weight: bold">
            @if(isset($data['exporter']))
                {{strtoupper($data['exporter']['name'])}}<br />
                {!! $data['exporter']->addressString() !!}
                {!! $data['exporter']->regString() !!}
                {!! $data['exporter']->contactString() !!}
            @endif
        </p>
    </div>
</div>

<table cellspacing="0" width="100%">
    <tr valign="top">
        <th width="15%">
            <p style="text-align: left">TERMS OF AGREEMENT<br />
            <span style="font-size: 6px">PART 2:</span></p>
        </th>
        <th style="text-align: justify">

            @if(isset($data['signature']['term2']))
                <ul>
                    @foreach(explode(chr(10), $data['signature']['term2']); as $term)
                        <li>{!! $term !!}</li>
                    @endforeach
                
                </ul>
            @endif
        </th>
    </tr>
</table>


<div class="signature">
    <table width="100%" border="0">
        <tr>
            <th align="left" valign="bottom">
                    @if(isset($data['signature']['content']))
                        <table width="100%"  border="0">>
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
                <table width="100%"  border="0">
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
            <th align="left">{{strtoupper($data['buyer']['name'])}}.<br/>DESCRIBE YOUR FULL NAME</th>
        </tr>
    </table>
</div>
