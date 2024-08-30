<?php
function split_nfe_key($nfe_key) {
    if ($nfe_key) {
        $parts = preg_split('/[-;,\/|]/', $nfe_key);
        return implode("\n", $parts);
    }
    return '';
}
?>
@include("documents.header")
<style>
    th, td{
        font-size: 13px;
    }
</style>
<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th> CONTRACT </th>
        <th> SUPPLIER </th>
        <th> PO </th>
        <th> NFE </th>
        <th> BOOKING </th>
        <th> SERVICE TYPE </th>
        <th> CONTAINER QTY </th>
        <th> AMOUNT DOLAR </th>
        <th> AMOUNT REAL </th>
        <th> PROVISION STATUS </th>
        <th> DUE DATE </th>
    </tr>

    @foreach ($data as $provision)
        @if ($provision->id)
            <tr>
                <td style="padding-left: 2px">{{ $provision->provider_contract->identifier }}</td>
                <td style="padding-left: 2px">
                    @if($provision->provider_contract->provider)
                        {{ $provision->provider_contract->provider->name }}
                    @endif
                    
                </td>
                <td style="padding-left: 2px">{{ $provision->order->number }}</td>
                <td style="padding-left: 2px">{{ $provision->order->mapa ? split_nfe_key($provision->order->mapa->nfe_key) : '' }}</td>
                <td style="padding-left: 2px">{{ $provision->booking }}</td>
                <td style="padding-left: 2px">{{ $provision->provider_contract->service_type }}</td>
                <td style="padding-left: 2px">{{ $provision->quantity_container }}</td>
                <td style="padding-left: 2px">US$ {{ number_format($provision->dolar_budgeted_amount, 2, '.', ',') }}</td>
                <td style="padding-left: 2px">R$ {{ number_format($provision->real_budgeted_amount, 2, '.', ',') }}</td>
                <td style="padding-left: 2px">{{ $provision->status_text }}</td>
                <td style="padding-left: 2px">{{ (new Carbon\Carbon($provision->provider_contract->expirated_at))->format('m/d/Y') }}</td>
            </tr>
        @endif
    @endforeach

    <tr>
      <th colspan="7"> Total </th>
      <th> US$ {{ number_format($data->sum('dolar_budgeted_amount'), 2, '.', ',') }}</th>
      <th> R$ {{ number_format($data->sum('real_budgeted_amount'), 2, '.', ',') }}</th>
      <th colspan="2">&nbsp;</th>
    </tr>
</table>
