<tr>
    <td class="border" width="10%">EXPORTER/SHIPPER</td>
    <td class="border" colspan="5">
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
<tr>
    <th class="border">IMPORTER</th>
    <td class="border" colspan="5">
        {{$data->owner->name}} <br/>
        {!! $data->owner->addressString() !!}
        {!! $data->owner->regString() !!}
        <br/>

        {!! $data->owner->contactString() !!}
    </td>
</tr>

<tr>
    <th class="border">NOTIFY</th>
    <td class="border" valign="top" colspan="5">
        <table width="100%" height="100%" cellpadding="0">
            <tr>
                <td>
                    {{$data->notify->name}} <br/>
                    {!! $data->notify->addressString() !!}
                    {!! $data->notify->regString() !!}

                    <br/>

                    {!! $data->notify->contactString() !!}
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <th class="border">CONSIGNEE</th>
    <td class="border" valign="top" colspan="5">
        <table width="100%" height="100%" cellpadding="0">
            <tr>
                <td>
                    {{$data->consignee->name}} <br/>
                    {!! $data->consignee->addressString() !!}
                    {!! $data->consignee->regString() !!}

                    <br/>
                                
                    {!! $data->consignee->contactString() !!}
                 </td>
            </tr>
        </table>
    </td>
</tr>