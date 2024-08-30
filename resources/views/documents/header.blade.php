<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
        padding: 1px
    }

    table {
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }
</style>

@php
    $exporter = (
        (isset($data['exporter']) ? $data['exporter'] : (
            (isset($data['order']['exporter']) ? $data['order']['exporter'] : null)
        ))
    );
@endphp

@if($exporter)
<table width="100%">
    <tr>
        <td align="center" width="20%" style="border-top: 1px solid black !important; border-left: 1px solid black !important; border-bottom: 0px !important; border-right: 0px !important">
            <img width="70px" src="@include('documents.logo')" alt="">
        </td>
        <td style="border-bottom-width: 1;border-top: 1px solid black !important;border-right: 1px solid black !important;border-left: 0px !important">
            {{strtoupper($exporter['name'])}}<br />
            {!! $exporter->addressString() !!}
            {!! $exporter->regString() !!}
            {!! $exporter->contactString() !!}

            </th>
    </tr>
</table>
@endif