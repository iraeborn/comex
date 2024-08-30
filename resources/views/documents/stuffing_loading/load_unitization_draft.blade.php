<style>
    body {
        font-family: sans-serif;
        font-size: 9px
    }

    .border {
        border: 1px solid black;
    }
    .border-top-bottom {
        border-top: 1px solid black;
        border-bottom: 1px solid black;
    }
    table {
        border-collapse: collapse;
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

<table align="center" width="50%" cellpadding="0">

    <tr class="section-title">
        <th colspan="6">DRAFT PARA UNITIZAÇÃO DE CARGA</th>
    </tr>
    <tr>
        <th>EXPORTADOR:</th>
        <td colspan="6">
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

    <tr>
        <th class="border">BOOKING:</th>
        <td class="border" colspan="6"> {{ ($data->shipping->first() ? $data->shipping->first()->booking : '') }} </td>
    </tr>
    <tr>
        <th class="border">PO:</th>
        <td class="border" colspan="6"> {{ $data->number }} </td>
    </tr>
    <tr>
        <th class="border">PRODUTO:</th>
        <td class="border" colspan="6"> {{ ($data->items->first() ? $data->items->first()->description : '') }} </td>
    </tr>
    <tr>
        <th class="border">DU-E:</th>
        <td class="border" colspan="6"> {{ ($data->mapa ? $data->mapa->due_code : '') }} </td>
    </tr>
    <tr>
        <th class="border">CHAVE DE ACESSO:</th>
        <td class="border" colspan="6"> {{ ($data->mapa ? $data->mapa->access_key : '') }} </td>
    </tr>
    <tr>
        <th class="border">RUC:</th>
        <td class="border" colspan="6"> {{ ($data->mapa ? $data->mapa->ruc_code : '') }} </td>
    </tr>

    <tr> <td colspan="7" style="height: 2rem"></td></tr>

    <tr>
        <th class="border" align="left">CONTEINER:</th>
        <th class="border" align="left">TARA:</th>
        <th class="border" align="left">LACRE:</th>
        <!-- <th>PESO:</th>
        <th>NFE REMESSA:</th> -->
    </tr>
    @if ($data->shipping->first() && $data->shipping->first()->containers)
        @foreach($data->shipping->first()->containers as $container)
            <tr>
                <td class="border-top-bottom"> {{ $container->name }} </td>
                <td class="border-top-bottom"> {{ $container->tare }} </td>
                <td class="border-top-bottom"> {{ $container->seal }} </td>
            </tr>

            <tr>
                <td> NFE </td>
                <td> PESO </td>
                <td> SACAS </td>
            </tr>
            @if ($container->bills)
                @foreach($container->bills as $bill)
                    <tr>
                        <td> {{ $bill->number }} </td>
                        <td> {{ $bill->weight }} </td>
                        <td> {{ $bill->bags }} </td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <th align="left" style="background-color:#ffeb3bc2;color: black;"> TOTAL </th>
                <th align="left" style="background-color:#ffeb3bc2;color: black;"> {{ ($container->bills ? $container->bills->sum('weight') : '')}} </th>
                <th align="left" style="background-color:#ffeb3bc2;color: black;"> {{ ($container->bills ? $container->bills->sum('bags') : '')}} </th>
            </tr>

            <tr> <td colspan="7" style="height: 1rem"></td></tr>    
        @endforeach
    @endif

</table>
