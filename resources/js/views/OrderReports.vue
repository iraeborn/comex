<template>
    <div class="container-fluid">
        <div id="ui-view">
            <div class="panel panel-success">                    
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                Order Reports          
                            </div>
                            <div class="col-2">
                              <label for="initial_date">Initial Date</label>
                              <input type="date" class="form-control" id="initial_date" v-model="initial_date">
                            </div>
                            <div class="col-2">
                              <label for="">Final Date</label>
                              <input type="date" class="form-control" v-model="final_date">
                            </div>

                            <div :class="'col-' + (this.$data.filters.length + 1)">                                
                                <label>
                                    Filters
                                    <a href="#" @click="addFilter()">
                                        <i class="icon-plus" title="Add filter"></i>
                                    </a>
                                </label>
                                <div class="row" id="filters">
                                    <div class="col" v-for="(filter, index) in filters">
                                        <div class="filter" v-if="filters.length > 1">
                                            <i class="icon-close text-danger" @click="removeFilter(index)"></i>
                                            <input type="text" class="form-control" v-model="filters[index]" placeholder="Filter">
                                        </div>
                                        <input type="text" class="form-control" v-else v-model="filters[index]" placeholder="Filter">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <router-link class="nav-link-alt" :class="this.$route.query.status == 'first' || !this.$route.query.status ? 'active' : ''" :to="{ name: 'Order Reports', query: { status: 'first' } }">&emsp;First&emsp;</router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link class="nav-link-alt" :class="this.$route.query.status == 'second' ? 'active' : ''" :to="{ name: 'Order Reports', query: { status: 'second' } }">Second</router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link class="nav-link-alt" :class="this.$route.query.status == 'third' ? 'active' : ''" :to="{ name: 'Order Reports', query: { status: 'third' } }">Third</router-link>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <p class="text-center" v-if="!orders.length">{{ loading == false ? 'No records found' : 'Loading...' }}</p>
                            <table class="table table-sm" v-else>
                                <thead>
                                    <tr>
                                        <template v-if="this.$route.query.status == 'first' || !this.$route.query.status">
                                            <th>PO</th>
                                            <th>Booking</th>
                                            <th>Loading Location</th>
                                            <th>Unloading Location</th>
                                            <th>Product</th>
                                            <th>Quantity Kg</th>
                                            <th>Bill Number</th>
                                            <th>DU-E</th>
                                            <th>Access Key</th>
                                        </template>

                                        <template v-else-if="this.$route.query.status == 'second'">
                                            <th>PO</th>
                                            <th>Booking</th>
                                            <th>Loading Location</th>
                                            <th>Unloading Location</th>
                                            <th colspan="2">Unloading Date</th>
                                            <th>Product</th>

                                            <th>Weight</th>
                                            <th>Net Gross</th>
                                            <th>Bags Qty</th>

                                            <th>Packing</th>
                                            <th>Carrier</th>
                                            <th>Driver</th>
                                            <th>License Plate</th>
                                            <th>Phone Number</th>
                                            <th>Bills</th>
                                        </template>

                                        <template v-else-if="this.$route.query.status == 'third'">
                                            <th>PO</th>
                                            <th>Importer</th>
                                            <th>Quantity Kg</th>
                                            <th>NFE</th>
                                            <th>PTAX Date</th>
                                            <th>DU-E</th>
                                            <th>Access Key</th>
                                            <th>Value (R$)</th>
                                            <th>Value (US$)</th>
                                            <th>ETD</th>
                                        </template>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template v-if="this.$route.query.status != 'second'">
                                        <tr v-for="(order, index) in orders">
                                            <template v-if="$parent.$route.query.status == 'first' || !$parent.$route.query.status">
                                                <td>{{ order.number }}</td>
                                                <td>{{ (order.shipping[0] ? order.shipping[0].booking : '') }}</td>
                                                <td>{{ (order.loadings ? order.loadings.loading_location : '') }}</td>
                                                <td>{{ (order.loadings ? order.loadings.unloading_location : '') }}</td>
                                                <td>{{ (order.items[0] ? order.items[0].description : '') }}</td>
                                                <td>{{ (order.items[0] ? FormatNumber(order.items[0].net_weight) : '') }}</td>
                                                <td>{{ (order.mapa ? order.mapa.bill_of_lading : '') }}</td>
                                                <td>{{ (order.mapa ? order.mapa.due_code : '') }}</td>
                                                <td>{{ (order.mapa ? order.mapa.access_key : '') }}</td>
                                            </template>                                        

                                            <template v-else-if="$parent.$route.query.status == 'third'">
                                                <td>{{ order.number }}</td>
                                                <td>{{ (order.owner ? order.owner.name : '') }}</td>
                                                <td>{{ (order.items[0] ? FormatNumber(order.items[0].net_weight) : '') }}</td>
                                                <td>{{ (order.mapa ? order.mapa.nfe_key : '') }}</td>
                                                <td>{{ (order.loadings ? FormatDate(order.loadings.date_ptax) : '') }}</td>
                                                <td>{{ (order.mapa ? order.mapa.due_code : '') }}</td>
                                                <td>{{ (order.mapa ? order.mapa.access_key : '') }}</td>
                                                <td>{{ (order.items[0] ? FormatNumber(order.items[0].total_price * (order.loadings && order.loadings.tax_ptax ? order.loadings.tax_ptax : 1)) : '') }}</td>
                                                <td>{{ (order.items[0] ? FormatNumber(order.items[0].total_price) : '') }}</td>
                                                <td>{{ (order.shipping[0] ? FormatDate(order.shipping[0].etd) : '') }}</td>
                                            </template>
                                        </tr>                                    
                                    </template>

                                    <template v-else-if="this.$route.query.status == 'second'">
                                        <template v-for="(order, index) in orders" v-if="order.loadings && order.loadings.vehicles && order.loadings.vehicles.length">
                                            <tr v-for="truck in order.loadings.vehicles">
                                                <td>{{ order.number }}</td>
                                                <td>{{ (order.shipping[0] ? order.shipping[0].booking : '') }}</td>
                                                <td>{{ (order.loadings.loading_location) }}</td>
                                                <td>{{ (order.loadings ? order.loadings.unloading_location : '') }}</td>
                                                <td>{{ FormatDate(truck.truck_unloading_datetime) }}</td>
                                                <td>{{ FormatTime(truck.truck_unloading_datetime) }}</td>
                                                <td>{{ (order.items[0] ? order.items[0].description : '') }}</td>
                                                
                                                <td>{{ FormatNumber(truck.wheight) }}</td>
                                                <td>{{ truck.net_gross }}</td>
                                                <td>{{ (order.items[0] ? order.items[0].total_bags : '') }}</td>

                                                <td>{{ (order.full_packing) }}</td>
                                                <td>{{ (truck.carriers ? truck.carriers.name : '') }}</td>
                                                <td>{{ truck.driver || (truck.drivers ? truck.drivers.name : '') }}</td>
                                                <td>{{ truck.plate }}</td>
                                                <td>{{ (truck.drivers ? truck.drivers.phone : '') }}</td>
                                                <td>
                                                    <span v-for="(bill, index) in truck.bills" :key="index">
                                                        {{ bill.number }}
                                                    <br v-if="index < (truck.bills.length - 1)" />
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>

                                    <tr v-if="orders.length">
                                        <template v-if="this.$route.query.status == 'first' || !this.$route.query.status">
                                            <th colspan="5"> Total </th>
                                            <th>{{ FormatNumber(orders.reduce((acc, order) => { return acc + parseInt((order.items[0] ? order.items[0].net_weight : 0)); }, 0)) }} </th>
                                            <th colspan="3"> &nbsp; </th>
                                        </template>
                                        <template v-if="this.$route.query.status == 'second'">
                                            <th colspan="7"> Total </th>
                                            <th>
                                                {{ FormatNumber(orders.reduce((acc, order) => { 
                                                    return acc + parseInt((order.loadings && order.loadings.vehicles && order.loadings.vehicles.length ? order.loadings.vehicles.reduce((acc2, truck) => {
                                                            return acc2 + parseInt(truck.wheight);
                                                    }, 0) : 0)); }, 0)) 
                                                }} 
                                            </th>
                                            <th colspan="4"> &nbsp; </th>
                                        </template>
                                        <template v-if="this.$route.query.status == 'third'">
                                            <th colspan="2"> Total </th>
                                            <th>{{ FormatNumber(orders.reduce((acc, order) => { return acc + parseInt((order.items[0] ? order.items[0].net_weight : 0)); }, 0)) }} </th>
                                            <th colspan="4">&nbsp;</th>
                                            <th>{{ FormatNumber(orders.reduce((acc, order) => { return acc + parseInt((order.items[0] ? order.items[0].total_price : 0) * (order.loadings && order.loadings.tax_ptax ? order.loadings.tax_ptax : 1)); }, 0)) }}</th>
                                            <th>{{ FormatNumber(orders.reduce((acc, order) => { return acc + parseInt((order.items[0] ? order.items[0].total_price : 0)); }, 0)) }}</th>
                                            <th> &nbsp; </th>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer" v-if="orders.length">
                        <button class="btn btn-success btn-block" @click="generateReport()"> Export PDF </button>
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

/* .nav-link-alt {
    display: block;
    padding: .6rem 1rem;
}

.nav-tabs .nav-link-alt {
    border: 1px solid transparent;
    border-top-left-radius: .25rem;
    border-top-right-radius: .25rem;
    color: #73818f;
}

.nav-tabs .nav-link-alt:hover {
    cursor: pointer;
    border-color: #e4e7ea #e4e7ea #c8ced3;
    text-decoration: none;
} */

/* .nav-tabs .nav-link-alt.active {
    color: #23282c;
    background: #fff;
    border-color: #7ccc6e #7ccc6e transparent #7ccc6e;
} */

.filter { 
    position: relative; 
}

.filter .icon-close {
  position: absolute;
  padding: 10px;
  cursor: pointer;
  right: 0px;
}

.filter input { padding-right: 30px; }
</style>

<script>
import queryString from 'query-string'
import moment from 'moment'
import NProgress from 'nprogress'

export default {
    data() {
        return {
            orders: [],
            status: [],
            error: [],
            filters: [''],
            initial_date: '',
            final_date: '',
            filterTimeout: null,
            loading: null
        }
    },
    watch: {
        async '$route.query'() {
            this._filterOrders();
        },
        initial_date: function () {
            this._filterOrders();
        },
        final_date: function () {
            this._filterOrders();
        },
        filters: function () {
            this.filterOrders();
        }
    },
    methods: {        
        FormatNumber: function (value) {
          let value_fixed = parseFloat(value).toFixed(2);
          let [value_decimal, value_fraction] = value_fixed.split(/\./);

          value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

          value_fixed = value_decimal// + ',' + value_fraction;
          return value_fixed;

        },
        FormatNumberDecimal: function (value) {
          let value_fixed = parseFloat(value).toFixed(2);
          let [value_decimal, value_fraction] = value_fixed.split(/\./);

          value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

          value_fixed = value_decimal + '.' + value_fraction;
          return value_fixed;

        },
        FormatDate: function (date) {
            if(!date) return '';
            var d = new Date(date)
            var month = (d.getUTCMonth() + 1).toString()
            var day = d.getUTCDate().toString()
            var year = d.getUTCFullYear().toString()

            month   = month.length < 2   ? "0" + month : month
            day     = day.length < 2     ? "0" + day : day

            return month + "/" + day + "/" + year
        },
        FormatDateTime: function (date) {
            if(!date) return '';
            var d = new Date(date)
            var month = (d.getUTCMonth() + 1).toString()
            var day = d.getUTCDate().toString()
            var year = d.getUTCFullYear().toString()
            var hours = d.getUTCHours().toString()
            var minutes = d.getUTCMinutes().toString()
            var seconds = d.getUTCSeconds().toString()

            month   = month.length < 2   ? "0" + month : month
            day     = day.length < 2     ? "0" + day : day
            hours   = hours.length < 2   ? "0" + hours : hours
            minutes = minutes.length < 2 ? "0" + minutes : minutes
            seconds = seconds.length < 2 ? "0" + seconds : seconds

            return month + "/" + day + "/" + year + " " + hours + ":" + minutes
        },
        FormatTime: function (date) {
            if(!date) return '';
            var d = new Date(date)
            var hours = d.getUTCHours().toString()
            var minutes = d.getUTCMinutes().toString()
            var seconds = d.getUTCSeconds().toString()

            hours   = hours.length < 2   ? "0" + hours : hours
            minutes = minutes.length < 2 ? "0" + minutes : minutes
            seconds = seconds.length < 2 ? "0" + seconds : seconds

            return hours + ":" + minutes
        },

        DateToTimestamp(date) {
            if(!date) return 0;
            let [year, month, day] = date.split(/-/);
            let dt = new Date(year, month, day);
            return dt.getTime();
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
          let url = '/api/reports/reports/export?' + this.getQuery();

          this.generateFile(url, 'relatorio-' + this.$route.query.status);
        },

        getQuery: function () {
            let params = {
                status: this.$route.query.status || '',
                filters: this.filters || [],
                initial_date: this.initial_date || '',
                final_date: this.final_date || '',
            }

            return queryString.stringify(params, {arrayFormat: 'bracket'});       
        },

        _filterOrders: async function() {
            this.$data.orders = [];
            this.$data.loading = true;
            let response = await this.$http.get('/api/orders/reports?' + this.getQuery());
            
            if (response.body && response.body.orders) {
                this.$data.loading = false;
                let orders = response.body.orders;
                let status = response.body.status;
                let filters = response.body.filters;

                if (typeof orders == "object" && orders !== null) {
                    orders = Object.values(orders);
                }

                if (((!status && !this.$route.query.status) || status == this.$route.query.status) && ((!filters && !this.$data.filters) || JSON.stringify(filters.filter(Boolean).map(f => f.trim())) == JSON.stringify(this.$data.filters.filter(Boolean).map(f => f.trim())))) {
                    this.$data.orders = this.sortOrders(orders);
                }
            }
        },

        filterOrders: function () {
            clearTimeout(this.filterTimeout);
            this.filterTimeout = setTimeout(this._filterOrders, 500);
        },

        sortOrders: function (orders) {
            orders = orders.map(function(order) {
                let date = '';

                if (order.hasOwnProperty('loading_deadline') && (order.shipping[0] ? order.shipping[0].loading_deadline : '')) {
                    date = (order.shipping[0] ? order.shipping[0].loading_deadline : '');
                } else {
                    date = order.created_at;
                }

                order.date = date;

                return order;
            })

            orders = orders.sort(function (a, b) {
                let dateA = moment(a.date);
                let dateB = moment(b.date);

                if (dateA.isAfter(dateB)) {
                    return -1;
                } else if (dateB.isAfter(dateA)) {
                    return 1;
                }

                return 0;
            })

            return orders;
        },

        addFilter() {
            this.$data.filters.push('');
        },

        removeFilter(index) {
            this.$data.filters.splice(index, 1);
        },
    },
    mounted: async function () {
        this._filterOrders();
    },
}

</script>