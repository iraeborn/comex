@include("documents.header")

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th> ORDER NUMBER </th>
        <th> IMPORTER NAME </th>
        <th> PRODUCT </th>
        <th> FCL </th>
        <th> QUANTITY KG </th>
        <th> PORT DESTINY </th>
        <th> SHIPMENT </th>
    </tr>
    <?php
    function custom_number_format($number, $decimals = 2, $decimal_separator = ',', $thousands_separator = '.') {
        return number_format(str_replace(',', '', $number), $decimals, $decimal_separator, $thousands_separator);
    }
    ?>
    @foreach($data as $order)
        <tr>
            <td>{{ $order->number }}</td>
            <td>{{ ($order->owner ? $order->owner->name : '') }}</td>
            <td>{{ ($order->items[0] ? $order->items[0]->description : '') }}</td>
            <td>{{ ($order->items[0] ? $order->items[0]->container : '') }}</td>
            <td>{{ ($order->items[0] ? custom_number_format($order->items[0]->net_weight) : '') }}</td>
            <td>{{ $order->port_destiny }}</td>
            <td>{{ $order->shipment }}</td>
        </tr>
    @endforeach

    <tr>
        <th colspan="3"> Total </th>
        <th> 
            {{ 
                $data->sum(function ($order) {
                    $container = ($order->items[0] ? $order->items[0]->container : 0);
                    return intval($container); 
                }) 
            }} 
        </th>
        <th colspan="3">
            {{ $order->items[0] ? custom_number_format($data->sum(function ($order) {
                return $order->items[0]->net_weight;
            })) : 0 }}
        </th>
    </tr>
</table>
