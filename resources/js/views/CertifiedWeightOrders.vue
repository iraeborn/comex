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
            </ul>
        </div>
    </div>
  </div>
  <div class="card-body">
    <p class="text-center" v-if="!orders.length && !this.filter">No records found</p>
    <table class="table table-sm table-container" v-else>
        <tr>
            <th>Importer name</th>
            <th>Order date</th>
            <th>Order status</th>
            <th><input type="text" placeholder="Filter" class="form-control form-control-sm" v-model="filter" v-if="this.$route.query.status != 'filter'" /></th>
        </tr>
        <tr v-for="(order, index) in page_orders" v-if="order.document.length > 0">
            <td>{{ order.owner.name }}</td>
            <td>{{ FormatDate(order.created_at) }}</td>
            <td>{{ order.status.name }}</td>
            <td class="text-right">
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" :href='"#modal-" + index'>File</button>

                <div class="modal fade text-left" :id="'modal-' + index">
                    <div class="modal-dialog modal-success">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">File</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>
                            <div class="modal-body">
                                <popcorn-upload-new :allowed_pattern="/^application\/pdf$/" v-model="order.document[0].certificate_weight"></popcorn-upload-new>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" @click="Save(order)">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

            </td>
        </tr>
    </table>

    <popcorn-pagination v-model="page_orders" :items="orders"></popcorn-pagination>
  </div>
</div>



                </div>

            </div>
    </div>

</template>

<style>
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
        FormatDate: function (date) {
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
        Save: function ( order ) {
            let post_data = {
                order_id: order.id,
                document_url: order.document[0].certificate_weight,
            }

            this.$http.post('/api/orders-weight', post_data).then( e => {
                if (e.body.success) this.$toaster.success(e.body.success)
                if (e.body.error) this.$toaster.error('The document cannot be saved. Try again later!')
            })
        },
        _filterOrders: async function() {
            this.$data.orders = this.$data.page_orders = [];
            
            let response = await this.$http.get('/api/orders-weight?' + this.getQuery());

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