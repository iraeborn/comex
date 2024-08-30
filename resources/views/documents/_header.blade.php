<style>
    body {
        font-family: sans-serif;
        font-size: 8px
    }
    .page-break {
        page-break-after: always;
    }
    .header {
        display: inline-block;
        margin-top: 20px
    }
    table {
        border-spacing: 0;
        border-collapse: collapse;
    }
    .border {
        border: 1px solid black;
    }
    .p5 {
        padding: 5px
    }
    .mt5 {
        margin-top: 5px;
    }
    .pt5 {
        padding: 5px
    }
    .pb5 {
        padding: 5px
    }
    .tal{
        text-align: left;
    }
</style>

<div class="header">
    <div>
        @if(isset($data['exporter']))
            <img style="margin-top: 11px;" width="90px" src="data:image/png;base64, {{$data['exporter']['profile_picture']}}" alt="">
        @else 
            <img style="margin-top: 11px;" width="90px" src="@include('documents.logo')" alt="">
        @endif
    </div>
    <div style="margin-top: -200px; margin-left: 140px;">
        <p style="font-weight: bold">
            @if(isset($data['exporter']))
                    {{strtoupper($data['exporter']['name'])}}<br />
                    {!! $data['exporter']->addressString() !!}
                    {!! $data['exporter']->regString() !!}
                    {!! $data['exporter']->contactString() !!}
            @endif
         
        </p>
    </div>

    <div style="margin-left: 580px">
        <p style="font-weight: bold">
        @if(isset($data['updated_at']))
            {{mb_strtoupper(date("d/m/Y", strtotime($data['updated_at'])))}}
        @else 
            {{mb_strtoupper(date("d/m/Y"))}}
        @endif
        </p>
    </div>
</div>