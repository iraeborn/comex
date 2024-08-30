<template>

  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">DRE Operssations</div>
            </div>
          </div>
          <div class="card-body">

            <div class="row">

              <div class="col">
                <label for="">PO</label>
                <input type="text" class="form-control" v-model="order_number">
              </div>

              <div class="col">
                <label for="">Start Date</label>
                <input type="date" class="form-control" v-model="start_date">
              </div>

              <div class="col">
                <label for="">End Date</label>
                <input type="date" class="form-control" v-model="end_date">
              </div>

            </div>

            <div class="row">

              <div class="col">
                <label for="">&nbsp;</label>
                <button class="btn btn-success btn-block" @click="applyFilters()">Filter</button>
                <button class="btn btn-danger btn-block" @click="clearFilters()">Clear Filter</button>
                <button class="btn btn-info btn-block" @click="generateReport()" v-if="page.length"> Export to Excel </button>
              </div>

            </div>

            <div class="row" style="margin-top: 20px;">
              <div class="col">

                <p class="text-center" v-if="!page.length">No records found</p>

                <table class="table table-sm" v-if="page.length">
                  <tr>
                    <th>PO</th>
                    <th>Tons</th>
                    <th>Containers</th>
                    <th>Total Expenses (BRL)</th>
                    <th>Total Expenses (USD)</th>
                    <th>Expense per ton</th>
                  </tr>

                  <tbody v-for="(record, index) in page">
                    <tr>
                      <td> {{ record.po }} </td> 
                      <td> {{ record.qty_ton }} </td>
                      <td> {{ record.qty_container }} </td>
                      <td> {{ formatPrice(record.total_expenses, 'BRL') }} </td>
                      <td> {{ formatPrice(record.total_expenses_usd, 'USD') }} </td>  
                      <td> {{ formatPrice(record.expense_per_ton, 'BRL') }} </td>
                    </tr>
                  </tbody>

                  <tr>
                    <th colspan="6" class="spacer"></th>
                  </tr>

                  <tr>
                    <th>Total:</th>
                    <th> {{ sumTons(page) }} </th>
                    <th> {{ sumContainers(page) }} </th>
                    <th> {{ formatPrice(sumExpenses(page), 'BRL') }} </th>
                    <th> {{ formatPrice(sumExpenses(page,'usd'), 'USD') }} </th>
                    <th> {{ formatPrice(sumExpensesPerTon(page), 'BRL') }} </th>
                  </tr>


                </table>

              </div>
            </div>

            <popcorn-pagination v-model="page" :items="filteredRecords"></popcorn-pagination>

          </div>
        </div>
      </div>
    </div>
  </div>


</template>

<style scoped>
.table .table {
  background-color: #fef101;
}
</style>

<script>

import currencyFormatter from './../../mixins/currencyFormatter';
import datesFormatter from './../../mixins/datesFormatter';

export default {

    mixins: [ currencyFormatter, datesFormatter ],

    data() {
        return {
          filteredRecords: [],
          page: [],
          records: [],
          order_number: null,
          start_date: null,
          end_date: null
        }
    },

    methods: {

      applyFilters: function () {

        let filteredRecords = this.records;

        filteredRecords = this.filterByOrderNumber(filteredRecords, this.order_number);
        this.filteredRecords = filteredRecords;

      },

      clearFilters: function () {

        this.order_number = null;

        this.applyFilters();

      },

      clearStringTerm: function (string) {

        string = string.toLowerCase();
        string = string.replace(new RegExp('[ÁÀÂÃ]','gi'), 'a');
        string = string.replace(new RegExp('[ÉÈÊ]','gi'), 'e');
        string = string.replace(new RegExp('[ÍÌÎ]','gi'), 'i');
        string = string.replace(new RegExp('[ÓÒÔÕ]','gi'), 'o');
        string = string.replace(new RegExp('[ÚÙÛ]','gi'), 'u');
        string = string.replace(new RegExp('[Ç]','gi'), 'c');

        return string;
      },

      createSearchRegexPatern: function (term) {

        let pattern = `(?=.*${term})`;

        return new RegExp(`${pattern}`, "g");
      },

      searchInStringUsingDefaultRegex: function (string, term) {

        let clearedTerm = this.clearStringTerm(term);

        let regex = this.createSearchRegexPatern(clearedTerm);

        let result = string.toLowerCase().match(regex) || [];

        if (result.length > 0) {
          return true;
        }

          return false;

      },

      filterByOrderNumber: function (data, term) {

        if (! term) {
          return data;
        }

        return data.filter( order => {
          return this.searchInStringUsingDefaultRegex(order.po, term);
        } );

      },

      sumTons: function (filteredRecords) {

        let map = filteredRecords.map( record => {
          return record.qty_ton;
        });

        if(!map.length) return 0;

        return map.reduce( (acc, value ) => {
          return acc + value;
        });
      },

      sumContainers: function (filteredRecords) {

        let map = filteredRecords.map( record => {
          if (record.qty_container == null) {
            return 0;
          }
          return parseInt(record.qty_container);
        });

        if(!map.length) return 0;

        return map.reduce( (acc, value ) => {
          return acc + value;
        });
      },

      sumExpenses: function (filteredRecords,currency_type = '') {

        let map = filteredRecords.map( record => {

          let value = record['total_expenses'+(currency_type ? '_'+currency_type : '')];
          
          if (value == null) {
            return 0;
          }
          return parseFloat(value);
          
        });

        if(!map.length) return 0;

        let total = map.reduce( (acc, value ) => {
          return acc + value;
        });

        return total.toFixed(2);
      },


      sumExpensesPerTon: function (filteredRecords) {

        let map = filteredRecords.map( record => {
          if (record.expense_per_ton == null) {
            return 0;
          }
          return parseFloat(record.expense_per_ton);
        });

        if(!map.length) return 0;

        let total = map.reduce( (acc, value ) => {
          return acc + value;
        });

        return total.toFixed(2);
      },

      forceFileDownload(response, type){
        let filename = type + '.xls';
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', filename)
        document.body.appendChild(link)
        link.click()

      },

      generateFile: function (url, type) {

        this.$http({
            method: 'get',
            url: url,
            responseType: 'arraybuffer'
        })
        .then(response => {
          this.forceFileDownload(response, type)  
        })
        .catch(() => console.log('error occured'));

      },

      generateReport: function () {

        let objectParams = {
          start_date: this.start_date,
          end_date: this.end_date,
          order_number: this.order_number,
        };

        Object.keys(objectParams).forEach((key) => ( (objectParams[key] == null) || (objectParams[key] == '') ) && delete objectParams[key]);
        const params = new URLSearchParams(objectParams);
        const url = `/api/reports/dre/export?${params}`;

        this.generateFile(url, 'relatorio-dre');

      },

      generateExcelReport(){

      }

    },

    mounted: async function () {

      this.$http.get('/api/reports/dre')
        .then( result => {

          this.records = this.page = result.body;

          this.records = this.page = result.body.sort((a, b) => {

              if(!a.loading_deadline) return -1;
              if(!b.loading_deadline) return 1;

              return this.DateToTimestamp(a.loading_deadline) - this.DateToTimestamp(b.loading_deadline);
            });

            this.filteredRecords = this.records;

        });

    }
}

</script>
