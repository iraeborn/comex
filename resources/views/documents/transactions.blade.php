<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
        padding: 1px
    }

    table {
        border-collapse: collapse;
    }

    table,
    th,
    td {
        padding: 2px;
        border: 1px solid black;
    },
    .header {
        display: flex;
        padding: 0 0 10px 0;
        margin: 0;
    }
</style>

<div class="header">
    <div>
        <p style="font-weight: bold">Transaction report</p>
    </div>

    <div style="margin-left: 890px">
        <p style="font-weight: bold">{{ $data['initial_date'] ." / ". $data['final_date'] }}</p>
    </div>
</div>

<table style="margin-top: 10px" width="100%" cellpadding="0">
    <tr>
        @if($data['columns']['po'] == 'true') <th> PO </th> @endif
        @if($data['columns']['accessKey'] == 'true') <th> Access Key </th> @endif
        @if($data['columns']['importerName'] == 'true') <th> Importer name </th> @endif
        @if($data['columns']['country'] == 'true') <th> Country </th> @endif
        @if($data['columns']['date'] == 'true') <th> Date </th> @endif
        @if($data['columns']['weight'] == 'true') <th> Weight(Kg) </th> @endif
        @if($data['columns']['transactionType'] == 'true') <th> Transaction Type </th> @endif
        @if($data['columns']['bank'] == 'true') <th> Bank </th> @endif
        @if($data['columns']['description'] == 'true') <th> Description </th> @endif
        @if($data['columns']['value'] == 'true') <th> Value </th> @endif
        @if($data['columns']['dollar'] == 'true') <th> Dollar value </th> @endif
        @if($data['columns']['converted'] == 'true') <th> Converted value </th> @endif
        @if($data['columns']['due_code'] == 'true') <th> Due code </th> @endif
        @if($data['columns']['reference'] == 'true') <th> Reference </th> @endif
        @if($data['columns']['cambio_contract'] == 'true') <th> Exchange contract </th> @endif
    </tr>

    @foreach ($data['transactions'] as $transaction)
        <tr>
            @if($data['columns']['po'] == 'true') <td>{{ $transaction['order']['number'] }}</td> @endif
            @if($data['columns']['accessKey'] == 'true') <td>{{ $transaction['order']['nf'] }}</td>  @endif
            @if($data['columns']['importerName'] == 'true') <td>{{ $transaction['order']['owner']['name'] }}</td> @endif
            @if($data['columns']['country'] == 'true') <td>{{ $transaction['order']['owner']['country'] }}</td> @endif
            @if($data['columns']['date'] == 'true') <td>{{ $transaction['created_at'] }}</td> @endif
            @if($data['columns']['weight'] == 'true') <td>{{ $transaction['qty_ton'] }}</td> @endif
            @if($data['columns']['transactionType'] == 'true') <td>{{ $transaction['transaction_type']['name'] }}</td> @endif
            @if($data['columns']['bank'] == 'true') <td>{{ $transaction['bank'] }}</td> @endif
            @if($data['columns']['description'] == 'true') <td>{{ $transaction['description'] }}</td> @endif
            @if($data['columns']['value'] == 'true') <td>{{ $transaction['value'] ? "R$ ". number_format($transaction['value'], 2, '.', ',') : '' }}</td> @endif
            @if($data['columns']['dollar'] == 'true') <td>{{ $transaction['dollar_value'] ? "$ ". number_format($transaction['dollar_value'], 2, '.', ',') : '' }}</td> @endif
            @if($data['columns']['converted'] == 'true') <td>{{ $transaction['converted_value'] ? "R$ ". number_format($transaction['converted_value'], 2, '.', ',') : '' }}</td> @endif
            @if($data['columns']['due_code'] == 'true') <td>{{ $transaction['order']['mapa']['due_code'] }}</td> @endif
            @if($data['columns']['reference'] == 'true') <td>{{ $transaction['reference'] }}</td> @endif
            @if($data['columns']['cambio_contract'] == 'true') <td>{{ $transaction['cambio_contract'] }}</td> @endif
        </tr>
    @endforeach
</table>

<table style="margin-top: 10px" width="100%" cellpadding="0">
<tr>
    <td>Total NFE</td>
    <td>Total</td>
    <td>Total converted</td>
    <td>Total variation</td>
</tr>
<tr>
    <td>R$</td>
    <td>$ {{ $data['total'] ? number_format($data['total'], 2, ',', '.') : ''  }}</td>
    <td>R$ {{ $data['total'] ? number_format($data['total_converted'], 2, ',', '.') : ''  }}</td>
    <td>R$ {{ $data['total'] ? number_format($data['total_variation'], 2, ',', '.') : ''  }}</td>
</tr>
</table>