<style>
    @page { margin: 10px; }
    body {
        font-family: sans-serif;
    }

    .border {
        border: 1px solid black;
    }

    .border-round {
        border: 2px solid black;
        border-radius: 10px;
    }

    .border-bottom {
        border-bottom: 1px solid black;
    }

    .border-left {
        border-left: 1px solid black;
    }

    .border-right {
        border-right: 1px solid black;
    }

    .italic {
        font-style: italic;
    }

    .p1 {
        padding: 0.5rem;
    }

    .p2 {
        padding: 1rem;
    }

    .p3 {
        padding: 1.5rem;
    }

    .p4 {
        padding: 2rem;
    }

    .p5 {
        padding: 2.5rem;
    }

    .pt {
        padding-left: 0;
        padding-bottom: 0;
        padding-right: 0;
    }

    .pb {
        padding-left: 0;
        padding-top: 0;
        padding-right: 0;
    }

    .pl {
        padding-top: 0;
        padding-bottom: 0;
        padding-right: 0;
    }

    .pr {
        padding-left: 0;
        padding-bottom: 0;
        padding-top: 0;
    }

    .px {
        padding-top: 0;
        padding-bottom: 0;
    }

    .py {
        padding-left: 0;
        padding-right: 0;
    }

    .m0 {
        margin: 0;
    }

    .f9 {
        font-size: 7px;
    }

    .f10 {
        font-size: 8px;
    }

    .f12 {
        font-size: 10px;
    }

    .f14 {
        font-size: 12px;
    }

    .f16 {
        font-size: 14px;
    }

    .f24 {
        font-size: 20px;
    }

    .upper {
        text-transform: uppercase;
    }

    .underline {
        text-decoration: underline;
    }

    .normal {
        font-weight: normal;
        line-height: 1;
    }

    table.collapse {
        border-collapse: collapse;
    }

</style>

<table width="100%" cellpadding="0">
    <tr>
        <td class="border-round p1">
            <table width="100%" cellpadding="0">
                <tr>
                    <td width="20%">
                        <!-- @if(isset($data->exporter) && $data->exporter->profile_picture)
                            <img width="100px" src="{{$data->exporter->profile_picture}}" alt="">
                        @else 
                            <img src="@include('documents.logo')" alt="AgricolaLucas" width="100px">
                        @endif -->
                        <img src="@include('documents.certificate')" alt="AgricolaLucas" width="100px">
                    </td>

                    <td align="center" valign="top">
                        <h2 class="m0 normal p1 pb f24">
                            CERTIFICADO FITOSSANITÁRIO
                            <strong>PHYTOSANITARY CERTIFICATE</strong>
                        </h2>
                        <span class="f16">
                            MINISTÉRIO DA AGRICULTURA, PECUÁRIA E ABASTECIMENTO<br/>
                            DEPARTAMENTO DE SANIDADE VEGETAL
                        </span><br/>
                        <span class="f14">
                            ORGANIZAÇÃO NACIONAL DE PROTEÇÃO FITOSSANITÁRIA DO BRASIL<br/>
                            <strong>PLANT PROTECTION ORGANIZATION OF BRAZIL</strong>
                        </span>
                    </td>

                    <td width="20%" valign="top">
                        <h2 class="m0 normal p1 pb f24" style="margin-left: -2rem;">
                            &nbsp;<br/>
                            <strong>N&ordm; </strong><span class="f16 upper">{{ ($data->mapa ? $data->mapa->lpco_key : '') }}</span>
                        </h2>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="border-round p1 px">
            <table width="100%" cellpadding="0">
                <tr>
                    <td width="40%" class="f12">
                        1. Para: Organização Nacional de Proteção Fitossanitária de:                        
                    </td>
                    <td width="60%" class="border-bottom f16 upper">
                        {{ ($data->consignee ? $data->consignee->country : '') }}
                    </td>
                </tr>
                <tr>
                    <td width="40%" class="f12">
                        <strong class="italic">To: Plant Protection Organization(s) of </strong>                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td align="center" class="f12">
            DESCRIÇÃO DO ENVIO / <strong class="italic">DESCRIPTION OF CONSIGNMENT</strong>
        </td>
    </tr>

    <tr>
        <td class="border-round">
            <table width="100%" cellpadding="0" class="collapse">
                <tr>
                    <td colspan="3" width="50%" valign="top" class="border-bottom border-right p1 px">
                        <span class="f10">
                            2. Nome e endereço do exportador / 
                            <strong class="italic">Name and address of exporter</strong>
                        </span><br/>
                        <span class="f14">
                        @if(isset($data->exporter))
                            {{strtoupper($data->exporter->name)}}<br />
                            {!! $data->exporter->addressString() !!}
                            {!! $data->exporter->regString() !!}
                            {!! $data->exporter->contactString() !!}
                        @endif
                        </span>
                    </td>

                    <td colspan="3" width="50%" valign="top" class="border-left border-bottom p1 px">
                        <span class="f10">
                            3. Nome e endereço do destinatário declarado / 
                            <strong class="italic">Declared name and address of consignee</strong>
                        </span><br/>
                        <span class="f14 upper">
                            {{ $data->consignee->name }}<br/>
                            ADDRESS: {!! $data->consignee->addressString() !!}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" width="32%" class="border-bottom border-right p1 px" valign="top">
                        <span class="f10">
                            4. Lugar de Origem / 
                            <strong class="italic">Place of Origin </strong>
                        </span><br/>
                        <span class="f14"> 
                            @if($data->exporter)
                                {{ $data->exporter->state }} / {{ $data->exporter->country }}
                            @endif
                        </span>
                    </td>

                    <td colspan="2" width="36%" class="border-bottom border-right border-left p1 px" valign="top">
                        <span class="f10">
                            5. Meios de transporte declarados / 
                            <strong class="italic">Declared means of conveyance</strong>
                        </span><br/>
                        <span class="f14 upper">
                            {{ $data->transportion }}
                        </span>
                    </td>

                    <td colspan="2" width="32%" class="border-bottom border-left p1 px" valign="top">
                        <span class="f10">
                            6. Ponto de ingresso declarado / 
                            <strong class="italic">Declared point of entry</strong>
                        </span><br/>
                        <span class="f14 upper">
                            {{ $data->port_destiny }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" width="50%" class="border-bottom border-right p1 px" valign="top">
                        <span class="f10">
                            7. Número e descrição dos volumes / 
                            <strong class="italic">Number and description of packages</strong>
                        </span><br/>
                        <span class="f14">
                            {{ ($data->bills ? $data->bills->sum('bags') : '0') }} SACAS / BAGS
                        </span><br/><br/>
                    </td>

                    <td colspan="3" width="50%" class="border-bottom border-left p1 px" valign="top">
                        <span class="f10">
                            8. Nome do produto e quantidade declarada / 
                            <strong class="italic">Name of produce and quantity declared</strong>
                        </span><br/>
                        <span class="f14 upper">
                            {{ ($data->items && $data->items[0] ? $data->items[0]->description : '') }}<br/>
                            PESO LíQUIDO / NET WEIGHT: {{ ($data->loadings && $data->loadings->vehicles ? number_format($data->loadings->vehicles->sum('wheight'), 2, ',', '.') : '0') }} KG
                        </span><br/>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" width="50%" class="border-bottom border-right p1 px" valign="top"> 
                        <span class="f10">
                            9. Marcas distintivas / 
                            <strong class="italic">Distinguishing marks</strong>
                        </span><br/>
                        <span class="f14 upper">
                            NAVIO / VESSEL: {{ ($data->shipping->first() ? $data->shipping->first()->vessel : '') }}.
                            BL: {{ ($data->mapa ? $data->mapa->bill_of_lading : '') }}<br/>
                            CONTAINERS: 
                            @if ($data->shipping->first() && $data->shipping->first()->containers)
                                @php $separator = ''; @endphp
                                @foreach ($data->shipping->first()->containers as $container)
                                    {{ $separator . $container->name }}
                                    @php $separator = ' / '; @endphp
                                @endforeach
                            @endif
                        </span>
                    </td>

                    <td colspan="3" width="50%" class="border-bottom border-left p1 px" valign="top">
                        <span class="f10">
                            10. Nome científico dos vegetais / 
                            <strong class="italic">Botanical name of plants</strong>
                        </span><br/>
                        <span class="f14 italic">
                            {{ ($data->items && $data->items[0] ? $data->items[0]->botanical_name : '') }}<br/>
                        </span>
                    </td>
                </tr>

                <tr>
                    <td colspan="6" width="50%" class="border-bottom p1 px" valign="top">
                        <span class="f12">
                            <strong>11</strong>. Pelo presente certifica-se que os vegetais, seus produtos ou outros artigos regulamentados aqui descritos foram inspecionados e/ou analisados, de acordo com os<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;procedimentos oficiais adequados e considerados livres das pragas quarentenárias especificadas pela parte contratante importadora e que cumprem os requisitos<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;fitossanitários vigentes da parte contratante importadora, incluídos os relativos às pragas não quarentenárias regulamentadas.
                        </span><br/>
                        <strong class="f9 italic">
                            This is to certify that the plants, plant products or other regulated articles described herein have been inspected and/or tested according to appropriate official procedures and are considered to be free from the quarantine pests specified by the importing contracting partyand to conform with the current phytosanitary requirements of the importing contracting party, including those for regulated non-quarantine pests.
                        </strong>
                    </td>
                </tr>

                <tr>
                    <td colspan="6" width="50%" class="border-bottom f12" align="center">
                        DECLARAÇÃO ADICIONAL / <strong class="italic">ADDITIONAL DECLARATION</strong>
                    </td>
                </tr>

                <tr>
                    <td colspan="6" width="50%" class="f14" valign="top">
                        {!! ($data->mapa ? nl2br($data->mapa->additional_declaration) : '&nbsp;') !!}
                        <br/><br/><br/>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td align="center" class="f12">
            TRATAMENTO DE DESINFESTAÇÃO E/OU DESINFECÇÃO / <strong class="italic">DISINFESTATION AND/OR DISINFECTION TREATMENT</strong>
        </td>
    </tr>

    <tr>
        <td class="border-round">
            <table width="100%" cellpadding="0" class="collapse">
                <tr>
                    <td colspan="2" width="16.6%" class="border-bottom border-right p1 px" valign="top">
                        <span class="f10">
                            12. Data do tratamento /<br/>
                            <strong class="italic">Date of treatment</strong>
                        </span><br/>
                        <span class="f14 upper">
                            @if ($data->fumigation->first())
                                @php 
                                    $date = new Carbon\Carbon($data->fumigation->first()->date_of_fumigation_certificate); 
                                @endphp
                                @php
                                    $translate = [
                                        'Jan' => 'Jan',
                                        'Feb' => 'Fev',
                                        'Mar' => 'Mar',
                                        'Apr' => 'Abr',
                                        'May' => 'Mai',
                                        'Jun' => 'Jun',
                                        'Jul' => 'Jul',
                                        'Aug' => 'Ago',
                                        'Sep' => 'Set',
                                        'Oct' => 'Out',
                                        'Nov' => 'Nov',
                                        'Dec' => 'Dez'
                                    ];
                                @endphp
                                {{ $date->format('d') }}/{{ $translate[$date->format('M')] }}-{{ $date->format('M') }}/{{ $date->format('Y') }}
                            @endif
                        </span>
                    </td>

                    <td colspan="4" width="33.3%" class="border-bottom border-right border-left p1 px" valing="top">
                        <span class="f10">
                            13. Produto Químico (ingrediente ativo)/<br/>
                            <strong class="italic">Chemical (active ingredient)</strong>
                        </span><br/>
                        <span class="f14 upper">
                            {{ ($data->fumigation->first() ? $data->fumigation->first()->treatment : '') }}
                        </span>
                    </td>

                    <td colspan="2" width="16.6%" class="border-bottom border-right border-left p1 px" valing="top">
                        <span class="f10">
                            14. Concentração/<br/>
                            <strong class="italic">Concentration</strong>
                        </span><br/>
                        <span class="f14 upper">
                            {{ ($data->fumigation->first() ? $data->fumigation->first()->dosage : '') }}
                        </span>
                    </td>

                    <td colspan="4" width="33.3%" class="border-bottom border-left p1 px" valing="top">
                        <span class="f10">
                            15. Duração e Temperatura
                            <strong class="italic">Duration and Temperature</strong><br/>&nbsp;
                        </span><br/>
                        <span class="f14 upper">
                            {{ ($data->fumigation->first() ? (int)$data->fumigation->first()->exposition_time . ' HORAS/HOURS A/AT ' . $data->fumigation->first()->temperature_local : '') }}
                        </span>
                    </td>
                </tr>

                <tr>
                    <td colspan="4" width="33.3%" class="border-right p1 px" valing="top">
                        <span class="f10">
                            16. Tratamento
                            <strong class="italic">Treatment</strong><br/>&nbsp;
                        </span><br/>
                        <span class="f14 upper">
                            {{ ($data->fumigation->first() ? 'FUMIGAÇÃO / FUMIGATION' : '') }}
                        </span>
                    </td>

                    <td colspan="8" width="66.6%" class="border-left p1 px" valing="top">
                        <span class="f10">
                            17. Informação Adicional
                            <strong class="italic">Additional Information</strong><br/>&nbsp;
                        </span><br/>
                        <span class="f14 upper">
                            {{ ($data->fumigation->first() ? 'NONE' : '') }}
                        </span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td class="border-round">
            <table width="100%" cellpadding="0" class="collapse">
                <tr>
                    <td rowspan="3" width="20%" class="border-bottom p1 px" valign="top">
                        <span class="f10">
                            18. Carimbo da Organização /<br/>
                            <strong class="italic">Stamp of Organization</strong>
                        </span>
                    </td>
                    <td width="55%" class="border-bottom border-left p1 px" valign="top">
                        <span class="f10">
                            19. Lugar de Expedição /<
                            <strong class="italic">Place of Issue</strong>
                        </span><br/><br/><br/>
                    <td width="25%" class="border-bottom border-left p1 px" valign="top">
                        <span class="f10">
                            20. Data de Emissão /
                            <strong class="italic">Date of Issue</strong>
                        </span><br/><br/><br/>
                    </td>                    
                </tr>

                <tr>
                    <td colspan="2" width="75%" class="border-bottom border-left p1 px" valign="top">
                        <span class="f10">
                            21. Nome do Fiscal Federal Agropecuário Autorizado /
                            <strong class="italic">Name of Authorized Officer</strong>
                        </span><br/><br/><br/>
                    </td>
                </tr>

                <tr>
                    <td width="45%" class="border-bottom border-left p1 px" valign="top">
                        <span class="f10">
                            22. Assinatura do Fiscal Federal Agropecuário Autorizado /
                            <strong class="italic">Signature of Authorized Officer</strong>
                        </span><br/><br/><br/>
                    </td>0
                    <td width="30%" class="border-bottom border-left p1 px" valign="top">
                        <span class="f10">
                            23. N&ordm; de Registro COSAVE /
                            <strong class="italic">COSAVE Registration Number</strong>
                        </span><br/><br/><br/>
                    </td>
                </tr>

                <tr>
                    <td colspan="3" width="50%" class="p2 f12">
                        O Departamento de Sanidade Vegetal, sseus funcionários e repressentantes isentam-se de toda responsabilidade econômica e/ou comercial resultantes deste certificado.<br/>
                        <strong class="italic">No financial liability with respect to this certificate shall attach to Departamento de Sanidade Vegetal or to any of its officers or representatives.</strong>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
