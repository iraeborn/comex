 @component('mail::message')

<p>Dear Customer,</p>

<p>We forward documents for your approval.</p>

@if(isset($show_approval) && $show_approval)
  <table border="1" bordercolor="#ebe8d7" align="center" style="width:100%;border-collapse: collapse;" cellpadding="5">
    <tr>
      <th style="background-color:#44546A;color:#fff;;font-weight: bold" >Document</th>
      <th style="background-color:#44546A;color:#fff;;font-weight: bold" ></th>
      <th style="background-color:#44546A;color:#fff;;font-weight: bold" ></th>
    </tr>

    @foreach ($order->draft_documents()->get() as $draft_documents)

      @if($draft_documents->draft_bl_status)
        <tr>
          <td><a href="{{$draft_documents->draft_bl}}">Draft BL</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/draft_bl/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/draft_bl/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

      @if($draft_documents->draft_comercial_status)
        <tr>
          <td><a href="{{$draft_documents->draft_comercial}}">Draft Comercial</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/draft_comercial/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/draft_comercial/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

      @if($draft_documents->packing_list_status)
        <tr>
          <td><a href="{{$draft_documents->packing_list}}">Draft Packagin List</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/packing_list/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/packing_list/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

      @if($draft_documents->certificate_origin_status)
        <tr>
          <td><a href="{{$draft_documents->certificate_origin}}">Draft Origin Certificate</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/certificate_origin/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/certificate_origin/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

      @if($draft_documents->certificate_fumigation_status)
        <tr>
          <td><a href="{{$draft_documents->certificate_fumigation}}">Draft Fumigation Certificate</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/certificate_fumigation/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/certificate_fumigation/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

      @if($draft_documents->certificate_quality_status)
        <tr>
          <td><a href="{{$draft_documents->certificate_quality}}">Draft Quality Certificate</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/certificate_quality/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/certificate_quality/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

      @if($draft_documents->certificate_weight_status)
        <tr>
          <td><a href="{{$draft_documents->certificate_weight}}">Draft Weight Certificate</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/certificate_weight/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/certificate_weight/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

      @if($draft_documents->certificate_seguro_status)
        <tr>
          <td><a href="{{$draft_documents->certificate_seguro}}">Draft Seguro Certificate</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/certificate_seguro/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/certificate_seguro/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

      @if($draft_documents->certificate_phyto_status)
        <tr>
          <td><a href="{{$draft_documents->certificate_phyto}}">Draft Phyto Certificate</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/certificate_phyto/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/certificate_phyto/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

      @if($draft_documents->report_status)
        <tr>
          <td><a href="{{$draft_documents->report}}">Draft Report</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/report/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/report/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

      @if($draft_documents->health_certificate_status)
        <tr>
          <td><a href="{{$draft_documents->health_certificate}}">Draft Health Certificate</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/health_certificate/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/health_certificate/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif


      @if($draft_documents->non_gmo_certificate_status)
        <tr>
          <td><a href="{{$draft_documents->non_gmo_certificate}}">Draft GMO Certificate</a></td>
          <td align="center">
            <a href="{{ url('api/order/draft-documents/approved/non_gmo_certificate/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approved</a>
          </td>
          <td align="center">
          <a href="{{ url('api/order/draft-documents/reject/non_gmo_certificate/'.md5($order->id))}}"
      style="color:#e7182c;">Reject</a>
          </td>
        </tr>
      @endif

    @endforeach
    <tr>
      <td colspan="3" align="center" style="padding:10px">
      <a href="{{ url('api/order/draft-documents/approved/all/'.md5($order->id))}}"
      style="color:#2c8d1b;">Approve All Documents</a>

      <a href="{{ url('api/order/draft-documents/reject/all/'.md5($order->id))}}"
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