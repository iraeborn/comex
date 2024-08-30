@include("documents.header")

@php
    function formatCurrency($value) {
        $response = number_format($value,2,",",".");
        return $response;
    }
@endphp

@foreach($data['months'] as $month)

    @php
        $orders = $data['orders']->where('month', $month);
    @endphp

    <h2>{{ $month }}</h2>

    <table style="margin-top: 10px" border="" width="100%" cellpadding="0">
        <tr>
            <th> PO</th>
            <th> NF </th>
            <th> Product </th>
            <th> Importer </th>
            <th> Country </th>
            <th> Booking </th>
            <th> Current Balance </th>
            <th> Available Value </th>
            <th> Expiration Date </th>
        </tr>
        
        @foreach($orders as $order)
            <tr style="font-size: 13px">
                <td>{{ $order->number }}</td>
                <td>{{ $order->nf }}</td>
                <td>{{ ($order->items[0] ? $order->items[0]->description : '') }}</td>
                <td>{{ $order->owner->name }}</td>
                <td>{{ $order->owner->country }}</td>
                <td>{{ $order->shipping->booking ?? "-" }}</td>
                <td>U$ {{ formatCurrency($order->sum) }}</td>
                <td>U$ {{ formatCurrency($order->sum2) }}</td>
                <td>{{ $order->expiry_date }}</td>
            </tr>
        @endforeach

        <tr>
            <th colspan="6"> Total </th>
            <th> U$ {{ formatCurrency($orders->sum('sum')) }} </th>
            <th colspan="2"> U$ {{ formatCurrency($orders->sum('sum2')) }} </th>
        </tr>
    </table>

@endforeach

<h3>
    Grand Total Current Balance: U$ {{ formatCurrency($data['orders']->sum('sum')) }}
</h3>

<h3>
    Grand Total Available Value: U$ {{ formatCurrency($data['orders']->sum('sum2')) }}
</h3>