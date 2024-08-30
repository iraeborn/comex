@include("documents.header")

<table style="margin-top: 10px" border="" width="100%" cellpadding="0">
    <tr>
        <th style="font-size: 12px; padding-left: 2px"> PO </th>
        <th style="font-size: 12px; padding-left: 2px"> BOOKING </th>
        <th style="font-size: 12px; padding-left: 2px"> NFE </th>
        <th style="font-size: 12px; padding-left: 2px"> SUPPLIER </th>
        <th style="font-size: 12px; padding-left: 2px"> INVOICE </th>
        <th style="font-size: 12px; padding-left: 2px"> INVOICE AMOUNT REAL </th>
        <th style="font-size: 12px; padding-left: 2px"> INVOICE AMOUNT DOLAR </th>
        <th style="font-size: 12px; padding-left: 2px"> INVOICE CONVERTED AMOUNT </th>
        <th style="font-size: 12px; padding-left: 2px"> INVOICE CURRENCY FEE </th>
        <th style="font-size: 12px; padding-left: 2px"> INVOICE DUE DATE </th>
    </tr>
    
    @foreach($data as $invoice)
        <tr>
            <td style="font-size: 12px; padding-left: 2px"> {{ $invoice->order_po }} </td> 
            <td style="font-size: 12px; padding-left: 2px"> {{ $invoice->order_booking }} </td> 
            <td style="font-size: 12px; padding-left: 2px"> {{ $invoice->order_nfe }} </td>
            <td style="font-size: 12px; padding-left: 2px"> {{ $invoice->supplier_name }} </td> 
            <td style="font-size: 12px; padding-left: 2px"> {{ $invoice->invoice_id }} </td> 
            <td style="font-size: 12px; padding-left: 2px"> R$ {{ number_format($invoice->invoice_amount_real, 2, '.', ',') }} </td> 
            <td style="font-size: 12px; padding-left: 2px"> US$ {{ number_format($invoice->invoice_amount_dollar, 2, '.', ',') }} </td>
            <td style="font-size: 12px; padding-left: 2px"> R$ {{ number_format($invoice->invoice_amount_converted, 2, '.', ',') }} </td>
            <td style="font-size: 12px; padding-left: 2px"> R$ {{ number_format($invoice->invoice_currency_fee, 2, '.', ',') }} </td>
            <td style="font-size: 12px; padding-left: 2px"> {{ ($invoice->invoice_due_date ? (new Carbon\Carbon($invoice->invoice_due_date))->format('m/d/Y') : '') }} </td>
        </tr>
    @endforeach

    <tr>
        <th colspan="5"> Total </th>
        <th> R$ {{ number_format($data->sum('invoice_amount_real'), 2, '.', ',') }} </th>
        <th> US$ {{ number_format($data->sum('invoice_amount_dollar'), 2, '.', ',') }} </th>
        <th> R$ {{ number_format($data->sum('invoice_amount_converted'), 2, '.', ',') }} </th>
        <th colspan="2"> &nbsp; </th>
    </tr>
</table>
