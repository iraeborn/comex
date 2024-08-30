@component('mail::message')

<p>Olá,</p>

<p>Enviamos Draft do BL para emissão dos drafts documentos PO {{$order->number}} Booking {{$booking}}.</p>

@if(isset($show_approval) && $show_approval)
  <table border="1" bordercolor="#ebe8d7" align="center" style="width:100%;border-collapse: collapse;" cellpadding="5">
    <tr>
      <th style="background-color:#44546A;color:#fff;;font-weight: bold" >Document</th>
      {{-- <th style="background-color:#44546A;color:#fff;;font-weight: bold" ></th>
      <th style="background-color:#44546A;color:#fff;;font-weight: bold" ></th> --}}
    </tr>

    @foreach ($order->draft_documents()->get() as $draft_documents)

      @if($draft_documents->draft_bl_status)
        <tr>
          <td><a href="{{$draft_documents->draft_bl}}">Draft BL</a></td>
          {{-- <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/draft_bl/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/draft_bl/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td> --}}
        </tr>
      @endif

    @endforeach
  </table>
@elseif(isset($senders) && $senders)
  <p style="text-align: center;background:#cccccc;padding:10px;margin-top:15px;margin-bottom:15px">
    <b>Notified importers: </b> {{implode(', ',$senders)}}
  </p>
@endif

<br/>
Regards,<br/>
{{ config('app.name') }}.

@endcomponent