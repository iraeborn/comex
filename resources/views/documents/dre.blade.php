@include("documents.header")

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th> PO </th>
        <th> TONS </th>
        <th> CONTAINERS </th>
        <th> TOTAL EXPENSES </th>
        <th> EXPENSES PER TON / CIF </th>
        <th> EXPENSES PER TON / FOB </th>
    </tr>
    
        @foreach($data as $record)
        <tr>
            <td> {{ $record->po }} </td>
            <td> {{ $record->qty_ton }} </td>
            <td> {{ $record->qty_container }} </td>
            <td> R$ {{ number_format($record->total_expenses, 2, ',', '.') }} </td>
            <td> R$ {{ number_format($record->expense_per_ton, 2, ',', '.') }} </td>
            <td> R$ {{ number_format($record->expense_per_ton_fob, 2, ',', '.') }} </td>
            </tr>
        @endforeach
    <tr>
        <th> Total </th>
        <th> {{ $data->sum('qty_ton') }} </th>
        <th> {{ $data->sum('qty_container') }} </th>
        <th> R$ {{ number_format($data->sum('total_expenses'), 2, ',', '.') }} </th>
        <th> R$ {{ number_format( ($data->sum('total_expenses') / $data->sum('qty_ton')) * 1000, 2, ',', '.') }} </th>
        <th> R$ {{ number_format( ($data->sum('total_expenses_fob') / $data->sum('qty_ton')) * 1000, 2, ',', '.') }} </th>
    </tr>
</table>
