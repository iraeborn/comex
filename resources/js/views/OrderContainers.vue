<template>
    <div class="container-fluid">
        <div id="ui-view">
            <div class="panel panel-success">                    
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                Containers
                            </div>



                            <div class="col-2" v-if="has_containers == 'no_return' || has_containers == 'with'">
                                <label for="initial_date">Initial Date</label>
                                <input type="date" id="initial_date" class="form-control" v-model="initial_date">
                            </div>

                            <div class="col-2" v-if="has_containers == 'no_return' || has_containers == 'with'">
                                <label for="final_date">Final Date</label>
                                <input type="date" id="final_date" class="form-control" v-model="final_date">
                            </div>

                            <div class="col-2">
                                <label for="company">Status</label>
                                <select class="form-control" name="containers" v-model="has_containers">
                                    <option value="waiting_booking">Waiting booking</option>
                                    <option value="without">Without containers</option>
                                    <option value="with">With containers</option>
                                    <option value="no_return">With no return</option>
                                </select>
                            </div>

                            <div class="col-2">                                
                                <label for="filter">Filter</label>
                                <input type="text" id="filter" name="filter" class="form-control" v-model="filters[0]" placeholder="Filter">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <p class="text-center" v-if="!orders.length">No records found</p>       
                            <table class="table table-sm" v-else>
                                <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>Container</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(order, orderIndex) in orders">
                                        <td>{{ order.number }}</td>

                                        <td v-if="order.shipping.length == 0">
                                                <span class="wainting">Wainting booking</span>
                                        </td>
                                        <td v-else-if="(order.shipping.length > 0) && (order.containers_count == 0)">
                                            <table width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="40%">Containers ({{ order.container_stuffing.qtd_container !== null ? order.container_stuffing.qtd_container + ' planned' : 'none planned' }})</th>
                                                        <th width="5%">Free Time</th>
                                                        <th width="10%">Line</th>
                                                        <th width="10%">Booking</th>
                                                        <th width="5%">Loading Deadline</th>
                                                        <th width="10%">Vessel</th>
                                                        <th width="5%">Type</th>
                                                        <th width="10%">Dispatch place</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="shipping in order.shipping">
                                                        <td><button @click="OpenAddContainerModal(order.id,  order.shipping[0].id)" class="btn btn-success btn-md w-100">Add new container</button></td>
                                                        <td>{{ shipping.free_time_origin }}</td>
                                                        <td>{{ shipping.shipping_line }}</td>
                                                        <td>{{ shipping.booking }}</td>
                                                        <td>{{ shipping.loading_deadline }}</td>
                                                        <td>{{ shipping.vessel }}</td>
                                                        <td>{{ order.container_stuffing.container_type }}</td>
                                                        <td>{{ order.container_stuffing.dispatch_place_name }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td v-else>
                                            <table width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="40%">Containers ({{ order.containers_count }} out of {{ order.container_stuffing.qtd_container  }} planned)</th>
                                                        <th width="5%">Free Time</th>
                                                        <th width="10%">Line</th>
                                                        <th width="10%">Booking</th>
                                                        <th width="5%">Loading Deadline</th>
                                                        <th width="10%">Vessel</th>
                                                        <th width="5%">Type</th>
                                                        <th width="10%">Dispatch place</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="shipping in order.shipping">
                                                        <td>
                                                            
                                                            <table v-if="shipping.containers" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th width="20%">Number</th>
                                                                        <th>Withdrawal</th>
                                                                        <th>Return</th>
                                                                        <th>Limit</th>
                                                                        <th>Excess</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody v-for="(container, containerIndex) in shipping.containers">
                                                                    <tr>
                                                                        <td>
                                                                            <button type="button" class="edit-button" data-toggle="modal" :href='"#modal-update-container-" + orderIndex +"-"+containerIndex'>
                                                                                <i class="fas fa-edit"></i>
                                                                            </button>
                                                                        </td>
                                                                        <td>{{ container.name }}</td>
                                                                        <td>{{ moment(container.withdrawal_date).format('DD/MM/YYYY') }}</td>
                                                                        <td>{{ formatReturnDate(container) }}</td>

                                                                        <td :style="container.excess_time == 'up to date' ? '' : 'color: red'">
                                                                            {{ moment.utc(container.free_time_limit).format('DD/MM/YYYY') }}
                                                                        </td>
                                                                        
                                                                        <td :style="container.excess_time == 'up to date' ? 'color: blue' : 'color: red'">
                                                                            {{ container.excess_time }}
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <div class="modal fade text-left" :id="'modal-update-container-'+ orderIndex +'-'+containerIndex">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title">Container</h4>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                    <label for="name">Container number</label>
                                                                                    <input-icon type="text" name="name" :id="'name-'+ orderIndex +'-'+containerIndex" autocomplete="off" icon="fas fa-box" v-model="container.name" :class="error.name != '' && error.name ? 'is-invalid' : ''"/>
                                                                                    <div class="invalid-feedback" v-if="error.name" v-for="message in error.name">{{message}}</div>
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                    <label for="tare">Tare</label>
                                                                                    <input-icon type="number" name="tare" :id="'tare-'+ orderIndex +'-'+containerIndex" autocomplete="off" icon="fas fa-box" v-model=container.tare :class="error.tare != '' && error.tare ? 'is-invalid' : ''"/>
                                                                                    <div class="invalid-feedback" v-if="error.tare" v-for="message in error.tare">{{message}}</div>
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                    <label for="seal">Seal</label>
                                                                                    <input-icon type="text" name="seal" :id="'seal-'+ orderIndex +'-'+containerIndex" autocomplete="off" icon="fas fa-box" v-model="container.seal" :class="error.seal != '' && error.seal ? 'is-invalid' : ''"/>
                                                                                    <div class="invalid-feedback" v-if="error.seal" v-for="message in error.seal">{{message}}</div>
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                    <label for="cbm">CBM</label>
                                                                                    <input-icon type="number" name="cbm" :id="'cbm-'+ orderIndex +'-'+containerIndex" autocomplete="off" icon="fas fa-box" v-model="container.cbm" :class="error.cbm != '' && error.cbm ? 'is-invalid' : ''"/>
                                                                                    <div class="invalid-feedback" v-if="error.cbm" v-for="message in error.cbm">{{message}}</div>
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                    <label for="withdrawal_date">Withdrawal Date</label>
                                                                                    <input-icon type="date" name="withdrawal_date" :id="'withdrawal_date-'+ orderIndex +'-'+containerIndex" autocomplete="off" icon="fas fa-box" v-model="container.withdrawal_date" :class="error.withdrawal_date != '' && error.withdrawal_date ? 'is-invalid' : ''"/>
                                                                                    <div class="invalid-feedback" v-if="error.withdrawal_date" v-for="message in error.withdrawal_date">{{message}}</div>
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                    <label for="return_date">Return Date</label>
                                                                                    <input-icon type="date" name="return_date" :id="'return_date-'+ orderIndex +'-'+containerIndex" autocomplete="off" icon="fas fa-box" v-model="container.return_date" :class="error.return_date != '' && error.return_date ? 'is-invalid' : ''"/>
                                                                                    <div class="invalid-feedback" v-if="error.return_date" v-for="message in error.return_date">{{message}}</div>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                                    <button type="button" class="btn btn-primary" data-dismiss="modal" @click="UpdateContainer(orderIndex+'-'+containerIndex, order.id, container.id)">Update</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </tbody>
                                                            </table>

                                                        </td>
                                                        <td>{{ shipping.free_time_origin }}</td>
                                                        <td>{{ shipping.shipping_line }}</td>
                                                        <td>{{ shipping.booking }}</td>
                                                        <td>{{ shipping.loading_deadline }}</td>
                                                        <td>{{ shipping.vessel }}</td>
                                                        <td>{{ order.container_stuffing.container_type }}</td>
                                                        <td>{{ order.container_stuffing.dispatch_place_name }}</td>
                                                    </tr>
                                                    <tr v-if="order.container_stuffing">
                                                        <td>
                                                            <button @click="OpenAddContainerModal(order.id,  order.shipping[0].id)" class="btn btn-success btn-md w-100">Add new container</button>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- <popcorn-pagination v-model="page_containers" :items="orders"></popcorn-pagination> -->
                        <template>
                            <div class="d-flex">
                                <div class="pill-center" v-if="pages.length > 1">
                                    <ul class="pagination">
                                    <li class="page-item" v-if="this.$data.current_page - 1 >= 0"><a class="page-link" @click="PrevPage">Previous</a></li>
                                    <li class="page-item" :class="index == current_page ? 'active' : ''" v-for="(page, index) in pages"><a class="page-link" @click="SetPage(page, index)">{{index + 1}}</a></li>
                                    <li class="page-item" v-if="this.$data.current_page + 1 < this.$data.pages.length"><a class="page-link" @click="NextPage">Next</a></li>
                                    </ul>
                                </div>
                            
                                <div class="ml-auto">
                                    <select v-model="perPage" name="perPage" id="perPage" class="form-control">
                                        <option value="15">15</option>
                                        <option value="30">30</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="150">150</option>
                                        <option value="200">200</option>
                                    </select>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade text-left" id="modal-new-container">
          <div class="modal-dialog modal-primary">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Container</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="nameNew">Container number</label>
                  <input-icon type="text" name="name" id="nameNew" autocomplete="off" icon="fas fa-box" v-model="current_container.name" :class="error.name != '' && error.name ? 'is-invalid' : ''"/>
                  <div class="invalid-feedback" v-if="error.name" v-for="message in error.name">{{message}}</div>
                </div>

                <div class="form-group">
                  <label for="editNew">Tare</label>
                  <input-icon type="number" name="tare" id="editNew" autocomplete="off" icon="fas fa-box" v-model="current_container.tare" :class="error.tare != '' && error.tare ? 'is-invalid' : ''" />
                  <div class="invalid-feedback" v-if="error.tare" v-for="message in error.tare">{{message}}</div>
                </div>

                <div class="form-group">
                  <label for="sealNew">Seal</label>
                  <input-icon type="text" name="seal" id="sealNew" autocomplete="off" icon="fas fa-box" v-model="current_container.seal" :class="error.seal != '' && error.seal ? 'is-invalid' : ''"/>
                  <div class="invalid-feedback" v-if="error.seal" v-for="message in error.seal">{{message}}</div>
                </div>

                <div class="form-group">
                  <label for="cbmNew">CBM</label>
                  <input-icon type="number" name="cbm" id="cbmNew" autocomplete="off" icon="fas fa-box" v-model="current_container.cbm" :class="error.cbm != '' && error.cbm ? 'is-invalid' : ''"/>
                  <div class="invalid-feedback" v-if="error.cbm" v-for="message in error.cbm">{{message}}</div>
                </div>

                <div class="form-group">
                  <label for="withdrawal_dateNew">Withdrawal Date</label>
                  <input-icon type="date" name="withdrawal_date" id="withdrawal_dateNew" autocomplete="off" icon="fas fa-box" v-model="current_container.withdrawal_date" :class="error.withdrawal_date != '' && error.withdrawal_date ? 'is-invalid' : ''"/>
                  <div class="invalid-feedback" v-if="error.withdrawal_date" v-for="message in error.withdrawal_date">{{message}}</div>
                </div>

                <div class="form-group">
                  <label for="return_dateNew">Return Date</label>
                  <input-icon type="date" name="return_date" id="return_dateNew" autocomplete="off" icon="fas fa-box" v-model="current_container.return_date" :class="error.return_date != '' && error.return_date ? 'is-invalid' : ''"/>
                  <div class="invalid-feedback" v-if="error.return_date" v-for="message in error.return_date">{{message}}</div>
                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" @click="SaveContainer">Save</button>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<style scoped>
/* .edit-button,
.edit-button:focus,
.edit-button:focus-visible,
.edit-button:hover {
    border: none;
    background-color: rgba(255, 255, 255, 0);
    outline: -webkit-focus-ring-color auto 0;
}
.edit-button i {
    color: #addc88;
}
.edit-button,i:hover {
    color: #4dbd74;
} */
.table .table {
    background-color: #fef101;
}
th {
    border: none;
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

.btn-warning, .btn-warning:hover , .btn-warning:active {
    color: white;
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

.wainting {
    display: inline-block;
    padding: 4px 10px;
    border: 0;
    border-radius: 20px;
    background-color: rgb(222, 219, 123);
    color: #333!Important;
}
.page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: #4dbd74;
    border-color: #4dbd74;
    cursor: pointer;
    user-select: none;
}
.page-item {
    cursor: pointer;
    user-select: none;
}
</style>

<script>
import queryString from 'query-string'
import moment from 'moment'

export default {
    data() {
        return {
            current_container: {},
            orders: [],
            page_containers: [],
            status: [],
            error: [],
            initial_date: '',
            final_date: '',
            filters: [''],
            status: '',
            has_containers:'waiting_booking',
            filterTimeout: null,
            moment: moment,
            last_page: null,
            current_page: null,
            pages: [],
            page: null,
            next_page_url: null,
            prev_page_url: null,
            perPage: 15,
        }
    },
    watch: {
        filters: function () {
            this.filterContainers();
        },
        has_containers: function () {
            this._filterContainers();
        },
        initial_date: function () {
            this._filterContainers();
        },
        final_date: function () {
            this._filterContainers();
        },
        page: function () {
            this._filterContainers();
        },
        perPage: function () {
            this._filterContainers();
        }
    },
    methods: {

        formatReturnDate(container) {

            if (container.return_date) {
                return moment(container.return_date).format('DD/MM/YYYY');
            } else {
                return 'No return';
            }
        },

        freeTimeLimit(withdrawal_date, free_time){
            // const freeTimeOrigin = parseInt(free_time.match(/\d+/)[0]);
            const freeTime = moment(withdrawal_date).add(free_time, 'days');

            return freeTime.format('DD/MM/YYYY');
        },

        excessTime(free_time, return_date, withdrawal_date) {

            // const freeTimeOrigin = parseInt(free_time.match(/\d+/)[0]);
            const freeTime = moment(withdrawal_date).add(free_time, 'days');
            const late = moment(return_date).diff(moment(freeTime), 'days')

            return  -late < 0 ? (late > 1 ? late + ' days' : late + ' day') : 'up to date';
        },

        SaveContainer: function () {
            this.$http.put('/api/containers/' + this.current_container.orderId, [this.current_container], false).then(e => {
                if(e.body.errors) {
                    this.$data.error = e.body.errors
                    this.$toaster.error('There are problems in Container stuffing')
                    return;
                }
                this._filterContainers();
                this.$toaster.success('Containers saved!')
            })
        },
        UpdateContainer: function (index, orderId, containerId){
            const orderToUpdate = this.$data.orders.find(order => order.id === orderId);

                for (const shipping of orderToUpdate.shipping) {
                    const containerToUpdate = shipping.containers.find(container => container.id === containerId);
                    console.log(containerToUpdate)
                        const payload = {
                            name: containerToUpdate.name,
                            tare: parseFloat(containerToUpdate.tare),
                            seal: containerToUpdate.seal,
                            cbm: containerToUpdate.cbm,
                            withdrawal_date: containerToUpdate.withdrawal_date,
                            return_date: containerToUpdate.return_date
                        }

                        this.$http.post('/api/order/container/'+containerId, payload)
                        .then(() => {
                            $('#modal-update-container-' + index).modal('hide');
                        })
                        .catch(error => {
                            console.log(error.message);
                        })

                        return;
                    
                }
            
        },
        OpenAddContainerModal: function (orderId, shippingId) {
            this.$data.current_container.orderId = orderId
            this.$data.current_container.shipping_id = shippingId
            this.$data.current_container.isMulti = false
            $('#modal-new-container').modal('show');
        },
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

        getQuery: function () {
            let params = {
                initial_date: this.initial_date || '',
                final_date: this.final_date || '',
                filters: this.filters || [],
                next_page_url: this.next_page_url || null,
                prev_page_url: this.prev_page_url || null,
                page: this.page || null,
                perPage: this.perPage || null
            }

            return queryString.stringify(params, {arrayFormat: 'bracket'});       
        },
        SetPage: function (page, index) {
            this.$data.page = page
            this.$data.current_page = index
        },
        NextPage: function () {
            var next_page = this.$data.current_page + 1

            if(next_page >= this.$data.pages.length) return

            this.SetPage(this.$data.pages[next_page], next_page)
        },
        PrevPage: function () {
            var prev_page = this.$data.current_page - 1

            if(prev_page < 0) return

            this.SetPage(this.$data.pages[prev_page], prev_page)
        },

        _filterContainers: async function() {
            let query = this.getQuery();
            query += '&status=' + this.has_containers;

            let response = await this.$http.get('/api/orders/containers?' + query);

            const last_page = response.body.orders.last_page;
            const current_page = response.body.orders.current_page;
            
            this.$data.current_page = current_page - 1;
            this.$data.next_page_url = response.body.orders.last_page_url;
            this.$data.prev_page_url = response.body.orders.prev_page_url;

            this.$data.last_page = last_page;
            const pages = Array.from({ length: last_page }, (_, index) => index + 1);
            this.$data.pages = pages

            let orders = response.body.orders.data;
            if (response.body && orders) {

                for (let order of orders) {
                    for (let shipping of order.shipping) {
                        for (let container of shipping.containers) {
                            let tareString = container.tare;
                            tareString = tareString.replace(/,/g, '');
                            tareString = tareString.replace('.', '');
                            tareString = tareString.replace(',', '.');
                            container.tare = tareString;
                        }
                    }
                }
                
                let filters = response.body.filters;

                this.$data.orders = orders;

                if (typeof orders == "object" && orders !== null) {
                    orders = Object.values(orders);
                }

                if ((!filters && !this.$data.filters) || JSON.stringify(filters.filter(Boolean).map(f => f.trim())) == JSON.stringify(this.$data.filters.filter(Boolean).map(f => f.trim()))) {
                    this.$data.orders = this.$data.page_containers = this.sortContainers(orders);
                }

                this.$data.orders = this.$data.orders.map(function (order) {
                        order.freeTimeLimit = (order.container_stuffing &&
                        order.container_stuffing.empty_release_for_outbound_date ?
                            moment(order.container_stuffing.empty_release_for_outbound_date).add((order.shipping ? parseInt(order.shipping.free_time_origin) : 0), 'days') : null);
                        
                        let return_date = (order.return_date ? moment(order.return_date) : moment());

                        order.excessTime = (
                            order.freeTimeLimit &&
                            return_date.isAfter(order.freeTimeLimit, 'day')
                            ? return_date.diff(order.freeTimeLimit, 'days') + ' Days'
                           : null
                        );

                        return order;
                });
            }
        },

        filterContainers: function () {
            clearTimeout(this.filterTimeout);
            this.filterTimeout = setTimeout(this._filterContainers, 500);
        },

        sortContainers: function (containers) {
            containers = containers.map(function(container) {
                let date = '';

                if (container.withdrawal_date) {
                    date = container.withdrawal_date;
                } else {
                    date = container.created_at;
                }

                container.date = date;

                return container;
            })

            containers = containers.sort(function (a, b) {
                let dateA = moment(a.date);
                let dateB = moment(b.date);

                if (dateA.isAfter(dateB)) {
                    return -1;
                } else if (dateB.isAfter(dateA)) {
                    return 1;
                }

                return 0;
            })

            return containers;
        },

        changeStatus: function (status) {
            this.$data.status = status;
        },

        addFilter() {
            this.$data.filters.push('');
        },

        removeFilter(index) {
            this.$data.filters.splice(index, 1);
        }
    },
    mounted: async function () {
        this._filterContainers();
    }
}

</script>