@include("documents.header")

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th width="50%" style="color: white; background-color: #a6a6a6" align="center">ORDEM DE CARREGAMENTO</th>
        <th align="center">Nº: {{ $data['loading_number'] ?? 'N/A' }}</th>
        <th align="center">DATA: {{ isset($data['created_at']) ? Carbon\Carbon::parse($data['created_at'])->format('d/m/Y') : 'N/A' }}</th>
    </tr>
</table>

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th width="30%" colspan="2" align="center">PEDIDO: {{ $data['order']['number'] ?? 'N/A' }}</th>
    </tr>
    <tr>
        <th width="30%">ORIGEM: {{ $data['loading']['loading_location'] ?? 'N/A' }}</th>
        <th width="30%">DESTINO: {{ $data['loading']['unloading_location'] ?? 'N/A' }}</th>
    </tr>
</table>

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th width="30%">MOTORISTA: {{ $data['driver']['name'] ?? 'N/A' }}</th>
        <th width="30%">TRANSPORT.: {{ $data['carrier']['name'] ?? 'N/A' }}</th>
    </tr>
    <tr>
        <th width="30%">TELEFONE: {{ $data['driver']['phone'] ?? 'N/A' }}</th>
        <th width="30%">
            PLACA VEÍCULO:
            {{ $data['plate'] ?? 'N/A' }}
            @if($data['plate_two'])
                {{ $data['plate_two'] }}
            @endif
            @if($data['plate_three'])
                {{ $data['plate_three'] }}
            @endif
        </th>
    </tr>
</table>

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th>
            PRODUTO:
            @foreach($data['order']['items'] as $itens)
                {{ $itens->description }};
            @endforeach
        </th>
        <th width="30%">PESO LÍQUIDO: {{ is_numeric($data['wheight']) ? number_format($data['wheight'], 2, ",", ".") . ' KG' : 'N/A' }}</th>
    </tr>
    <tr>
        <th width="30%">SACARIA: {{ $data['order']['full_packing'] ?? 'N/A' }}</th>
        <th width="30%">
            PESO BRUTO:
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
        </th>
    </tr>
    <tr>
        <th width="30%">
            QUANT. SACOS:
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
        </th>
        <th>OBS.: {{ $data['note'] ?? 'N/A' }}</th>
    </tr>
</table>

<table style="margin-top: 10px; margin-bottom: 50px" border="" width="100%" cellpadding="0">
    <tr>
        <th>AUTORIZAÇÃO: {{ $data['authorization'] ?? 'N/A' }}</th>
    </tr>
</table>

@include("documents.header")

<!-- Segunda parte do documento -->

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th width="50%" style="color: white; background-color: #a6a6a6" align="center">ORDEM DE CARREGAMENTO</th>
        <th align="center">Nº: {{ $data['loading_number'] ?? 'N/A' }}</th>
        <th align="center">DATA: {{ isset($data['created_at']) ? Carbon\Carbon::parse($data['created_at'])->format('d/m/Y') : 'N/A' }}</th>
    </tr>
</table>

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th width="30%" colspan="2" align="center">PEDIDO: {{ $data['order']['number'] ?? 'N/A' }}</th>
    </tr>
    <tr>
        <th width="30%">ORIGEM: {{ $data['loading']['loading_location'] ?? 'N/A' }}</th>
        <th width="30%">DESTINO: {{ $data['loading']['unloading_location'] ?? 'N/A' }}</th>
    </tr>
</table>

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th width="30%">MOTORISTA: {{ $data['driver']['name'] ?? 'N/A' }}</th>
        <th width="30%">TRANSPORT.: {{ $data['carrier']['name'] ?? 'N/A' }}</th>
    </tr>
    <tr>
        <th width="30%">TELEFONE: {{ $data['driver']['phone'] ?? 'N/A' }}</th>
        <th width="30%">
            PLACA VEÍCULO:
            {{ $data['plate'] ?? 'N/A' }}
            @if($data['plate_two'])
                {{ $data['plate_two'] }}
            @endif
            @if($data['plate_three'])
                {{ $data['plate_three'] }}
            @endif
        </th>
    </tr>
</table>

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th>
            PRODUTO:
            @foreach($data['order']['items'] as $itens)
                {{ $itens->description }};
            @endforeach
        </th>
        <th width="30%">PESO LÍQUIDO: {{ is_numeric($data['wheight']) ? number_format($data['wheight'], 2, ",", ".") . ' KG' : 'N/A' }}</th>
    </tr>
    <tr>
        <th width="30%">SACARIA: {{ $data['order']['full_packing'] ?? 'N/A' }}</th>
        <th width="30%">
            PESO BRUTO:
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
        </th>
    </tr>
    <tr>
        <th width="30%">
            QUANT. SACOS:
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
        </th>
        <th>OBS.: {{ $data['note'] ?? 'N/A' }}</th>
    </tr>
</table>

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th>AUTORIZAÇÃO: {{ $data['authorization'] ?? 'N/A' }}</th>
    </tr>
</table>
