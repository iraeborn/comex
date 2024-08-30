<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Provision</div>
            </div>
          </div>
          <div class="card-body">

            <div class="row justify-content-end">

              <div class="col-12 col-sm-6 col-md-4">
                <label for="">Supplier</label>
                <vue-select2 class="vue-select1 form-control" name="select1" :options="providers" placeholder="Choose a supplier" v-model="provider">
                </vue-select2>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <label for="">PO</label>
                <input type="text" class="form-control" v-model="order_number" />
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <label for="">Booking</label>
                <input type="text" class="form-control" v-model="booking" />
              </div>

              <div class="col-12 col-sm-6 col-md-4 mt-1">
                <label for="">Service Type</label>
                <input type="text" class="form-control" v-model="service_type">
              </div>

              <div class="col-12 col-sm-6 col-md-2 mt-1">
                <label for="">Status</label>
                <multiselect
                  v-model="status"
                  placeholder="Select a status"
                  track-by="id"
                  :options="provisions_status"
                  :custom-label="customLabel"
                  :show-labels="false"
                  :allow-empty="false"
                  >
                </multiselect>
              </div>

              <div class="col-12 col-sm-6 col-md-3 mt-1">
                <iconinput
                  label="Initial date:"
                  type="date"
                  v-model="initial_date"
                  id="initial_date"
                  name="initial_date"
                  icon="fas fa-calendar"
                />
              </div>

              <div class="col-12 col-sm-6 col-md-3 mt-1">
                <iconinput
                  label="Final date:"
                  type="date"
                  v-model="final_date"
                  id="final_date"
                  name="final_date"
                  icon="fas fa-calendar"
                />
              </div>

              <div class="col-12 col-sm-3 col-md-2 pt-2">
                <button class="btn btn-success btn-block" @click="applyFilters()">Filter</button>
              </div>

              <div class="col-12 col-sm-3 col-md-2 pt-2">
                <button class="btn btn-danger btn-block" @click="clearFilters()">Clear Filter</button>
              </div>

            </div>

            <div class="row" style="margin-top: 20px;">
              <div class="col">

                <p class="text-center" v-if="!provisions.length">No records found</p>

                <table class="table table-sm" v-if="provisions.length">
                  <tr>
                    <th>Contract</th>
                    <th>Supplier</th>
                    <th>PO</th>
                    <th>NFE</th>
                    <th>Booking</th>
                    <th>Service Type</th>
                    <th>Container Qty</th>
                    <th>Amount Dolar</th>
                    <th>Amount Real</th>
                    <th>Provision Status</th>
                    <th>Due Date</th>
                    <th> </th>
                  </tr>
                  <tbody :key="index" v-for="(provision, index) in provisions">
                    <tr v-if="provision.id">
                        <td>{{ provision.provider_contract.identifier }}</td>
                        <td>{{ (provision.provider_contract.provider && provision.provider_contract.provider.name ? provision.provider_contract.provider.name : '') }}</td>
                        <td>{{ provision.order.number }}</td>
                        <td>{{ provision.order.mapa ? split_nfe_key(provision.order.mapa.nfe_key) : '' }}</td>
                        <td>{{ provision.booking }}</td>
                        <td>{{ (provision.provider_contract.service ? provision.provider_contract.service.name : null) }}</td>
                        <td>{{ provision.quantity_container }}</td>
                        <td>{{ formatPrice(provision.dolar_budgeted_amount, 'USD') }}</td>
                        <td>{{ formatPrice(provision.real_budgeted_amount, 'BRL') }}</td>
                        <td>{{ provision.status_text }}</td>
                        <td>{{ FormatDate(provision.due_date) }}</td>

                        <td class="action-column text-right">
                          <div class="dropdown dropleft">
                              <button class="btn btn-secondary dropdown-toggle" type="button"
                                  data-toggle="dropdown" aria-expanded="false">
                              </button>
                              <div class="dropdown-menu">
                                <router-link :to="{ name: 'New Provision Posting', params: { id: provision.id } }"
                                  class="dropdown-item">
                                    Add
                                </router-link>
                                <button class="dropdown-item pendence" @click="deleteProvision(provision.id)">
                                  delete
                                </button>
                              </div>
                          </div>
                        </td>
                    </tr>
                  </tbody>

                  <tr>
                    <th colspan="12" class="spacer"></th>
                  </tr>

                  <tr>
                    <th colspan="6">&nbsp;</th>
                    <th>Total:</th>
                    <th> {{ formatPrice(sumProvisionDollarBudgetedAmount(provisions), 'USD') }}</th>
                    <th> {{ formatPrice(sumProvisionRealBudgetedAmount(provisions), 'BRL') }}</th>
                    <th colspan="3">&nbsp;</th>
                  </tr>

                </table>

              </div>
            </div>

          </div>
        </div>

        <button class="btn btn-success btn-block" @click="generateReport()"> Export PDF </button><br/>

      </div>
    </div>
  </div>
</template>

<style scoped>
.action-column {
  width: 121px;
}
.table .table {
  background-color: #fef101;
}
</style>

<script>

import currencyFormatter from './../../mixins/currencyFormatter';
import datesFormatter from './../../mixins/datesFormatter';
import Querystring from 'querystring';

export default {

    mixins: [ currencyFormatter, datesFormatter ],

    data() {
      return {
        provisions: [],
        provider: null,
        providers: null,
        service_type: null,
        order_number: null,
        booking: null,
        initial_date: null,
        final_date: null,
        status: {
            id: 1,
            name: 'A faturar'
          },
        provisions_status: [
          {
            id: 1,
            name: 'A faturar'
          },
          {
            id: 2,
            name: 'Faturado'
          } 
        ],
      }
    },

    methods: {

      customLabel ({ name }) {
        return `${name}`
      },

      deleteProvision: async function (provisionId) {
        try {
          const response = await this.$http.delete('/api/provisions/' + provisionId);
          response.status == 200 ? this.$toaster.success(response.body.success) : this.$toaster.error(response.body.error);
          this.applyFilters()
        } catch (error) {
          throw error;
        }
      },

      applyFilters: function () {
        this.$http.get('/api/contracts/provisions?' + this.getQuery()).then( response => {

          // this.provisions = response.body;

          this.provisions = response.body.sort((a, b) => {

            if(!a.loading_deadline) return -1;
            if(!b.loading_deadline) return 1;

            return this.DateToTimestamp(a.loading_deadline) - this.DateToTimestamp(b.loading_deadline);
          });
        });
      },

      clearFilters: function () {
        this.provider = null;
        this.order_number = null;
        this.booking = null;
        this.service_type = null;
        this.initial_date = null;
        this.final_date = null;
        this.status = { id: 0, name: 'A faturar' };

        this.applyFilters();
      },

      sumProvisionDollarBudgetedAmount: function (provisions) {

        let map = provisions.map( provision => {
          return provision.dolar_budgeted_amount;
        });

        if(!map.length) return 0;

        return map.reduce( (acc, value ) => {
          return acc + value;
        });
      },

      sumProvisionRealBudgetedAmount: function (provisions) {

        let map = provisions.map( provision => {
          return provision.real_budgeted_amount;
        });

        if(!map.length) return 0;

          return map.reduce( (acc, value ) => {
            return acc + value;
          });
      },

      getQuery() {
        let params = {
          filter: null,
          provider: this.provider,
          order_number: this.order_number,
          booking: this.booking,
          service_type: this.service_type,
          initial_date: this.initial_date,
          final_date: this.final_date,
          status: this.status.id
        };

        if (this.provider || this.order_number || this.booking || this.service_type || this.initial_date || this.final_date || this.status) {
          params.filter = true;
        }

        return Querystring.encode(params);
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
        let url = '/api/reports/provisions/export?' + this.getQuery();

        this.generateFile(url, 'relatorio-provisions');
      },

      FormatDate: function (date) {
        if (!date) {
          return null;
        }

        let [year, month, day, hour, minutes, seconds] = date.split(/[- :]/g);

        return month + "/" + day + "/" + year;
      },

      split_nfe_key: function (nfe_key) {
        if (nfe_key) {
            const parts = nfe_key.split(new RegExp(`[-;,/|]`));
            return parts.join('\n');
        }
        return '';
      },
    },

    created: function () {
      var today = new Date();

      today.setDate(today.getDate() + 2);
      var formattedToday = today.toISOString().split('T')[0];
      this.final_date = formattedToday;

      today.setDate(today.getDate() - 30);
      this.initial_date = today.toISOString().split('T')[0];
    },

    mounted: async function () {

      this.applyFilters();
      const providersResponse = await this.$http.get('/api/users');
      
      let providers = [];
      
      providersResponse.body.forEach(element => {
        providers.push({
          id:element.id,
          text:element.name
        })
      });

      providers.unshift({ id: '', text: '' });
      this.providers = providers;

    }
}

</script>
