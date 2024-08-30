<style>
    body {
        font-family: sans-serif;
        font-size: 9px
    }

    table {
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 3px;
    }
</style>

<table border="" width="100%" cellpadding="0">
    <tr>
        <th style="padding: 5px" align="center" colspan="6">REMESSA DE FORMAÇÃO DE LOTE PARA EXPORTAÇÃO</th>
    </tr>
    <tr style="background-color:#191970; color: white; text-align: center">
        <th colspan="6">INFORMAÇÕES DO PEDIDO</th>
    </tr>

    @if(isset($data['order']['number']) || isset($data['order']['shipping'][0]['booking']))
    <tr>
        @isset($data['order']['number'])
            <th>PROFORMA INVOICE:</th>
            <td>{{ $data['order']['number'] }}</td>
        @endisset

        @isset($data['order']['shipping'][0]['booking'])
            <th>BOOKING:</th>
            <td colspan="3">{{ $data['order']['shipping'][0]['booking']}}</td>
        @endisset
    </tr>
    @endif
    
    @isset($data['loading']['unloading_location'])
    <tr>
        <th>TERMINAL DE ENTREGA:</th>
        <td colspan="5">{{ $data['loading']['unloading_location'] ?? 'N/A' }}</td>
    </tr>
    @endisset

    @isset($data['stuffing'])
    <tr>
        <th>TERMINAL DE DESPACHO MARÍTIMO:</th>
        <td colspan="5">
            @isset($data['stuffing']['dispatch_place_code']) {{ $data['stuffing']['dispatch_place_code'] }} @endisset
            @isset($data['stuffing']['dispatch_place_name']), {{ $data['stuffing']['dispatch_place_name'] }} @endisset
        </td>
    </tr>
    @endisset

    @isset($data['truck_unloading_datetime'])
    <tr>
        <th>DATA AGENDAMENTO DESCARGA:</th>
        <td>{{ Carbon\Carbon::parse($data['truck_unloading_datetime'])->format('d/m/Y') }}</td>
        <th>HORA:</th>
        <td colspan="3">{{ Carbon\Carbon::parse($data['truck_unloading_datetime'])->format('H:m') }}</td>
    </tr>
    @endisset

    @isset($data['order']['owner']['name'])
    <tr>
        <th>IMPORTADOR:</th>
        <td colspan="5">{{ $data['order']['owner']['name'] }}</td>
    </tr>
    @endisset

    @isset($data['order']['terminal_agent_name'])
    <tr>
        <th>LOCAL DE EMBARQUE:</th>
        <td colspan="5">{{ $data['order']['terminal_agent_name'] }}</td>
    </tr>
    @endisset

    <tr>
        <th style="background-color:#191970; color: white; text-align: center" colspan="6">INFORMAÇÕES TRANSPORTADORA</th>
    </tr>

    @isset($data['carrier']['name'])
    <tr>
        <th>RAZÃO SOCIAL:</th>
        <td colspan="5">{{ $data['carrier']['name'] }}</td>
    </tr>
    @endisset

    @isset($data['carrier']['address_1'])
    <tr>
        <th>ENDERECO:</th>
        <td colspan="5">
                {!! strtoupper($data['carrier']['address_1']) !!}
                @isset($data['carrier']['number']) , {!! strtoupper($data['carrier']['number']) !!} @endisset
                @isset($data['carrier']['neighborhood']), {!! strtoupper($data['carrier']['neighborhood']) !!} @endisset
                @isset($data['carrier']['zip']), {!! strtoupper($data['carrier']['zip']) !!} @endisset
                @isset($data['carrier']['city']), {!! strtoupper($data['carrier']['city']) !!} @endisset
                @isset($data['carrier']['state']), {!! strtoupper($data['carrier']['state']) !!} @endisset
                @isset($data['carrier']['country']), {!! strtoupper($data['carrier']['country']) !!} @endisset
        </td>
    </tr>
    @endisset

    @isset($data['carrier']['contacts'])
    <tr>
        <th>FONE:</th>
        <td colspan="5">
            @foreach($data['carrier']['contacts'] as $contact)
                @if($contact['contact_type_id'] === 3 || $contact['contact_type_id'] === 4)
                    {{ $contact['value'] }}
                @endif
            @endforeach
        </td>
    </tr>
    @endisset

    @isset($data['carrier']['email'])
    <tr>
        <th>EMAIL:</th>
        <td colspan="5">{{ $data['carrier']['email'] }}</td>
    </tr>
    @endisset

    @isset($data['carrier']['cnpj'])
    <tr>
        <th>CNPJ:</th>
        <td colspan="5">{{ $data['carrier']['cnpj'] }}</td>
    </tr>
    @endisset

    @isset($data['carrier']['state_registration'])
    <tr>
        <th>INSCRIÇÃO ESTADUAL:</th>
        <td colspan="5">{{ $data['carrier']['state_registration'] }}</td>
    </tr>
    @endisset

    @isset($data['carrier']['tax_substitution'])
    <tr>
        <th>SUBSTITUIÇÃO TRIBUTÁRIA:</th>
        <td colspan="5">
            @if( $data['carrier']['tax_substitution'] === 1 )
                (X) SIM ( ) NÃO
            @else
                ( ) SIM (X) NÃO
            @endif
        </td>
    </tr>
    @endisset

    <tr>
        <th style="background-color:#191970; color: white; text-align: center" colspan="6">INFORMAÇÕES DO MOTORISTA</th>
    </tr>

    @isset($data['driver']['name'])
    <tr>
        <th>NOME:</th>
        <td colspan="5">{{ $data['driver']['name'] }}</td>
    </tr>
    @endisset

    @isset($data['driver']['phone'])
    <tr>
        <th>FONE:</th>
        <td colspan="5">{{ $data['driver']['phone'] }}</td>
    </tr>
    @endisset

    @isset($data['driver']['cnh'])
    <tr>
        <th>CNH CAT/ VAL / EST / 1º HA:</th>
        <td colspan="5">{{ $data['driver']['cnh'] }}</td>
    </tr>
    @endisset

    @isset($data['driver']['rg'])
    <tr>
        <th>RG / ORG EMISS. / EXP:</th>
        <td colspan="5">{{ $data['driver']['rg'] }}</td>
    </tr>
    @endisset

    @isset($data['driver']['born_at'])
    <tr>
        <th>DATA NASCIMENTO:</th>
        <td colspan="5">{{ $data['driver']['born_at'] }}</td>
    </tr>
    @endisset

    <tr>
        <th style="background-color:#191970; color: white; text-align: center" colspan="6">INFORMAÇÕES DO VEÍCULO</th>
    </tr>

    @isset($data['plate'])
    <tr>
        <th>PLACA DO CAVALO E IMPLEMENTOS:</th>
        <td colspan="5">
            {{$data['plate']}}

            @isset($data['plate_two'])
                {{$data['plate_two']}}
            @endisset

            @isset($data['plate_three'])
                {{$data['plate_three']}}
            @endisset

        </td>
    </tr>
    @endisset

    @isset($data['wheight'])
    <tr>
        <th>PESO LIQUIDO PRODUTO:</th>
        <td>
            {{ is_numeric($data['wheight']) ? number_format($data['wheight'], 2, ",", ".") . ' KG' : 'N/A' }}
        </td>
        <th>PESO BRUTO PRODUTO</th>
        <td colspan="3">
            @php
                $wheight = floatval($data['wheight']);
                $packing = floatval($data['order']['packing']);
                $tara_bags = floatval($data['tara_bags']);
                
                if ($packing != 0) {
                    $result = $wheight + ($wheight / $packing) * $tara_bags;
                    $formattedResult = number_format($result, 2, ",", ".");
                } else {
                    $formattedResult = 'N/A';
                }
            @endphp
        
            {{ $formattedResult }} KG
        </td>
    </tr>
    @endisset

    @isset($data['order']['full_packing'])
    <tr>
        <th>TIPO SACARIA:</th>
        <td colspan="5">{{ $data['order']['full_packing'] }}</td>
    </tr>
    @endisset
    
    @isset($data['tara_bags'])
    <tr>
        <th>TARA SACO:</th>
        <td colspan="5">
            @if(is_numeric($data['tara_bags']))
                {{ number_format($data['tara_bags'],2,",",".") }} KG
            @else
                N/A
            @endif
        </td>
    </tr>
    @endisset

    @isset($data['wheight'])
    <tr>
        <th>TOTAL SACOS:</th>
        <td colspan="5">
            @php
                $wheight = floatval($data['wheight']);
                $packing = floatval($data['order']['packing']);
                
                if ($packing != 0) {
                    $result = $wheight / $packing;
                    $formattedResult = number_format($result, 2, ",", ".");
                } else {
                    $formattedResult = 'N/A';
                }
            @endphp
        
            {{ $formattedResult }}
        </td>
        
    </tr>
    <tr>
        <th>PESO BRUTO VEICULO DE FABRICA:</th>
        <td colspan="5">
            @php

                $wheight = floatval($data['wheight']);
                $packing = floatval($data['order']['packing']);
                $taraBags = floatval($data['tara_bags']);
                $taraHorse = floatval($data['tara_horse']);
                $taraTruckOne = floatval($data['tara_truck_one']);
                $taraTruckTwo = floatval($data['tara_truck_two']);
                
                if ($packing != 0) {
                    $result = $wheight +
                        (($wheight / $packing) * $taraBags) +
                        $taraHorse +
                        $taraTruckOne +
                        $taraTruckTwo;
                        
                    $formattedResult = number_format($result, 2, ",", ".");
                } else {
                    $formattedResult = 'N/A';
                }
            @endphp
        
            {{ $formattedResult }} KG
        </td>
        
    </tr>
    @endisset

    <tr>
        <th style="background-color:#191970; color: white; text-align: center" colspan="6">CONDIÇÕES NEGOCIADAS</th>
    </tr>

    @isset($data['order']['items'])
    <tr>
        <th>PRODUTO:</th>
        <td colspan="5">
            @foreach($data['order']['items'] as $itens)
                {{ $itens->description }};
            @endforeach
        </td>
    </tr>
    <tr>
        <th>TAXA PTAX:</th>
        <td>{{ number_format($data['loading']['tax_ptax'],4,",",".") }}</td>
        <th>DATA PTAX:</th>
        <td colspan="3">{{ Carbon\Carbon::parse($data['loading']['date_ptax'])->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <th>VALOR FRETE TON:</th>
        <td>{{  number_format($data['freight'],2,",",".") }}</td>
        <th>VALOR UNIT KG/ R$:</th>
        <td colspan="3">
            @foreach($data['order']['items'] as $itens)
                {{ number_format((($itens->unit_price/1000)*$data['loading']['tax_ptax']),4,",",".") }};
            @endforeach
        </td>
    </tr>
    <tr>
        <th>TOTAL TRANSPORTADO VEICULO:</th>
        <td>{{ number_format($data['wheight'],2,",",".") }} KG</td>
        <th>VALOR TOTAL FRETE:</th>
        <td>{{ number_format($data['wheight']*$data['freight']/1000,2,",",".") }}</td>
        <th>TOTAL PRODUTO:</th>
        @foreach($data['order']['items'] as $itens)
            <td>
                R$ {{ number_format(($data['wheight']*round((($itens->unit_price/1000)*$data['loading']['tax_ptax']), 4)),4,",",".") }}
            </td>
        @endforeach
    </tr>
    @endisset
    
    @isset($data['carrier']['banks'])
    <tr>
        <th>DADOS BANCARIOS TRANSPORTADORA</th>
        <td>BANCO: {{$data['carrier']['banks']['beneficiary_bank']?:''}}</td>
        <td>AG:{{$data['carrier']['banks']['branch_number']?:''}}</td>
        <td colspan="3">C/C:{{$data['carrier']['banks']['account_nr_one']?:''}}</td>
    </tr>
    <tr>
        <th>FAVORECIDO:</th>
        <td colspan="5">BANCO: {{$data['carrier']['banks']['beneficiary_bank']?:''}}</td>
    </tr>
    @endisset

    @isset($data['toll_payment'])
    <tr>
        <th>MOTORISTA POSSUI VALE PEDÁGIO:</th>
        <td colspan="5">
            @if($data['toll_payment'] === 1)
                (X) SIM ( ) NÃO
            @else
                ( ) SIM (X) NÃO
            @endif
        </td>
    </tr>
    @endisset

    @isset($data['note'])
    <tr>
        <th style="background-color:#191970; color: white; text-align: center" colspan="6">OUTRAS OBSERVAÇÕES</th>
    </tr>
    <tr>
        <td colspan="6">
            {{ $data['note'] }}
        </td>
    </tr>
    @endisset
</table>
