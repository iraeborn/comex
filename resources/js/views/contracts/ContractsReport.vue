<template>

  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Contracts Report</div>
            </div>
          </div>
          <div class="card-body">

            <div class="row">

              <div class="col">
                <label for="">Supplier</label>
                <select class="form-control" v-model="provider">
                  <option :value="null">Chose an supplier</option>
                  <option :key="provider.id" v-for="provider in providers" :value="provider">{{ provider.name }}</option>
                </select>
              </div>

              <div class="col">
                <label for="">Service Location</label>
                <input type="text" class="form-control" v-model="service_location">
              </div>

              <div class="col">
                <label for="">Service Type</label>
                <input type="text" class="form-control" v-model="service_type">
              </div>

              <div class="col">
                <label for="">Service Description</label>
                <input type="text" class="form-control" v-model="service_description">
              </div>

              <div class="col">
                <label for="">&nbsp;</label>
                <button class="btn btn-success btn-block" @click="applyFilters()">Filter</button>
                <button class="btn btn-danger btn-block" @click="clearFilters()">Clear Filter</button>
              </div>
            </div>

            <div class="row" style="margin-top: 20px;">
              <div class="col">

                <p class="text-center" v-if="!filteredContracts.length">No records found</p>

                <table class="table table-sm" v-if="filteredContracts.length">
                  <tr>
                    <th>Identifier</th>
                    <th>Supplier</th>
                    <th>Service Location</th>
                    <th>Service Type</th>
                    <th>Service Amount</th>
                    <th>Service Description</th>
                    <th>Service Factor</th>
                  </tr>
                  <tbody v-for="(contract, index) in page">
                    <tr v-for="(service, index) in contract.contract_services">
                      <td :rowspan="index == 0 ? contract.contract_services.length : 1" v-if=" index == 0 || index == undefined">
                        {{ contract.identifier }}
                      </td>
                      <td :rowspan="index == 0 ? contract.contract_services.length : 1" v-if=" index == 0 || index == undefined">
                        {{ contract.provider.name }}
                      </td>
                      <td :rowspan="index == 0 ? contract.contract_services.length : 1" v-if=" index == 0 || index == undefined">
                        {{ contract.service_location }}
                      </td>

                      <td :rowspan="index == 0 ? contract.contract_services.length : 1" v-if=" index == 0 || index == undefined">
                        {{ contract.service_type }}
                      </td>

                      <td>
                        {{ formatPrice(service.billing_value, service.currency_type) }}
                      </td>
                      <td>
                        {{ service.description }}
                      </td>
                      <td>
                        {{ service.factor_display_name }}
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <popcorn-pagination v-model="page" :items="filteredContracts"></popcorn-pagination>

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
            contracts: [],
            filteredContracts: [],
            provider: null,
            providers: null,
            service_description: null,
            service_type: null,
            service_location: null,
            page: [],
        }
    },

    methods: {

      applyFilters: function () {

        let filteredContracts = this.contracts;

        filteredContracts = this.filterByProvider(filteredContracts, this.provider);
        filteredContracts = this.filterByServiceLocation(filteredContracts, this.service_location);
        filteredContracts = this.filterByServiceType(filteredContracts, this.service_type);
        filteredContracts = this.filterByServiceDescription(filteredContracts, this.service_description);

        this.filteredContracts = filteredContracts;

      },

      clearFilters: function () {

        this.provider = null;
        this.service_description = null;
        this.service_type = null;
        this.service_location = null;

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

        let pattern = term.split("")
          .map((x)=>{
            return `(?=.*${x})`
          })
          .join("");

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

      filterByProvider: function (data, provider) {

        if (!provider) {
          return data;
        } 

        return data.filter( contract => {
          return contract.provider.name == provider.name;
        });

      },

      filterByServiceLocation: function (data, term) {

        if (! term) {
          return data;
        }

        return data.filter( contract => {
          return this.searchInStringUsingDefaultRegex(contract.service_location, term);
        } );

      },

      filterByServiceType: function (data, term) {

        if (! term) {
          return data;
        }

        return data.filter( contract => {
          return this.searchInStringUsingDefaultRegex(contract.service_type, term);
        } );

      },

      filterByServiceDescription: function (data, term) {

        if ( !term ) {
          return data;
        }

        return data.filter( contract => {

          let result = contract.contract_services.filter( service => {
            return this.searchInStringUsingDefaultRegex(service.description, term);
          } );

          return result.length > 0;
        } );

      },

      requestFactors: async function () {

        const result = await this.$http.get('/api/contracts/services/factor-types');

        if (!result.body.length) {
            return;
          }

          const factors = result.body.reduce((servicesFormatted, next) => {

              servicesFormatted[next.id] = next.text;

              return servicesFormatted;
          }, {});

        return factors;

      },

      getFactorTypeText: async function (index) {

        const factors = await this.requestFactors();

        const factor = factors[index];

        if (factor == undefined) {
          return "Undefined";
        }
        return factor;
      }
    },

    mounted: async function () {

      this.$http.get('/api/providers/contracts').then( e => {

          this.contracts = this.$data.page = e.body;

          this.contracts = this.$data.page = e.body.sort((a, b) => {

              if(!a.loading_deadline) return -1;
              if(!b.loading_deadline) return 1;

              return this.DateToTimestamp(a.loading_deadline) - this.DateToTimestamp(b.loading_deadline);
            });

            this.filteredContracts = this.contracts;

          });

      this.$http.get('/api/contracts/services/factor-types')
        .then(result => {

          if (!result.body.length) {
            return;
          }

          this.contract_services_factor_types = result.body.reduce((servicesFormatted, next) => {

              servicesFormatted[next.id] = next.text;

              return servicesFormatted;
          }, {});
        })
        .catch(error => console.error(error));


        const providersResponse = await this.$http.get('/api/users');
        this.providers = providersResponse.body;

    }
}

</script>
