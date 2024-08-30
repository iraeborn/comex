<html>
<head>
    <meta charset="iso-8859-1"/>
</head>

<body>

    <table style="margin-top: 10px" border="" width="100%" cellpadding="0">
        <tr>
            <th colspan="{{($services->count()+5)}}" align="center" style="background-color:#44546A;color:#fff;;font-weight: bold">DRE OPERATIONS</th>
        </tr>
        <tr>
            <th style="background-color:#44546A;color:#fff;;font-weight: bold" rowspan="2"> PO </th>
            <th style="background-color:#44546A;color:#fff;;font-weight: bold"  rowspan="2"> BOOKING </th>
            <th style="background-color:#44546A;color:#fff;;font-weight: bold"  rowspan="2"> TONS </th>
            <th style="background-color:#44546A;color:#fff;;font-weight: bold"  rowspan="2"> CONTAINERS </th>
            @foreach($services as $service)
                <th style="background-color:#44546A;color:#fff;;font-weight: bold" colspan="2"> {{$service->name}} </th>
            @endforeach
            <th style="background-color:#44546A;color:#fff;;font-weight: bold"  colspan="2"> CUSTO POR TONELADA </th>
            <th style="background-color:#44546A;color:#fff;;font-weight: bold"  colspan="2"> TOTAL GERAL </th>
        </tr>
        <tr>
            @foreach($services as $service)
                <th style="background-color:#44546A;color:#fff;;font-weight: bold">R$ </th>
                <th style="background-color:#44546A;color:#fff;;font-weight: bold">US$ </th>
            @endforeach

            <th style="background-color:#44546A;color:#fff;;font-weight: bold">R$ </th>
            <th style="background-color:#44546A;color:#fff;;font-weight: bold">US$ </th>

            <th style="background-color:#44546A;color:#fff;;font-weight: bold">R$ </th>
            <th style="background-color:#44546A;color:#fff;;font-weight: bold">US$ </th>
        </tr>
            @php($totalServices = [])
            @php($totalServicesUsd = [])
            @php($totalItems = count($data))
            @php($totalTons = 0)
            @php($totalGeral = 0)
            @php($totalGeralUsd = 0)
            @php($totalContainer = 0)

            @foreach($data as $record)
            <tr>
                <td style="font-size: 8pt"> {{ $record->po }} </td>
                <td style="font-size: 8pt" align="center"> 
                    @if($record->shipping)
                        {{$record->shipping->booking}}
                    @endif
                </td>
                <td style="font-size: 8pt" align="center"> {{$record->qty_ton}} </td>
                <td style="font-size: 8pt" align="center"> {{$record->qty_container}} </td>
                
                @php($totalLinha = 0)
                @php($totalLinhaUsd = 0)
                @php($totalTons += $record->qty_ton)
                @php($totalContainer += $record->qty_container)

                @foreach($services as $service)
                    @if(!isset($totalServices['service'.$service->id]))
                        @php($totalServices['service'.$service->id] = 0)
                    @endif

                    @if(!isset($totalServicesUsd['service'.$service->id]))
                        @php($totalServicesUsd['service'.$service->id] = 0)
                    @endif

                    <td style="font-size: 8pt" align="center"> 
                        @if(isset($record->expensesBrl['service'.$service->id]))
                            R$ {{ number_format($record->expensesBrl['service'.$service->id], 2, ',', '.') }}
                            @php($totalLinha += floatval($record->expensesBrl['service'.$service->id]))
                            @php($totalGeral += floatval($record->expensesBrl['service'.$service->id]))
                            @php($totalServices['service'.$service->id] += floatval($record->expensesBrl['service'.$service->id]))
                        @else 
                            R$ {{ number_format(0, 2, ',', '.') }}
                        @endif
                     </td>

                     <td style="font-size: 8pt" align="center"> 
                        @if(isset($record->expensesUsd['service'.$service->id]))
                            US$  {{ number_format($record->expensesUsd['service'.$service->id], 2, ',', '.') }}
                            @php($totalLinhaUsd += floatval($record->expensesUsd['service'.$service->id]))
                            @php($totalGeralUsd += floatval($record->expensesUsd['service'.$service->id]))
                            @php($totalServicesUsd['service'.$service->id] += floatval($record->expensesUsd['service'.$service->id]))
                        @else 
                            US$ {{ number_format(0, 2, ',', '.') }}
                        @endif

                     </td>
                @endforeach

                    <!--CUSTO POR TONELADA-->
                    <td style="font-size: 8pt" align="center"> 
                        @if($record->qty_ton > 0)
                            R$ {{ number_format(round(($totalLinha / $record->qty_ton) * 1000, 2), 2, ',', '.') }}
                        @else 
                            R$ {{ number_format(0, 2, ',', '.') }}
                        @endif
                    </td>

                    <td style="font-size: 8pt" align="center"> 
                        @if($record->qty_ton > 0)
                            US$ {{ number_format(round(($totalLinhaUsd / $record->qty_ton) * 1000, 2), 2, ',', '.') }}
                        @else 
                            US$ {{ number_format(0, 2, ',', '.') }}
                        @endif
                    </td>

                    <!--TOTAL ITEM-->
                    <td style="font-size: 8pt" align="center"> 
                        R$ {{ number_format($totalLinha, 2, ',', '.') }}
                    </td>
                    <td style="font-size: 8pt" align="center"> 
                        US$ {{ number_format($totalLinhaUsd, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach

            @if($totalItems>0)
                <!--TOTAL-->
                <tr>
                    <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center">TOTAL</th>
                    <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center"></th>
                    <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center">
                        {{number_format(floatval($totalTons), 2, ',', '.')}}
                    </th>
                    <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center">
                        {{number_format(floatval($totalContainer), 2, ',', '.')}}
                    </th>

                    @foreach($services as $service)
                        <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center"> 
                            R$ {{ number_format($totalServices['service'.$service->id], 2, ',', '.') }}
                        </th>
                        <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center"> 
                            US$ {{ number_format($totalServicesUsd['service'.$service->id], 2, ',', '.') }}
                        </th>
                    @endforeach

                    <!--CUSTO POR TONELADA-->
                    <th style="font-size: 8pt" align="center"> 
                        @if($totalTons > 0)
                            R$ {{ number_format(round(($totalGeral / $totalTons) * 1000, 2), 2, ',', '.') }}
                        @else 
                            R$ {{ number_format(0, 2, ',', '.') }}
                        @endif
                    </th>
                    <th style="font-size: 8pt" align="center"> 
                        @if($totalTons > 0)
                            US$ {{ number_format(round(($totalGeralUsd / $totalTons) * 1000, 2), 2, ',', '.') }}
                        @else 
                            US$ {{ number_format(0, 2, ',', '.') }}
                        @endif
                    </th>

                    <!--TOTAL-->
                    <th style="font-size: 8pt" align="center"> 
                        R$ {{ number_format($totalGeral, 2, ',', '.') }}
                    </th>
                    <th style="font-size: 8pt" align="center"> 
                        US$ {{ number_format($totalGeralUsd, 2, ',', '.') }}
                    </th>
                </tr>
                
                <!--AVERAGE-->
                <tr>
                    <th style="background:#44546A;color:#fff;;font-weight: bold" align="center">AVERAGE</th>
                    <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center"></th>
                    <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center">
                        {{\App\Helpers\Utilidade::floatToMoney($totalTons/$totalItems)}}
                    </th>
                    <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center">
                        {{\App\Helpers\Utilidade::floatToMoney($totalContainer/$totalItems)}}
                    </th>

                    @foreach($services as $service)
                        <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center"> 
                            R$ {{ \App\Helpers\Utilidade::floatToMoney($totalServices['service'.$service->id]/$totalItems) }}
                        </th>

                        <th style="background-color:#44546A;color:#fff;;font-weight: bold" align="center"> 
                            R$ {{ \App\Helpers\Utilidade::floatToMoney($totalServicesUsd['service'.$service->id]/$totalItems) }}
                        </th>
                    @endforeach

                    <td style="font-size: 8pt" align="center"></td>
                    <td style="font-size: 8pt" align="center"></td>
                </tr>
            @endif
    </table>
</body>
</html>
