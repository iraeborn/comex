<template>
    <div class="container-fluid">
        <div id="ui-view">
            <div class="panel panel-success">                    
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                Railway Orders            
                            </div>

                            <div class="col-2">
                              <label for="">Initial Date</label>
                              <input type="date" class="form-control" v-model="initial_date">
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
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <p class="text-center" v-if="!orders.length">No records found</p>       
                            <table class="table table-sm" v-else>
                                <thead>
                                    <tr>
                                        <th>PO</th>
                                        <th>Dispatch Place</th>
                                        <th>Vessel</th>
                                        <th>Booking</th>
                                        <th>Cont. Type</th>
                                        <th>Qty Cont.</th>
                                        <th>Qty Kg</th>
                                        <th>Free Time</th>
                                        <th>Loading DL</th>
                                        <th>Empty Pickup</th>
                                        <th>Empty Loading</th>
                                        <th>Rondo Arrival</th>
                                        <th>Stuffing Start</th>
                                        <th>Stuffing End</th>
                                        <th>Fumigation</th>
                                        <th>Aeration</th>
                                        <th>Mapa</th>
                                        <th>Train Leave</th>
                                        <th>Cesari Arrival</th>
                                        <th>Port Delivery</th>
                                        <th style="min-width: 235px;"> &nbsp; </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(order, index) in page_orders">
                                        <td>{{ order.number }}</td>
                                        <td>{{ (order.container_stuffing ? order.container_stuffing.dispatch_place_name : '') }}</td>
                                        <td>{{ (order.shipping[0] ? order.shipping[0].vessel : '') }}</td>
                                        <td>{{ (order.shipping[0] ? order.shipping[0].booking : '') }}</td>
                                        <td>{{ (order.container_stuffing ? order.container_stuffing.container_type : '') }}</td>
                                        <td>{{ (order.container_stuffing ? order.container_stuffing.qtd_container : '') }}</td>
                                        <td>{{ (order.items[0] ? FormatNumber(order.items[0].net_weight) : '') }}</td>
                                        <td>{{ (order.shipping[0] ? order.shipping[0].free_time_origin : '') }}</td>
                                        <td><span :class="order.dateClasses.loading">{{ (order.shipping[0] ? FormatDate(order.shipping[0].loading_deadline) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.withdrawal">{{ (order.railroads ? FormatDate(order.railroads.withdrawal_date) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.start">{{ (order.railroads ? FormatDate(order.railroads.start_date) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.arrival">{{ (order.railroads ? FormatDate(order.railroads.arrival_date) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.stuffing_start">{{ (order.container_stuffing ? FormatDate(order.container_stuffing.stuffing_starting_date) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.stuffing_end">{{ (order.container_stuffing ? FormatDate(order.container_stuffing.stuffing_ending_date) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.fumigation">{{ (order.fumigation ? FormatDateTime(order.fumigation.init) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.aeration">{{ (order.fumigation ? FormatDateTime(order.fumigation.end) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.mapa">{{ (order.mapa ? FormatDate(order.mapa.inspection_date) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.withdrawal_start">{{ (order.railroads ? FormatDate(order.railroads.withdrawal_start_date) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.estimated">{{ (order.railroads ? FormatDate(order.railroads.estimated_time) : '') }}</span></td>
                                        <td><span :class="order.dateClasses.transfer">{{ (order.railroads ? FormatDate(order.railroads.transfer_terminal_date) : '') }}</span></td>
                                        <!-- <td :style="order.freeTimeLimit && order.freeTimeLimit.isBefore(moment()) ? 'color: red' : ''"><span :class="order.dateClasses.free_time">{{ (order.container_stuffing && order.freeTimeLimit ? order.freeTimeLimit.format('MM/DD/YYYY') : '') }}</span></td> -->
                                        <td class="text-right">
                                            <div class="row mb-1">
                                                <div class="col p-0 pl-2 pr-1">
                                                    <router-link :to="{ name: 'Order information', params: { id: order.id } }" class="btn btn-primary btn-sm btn-block" v-if="order.status.id == 3 || order.status.information == true">Order information</router-link>
                                                </div>
                                                <div class="col p-0 pl-1 pr-2">
                                                    <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" :href='"#modal-view-" + index' v-if="">Documents</button>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col p-0 pl-2 pr-1">
                                                    <router-link :to="{ name: 'New order', query: { copy_id: order.id } }" class="btn btn-warning btn-sm btn-block">Copy</router-link>
                                                </div>
                                                <div class="col p-0 pl-1 pr-1">
                                                    <router-link :to="{ name: 'Edit order', params: { id: order.id } }" class="btn btn-success btn-sm btn-block">Edit</router-link>
                                                </div>
                                                <div class="col p-0 pl-1 pr-2">
                                                    <button type="button" class="btn btn-danger btn-sm btn-block" data-toggle="modal" :href='"#modal-" + index'>Delete</button>
                                                </div>
                                            </div>

                                            <div class="modal fade text-left" :id="'modal-' + index">
                                                <div class="modal-dialog modal-danger">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Are you sure?</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Deleting the request will no longer be possible to see your data in the panel and is an irreversible action.</p>
                                                            <p>Are you sure you want to continue?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-danger" @click="Exclude(order.id)">Confirm exclusion</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="modal fade text-left" :id="'modal-view-' + index">
                                                <div class="modal-dialog">
                                                    <div class="modal-content modal-lg">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">View</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                        </div>
                                                        <div class="modal-body">

                                                            <div class="row">
                                                                <div class="col-12" v-if="order.document_contract_signed">
                                                                    <div class="form-group">
                                                                      <label for="contract">Contract Signed</label>
                                                                      <a :href="order.document_contract_signed.url" download>[Download]</a>
                                                                      <select class="form-control" name="select1" v-model="order.document_contract_signed.document_status_id" v-bind:class="error.status != '' && error.status ? 'is-invalid select2-hidden-accessible' : ''">
                                                                        <option value="">Select status</option>
                                                                        <option v-for="status in document_status" :value="status.id">{{status.name}}</option>
                                                                      </select>

                                                                    </div>
                                                                    <div class="form-group" v-if="order.document_contract_signed">
                                                                      <textarea style="height: 50px;" name="" v-if="order.document_contract_signed.document_status_id == 2" v-model="order.document_contract_signed.reason" id="" cols="30" rows="10" class="form-control" placeholder="Reason"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12" v-if="!order.document_contract_signed">
                                                                    <p>Contract Signed</p>
                                                                    <div class="alert alert-danger">Customer did not send this document</div>
                                                                </div>
                                                                <div class="col-12" v-if="order.document_proforma_signed">
                                                                    <div class="form-group">
                                                                      <label for="proforma">Proforma Signed</label>
                                                                      <a :href="order.document_proforma_signed.url" download>[Download]</a>
                                                                        
                                                                      <select class="form-control" name="select1" v-model="order.document_proforma_signed.document_status_id" v-bind:class="error.status != '' && error.status ? 'is-invalid select2-hidden-accessible' : ''">
                                                                        <option value="">Select status</option>
                                                                        <option v-for="status in document_status" :value="status.id">{{status.name}}</option>
                                                                      </select>

                                                                    </div>
                                                                    <div class="form-group" v-if="order.document_proforma_signed">
                                                                      <textarea style="height: 50px;" name="" v-if="order.document_proforma_signed.document_status_id == 2" v-model="order.document_proforma_signed.reason" id="" cols="30" rows="10" class="form-control" placeholder="Reason"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12" v-if="!order.document_proforma_signed">
                                                                    <p>Proforma Signed</p>
                                                                    <div class="alert alert-danger">Customer did not send this document</div>
                                                                </div>
                                                                <div class="col-12" v-if="order.document_swift_advance">
                                                                    <div class="form-group">
                                                                      <label for="swift_advance">Swift Advance</label>
                                                                      <a :href="order.document_swift_advance.url" download>[Download]</a>
                                                                        
                                                                      <select class="form-control" name="select1" v-model="order.document_swift_advance.document_status_id" v-bind:class="error.status != '' && error.status ? 'is-invalid select2-hidden-accessible' : ''">
                                                                        <option value="">Select status</option>
                                                                        <option v-for="status in document_status" :value="status.id">{{status.name}}</option>
                                                                      </select>

                                                                    </div>
                                                                    <div class="form-group" v-if="order.document_swift_advance">
                                                                      <textarea style="height: 50px;" name="" v-if="order.document_swift_advance.document_status_id == 2" v-model="order.document_swift_advance.reason" id="" cols="30" rows="10" class="form-control" placeholder="Reason"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12" v-if="!order.document_swift_advance">
                                                                    <p>Swift Advance</p>
                                                                    <div class="alert alert-danger">Customer did not send this document</div>
                                                                </div>
                                                                <div class="col-12" v-if="order.document_label_model">
                                                                    <div class="form-group">
                                                                      <label for="label_model">Label model</label>
                                                                      <a :href="order.document_label_model.url" download>[Download]</a>
                                                                        
                                                                      <select class="form-control" name="select1" v-model="order.document_label_model.document_status_id" v-bind:class="error.status != '' && error.status ? 'is-invalid select2-hidden-accessible' : ''">
                                                                        <option value="">Select status</option>
                                                                        <option v-for="status in document_status" :value="status.id">{{status.name}}</option>
                                                                      </select>

                                                                    </div>
                                                                    <div class="form-group" v-if="order.document_label_model">
                                                                      <textarea style="height: 50px;" name="" v-if="order.document_label_model.document_status_id == 2" v-model="order.document_label_model.reason" id="" cols="30" rows="10" class="form-control" placeholder="Reason"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12" v-if="!order.document_label_model">
                                                                    <p>Label model</p>
                                                                    <div class="alert alert-danger">Customer did not send this document</div>
                                                                </div>
                                                                <div class="col-12" v-if="order.document_instructions_documents">
                                                                    <div class="form-group">
                                                                      <label for="swift_advance">Instructions Documents</label>
                                                                      <a :href="order.document_instructions_documents.url" download>[Download]</a>
                                                                        
                                                                      <select class="form-control" name="select1" v-model="order.document_instructions_documents.document_status_id" v-bind:class="error.status != '' && error.status ? 'is-invalid select2-hidden-accessible' : ''">
                                                                        <option value="">Select status</option>
                                                                        <option v-for="status in document_status" :value="status.id">{{status.name}}</option>
                                                                      </select>

                                                                    </div>
                                                                    <div class="form-group" v-if="order.document_instructions_documents">
                                                                      <textarea style="height: 50px;" name="" v-if="order.document_instructions_documents.document_status_id == 2" v-model="order.document_instructions_documents.reason" id="" cols="30" rows="10" class="form-control" placeholder="Reason"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12" v-if="!order.document_instructions_documents">
                                                                    <p>Instructions Documents</p>
                                                                    <div class="alert alert-danger">Customer did not send this document</div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-success" @click="UpdateDocumentStatus(order)">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>

                                    <tr>
                                        <th colspan="5"> Total </th>
                                        <th>
                                            {{ 
                                                orders.reduce((acc, order) => {
                                                    let container = (order.container_stuffing ? order.container_stuffing.qtd_container : 0);
                                                    return acc + (Number.isInteger(+container) ? +container : parseInt(container)); 
                                                }, 0) 
                                            }}
                                        </th>
                                        <th>{{ FormatNumber(orders.reduce((acc, order) => { return acc + parseInt((order.items[0] ? order.items[0].net_weight : 0)); }, 0)) }} </th>
                                        <th colspan="14"> &nbsp; </th>
                                    </tr>                                    
                                </tbody>
                            </table>
                        </div>

                        <popcorn-pagination v-model="page_orders" :items="orders"></popcorn-pagination>

                        <div class="row mt-3">
                            <div class="col text-right">
                                Date indicators: <span class="done">Done</span>&emsp;<span class="last-update">Last Update</span>&emsp;<span class="next-step">Next Step</span>
                            </div>
                        </div>
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

.btn-warning, .btn-warning:hover , .btn-warning:active {
    color: white;
}

.last-update {
    border-bottom: 3px solid blue;
}

.next-step {
    border-bottom: 3px solid lawngreen;
}

.done {
    border-bottom: 3px solid red;
}

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

export default {
    data() {
        return {
            orders: [],
            page_orders: [],
            status: [],
            error: [],
            filters: [''],
            initial_date: '',
            final_date: '',
            filterTimeout: null,
            document_status: [
                {name: 'In analysis', id: '1'},
                {name: 'Rejected',    id: '2'},
                {name: 'Approved',     id: '3'}
            ],
            moment: moment
        }
    },
    watch: {
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
        Exclude: function ( id ) {
            var index
            for (index in this.$data.orders) {
                if (this.$data.orders[index].id != id) continue
                var order = this.$data.orders[index]
                break
            }

            var self = this

            this.$http.delete('/api/order/' + order.id).then(function (e) {
                $('#modal-' + index).modal('hide')

                if (e.body.success) {
                    self.$data.orders.splice(index,1)
                    self.$toaster.success(e.body.success)
                    return;
                }

                self.$toaster.error(e.body.error)
            })
        },
        UpdateDocumentStatus: function (order) {
            var data = {
                contract_signed: order.document_contract_signed,
                proforma_signed: order.document_proforma_signed,
                swift_advance: order.document_swift_advance,
                label_model: order.document_label_model,
                instructions_documents: order.document_instructions_documents,
            };

            this.$http.put('/api/document/', data).then( e => {
                if(!e.body.success) {
                    return
                }

                this.$toaster.success(e.body.success)
                $('.modal').modal('hide');

                if (e.body.order_id) {
                    for (var i in this.$data.orders) {
                        if (this.$data.orders[i].id == e.body.order_id) {
                            this.$data.orders[i].status = e.body.status
                            return
                        }
                    }
                }
            })
        },

        DateToTimestamp(date) {
            if(!date) return 0;
            let [year, month, day] = date.split(/-/);
            let dt = new Date(year, month, day);
            return dt.getTime();
        },

        getQuery: function () {
            let params = {
                initial_date: this.initial_date || '',
                final_date: this.final_date || '',
                filters: this.filters || []
            }

            return queryString.stringify(params, {arrayFormat: 'bracket'});       
        },

        _filterOrders: async function() {
            this.$data.orders = this.$data.page_orders = [];

            let response = await this.$http.get('/api/orders/railway?' + this.getQuery());
            
            if (response.body && response.body.orders) {
                let orders = response.body.orders;
                let filters = response.body.filters;

                if (typeof orders == "object" && orders !== null) {
                    orders = Object.values(orders);
                }

                if ((!filters && !this.$data.filters) || JSON.stringify(filters.filter(Boolean).map(f => f.trim())) == JSON.stringify(this.$data.filters.filter(Boolean).map(f => f.trim()))) {
                    this.$data.orders = this.$data.page_orders = this.sortOrders(orders);
                }

                this.$data.orders = this.$data.orders.map(function (order) {
                    order.freeTimeLimit = (order.container_stuffing && order.container_stuffing.empty_release_for_outbound_date ? moment(order.container_stuffing.empty_release_for_outbound_date).add((order.shipping[0] ? parseInt(order.shipping[0].free_time_origin) : 0), 'days') : null);

                    let dates = {
                        'loading': (order.shipping[0] ? order.shipping[0].loading_deadline : null),
                        'withdrawal': (order.railroads ? order.railroads.withdrawal_date : null),
                        'start': (order.railroads ? order.railroads.start_date : null),
                        'arrival': (order.railroads ? order.railroads.arrival_date : null),
                        'stuffing_start': (order.container_stuffing ? order.container_stuffing.stuffing_starting_date : null),
                        'stuffing_end': (order.container_stuffing ? order.container_stuffing.stuffing_ending_date : null),
                        'fumigation': (order.fumigation ? order.fumigation.init : null),
                        'aeration': (order.fumigation ? order.fumigation.end : null),
                        'mapa': (order.mapa ? order.mapa.inspection_date : null),
                        'withdrawal_start': (order.railroads ? order.railroads.withdrawal_start_date : null),
                        'estimated': (order.railroads ? order.railroads.estimated_time : null),
                        'transfer': (order.railroads ? order.railroads.transfer_terminal_date : null),
                        'free_time': order.freeTimeLimit
                    };

                    let classes = {};
                    let lastUpdate = null;
                    let nextStep = null;

                    for (let [name, date] of Object.entries(dates)) {                            
                        classes[name] = '';
                        if (date) {
                            if (moment(date).isBefore(moment(), 'day')) {
                                classes[name] = 'done';
                                if (!lastUpdate || moment(date).isAfter(moment(dates[lastUpdate]), 'day')) {
                                    lastUpdate = name;
                                }
                            } else {
                                if (!nextStep || moment(date).isBefore(moment(dates[nextStep]), 'day')) {
                                    nextStep = name;
                                }
                            }
                        }
                    }

                    classes[lastUpdate] = 'last-update';
                    classes[nextStep] = 'next-step';

                    order.dateClasses = classes;

                    return order;
                });
            }
        },

        filterOrders: function () {
            clearTimeout(this.filterTimeout);
            this.filterTimeout = setTimeout(this._filterOrders, 500);
        },

        sortOrders: function (orders) {
            orders = orders.map(function(order) {
                let date = '';

                if (order.hasOwnProperty('loading_deadline') && (order.shipping ? order.shipping.loading_deadline : '')) {
                    date = (order.shipping ? order.shipping.loading_deadline : '');
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
        }
    },
    mounted: async function () {
        this._filterOrders();
    }
}

</script>