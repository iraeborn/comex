<style>

  @page { margin-top: 15px; }
  body {
    font-family: sans-serif;
    font-size: 12px;
  }

  table {
    border-collapse: collapse;
  }
  
  td {
    padding: 3px;
  }

  .border {
    border: 1px solid black;
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
</style>

<table cellpadding="0" width='100%'>
  <tr>
    <td>
      @if(isset($data->exporter) && $data->exporter->profile_picture)
        <img width="90px" src="{{$data->exporter->profile_picture}}" alt="">
      @else 
        <img src="@include('documents.logo')" alt="AgricolaLucas" width="90px">
      @endif
    </td>
    <td colspan="5" align="left">
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

  <tr> <td colspan="6" style="height: 2rem"></td></tr>

  <tr class="section-title">
    <th colspan="6" class="border">DRAFT NOTA FISCAL EXPORTAÇÃO</th>
  </tr>
  <tr>
    <th class="border">IMPORTER:</th>   
    <td class="border" colspan="5"> 
      {{$data->owner->name}} <br/>
      {!! $data->owner->addressString() !!}
    </td>
  </tr>
  <tr>
    <th class="border">CONSIGNEE:</th>
    <td class="border" colspan="5"> 
      {{$data->consignee->name}} <br/>
      {!! $data->consignee->addressString() !!}
    </td>
  </tr>

  <tr> <td colspan="6" style="height: 2rem"></td></tr>

  <tr class="section-title">
    <th colspan="6" class="border">INFORMAÇÕES ADICIONAIS</th>
  </tr>

  <tr>
    <th class="border">PO:</th>
    <td class="border" colspan="5"> {{ $data->number }} </td>
  </tr>

  <tr>
    <th class="border">ARMADOR:</th>
    <td class="border" colspan="5"> {{ $data->shipping->first() ? $data->shipping->first()->shipping_line : '' }} </td>
  </tr>

  <tr>
    <th class="border">DATA DE VENCIMENTO:</th>
    <td class="border" colspan="5"> {{ date("d/m/Y", strtotime($data->shipping->first()->eta)) }} </td>
  </tr>

  <tr>
    <th class="border">NAVIO:</th>
    <td class="border" colspan="5"> {{ $data->shipping->first() ? $data->shipping->first()->vessel : '' }} </td>
  </tr>

  <tr>
    <th class="border">BOOKING:</th>
    <td class="border" colspan="5"> {{ $data->shipping->first() ? $data->shipping->first()->booking : '' }} </td>
  </tr>

  <tr>
    <th class="border">PESO LIQUIDO:</th>
    <td class="border" colspan="5">
      @php
        $net_weight = optional($data->loadings->vehicles)->sum(function($vehicle) {
          return floatval($vehicle->wheight);
        })
      @endphp
      {{ number_format($net_weight, 0, ',', '.') }} KG
    </td>
  </tr>

  <tr>
    <th class="border">PESO BRUTO:</th>
    <td class="border" colspan="5">
      @php
        $firstVehicle = optional($data->loadings->vehicles->first());
        $tara_bags = floatval($firstVehicle->tara_bags ?? 0);

        $weight_plus_tara = floatval($data->full_packing) + $tara_bags;
        $total_bags = floatval($data->bills->sum('bags'));

        $total_weight = $total_bags * $weight_plus_tara;
      @endphp
  
      {{ number_format($total_weight, 0, ',', '.') }} KG
  </td>      
  </tr>

  <tr>
    <th class="border">EMBALAGEM:</th>
    <td class="border" colspan="5"> {{ $data->full_packing }} </td>
  </tr>

  <tr>
    <th class="border">QUANT. TOTAL EMBALAGENS:</th>
    <td class="border" colspan="5"> {{ number_format(floatval($data->bills->sum('bags')), 0, ',', '.') }} </td>
  </tr>

  <tr>
    <th class="border">PTAX DOLAR:</th>
    <td class="border" colspan="5"> {{ $data->mapa ? $data->mapa->currency_fee : '' }} </td>
  </tr>

  <tr>
    <th class="border">DATA PTAX:</th>
    <td class="border" colspan="5"> {{ $data->mapa && $data->mapa->date_currency_fee ? date("d/m/Y", strtotime($data->mapa->date_currency_fee)) : '' }} </td>
  </tr>

  <tr> <td colspan="6" style="height: 2rem"></td></tr>

  <tr class="section-title multi-cols">
    <th colspan="2" class="border">PRODUTO:</th>
    <th class="border">TOTAL BAGS:</th>
    <th class="border">QUANTIDADE:</th>
    <th class="border">VALOR UNIT.:</th>
    <th class="border">VALOR TOTAL:</th>
  </tr>
  @foreach($data->items as $item)
    <tr>
      <td class="border" colspan="2"> {{ $item->description }} </td>
      <td class="border"> KG </td>
      <td class="border"> {{
        optional($data->loadings->vehicles)->sum(function($vehicle) {
          return is_numeric($vehicle->wheight) ? $vehicle->wheight : 0;
        }) ?? ''
      }} </td>
      <td class="border"> 
        R$ {{
          is_numeric($item->unit_price) && is_numeric($data->mapa->currency_fee ?? 1)
            ? number_format((($item->unit_price * ($data->mapa->currency_fee ?? 1)) / 1000), 2, ',', '.')
            : '0,00'
        }}
      </td>

      <td class="border"> 
        {{
          optional($data->loadings->vehicles)->sum(function($vehicle) use ($item, $data) {
            return is_numeric($vehicle->wheight) && is_numeric($item->unit_price) && is_numeric($data->mapa->currency_fee ?? 1)
              ? (($item->unit_price * ($data->mapa->currency_fee ?? 1)) / 1000) * $vehicle->wheight
              : 0;
          }) !== null 
          ? number_format(optional($data->loadings->vehicles)->sum(function($vehicle) use ($item, $data) {
              return is_numeric($vehicle->wheight) && is_numeric($item->unit_price) && is_numeric($data->mapa->currency_fee ?? 1)
                ? (($item->unit_price * ($data->mapa->currency_fee ?? 1)) / 1000) * $vehicle->wheight
                : 0;
            }), 2, ',', '.')
          : ''
        }} 
      </td>
      
    </tr>
    <tr>
      <td class="border" colspan="2"> {{ $item->description }} </td>
      <td class="border"> KG </td>
      <td class="border"> {{
        optional($data->loadings->vehicles)->sum(function($vehicle) {
          return is_numeric($vehicle->wheight) ? $vehicle->wheight : 0;
        }) ?? ''
      }} </td>
      <td class="border"> 
        US$ {{ is_numeric($item->unit_price) ? number_format($item->unit_price / 1000, 2, ',', '.') : '0,00' }} 
      </td>
      
      <td class="border"> 
        {{
          is_numeric(optional($data->loadings->vehicles)->sum(function($vehicle) use ($item) {
            return is_numeric($vehicle->wheight) && is_numeric($item->unit_price) ? 
              ($item->unit_price / 1000) * $vehicle->wheight : 0;
          })) ? number_format(optional($data->loadings->vehicles)->sum(function($vehicle) use ($item) {
            return is_numeric($vehicle->wheight) && is_numeric($item->unit_price) ? 
              ($item->unit_price / 1000) * $vehicle->wheight : 0;
          }), 2, ',', '.') : '0,00'
        }} 
      </td>
      

    </tr>
  @endforeach

  <tr> <td colspan="6" style="height: 2rem"></td></tr>

  <tr class="section-title multi-cols">
    <th class="border">NOTA DE REMESSA:</th>
    <th colspan="3" class="border">CHAVE:</th>
    <th class="border">PESO (KGS):</th>
    <th class="border">SACAS:</th>
  </tr>
  @if ($data->bills)
    @foreach($data->bills->unique('number') as $bill)
      @php 
        $bill->sum_weight = is_numeric($bill->weight) ? number_format($bill->weight, 2, ',', '.') : 0;
      @endphp
      <tr>
        <td class="border">{{ $bill->number }}</td>
        <td class="border" colspan="3">{{ $bill->key ?? '' }}</td>
        <td class="border">{{ $bill->sum_weight }} KG</td>
        <td class="border">{{ $bill->bags ?? '' }}</td>
      </tr>
    @endforeach
  @endif

  <tr> <td colspan="6" style="height: 2rem"></td></tr>

  <tr>
      <th class="border">LOCAL DE DESPACHO:</th>
      <td class="border" colspan="5"> {{ ($data->container_stuffing ? $data->container_stuffing->dispatch_place_name : '') }} </td>
  </tr>
  <tr>
      <th class="border">RECINTO ADUANEIRO</th>
      <td class="border" colspan="5"> {{ ($data->container_stuffing ? $data->container_stuffing->dispatch_place_code : '') }} </td>
  </tr>

  <tr> <td colspan="6" style="height: 2rem"></td></tr>

  <tr>
      <th class="border">LOCAL DE EMBARQUE</th>
      <td class="border" colspan="5"> {{ ($data->shipping->first() && $data->shipping->first()->loading_port ? $data->shipping->first()->loading_port->name : '') }} </td>
  </tr>
  <tr>
      <th class="border">RECINTO ADUANEIRO:</th>
      <td class="border" colspan="5"> {{ ($data->shipping->first() && $data->shipping->first()->loading_port ? $data->shipping->first()->loading_port->code : '') }} </td>
  </tr>
</table>