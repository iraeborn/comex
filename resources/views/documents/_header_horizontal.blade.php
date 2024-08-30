<style>
    body {
        font-family: sans-serif;
        font-size: 8px
    }

    .page-break {
        page-break-after: always;
    }


    .header {
        display: flex;
        padding: 10px 0 15px 0;
    }

    .header-logo {
        flex: 1;
    }

    .header-text {
        flex: 1;
        font-size: medium;
    }

    .header-secondary-text {
        font-weight: bold;
        text-align: right;
    }

</style>

<div class="header">
    <div class="header-logo">
        <!-- <img width="10%" src="@include('documents.logo')" alt=""> -->
        <div>
        @if(isset($data['exporter']))
            <img width="70px" src="data:image/png;base64, {{$data['exporter']['profile_picture']}}" alt="">
        @else 
            <img width="70px" src="@include('documents.logo')" alt="">
        @endif
    </div>
    </div>
    <div class="header-text">
        <p style="font-weight: bold">
            {{ mb_strtoupper(config('company.short_name')) }}<br />
            {{ mb_strtoupper(config('company.address')) }}<br />
            BAIRRO: {{ mb_strtoupper(config('company.district')) }}<br />
            {{ mb_strtoupper(config('company.city')) }} - {{ mb_strtoupper(config('company.uf')) }} - {{ mb_strtoupper(config('company.country')) }}<br />
            CNPJ.: {{ mb_strtoupper(config('company.vat')) }}<br />
            I.E.: {{ mb_strtoupper(config('company.ie')) }}<br />
            <span style="color: blue;">{{ mb_strtoupper(config('company.site')) }}</span>
        </p>
        @if(isset($secondaryText))
            <p class="header-secondary-text">
                COMMERCIAL INVOICE {{ $data->number }}
            </p>
        @endif
    </div>
</div>
