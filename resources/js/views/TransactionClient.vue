<template>
    <div class="container-fluid">

            <div id="ui-view">

                <div class="panel panel-success">
                    
<div class="card" v-if="order">
  <div class="card-header">
    <div class="row">
        <div class="col">
            Transactions for order number: {{ order.number }}
        </div>
        <div class="col-2">
            <div class="input-group float-right">
                <input type="text" placeholder="Filter" class="form-control form-control-sm" v-model="filter">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger btn-sm" @click="filter = ''">X</button>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="card-body">

    <table class="table table-sm table-amarelo" v-if="Filtered(transactions) && order">
        <tr>
            <th>Imorter name</th>
            <th>Date</th>
            <th>Order</th>
            <th>Bank</th>
            <th>Transaction type</th>
            <th>Description</th>
            <th>Value</th>
            <th>Dollar value</th>
            <th>Converted value</th>
            <th>Exchange contract</th>
        </tr>

        <tbody v-for="(transaction, index) in Filtered(transactions)">
            <tr>
                <td>{{ transaction.owner.name }}</td>
                <td>{{ dataCalc(transaction) }}</td>
                <td>{{ order.number }}</td>
                <td>{{ transaction.bank }}</td>
                <td>{{ transaction.transaction_type.name }}</td>
                <td>{{ transaction.description }}</td>
                <td>$ {{ FormatNumber(transaction.value || 0) }}</td>
                <td>$ {{ FormatNumber(transaction.dollar_value || 0) }}</td>
                <td>R$ {{ FormatNumber(transaction.converted_value || 0) }}</td>
                <td>{{ transaction.cambio_contract }}</td>
            </tr>
        </tbody>

    </table>

    <div class="row">
        <div class="col">
            <p>Current Balance: $ {{ FormatNumber(GetCurrentBalance()) }}</p>
        </div>
    </div>


    <div class="modal fade" id="modal-new-transaction">
        <div class="modal-dialog modal-primary">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add new transaction to Order</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <select-icon v-model="new_transaction.transaction_type_id" name="transaction_type_id">
                        <option :value="null">Chose an option</option>
                        <option v-for="(transaction_type, index) in transaction_types" :value="transaction_type.id">{{ transaction_type.name }}</option>
                    </select-icon>
                    <iconinput v-model="new_transaction.value" icon="fas fa-money-bill-wave" label="Value" @input="CalcDollar" type="number" />
                    <iconinput v-model="new_transaction.bank" icon="fas fa-university" label="Bank" />
                    <iconinput v-model="new_transaction.description" icon="fas fa-edit" label="Description" />
                    <iconinput v-model="new_transaction.dollar_value" icon="fas fa-money-bill-wave" label="Dollar value" @input="CalcDollar" type="number" />
                    <iconinput v-model="new_transaction.converted_value" icon="fas fa-money-bill-wave" label="Converted value" type="number" />
                    <iconinput v-model="new_transaction.cambio_contract" icon="fas fa-exchange-alt" label="Exchange contract" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="SaveTransaction">Save</button>
                </div>
            </div>
        </div>
    </div>
  </div>

  <div class="card-footer text-right">
    &nbsp;
  </div>
</div>



                </div>

            </div>
    </div>


</template>

<script>
export default {
    data() {
        return {
            filter: null,
            order: null,
            transactions: [],
            transaction_types: [],
            new_transaction: {
                transaction_type_id: 2,
                value: null,
                bank: null,
                description: null,
                dollar_value: null,
                converted_value: null,
                cambio_contract: null,
                value: null,
            },
        }
    },
    methods: {
        GetCurrentBalance: function () {
            let transactions_sum = this.$data.transactions.filter(transaction => {
                return transaction.transaction_type.display_to_importer == 1
            }).map(transaction => {
                return transaction.value;
            }).reduce((acc, value) => {
                return acc + value;
            })
            return transactions_sum;
        },
        EditTransaction: function (index) {
            this.$data.new_transaction = JSON.parse(JSON.stringify(this.$data.transactions[index]));

            $('#modal-new-transaction').modal('show');
            this.$forceUpdate();
        },
        CalcDollar: function () {
            this.$data.new_transaction.converted_value = (this.$data.new_transaction.value * this.$data.new_transaction.dollar_value).toFixed(2);
        },
        FormatDate: function (date) {
            let [year, month, day, hour, minutes, seconds] = date.split(/[- :]/g);

            return month + "/" + day + "/" + year + " " + hour + ":" + minutes;
        },
        TotalValue: function () {
            let value = this.$data.transactions.filter(transaction => {
                return transaction.transaction_type_id !== 1;
            }).map(transaction => {
                let value = transaction.value;
                if (transaction.transaction_type.id != 2) {
                    value = -value;
                }
                return value;
            })

            if(value.length > 0) {
                value = value.reduce((accumulator, transaction_value) => {
                    return accumulator + transaction_value;
                });
            } else {
                value = 0;
            }

            let value_fixed = this.FormatNumber(value);
            return "$" + value_fixed;
        },
        FormatNumber: function (value) {
            //value /= 100;

            let value_fixed = value.toFixed(2);
            let [value_decimal, value_fraction] = value_fixed.split(/\./);

            value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");

            value_fixed = value_decimal + ',' + value_fraction;
            return value_fixed;
        },
        Filtered: function () {
            if (!this.$data.filter) return this.$data.transactions;

            return this.$data.transactions.filter(transaction => {
                if (!transaction.description) return false;

                let description_has_text = transaction.description.toLowerCase().indexOf(this.$data.filter.toLowerCase()) != -1;
                let bank_has_text = transaction.bank.toLowerCase().indexOf(this.$data.filter.toLowerCase()) != -1;
                let transaction_type_has_text = transaction.transaction_type.name.toLowerCase().indexOf(this.$data.filter.toLowerCase()) != -1;

                return description_has_text || bank_has_text || transaction_type_has_text;
            });
        },
        LoadTransactions: async function (date) {
            let order = await this.$http.get('/api/order/' + this.$route.params.id);
            let transactions = await this.$http.get('/api/balance/' + this.$route.params.id);
            let transaction_types = await this.$http.get('/api/transaction-types');

            this.$data.order = order.body;
            this.$data.transactions = transactions.body;
            this.$data.transaction_types = transaction_types.body;
        },
        SaveTransaction: async function () {
            let transactions = await this.$http.put('/api/balance/' + this.$route.params.id, this.$data.new_transaction);
            if(!this.$data.new_transaction.id){
                this.$data.transactions.push(transactions.body.transaction);
            }

            if(this.$data.new_transaction.id) {
                for(let i in this.$data.transactions) {
                    if (this.$data.transactions[i].id == this.$data.new_transaction.id) {
                        this.$data.transactions[i] = transactions.body.transaction;
                        this.$forceUpdate();
                        break;
                    }
                }
            }
            $('.modal').modal('hide');

        },
        dataCalc(transaction) {
            let data = transaction.created_at.split(/\s/)[0];
            if(transaction.data) 
                data = transaction.data;

            let [year, month, day] = data.split(/-/);
            return `${month}/${day}/${year}`;
        }
    },
    mounted: function () {
        this.LoadTransactions();
    }
}

</script>