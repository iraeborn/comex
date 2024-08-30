<template>
  <div class="container-fluid nav-left">

    <div id="ui-view">

      <div class="panel panel-success">

        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">
                Report
              </div>
            </div>

          </div>
          <div class="card-body">

            <div class="row">
              <div class="col">
                <label for="">Importer</label>
                <vue-select2 class="form-control" name="select1" :options="importers" placeholder="Choose an importer"
                  v-model="importer">
                </vue-select2>

              </div>
              <div class="col">
                <label for="">Transaction type</label>

                <select class="form-control" v-model="transaction_type">
                  <option :value="null">Chose a transaction</option>
                  <option v-for="transaction_type in transaction_types" :value="transaction_type">{{ transaction_type.name
                  }}</option>
                </select>

              </div>

              <div class="col">
                <label for="">Initial Date</label>
                <input type="date" class="form-control" v-model="initial_date">
              </div>
              <div class="col">
                <label for="">Final Date</label>
                <input type="date" class="form-control" v-model="final_date">
              </div>
              <div class="col">
                <label for="">Filter</label>
                <input type="text" class="form-control" v-model="filter"
                  placeholder="PO/NFE/DU-E/Importer/Bank/Description">
              </div>

              <div class="col">
                <label for="">&nbsp;</label>
                <button class="btn btn-success btn-block" @click="ApplyFilters()">Filter</button>
              </div>
            </div>

            <div class="row" style="margin-top: 20px;">
              <div class="col">
                <div class="table-responsive">
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>PO</th>
                        <th>NFE</th>
                        <th>DU-E</th>
                        <th>Access Key</th>
                        <th>Importer name</th>
                        <th>Country</th>
                        <th>Weight (Kg)</th>
                        <th>Date</th>
                        <th>Bank</th>
                        <th>Transaction type</th>
                        <th>Description</th>
                        <th>Value NFE</th>
                        <th>Value</th>
                        <th>Dollar value</th>
                        <th>Converted value</th>
                        <th>Exchange variation</th>
                        <th>Exchange contract</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(transaction, index) in reports_filtered" :key="index">
                        <td>{{ transaction.order.number }}</td>
                        <td>{{ transaction.order.nf }}</td>
                        <td>{{ (transaction.order.mapa ? transaction.order.mapa.due_code : '') }}</td>
                        <td>{{ (transaction.order.mapa ? transaction.order.mapa.access_key : '') }}</td>
                        <td>{{ transaction.owner.name }}</td>
                        <td>{{ (transaction.owner && transaction.owner.country ? transaction.owner.country : '') }}</td>
                        <td>{{ (transaction.order ? transaction.qty_ton : 0) }}</td>
                        <td>{{ transactionDate(transaction.created_at) }}</td>
                        <td>{{ transaction.bank }}</td>
                        <td>{{ transaction.transaction_type.name }}</td>
                        <td>{{ transaction.description }}</td>
                        <td v-if="transaction.transaction_type_id != 2 && transaction.transaction_type_id != 5">R$ {{
                          FormatNumber(NFEValue(transaction.value, transaction.main_dollar_value)) }}</td>
                        <td v-if="transaction.transaction_type_id == 2 || transaction.transaction_type_id == 5">&nbsp;
                        </td>
                        <td>$ {{ FormatNumber(transaction.value || 0) }}</td>
                        <td>$ {{ FormatNumber(transaction.dollar_value || 0, 4) }}</td>
                        <td>R$ {{ FormatNumber(transaction.converted_value || 0) }}</td>
                        <td
                          :class="ExchangeVariation(transaction.value, transaction.dollar_value, transaction.main_dollar_value) >= 0 ? '' : 'red-color'">
                          R$
                          {{ FormatNumber(ExchangeVariation(transaction.value,
                            transaction.dollar_value, transaction.main_dollar_value)) }}
                        </td>
                        <td>{{ transaction.cambio_contract }}</td>
                      </tr>
                    </tbody>

                    <tr>
                      <th colspan="15" class="spacer"></th>
                    </tr>

                    <tr>
                      <th colspan="8">&nbsp;</th>
                      <th>Total:</th>
                      <th>R$ {{ FormatNumber(SumNFE(reports_filtered)) }}</th>
                      <th>$ {{ FormatNumber(SumValue(reports_filtered)) }}</th>
                      <th>&nbsp;</th>
                      <th>R$ {{ FormatNumber(SumConverted(reports_filtered)) }}</th>
                      <th>R$ {{ FormatNumber(SumVariation(reports_filtered)) }}</th>
                      <th>&nbsp;</th>
                    </tr>

                  </table>
                </div>
              </div>
            </div>

          </div>


          <div class="card-footer text-right">
          </div>
        </div>



      </div>

    </div>
  </div>
</template>

<style scoped>
.red-color {
  color: #c00;
}

thead th {
  min-width: 80px;
  word-break: normal;
}

tr td:nth-child(3),
tr td:nth-child(5) {
  word-break: break-all;
}
</style>

<script>
import reportValues from './../mixins/reportValues';
import moment from 'moment';

export default {

  mixins: [reportValues],

  data() {
    return {
      reports_original: null,
      reports_filtered: null,
      importer: null,
      transaction_type: null,
      initial_date: null,
      final_date: null,
      filter: null,
      transaction_types: [{
        id: 0,
        name: "",
      }],
      importers: null,
    }
  },
  methods: {
    SumNFE: function (reports) {
      let map = reports.map(report => {
        return report.value && this.NFEValue(report.value, report.main_dollar_value);
      })

      if (!map.length) return 0;

      return map.reduce((acc, value) => {
        return acc + value;
      })
    },
    SumValue: function (reports) {
      let map = reports.map(report => {
        return report.value;
      });

      if (!map.length) return 0;

      return map.reduce((acc, value) => {
        return acc + value;
      })
    },
    SumDollar: function (reports) {
      let map = reports.map(report => {
        return report.dollar_value;
      });

      if (!map.length) return 0;

      return map.reduce((acc, value) => {
        return acc + value;
      })
    },
    SumConverted: function (reports) {
      let map = reports.map(report => {
        return report.converted_value;
      })

      if (!map.length) return 0;

      return map.reduce((acc, value) => {
        return acc + value;
      })
    },
    SumVariation: function (reports) {
      let map = reports.map(report => {
        if (report.transaction_type_id === 3) {
          return this.ExchangeVariation(report.value, report.dollar_value, report.main_dollar_value);
        }
        return 0;
      })

      if (!map.length) return 0;

      return map.reduce((acc, value) => {
        return acc + value;
      })
    },
    ApplyFilters: function () {
      let reports_filtered = this.$data.reports_original;

      reports_filtered = this.FilterByImporter(reports_filtered, this.$data.importer)
      reports_filtered = this.FilterByTransactionType(reports_filtered, this.$data.transaction_type)
      reports_filtered = this.FilterByInitialDate(reports_filtered, this.$data.initial_date)
      reports_filtered = this.FilterByFinalDate(reports_filtered, this.$data.final_date)
      reports_filtered = this.FilterByTerm(reports_filtered, this.$data.filter)

      this.$data.reports_filtered = reports_filtered
    },
    FilterByImporter: function (reports, importer) {

      if (!importer || importer < 0) return reports;
      return reports.filter(report => {
        return report.owner.id == importer;
      });
    },
    FilterByTransactionType: function (reports, transaction_type) {
      if (!transaction_type) return reports;
      return reports.filter(report => {
        return report.transaction_type_id == transaction_type.id;
      });
    },
    FilterByInitialDate: function (reports, initial_date) {
      if (!initial_date) return reports;
      return reports.filter(report => {
        let date = moment(report.created_at);
        if (report.data) {
          date = moment(report.data);
        }

        let init_date = moment(initial_date);

        return date.isSameOrAfter(init_date);
      });
    },
    FilterByFinalDate: function (reports, final_date) {
      if (!final_date) return reports;
      return reports.filter(report => {
        let date = moment(report.created_at);
        if (report.data) {
          date = moment(report.data);
        }
        let end_date = moment(final_date);

        return date.isSameOrBefore(end_date);
      });
    },
    FilterByTerm: function (reports, term) {
      if (!term) return reports;
      term = term.toLowerCase();

      return reports.filter(report => {
        return (report.order && report.order.number ? report.order.number.toLowerCase().includes(term) : false) ||
          (report.order && report.order.nf ? report.order.nf.toLowerCase().includes(term) : false) ||
          (report.order && report.order.owner && report.order.owner.name ? report.order.owner.name.toLowerCase().includes(term) : false) ||
          (report.order && report.order.mapa && report.order.mapa.due_code ? report.order.mapa.due_code.toLowerCase().includes(term) : false) ||
          (report.bank ? report.bank.toLowerCase().includes(term) : false) ||
          (report.description ? report.description.toLowerCase().includes(term) : false) ||
          (report.cambio_contract ? report.cambio_contract.toLowerCase().includes(term) : false);
      });
    },
    FormatNumber: function (value, fixed = 2) {
      let value_fixed = value.toFixed(fixed);
      let [value_decimal, value_fraction] = value_fixed.split(/\./);

      value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");

      value_fixed = value_decimal + ',' + value_fraction;
      return value_fixed;
    },
    FormatDate: function (date) {
      if (!date) return 'Not informed';
      let [year, month, day, hour, minutes, seconds] = date.split(/[- :]/g);

      return month + "/" + day + "/" + year;
    },
    transactionDate(transaction) {
      return new Date(transaction).toLocaleDateString();

      // let data = transaction.created_at.split(/\s/)[0];
      // if (transaction.order && transaction.order.mapa && transaction.order.mapa.date_currency_fee) {
      //   data = transaction.order.mapa.date_currency_fee.split(/\s/)[0];
      // }
      // else if (transaction.data) {
      //   data = transaction.data;
      // }
      // else {
      //   data = transaction.created_at.split(/\s/)[0];
      // }


      // let [year, month, day] = data.split(/-/);
      // return `${month}/${day}/${year}`;
    },

  },
  mounted: async function () {
    let reports = await this.$http.get('/api/report');
    this.$data.reports_original = reports.body;
    this.ApplyFilters();

    let clients = await this.$http.get('/api/users/clients');
    clients = clients.body;
    let options = [{ id: -1, text: 'Choose an importer' }];

    clients.map(function (client) {
      //client.text = client.name;
      options.push({ id: client.id, text: client.name });
    });
    this.$data.importers = options;
    // clients.unshift({ id: '-1', text: 'Choose an importer' });
    // clients.unshift({ id: '', text: '' });

    let transaction_types = await this.$http.get('/api/transaction-types');
    this.$data.transaction_types = transaction_types.body;

  }
}
</script>
