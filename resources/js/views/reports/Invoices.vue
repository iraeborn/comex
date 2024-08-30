<template>

  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Invoices</div>
            </div>
          </div>
          <div class="card-body">

            <div class="row">

              <div class="col">
                <label for="">PO</label>
                <input type="text" class="form-control" v-model="order_number">
              </div>

              <div class="col">
                <label for="">Booking</label>
                <input type="text" class="form-control" v-model="booking">
              </div>

              <div class="col">
                <label for="">NFE</label>
                <input type="text" class="form-control" v-model="nfe">
              </div>

              <div class="col">
                <label for="">Supplier</label>
                <select class="form-control" v-model="provider">
                  <option :value="null">Chose an supplier</option>
                  <option :key="provider.id" v-for="provider in providers" :value="provider">{{ provider.name }}</option>
                </select>
              </div>

              <div class="col">
                <label for="">Invoice</label>
                <input type="text" class="form-control" v-model="invoice">
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
                <label for="">&nbsp;</label>
                <button class="btn btn-success btn-block" @click="applyFilters()">Filter</button>
                <button class="btn btn-danger btn-block" @click="clearFilters()">Clear Filter</button>
              </div>

            </div>

            <div class="row" style="margin-top: 20px;">
              <div class="col">

                <p class="text-center" v-if="!filteredRecords.length">No records found</p>

                <table class="table table-sm" v-if="filteredRecords.length">
                  <tr>
                    <th>PO</th>
                    <th>BOOKING</th>
                    <th>NFE</th>
                    <th>SUPPLIER</th>
                    <th>INVOICE</th>
                    <th>INVOICE AMOUNT REAL</th>
                    <th>INVOICE AMOUNT DOLAR</th>
                    <th>INVOICE CONVERTED AMOUNT</th>
                    <th>INVOICE CURRENCY FEE</th>
                    <th>INVOICE DUE DATE</th>
                  </tr>

                  <tbody v-for="(record, index) in filteredRecords">
                    <tr>
                      <td> {{ record.order_po }} </td> 
                      <td> {{ record.order_booking }} </td> 
                      <td> {{ record.order_nfe }} </td>
                      <td> {{ record.supplier_name }} </td> 
                      <td> {{ record.invoice_id }} </td> 
                      <td> {{ formatPrice(record.invoice_amount_real, 'BRL') }} </td> 
                      <td> {{ formatPrice(record.invoice_amount_dollar, 'USD') }} </td>
                      <td> {{ formatPrice(record.invoice_amount_converted, 'BRL') }} </td>
                      <td> {{ formatPrice(record.invoice_currency_fee, 'BRL') }} </td>
                      <td> {{ formatDate(record.invoice_due_date) }} </td>
                    </tr>
                  </tbody>

                  <tr>
                    <th colspan="10" class="spacer"></th>
                  </tr>

                  <tr>
                    <th colspan="4"> </th>
                    <th>Total:</th>
                    <th> {{ formatPrice(sumValues(filteredRecords, 'invoice_amount_real'), 'BRL') }} </th>
                    <th> {{ formatPrice(sumValues(filteredRecords, 'invoice_amount_dollar'), 'USD') }} </th>
                    <th> {{ formatPrice(sumValues(filteredRecords, 'invoice_amount_converted'), 'BRL') }} </th>
                  </tr>


                </table>

              </div>
            </div>

          </div>
        </div>
      </div>

      <button class="btn btn-success btn-block" @click="generateReport()"> Export PDF </button><br/>
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
import moment from 'moment';
import queryString from 'query-string';

export default {

    mixins: [ currencyFormatter, datesFormatter ],

    data() {
        return {
          filteredRecords: [],
          page: [],
          records: [],
          order_number: null,
          booking: null,
          nfe: null,
          invoice: null,
          provider: null,
          providers: null,
          initial_date: null,
          final_date: null
        }
    },

    methods: {

      applyFilters: function () {

        let filteredRecords = this.records;

        filteredRecords = this.filterByOrderNumber(filteredRecords, this.order_number);
        filteredRecords = this.filterByBooking(filteredRecords, this.booking);
        filteredRecords = this.filterByNFE(filteredRecords, this.nfe);
        filteredRecords = this.filterByProvider(filteredRecords, this.provider);
        filteredRecords = this.filterByInvoice(filteredRecords, this.invoice);
        filteredRecords = this.filterByInitialDate(filteredRecords, this.initial_date);
        filteredRecords = this.filterByFinalDate(filteredRecords, this.final_date);

        this.filteredRecords = filteredRecords;
      },

      clearFilters: function () {

        this.order_number = null;
        this.provider = null;
        this.initial_date = null;
        this.final_date = null;

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
        if (!string) {
          return false;
        }

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

        return data.filter( record => {
          return this.searchInStringUsingDefaultRegex(record.order_po, term);
        } );

      },

      filterByBooking: function (data, term) {
        if (!term) {
          return data;
        }

        return data.filter(record => {
          return this.searchInStringUsingDefaultRegex(record.order_booking, term);
        });
      },

      filterByNFE: function (data, term) {
        if (!term) {
          return data;
        }

        return data.filter(record => {
          return this.searchInStringUsingDefaultRegex(record.order_nfe, term);
        });
      },

      filterByProvider: function (data, provider) {

        if (!provider) {
          return data;
        } 

        return data.filter( record => {
          return record.supplier_name == provider.name;
        });

      },

      filterByInvoice: function (data, term) {
        if (!term) {
          return data;
        }

        return data.filter(record => {
          return this.searchInStringUsingDefaultRegex(record.invoice_id, term);
        });
      },

      filterByInitialDate: function (records, initial_date) {
        if (!initial_date) return records;
        const filtered = records.filter( record => {
          let date = moment(record.invoice_due_date);
          let init_date = moment(initial_date);

          return date.isSameOrAfter(init_date);
        });

        return filtered
      },

      filterByFinalDate: function (records, final_date) {
        if (!final_date) return records;
        return records.filter( record => {
          let date = moment(record.invoice_due_date);
          let end_date = moment(final_date);

          return date.isSameOrBefore(end_date);
        });
      },

      sumValues: function (records, column) {

        let map = records.map( record => {
          if (record[column] == null) {
            return 0;
          }
          return parseFloat(record[column]);
        });

        if (!map) return 0;

        if(!map.length) return 0;

        let total = map.reduce( (acc, value ) => {
          return acc + value;
        });

        return total.toFixed(2);

      },

      forceFileDownload(response, name){

        let filename = name + '.pdf';
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', filename)
        document.body.appendChild(link)
        link.click()

      },

      generateFile: function (url, name) {

        this.$http({
            method: 'get',
            url: url,
            responseType: 'arraybuffer'
        })
        .then(response => {
          this.forceFileDownload(response, name)  
        })
        .catch(() => console.log('error occured'));

      },

      generateReport: function () {
        let url = '/api/reports/invoices/export?' + this.getQuery();

        this.generateFile(url, 'relatorio-invoices');
      },

      getQuery: function () {
          let params = {
              order: this.order_number,
              booking: this.booking,
              nfe: this.nfe,
              provider: (this.provider ? this.provider.name : null),
              invoice: this.invoice,
              initial_date: this.initial_date,
              final_date: this.final_date,
          }

          return queryString.stringify(params, {arrayFormat: 'bracket'});       
      },
    },

    mounted: async function () {

      this.$http.get('/api/reports/invoices')
        .then( result => {

          this.records = this.page = result.body;

          this.records = this.page = result.body.sort((a, b) => {

              if(!a.loading_deadline) return -1;
              if(!b.loading_deadline) return 1;

              return this.DateToTimestamp(a.loading_deadline) - this.DateToTimestamp(b.loading_deadline);
            });

            this.filteredRecords = this.records;

        });

      const providersResponse = await this.$http.get('/api/users');
      this.providers = providersResponse.body;

    }
}

</script>
