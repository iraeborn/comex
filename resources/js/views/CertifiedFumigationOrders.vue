<template>
    <div class="container-fluid">

            <div id="ui-view">

                <div class="panel panel-success">
                    
<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col">
            Orders
        </div>        
        <div class="col-4">
            <input class="form-control form-control-sm" v-model="globalFilter" placeholder="Search..."/>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'all' || !this.$route.query.status ? 'active' : ''" :to="{ name: 'All orders', query: { status: 'all' } }">&emsp;All&emsp;</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'pending' ? 'active' : ''" :to="{ name: 'All orders', query: { status: 'pending' } }">Booking Pendence</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'loading' ? 'active' : ''" :to="{ name: 'All orders', query: { status: 'loading' } }">In Process Loading</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'release' ? 'active' : ''" :to="{ name: 'All orders', query: { status: 'release' } }">In Process Release</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'aftership' ? 'active' : ''" :to="{ name: 'All orders', query: { status: 'aftership' } }">After Ship</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'missing_docs' ? 'active' : ''" :to="{ name: 'All orders', query: { status: 'missing_docs' } }">Missing Docs</router-link>
                </li>
            </ul>
        </div>
    </div>
  </div>
  <div class="card-body">
    <p class="text-center" v-if="!orders.length && !this.filter">No records found</p>
    <table class="table table-sm table-container" v-else>
        <tr>
            <template v-if="this.$route.query.status != 'missing_docs'">
                <th>Order number</th>
                <th>Importer name</th>
                <th>Order date</th>
                <th>Order status</th>
            </template>

            <template v-else>
                <th>Order number</th>
                <th>Booking</th>
                <th>&nbsp;</th>
                <th>BL</th>
                <th>Fumigation cert.</th>
            </template>
            <th><input type="text" placeholder="Filter" class="form-control form-control-sm" v-model="filter" v-if="this.$route.query.status != 'filter'" /></th>
        </tr>
        <tbody v-for="(order, index) in page_orders">
            <template v-if="$parent.$route.query.status != 'missing_docs'">
                <tr v-if="order.owner">
                    <td>{{ order.number }}</td>
                    <td>{{ order.owner.name }}</td>
                    <td>{{ FormatDate(order.created_at) }}</td>
                    <td>{{ GetStatus(order) }}</td>
                    <td class="text-right">
                        <router-link :to="{ name: 'Order information', params: { id: order.id } }" class="btn btn-primary btn-sm" v-if="order.order_status_id == 3">Order information</router-link>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="5" v-if="order.booking">
                        <table class="table table-sm table-amarelo">
                            <tr>
                                <td>Booking</td>
                                <td style="width: 133px;">Cntrs Amount</td>
                                <td style="width: 133px;">M.A.P.A</td>
                                <td style="width: 133px;">Fumigation</td>
                                <td style="width: 133px;">Aeração</td>
                                <td style="width: 133px;">Draft Deadline</td>
                                <td style="width: 133px;">Loading Deadline</td>
                            </tr>
                            <tr>
                                <td>{{ order.booking }}</td>
                                <td>{{ order.cntrs_amount }}</td>
                                <td>{{ FormatDate(order.mapa) }}</td>
                                <td>{{ FormatDateTime(order.fumigation.init) }}</td>
                                <td>{{ FormatDateTime(order.fumigation.init) }}</td>
                                <td>{{ FormatDate(order.draft_deadline) }}</td>
                                <td>{{ FormatDate(order.loading_deadline) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </template>

            <template v-else>
                <tr v-if="order.owner">
                    <td>{{ order.number }}</td>
                    <td>{{ order.booking }}</td>
                    <td>
                        <span class="btn disabled">Draft</span><br/><br/>
                        <span class="btn disabled">Original</span>
                    </td>
                    <td class="text-center">
                        <a v-if="order.drafts && order.drafts.draft_bl" class="btn btn-success" :href="order.drafts.draft_bl" target="_blank" title="View document"><i class="fas fa-file"></i></a>
                        <span v-else-if="order.required_docs && order.required_docs.draft_bl" class="btn btn-danger disabled" title="Document required"><i class="fas fa-times"></i></span>
                        <span v-else class="btn btn-info disabled" title="Document missing"><i class="fas fa-minus"></i></span><br/><br/>

                        <a v-if="order.originals && order.originals.bl" class="btn btn-success" :href="order.originals.bl" target="_blank" title="View document"><i class="fas fa-file"></i></a>

                        <span v-else-if="order.required_docs && order.required_docs.draft_bl" class="btn btn-danger disabled" title="Document required"><i class="fas fa-times"></i></span>
                        <span v-else class="btn btn-info disabled" title="Document missing"><i class="fas fa-minus"></i></span>
                    </td>
                    <td class="text-center">
                        <a v-if="order.drafts && order.drafts.certificate_fumigation" class="btn btn-success" :href="order.drafts.certificate_fumigation" target="_blank" title="View document"><i class="fas fa-file"></i></a>
                        <span v-else-if="order.required_docs && order.required_docs.certificate_fumigation" class="btn btn-danger disabled" title="Document required"><i class="fas fa-times"></i></span>
                        <span v-else class="btn btn-info disabled" title="Document missing"><i class="fas fa-minus"></i></span><br/><br/>

                        <a v-if="order.originals && order.originals.certificate_fumigation" class="btn btn-success" :href="order.originals.certificate_fumigation" target="_blank" title="View document"><i class="fas fa-file"></i></a>

                        <span v-else-if="order.required_docs && order.required_docs.certificate_fumigation" class="btn btn-danger disabled" title="Document required"><i class="fas fa-times"></i></span>
                        <span v-else class="btn btn-info disabled" title="Document missing"><i class="fas fa-minus"></i></span>
                    </td>
                    <td class="text-right">
                        <router-link :to="{ name: 'Order information', params: { id: order.id } }" class="btn btn-primary btn-sm" v-if="order.order_status_id == 3">Order information</router-link>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>

    <popcorn-pagination v-model="page_orders" :items="orders"></popcorn-pagination>
  </div>
</div>



                </div>

            </div>
    </div>

</template>

<style scoped>
    /* .nav-link-alt {
        display: block;
        padding: .6rem 1rem;
    }

    .nav-tabs .nav-link-alt {
        border: 1px solid transparent;
        border-top-left-radius: .25rem;
        border-top-right-radius: .25rem;
        color: #73818f;
    } */

    /* .nav-tabs .nav-link-alt:hover {
        cursor: pointer;
        border-color: #e4e7ea #e4e7ea #c8ced3;
        text-decoration: none;
    } */

    /* .nav-tabs .nav-link-alt.active {
        color: #23282c;
        background: #fff;
        border-color: #7ccc6e #7ccc6e transparent #7ccc6e;
    } */

    .btn.disabled {
        opacity: 1 !important;
    }
</style>

<script>
import moment from 'moment'
import Querystring from 'querystring'

export default {
    data() {
        return {
            orders: [],
            page_orders: [],
            status: [],
            error: [],
            filter: '',
            filterTimeout: null,
            globalFilter: '',
            lastStatus: '',
            document_status: [
                {name: 'In analysis', id: '1'},
                {name: 'Rejected',    id: '2'},
                {name: 'Approved',     id: '3'}
            ],
        }
    },
    watch: {        
        async '$route.query'() {
            this.filter = '';
            this.globalFilter = '';
            this.lastStatus = this.$route.query.status || "all";
            this._filterOrders();
        },
        filter: function () {
            this.globalFilter = '';
            this.filterOrders();
        },
        globalFilter: function () {
            this.filter = '';
            this.$route.query.status = this.globalFilter ? 'filter' : this.lastStatus;
            this.filterOrders();
        }
    },
    methods: {
        GetStatus: function (order) {
            return order.status.name
        },
        FormatInspectionDate: function (date) {
            if(!date) return "Not informed";
            let data = date.split(/-/);
            return data[1] + '/' + data[2] + '/' + data[0];
        },
        FormatDate: function (date) {
            if(!date) return "Not informed";
            let [year, month, day, hours, minutes] = date.split(/[- :]/g);
            return month + "/" + day + "/" + year
        },
        FormatDateTime: function (date) {
            if(!date) return "Not informed";
            let [year, month, day, hours, minutes] = date.split(/[- :]/g);

            return month + "/" + day + "/" + year + " " + hours + ":" + minutes
        },
        Save: function ( order ) {
            let post_data = {
                order_id: order.id,
                document_url: order.document[0].certificate_fumigation,
            }

            this.$http.post('/api/orders-fumigation', post_data).then( e => {
                if (e.body.success) this.$toaster.success(e.body.success)
                if (e.body.error) this.$toaster.error('The document cannot be saved. Try again later!')
            })
        },

        _filterOrders: async function() {
            this.$data.orders = this.$data.page_orders = [];
            
            let response = await this.$http.get('/api/orders-fumigation?' + this.getQuery());

            if (response.body && response.body.orders) {
                let orders = response.body.orders;
                let filter = response.body.filter;

                if (typeof orders == "object" && orders !== null) {
                    orders = Object.values(orders);
                }

                if ((!filter && !this.$data.filter && !this.$data.globalFilter) || filter == this.$data.filter || filter == this.$data.globalFilter) {
                    this.$data.orders = this.$data.page_orders = this.sortOrders(orders);
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

                if (order.hasOwnProperty('loading_deadline') && order.loading_deadline) {
                    date = order.loading_deadline;
                } else {
                    date = order.created_at;
                }

                order.date = date;

                return order;
            })

            var reverse = this.$route.query.status == 'missing_docs';

            orders = orders.sort(function (a, b) {
                let dateA = moment(a.date);
                let dateB = moment(b.date);

                if (reverse) {
                    if (dateA.isAfter(dateB)) {
                        return 1;
                    } else if (dateB.isAfter(dateA)) {
                        return -1;
                    }
                } else {
                    if (dateA.isAfter(dateB)) {
                        return -1;
                    } else if (dateB.isAfter(dateA)) {
                        return 1;
                    }
                }

                return 0;
            })

            return orders;
        },        

        getQuery: function () {
            let params = {
                status: this.$route.query.status || "all",
                filter: this.globalFilter || (this.filter || '')
            }

            return Querystring.encode(params);       
        },
    },
    mounted: async function () {
        this._filterOrders();
    }
}

</script>