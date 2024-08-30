@component('mail::message')

<p>Dear Customer,</p>

<p>We sent documents for your original documents.</p>

@if(isset($show_approval) && $show_approval)
  <table border="1" bordercolor="#ebe8d7" align="center" style="width:100%;border-collapse: collapse;" cellpadding="5">
    <tr>
      <th style="background-color:#44546A;color:#fff;;font-weight: bold" >Document</th>
      <th style="background-color:#44546A;color:#fff;;font-weight: bold" ></th>
      <th style="background-color:#44546A;color:#fff;;font-weight: bold" ></th>
    </tr>
    @foreach ($order->original_documents()->get() as $original_documents)

      @if($original_documents->url)
        <tr>
          <td><a href="{{$original_documents->url}}">{{$original_documents->document_type->name}}</a></td>
          <td align="center">
            <a href="{{ url('api/order/original-documents/approved/'.$original_documents->document_type->id.'/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/original-documents/reject/'.$original_documents->document_type->id.'/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif
    @endforeach
    <tr>
      <td colspan="3" align="center" style="padding:10px">
      <a href="{{ url('api/order/original-documents/approved/all/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approve All Documents</a>

      <a href="{{ url('api/order/original-documents/reject/all/'.md5($order->id))}}"
      style="color:#e7182c;">Reject All Documents</a>
      </td>

    </tr>
  </table>

@elseif(isset($senders) && $senders)
  <p style="text-align: center;background:#cccccc;padding:10px;margin-top:15px;margin-bottom:15px">
    <b>Notified importers: </b> {{implode(', ',$senders)}}
  </p>
@endif

Regards,<br />
{{ config('app.name') }}.

@endcomponent