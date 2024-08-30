@component('mail::message')

<p>Dear Customer,</p>

<p>We forward your shipment plan for your contract. Kindly confirm.</p>


@foreach ($order->shipping as $shipping)
<table border="1" bordercolor="#ebe8d7" align="center" style="width:100%;border-collapse: collapse;margin-bottom:20px" cellpadding="5">
    <!-- LINHA 1-->
    <tr>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" colspan="4">Booking</td>
    </tr>
    <tr>
      <td style="" colspan="3">{{$shipping->booking}}</td>
    </tr>

   <!-- LINHA 2-->
    <tr>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" >Port of loading</td>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" colspan="2">Port of discharge</td>
    </tr>
    <tr>
      <td style="" >{{$shipping->loading_port->name}}</td>
      <td style="" colspan="2">{{$shipping->discharge_port->name}}</td>
    </tr>

    <!-- LINHA 3-->
    <tr>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" >Vessel/Voyage</td>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" >ETD</td>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" >ETA</td>
    </tr>
    <tr>
      <td style="" >{{$shipping->vessel}}</td>
      <td style="" >{{$shipping->etd}}</td>
      <td style="" >{{$shipping->eta}}</td>
    </tr>

     <!-- LINHA 4-->
     <tr>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" >Shipping line</td>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" >Free time origin</td>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" >Free time destination</td>
    </tr>
    <tr>
      <td style="" >{{$shipping->shipping_line}}</td>
      <td style="" >{{$shipping->free_time_origin}}</td>
      <td style="" >{{$shipping->free_time_destination}}</td>
    </tr>

    <!-- LINHA 5-->
    <tr>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" >Draft deadline</td>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" colspan="2">Loading deadline</td>
    </tr>
    <tr>
      <td style="" >{{$shipping->draft_deadline}}</td>
      <td style="" colspan="2">{{$shipping->loading_deadline}}</td>
    </tr>

    <!-- LINHA 5-->
    <tr>
      <td style="background-color:#44546A;color:#fff;;font-weight: bold" colspan="3">Freight</td>
    </tr>
    <tr>
      <td style="" colspan="3">{{$shipping->freight}}</td>
    </tr>

    <!-- LINHA 6-->
    @if($shipping->approves->count()>0)
      <tr>
        <td style="background-color:#44546A;color:#fff;;font-weight: bold" colspan="3">Approvation history</td>
      </tr>
      <tr>
        <td style="" colspan="3">
          <table style="width:100%" border="0">
            <tr>
              <th style="font-weight: bold">Old Vessel</th>
              <th style="font-weight: bold">Old ETD</th>
              <th style="font-weight: bold">Old ETA</th>
              <th style="font-weight: bold">Date and Time of approval</th>
              <th style="font-weight: bold">Approved by</th>
              <th style="font-weight: bold">Justification for change</th>
            </tr>
            @foreach ($shipping->approves as $approve)
              <tr>
                <td>
                  {{ $approve->vessel }}
                </td>
                <td>
                  {{ $approve->etd }}
                </td>
                <td>
                  {{ $approve->eta }}
                </td>
                <td>
                  {{ $approve.created_at }}
                </td>
                <td>
                  {{ $approve->name }}
                </td>
                <td>
                  {{ $approve->reason }}
                </td>
              </tr>
            @endforeach
          </table>
        </td>
      </tr>
    @endif
</table>
@endforeach

Regards,<br />
{{ config('app.name') }}.

@endcomponent