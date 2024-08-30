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
                  <input
                    type="text"
                    placeholder="Filter"
                    class="form-control form-control-sm"
                    v-model="filter"
                  />
                  <div class="input-group-append">
                    <button
                      type="button"
                      class="btn btn-danger btn-sm"
                      @click="filter = ''"
                    >
                      X
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-2">
                <iconinput
                  v-model="order.nf"
                  icon="fas fa-file-invoice"
                  label="NFE"
                  type="text"
                  error=""
                />
              </div>
              <div class="col-3">
                <iconinput
                  v-model="order.mapa.due_code"
                  icon="fas fa-file"
                  label="DU-E"
                  type="text"
                  error=""
                />
              </div>
              <div class="col-3">
                <iconinput
                  v-model="order.mapa.access_key"
                  icon="fas fa-file-key"
                  label="Access Key"
                  type="text"
                  error=""
                />
              </div>
              <div class="col-2">
                <iconinput
                  v-model="order.expiry_date"
                  icon="fas fa-calendar"
                  label="Expiration Date"
                  type="date"
                  error=""
                />
              </div>
              <div class="col-2">
                <label for="">&nbsp;</label><br />
                <button @click="Update()" class="btn btn-success">
                  Update
                </button>
              </div>
            </div>

            <table
              class="table table-sm"
              v-if="Filtered(transactions) && order"
            >
              <tr>
                <th>Imorter name</th>
                <th>Date</th>
                <th>Order</th>
                <th>Bank</th>
                <th>Transaction type</th>
                <th>Description</th>
                <th>Value NFE</th>
                <th>Value</th>
                <th>Dollar value</th>
                <th>Converted value</th>
                <th>Exchange variation</th>
                <th>Exchange contract</th>
                <th>&nbsp;</th>
              </tr>

              <tbody v-for="(transaction, index) in Filtered(transactions)">
                <tr>
                  <td>{{ transaction.owner.name }}</td>
                  <td>{{ dataCalc(transaction) }}</td>
                  <td>{{ order.number }}</td>
                  <td>{{ transaction.bank }}</td>
                  <td>{{ transaction.transaction_type.name }}</td>
                  <td>{{ transaction.description }}</td>
                  <td
                    v-if="
                      transaction.transaction_type_id != 2 &&
                      transaction.transaction_type_id != 5
                    "
                  >
                    R$
                    {{
                      FormatNumber(
                        NFEValue(transaction.value, main_dollar_value)
                      )
                    }}
                  </td>
                  <td
                    v-if="
                      transaction.transaction_type_id == 2 ||
                      transaction.transaction_type_id == 5
                    "
                  >
                    &nbsp;
                  </td>
                  <td>$ {{ FormatNumber(transaction.value || 0) }}</td>
                  <td>
                    $ {{ FormatDecimalNumber(transaction.dollar_value || 0) }}
                  </td>
                  <td>
                    R$ {{ FormatNumber(transaction.converted_value || 0) }}
                  </td>
                  <td
                    :class="
                      ExchangeVariation(
                        transaction.value,
                        transaction.dollar_value,
                        main_dollar_value
                      ) >= 0
                        ? ''
                        : 'red-color'
                    "
                  >
                    R$
                    {{
                      FormatNumber(
                        ExchangeVariation(
                          transaction.value,
                          transaction.dollar_value,
                          main_dollar_value
                        )
                      )
                    }}
                  </td>
                  <td>{{ transaction.cambio_contract }}</td>
                  <td>
                    <button
                      class="btn btn-sm btn-success"
                      @click="EditTransaction(index)"
                    >
                      Edit
                    </button>

                    <button
                      class="btn btn-sm btn-danger"
                      @click="DeleteTransaction(transaction,index)"
                      v-if="index == Filtered(transactions).length - 1"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="row">
              <div class="col">
                <p>
                  Current Balance: $ {{ FormatNumber(GetCurrentBalance()) }}
                </p>
                <p>Available Value: {{ TotalValue() }}</p>
              </div>
              <div class="col">
                <button
                  class="btn btn-success btn-sm float-right"
                  @click="AddNewTransaction"
                >
                  Add new transaction
                </button>
              </div>
            </div>

            <div class="modal fade" id="modal-new-transaction">
              <div class="modal-dialog modal-primary">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Add new transaction to Order</h4>
                    <button
                      type="button"
                      class="close"
                      data-dismiss="modal"
                      aria-hidden="true"
                    >
                      &times;
                    </button>
                  </div>
                  <div class="modal-body">
                    <select-icon
                      v-model="new_transaction.transaction_type_id"
                      name="transaction_type_id"
                    >
                      <option :value="null">Chose an option</option>
                      <option
                        v-for="(transaction_type, index) in transaction_types"
                        :value="transaction_type.id"
                      >
                        {{ transaction_type.name }}
                      </option>
                    </select-icon>
                    <iconinput
                      v-model="new_transaction.value"
                      icon="fas fa-money-bill-wave"
                      label="Value"
                      @input="CalcDollar"
                      type="number"
                      :error="IsNumeric(new_transaction.value)"
                    />
                    <iconinput
                      v-model="new_transaction.bank"
                      icon="fas fa-university"
                      label="Bank"
                    />
                    <iconinput
                      v-model="new_transaction.description"
                      icon="fas fa-edit"
                      label="Description"
                    />
                    <iconinput
                      v-model="new_transaction.reference"
                      icon="fas fa-edit"
                      label="Reference"
                    />
                    <iconinput
                      v-model="new_transaction.dollar_value"
                      icon="fas fa-money-bill-wave"
                      label="Dollar value"
                      @input="CalcDollar"
                      type="number"
                      :error="IsNumeric(new_transaction.dollar_value)"
                    />
                    <iconinput
                      v-model="new_transaction.converted_value"
                      icon="fas fa-money-bill-wave"
                      label="Converted value"
                      type="number"
                      :error="IsNumeric(new_transaction.converted_value)"
                    />
                    <iconinput
                      v-model="new_transaction.cambio_contract"
                      icon="fas fa-exchange-alt"
                      label="Exchange contract"
                    />
                    <iconinput
                      v-model="new_transaction.data"
                      icon="fas fa-calendar"
                      label="Transaction date"
                      type="date"
                    />
                  </div>
                  <div class="modal-footer">
                    <button
                      type="button"
                      class="btn btn-default"
                      data-dismiss="modal"
                    >
                      Cancel
                    </button>
                    <button
                      type="button"
                      class="btn btn-primary"
                      @click="SaveTransaction"
                      :disabled="HasError()"
                    >
                      Save
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer text-right">&nbsp;</div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.red-color {
  color: #c00;
}

tr td:first-child,
tr td:nth-child(4) {
  word-break: break-all;
}
</style>

<script>
export default {
  data() {
    return {
      main_dollar_value: 0,
      filter: null,
      order: null,
      transactions: [],
      transaction_types: [],
      new_transaction: {
        transaction_type_id: 2,
        value: 0,
        bank: null,
        description: null,
        reference:null,
        dollar_value: 0,
        converted_value: 0,
        cambio_contract: null,
      },
    };
  },
  methods: {
    Update: async function () {
      const nf = this.$data.order.nf;
      const expiry_date = this.$data.order.expiry_date;

      const params = {
        nf,
        expiry_date,
      };

      let updateResult = await this.$http.post(
        "/api/update-transaction/" + this.$route.params.id,
        params
      );
    },
    HasError: function () {
      return;
      // this.IsNumeric(this.$data.new_transaction.value) &&
      //   this.IsNumeric(this.$data.new_transaction.dollar_value) &&
      //   this.IsNumeric(this.$data.new_transaction.converted_value);
    },
    IsNumeric: function (value) {
      if (!value && value !== 0) {
        return ["Please insert a valid number like 1234.00"];
      }
    },
    GetCurrentBalance: function () {
      let transactions_sum = this.$data.transactions
        .filter((transaction) => {
          return transaction.transaction_type.display_to_importer == 1 || transaction.transaction_type.id == 9;
        })
        .map((transaction) => {
          return transaction.value;
        });

      let transactions_available_sum = this.$data.transactions
        .filter((transaction) => {
          return transaction.transaction_type.id == 8;
        })
        .map((transaction) => {
          return transaction.value;
        });

        if(!transactions_sum.length){
          return 0;
        }

        if(transactions_available_sum.length){
          transactions_available_sum = transactions_available_sum.reduce((acc, value) => {
            return acc + value;
          });
        }
        else{
          transactions_available_sum = 0;
        }

        transactions_sum = transactions_sum.reduce((acc, value) => {
          return acc + value;
        });
        console.log(transactions_sum);
      return Math.abs(transactions_sum)-transactions_available_sum;
    },

    AddNewTransaction: function () {
      this.$data.new_transaction = {
        transaction_type_id: 2,
        value: 0,
        bank: null,
        description: null,
        reference:null,
        dollar_value: 0,
        converted_value: 0,
        cambio_contract: null,
      };
      $("#modal-new-transaction").modal("show");
    },

    EditTransaction: function (index) {
      this.$data.new_transaction = JSON.parse(
        JSON.stringify(this.$data.transactions[index])
      );

      $("#modal-new-transaction").modal("show");
      this.$forceUpdate();
    },

    DeleteTransaction: async function (transaction,index) {
        let transactions = await this.$http.delete(
            "/api/balance/" + this.$route.params.id+"/"+transaction.id
        );

        if(transactions.status == 200){
            this.$data.transactions.splice(index, 1);
            this.$forceUpdate();
        }
    },

    CalcDollar: function () {
      this.$data.new_transaction.converted_value = (
        this.$data.new_transaction.value *
        this.$data.new_transaction.dollar_value
      ).toFixed(2);
    },

    FormatDate: function (date) {
      let [year, month, day, hour, minutes, seconds] = date.split(/[- :]/g);

      return month + "/" + day + "/" + year + " " + hour + ":" + minutes;
    },

    TotalValue: function () {
      let value = this.$data.transactions
        .filter((transaction) => {
          return transaction.transaction_type_id !== 1 && transaction.transaction_type_id !== 9;
        })
        .map((transaction) => {
          let value = transaction.value;
          if (
            transaction.transaction_type.id != 2 &&
            transaction.transaction_type.id != 5 &&
            transaction.transaction_type.id != 8
          ) {
            value = -value;
          }
          return value;
        });

      if (value.length > 0) {
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
      let value_fixed = value.toFixed(2);
      let [value_decimal, value_fraction] = value_fixed.split(/\./);

      value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");

      value_fixed = value_decimal + "," + value_fraction;
      return value_fixed;
    },

    FormatDecimalNumber: function (value) {

      let value_fixed = value.toFixed(4);
      let [value_decimal, value_fraction] = value_fixed.split(/\./);

      value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");

      value_fixed = value_decimal + "," + value_fraction;
      return value_fixed;
    },

    Filtered: function () {
      if (!this.$data.filter) return this.$data.transactions;

      return this.$data.transactions.filter((transaction) => {
        if (!transaction.description) return false;

        let description_has_text =
          transaction.description
            .toLowerCase()
            .indexOf(this.$data.filter.toLowerCase()) != -1;
        let bank_has_text =
          transaction.bank
            .toLowerCase()
            .indexOf(this.$data.filter.toLowerCase()) != -1;
        let transaction_type_has_text =
          transaction.transaction_type.name
            .toLowerCase()
            .indexOf(this.$data.filter.toLowerCase()) != -1;

        return (
          description_has_text || bank_has_text || transaction_type_has_text
        );
      });
    },
    
    ExchangeVariation: function (value, dollar_value, main_dollar_value) {
      if (!value || !dollar_value) return 0;
      return value * dollar_value - value * main_dollar_value;
    },
    NFEValue: function (value, main_dollar_value) {
      return value * main_dollar_value;
    },
    
    LoadTransactions: async function (date) {
      let order = await this.$http.get("/api/order/" + this.$route.params.id);
      let transactions = await this.$http.get(
        "/api/balance/" + this.$route.params.id
      );
      let transaction_types = await this.$http.get("/api/transaction-types");

      let initial_balance = transactions.body.filter((transaction) => {
        return transaction.transaction_type_id == 1;
      })[0];

      if (order.body.expiry_date) {
        order.body.expiry_date = order.body.expiry_date.split(/\s/)[0];
      }

      this.$data.order = order.body;
      this.$data.transactions = transactions.body;
      this.$data.transaction_types = transaction_types.body;
      this.$data.main_dollar_value = initial_balance.dollar_value;
    },

    SaveTransaction: async function () {
      let transactions = await this.$http.put(
        "/api/balance/" + this.$route.params.id,
        this.$data.new_transaction
      );
      if (!this.$data.new_transaction.id) {
        this.$data.transactions.push(transactions.body.transaction);
      }

      if (this.$data.new_transaction.id) {
        for (let i in this.$data.transactions) {
          if (this.$data.transactions[i].id == this.$data.new_transaction.id) {
            this.$data.transactions[i] = transactions.body.transaction;
            this.$forceUpdate();
            break;
          }
        }
      }
      $(".modal").modal("hide");
    },
    dataCalc(transaction) {
      let data = transaction.created_at.split(/\s/)[0];
      if (transaction.data) data = transaction.data;

      let [year, month, day] = data.split(/-/);
      return `${month}/${day}/${year}`;
    },
  },
  mounted: function () {
    this.LoadTransactions();
  },
};
</script>
