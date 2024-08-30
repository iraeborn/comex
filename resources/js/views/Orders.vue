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

                            <div class="col text-right">
                                <router-link :to="{ name: 'New order' }" class="btn btn-success btn-sm">Add new
                                    order</router-link>
                            </div>

                        </div>

                        <div class="row justify-content-end">
                            <div class="col-3">
                                <label>Search term</label>
                                <input class="form-control" v-model="globalFilter"
                                    placeholder="Search..." />
                            </div>

                            <div class="col-3"
                                v-if="this.$route.query.status != 'filter'">
                                <label>
                                    Filters
                                    <a href="#" @click="addFilter()">
                                        <i class="icon-plus" title="Add filter"></i>
                                    </a>
                                </label>
                                <div class="row m-0" id="filters">
                                    <div class="col" v-for="(filter, index) in filters">
                                        <div class="filter" v-if="filters.length > 1">
                                            <i class="icon-close text-danger" @click="removeFilter(index)"></i>
                                            <input type="text" class="form-control" v-model="filters[index]"
                                                placeholder="Filter">
                                        </div>
                                        <input type="text" class="form-control" v-else v-model="filters[index]"
                                            placeholder="Filter">
                                    </div>
                                </div>
                            </div>

                            <div class="col-2">
                                <label for="importer">Date type</label>
                                <multiselect
                                v-model="dateType"
                                placeholder="Select a date type"
                                track-by="id"
                                :options="dateTypesOptions"
                                :custom-label="customLabel"
                                :show-labels="false"
                                :allow-empty="true"
                                >
                                </multiselect>
                            </div>

                            <div class="col-2">
                                <iconinput
                                label="Initial date:"
                                type="date"
                                v-model="initial_date"
                                id="initial_date"
                                name="initial_date"
                                icon="fas fa-calendar"
                                />
                            </div>

                            <div class="col-2">
                                <iconinput
                                label="Final date:"
                                type="date"
                                v-model="final_date"
                                id="final_date"
                                name="final_date"
                                icon="fas fa-calendar"
                                />
                            </div>
                            
                            <div class="col-12 col-sm-3 col-md-2 mt-2">
                                <button class="btn btn-success btn-block" @click="_filterOrders()">Filter</button>
                            </div>

                            <div class="col-12 col-sm-3 col-md-2 mt-2">
                                <button class="btn btn-danger btn-block" @click="clearFilters()">Clear Filter</button>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col">
                                <ul class="nav nav-tabs mtest">
                                    <li class="nav-item">
                                        <router-link class="nav-link-alt"
                                            :class="this.$route.query.status == 'all' || !this.$route.query.status ? 'active' : ''"
                                            :to="{ name: 'Orders', query: { status: 'all' } }">&emsp;All&emsp;</router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link class="nav-link-alt"
                                            :class="this.$route.query.status == 'pending' ? 'active' : ''"
                                            :to="{ name: 'Orders', query: { status: 'pending' } }">Booking
                                            Pendence</router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link class="nav-link-alt"
                                            :class="this.$route.query.status == 'loading' ? 'active' : ''"
                                            :to="{ name: 'Orders', query: { status: 'loading' } }">In Process
                                            Loading</router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link class="nav-link-alt"
                                            :class="this.$route.query.status == 'transit' ? 'active' : ''"
                                            :to="{ name: 'Orders', query: { status: 'transit' } }">In Transit</router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link class="nav-link-alt"
                                            :class="this.$route.query.status == 'release' ? 'active' : ''"
                                            :to="{ name: 'Orders', query: { status: 'release' } }">In Process
                                            Release</router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link class="nav-link-alt"
                                            :class="this.$route.query.status == 'aftership' ? 'active' : ''"
                                            :to="{ name: 'Orders', query: { status: 'aftership' } }">After
                                            Ship</router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link class="nav-link-alt"
                                            :class="this.$route.query.status == 'missing_docs' ? 'active' : ''"
                                            :to="{ name: 'Orders', query: { status: 'missing_docs' } }">Checklist
                                            Docs</router-link>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="scrollme">
                            <p class="text-center" v-if="!orders.length">{{ loading == false ? 'No records found' : 'Loading...' }}</p>
                            <table class="table table-hover" v-else>
                                <thead>
                                    <tr v-if="this.$route.query.status == 'all' || !this.$route.query.status">
                                        <th></th>
                                        <th>Order number</th>
                                        <th>Booking</th>
                                        <th>Importer name</th>
                                        <th>Cntrs Amount</th>
                                        <th>Product</th>
                                        <th>Unit price</th>
                                        <th>Unit comission</th>
                                        <th>Destiny</th>
                                        <th>Shipment</th>
                                        <th class="col20">Status</th>
                                        <th>Note</th>
                                    </tr>
                                    <tr v-else-if="this.$route.query.status == 'pending'">
                                        <th></th>
                                        <th>Order number</th>
                                        <th>Importer name</th>
                                        <th>Exporter name</th>
                                        <th>Packing</th>
                                        <th>Products</th>
                                        <th>Port Origin</th>
                                        <th>Port Destiny</th>
                                        <th>Shipment</th>
                                        <th>Note</th>
                                    </tr>
                                    <tr v-else-if="this.$route.query.status == 'loading'">
                                        <th></th>
                                        <th>Order number</th>
                                        <th>Product</th>
                                        <th>Total order</th>
                                        <th>Shipped total</th>
                                        <th>Balance</th>
                                        <th>Start Truck Loading</th>
                                        <th>Unload</th>
                                        <th>Note</th>
                                    </tr>

                                    <tr v-else-if="this.$route.query.status == 'transit'">
                                        <th></th>
                                        <th>Order number</th>
                                        <th>Booking</th>
                                        <th>Product</th>
                                        <th>Quantity Kg</th>
                                        <th>Unloading Location</th>
                                        <th>End Loading Date</th>
                                        <th>Unload</th>
                                        <th>Note</th>
                                    </tr>

                                    <tr v-else-if="this.$route.query.status == 'release'">
                                        <th></th>
                                        <th>Order number</th>
                                        <th>Booking</th>
                                        <th>FCL</th>
                                        <th>Free Time</th>
                                        <th>Dispatch Place</th>
                                        <th>Empty Release for Outbound Date</th>
                                        <th>Stuffing Starting Date</th>
                                        <th>Stuffing Ending Date</th>
                                        <th>M.A.P.A Inspection date</th>
                                        <th>Fumigation</th>
                                        <th>Aeration</th>
                                        <th>Draft Deadline</th>
                                        <th>Loading Deadline</th>
                                        <th>Free Time Limit Date</th>
                                        <th>ETD</th>
                                        <th>LPCO key</th>
                                        <th>DUE code</th>
                                        <th>NFE</th>
                                        <th>Dossiê</th>
                                        <th>Note</th>
                                    </tr>

                                    <tr v-else-if="this.$route.query.status == 'aftership'">
                                        <th></th>
                                        <th>Order number</th>
                                        <th>Product</th>
                                        <th>Quantity Kg</th>
                                        <th>Port Destiny</th>
                                        <th>Booking</th>
                                        <th>Vessel</th>
                                        <th>Shipping line</th>
                                        <th>Cntrs Amount</th>
                                        <th>ETD</th>
                                        <th>ETA</th>
                                        <th>DHL Tracking</th>
                                        <th>Note</th>
                                    </tr>

                                    <tr v-else-if="this.$route.query.status == 'filter'">
                                        <th>Order number</th>
                                        <th>Importer name</th>
                                        <th>Product</th>
                                        <th>FCL</th>
                                        <th>Quantity Kg</th>
                                        <th>Shipped Total</th>
                                        <th>Balance</th>
                                        <th>Unload</th>
                                        <th>Port Destiny</th>
                                        <th>Shipment</th>
                                        <th>Note</th>
                                    </tr>

                                    <tr v-else-if="this.$route.query.status == 'missing_docs'">
                                        <th></th>
                                        <th>Order</th>
                                        <th>Booking</th>
                                        <th>&nbsp;</th>
                                        <th>Invoice</th>
                                        <th>Packing l.</th>
                                        <th>BL</th>
                                        <th>Origem cert.</th>
                                        <th>Fumigation cert.</th>
                                        <th>Phyto cert.</th>
                                        <th>Insurance cert.</th>
                                        <th>Quality cert.</th>
                                        <th>Weight cert.</th>
                                        <th>Stuffing report</th>
                                        <th>Health cert.</th>
                                        <th>Non-GMO cert.</th>
                                        <th>LPCO</th>
                                        <th>DU-E</th>
                                        <th>Others</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <template v-for="(order, index) in page_orders">
                                        <tr v-if="$parent.$route.query.status == 'all' || !$parent.$route.query.status">
                                            <td>
                                                <div class="dropdown dropright">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <div class="dropdown-submenu">
                                                            <button class="btn btn-secondary dropdown-item" id="sub-toogle" type="button"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                                Providers
                                                            </button>
                                                            <!-- <a tabindex="-1" href="#" class="dropdown-item">Providers</a> -->
                                                            <div class="dropdown-menu">


                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.broker_id == null }"
                                                                    @click="openProviderModal('broker', order, index)">
                                                                    Broker
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.fumigation_id == null }"
                                                                    @click="openProviderModal('fumigation', order, index)">
                                                                    Fumigation
                                                                </button>

                                                                <button  v-bind:class="{ 'dropdown-item': true, 'pendence': order.weight_id == null }"
                                                                    @click="openProviderModal('insurance', order, index)">
                                                                    Insurance
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.inspection_id == null }"
                                                                    @click="openProviderModal('inspection', order, index)">
                                                                    Inspection
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.forwarding_agent_id == null }"
                                                                    @click="openProviderModal('forwarding', order, index)">
                                                                    Forwarding
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.railway_agent_id == null }"
                                                                    @click="openProviderModal('railway', order, index)">
                                                                    railway
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.terminal_agent_id == null }"
                                                                    @click="openProviderModal('terminal', order, index)">
                                                                    Terminal
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.shipping_id == null }"
                                                                    @click="openProviderModal('shipping_line', order, index)">
                                                                    Shipping line
                                                                </button>

                                                            </div>
                                                        </div>
                                                        
                                                        <router-link
                                                            :to="{ name: 'Order information', params: { id: order.id } }"
                                                            class="dropdown-item"
                                                            v-if="order.status.id == 3 || order.status.information == true">Order
                                                            information</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-view-" + index'>Documents</button>

                                                        <router-link
                                                            :to="{ name: 'Copy order', params: { id: order.id } }"
                                                            class="dropdown-item">Copy</router-link>

                                                        <router-link :to="{ name: 'Edit order', params: { id: order.id } }"
                                                            class="dropdown-item">Edit</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-" + index'>Delete</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ order.number }}</td>
                                            <td>{{ (order.shipping ? order.shipping.booking : '') }}</td>
                                            <td>{{ order.owner_name }}</td>
                                            <td>{{ (order.container_stuffing ? order.container_stuffing.qtd_container :
                                                '') }}</td>
                                            <td>{{ (order.items[0] ? order.items[0].description : '') }}</td>
                                            <td>{{ (order.items[0] ? "US$" + order.items[0].unit_price : '') }}</td>
                                            <td>{{ (order.unit_comission == '0' ? '' : order.unit_comission) }}</td>
                                            <td>{{ order.port_destiny }}</td>
                                            <td>{{ order.shipment }}</td>
                                            <td>
                                                <span :class="badge_color(order.status.name)">
                                                    {{ order.status.name }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('note', 'note', 'Note', order.id )">
                                                    <span v-if="order.note">{{ order.note }}</span>
                                                    <span v-else><i class="fas fa-plus"></i></span>
                                                </button>
                                                
                                            </td>
                                        </tr>

                                        <tr v-if="$parent.$route.query.status == 'pending'">
                                            <td colspan="10">
                                                <tr>
                                                    <td>
                                                <div class="dropdown dropright">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu">

                                                        <div class="dropdown-submenu">
                                                            <button class="btn btn-secondary dropdown-item" id="sub-toogle" type="button"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                                Providers
                                                            </button>
                                                            <!-- <a tabindex="-1" href="#" class="dropdown-item">Providers</a> -->
                                                            <div class="dropdown-menu">


                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.broker_id == null }"
                                                                    @click="openProviderModal('broker', order, index)">
                                                                    Broker
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.fumigation_id == null }"
                                                                    @click="openProviderModal('fumigation', order, index)">
                                                                    Fumigation
                                                                </button>

                                                                <button  v-bind:class="{ 'dropdown-item': true, 'pendence': order.weight_id == null }"
                                                                    @click="openProviderModal('insurance', order, index)">
                                                                    Insurance
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.inspection_id == null }"
                                                                    @click="openProviderModal('inspection', order, index)">
                                                                    Inspection
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.forwarding_agent_id == null }"
                                                                    @click="openProviderModal('forwarding', order, index)">
                                                                    Forwarding
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.railway_agent_id == null }"
                                                                    @click="openProviderModal('railway', order, index)">
                                                                    railway
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.terminal_agent_id == null }"
                                                                    @click="openProviderModal('terminal', order, index)">
                                                                    Terminal
                                                                </button>

                                                                <button v-bind:class="{ 'dropdown-item': true, 'pendence': order.shipping_id == null }"
                                                                    @click="openProviderModal('shipping_line', order, index)">
                                                                    Shipping line
                                                                </button>

                                                            </div>
                                                        </div>

                                                        <router-link
                                                            :to="{ name: 'Order information', params: { id: order.id } }"
                                                            class="dropdown-item"
                                                            v-if="order.status.id == 3 || order.status.information == true">Order
                                                            information</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-view-" + index'>Documents</button>

                                                        <router-link
                                                            :to="{ name: 'New order', query: { copy_id: order.id } }"
                                                            class="dropdown-item">Copy</router-link>

                                                        <router-link :to="{ name: 'Edit order', params: { id: order.id } }"
                                                            class="dropdown-item">Edit</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-" + index'>Delete</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ order.number }}</td>
                                            <td>{{ order.owner_name }}</td>
                                            <td>{{ order.exporter_name }}</td>
                                            <td>{{ order.packing }}</td>
                                            <td>
                                                <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>FCL</th>
                                                        <th>Qty Kg</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(item, index) in order.items">
                                                        <td>{{ (item ? item.description : '') }}</td>
                                                        <td>{{ (item ? item.container : '') }}</td>
                                                        <td>{{ (item ? FormatNumber(item.net_weight) : '') }}</td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                            </td>
                                            <td>{{ order.port_origin }}</td>
                                            <td>{{ order.port_destiny }}</td>
                                            <td>{{ order.shipment }}</td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('note', 'note', 'Note', order.id )">
                                                    <span v-if="order.note">{{ order.note }}</span>
                                                    <span v-else><i class="fas fa-plus"></i></span>
                                                </button>
                                            </td>
                                                </tr>
                                                <!-- <tr>
                                                    <td colspan="10">
                                                        <button v-for="service in order.services_status" 
                                                                :key="service.id" 
                                                                :class="['btn', 'btn-sm', 'ml-1', 'mt-1', service.ativo ? 'btn-success' : 'btn-danger']">
                                                            {{ service.name }}
                                                        </button>
                                                    </td>
                                                </tr> -->
                                            </td>
                                            
                                            

                                        </tr>

                                        <tr v-if="$parent.$route.query.status == 'loading'">
                                            <td>
                                                <div class="dropdown dropright">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <router-link
                                                            :to="{ name: 'Order information', params: { id: order.id } }"
                                                            class="dropdown-item"
                                                            v-if="order.status.id == 3 || order.status.information == true">Order
                                                            information</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-view-" + index'>Documents</button>

                                                        <router-link
                                                            :to="{ name: 'New order', query: { copy_id: order.id } }"
                                                            class="dropdown-item">Copy</router-link>

                                                        <router-link :to="{ name: 'Edit order', params: { id: order.id } }"
                                                            class="dropdown-item">Edit</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-" + index'>Delete</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ order.number }}</td>
                                            <td>{{ (order.items[0] ? order.items[0].description : '') }}</td>
                                            <td>{{ (order.items[0] ? FormatNumber(order.items[0].net_weight) : '') }}
                                            </td>
                                            <td>
                                                {{ order.loadings && order.loadings.vehicles ?
                                                    FormatNumber(order.loadings.vehicles.reduce((acc, cur) => {
                                                        return acc + cur.wheight;
                                                    }, 0)) : 0 }}
                                            </td>
                                            <td>
                                                {{ order.loadings && order.items[0] ?
                                                    FormatNumber(order.items[0].net_weight -
                                                        order.loadings.vehicles.reduce((acc, cur) => {
                                                            return acc + cur.wheight;
                                                        }, 0)) : 0 }}
                                            </td>
                                            <td>{{ FormatDate(order.loadings.start_truck_loading_date) }}</td>
                                            <td>
                                                <table v-if="order.loadings">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">Weight</th>
                                                            <th width="5%" colspan="2">Unloading Date</th>
                                                            <th width="40%">Driver</th>
                                                            <th width="20%">License Plate</th>
                                                            <th width="5%">Bill No</th>
                                                            <th width="5%">Total Bags</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-for="truck in order.loadings.vehicles">
                                                        <tr>
                                                            <td>{{ FormatNumberDecimal(truck.wheight) }}</td>
                                                            <td>{{ FormatDate(truck.truck_unloading_datetime) }}</td>
                                                            <td>{{ FormatTime(truck.truck_unloading_datetime) }}</td>
                                                            <td>{{ truck.driver || truck.drivers.name }}</td>
                                                            <td>{{ truck.plate }}</td>
                                                            <td>
                                                                <span v-for="(bill, index) in truck.bills" :key="index">
                                                                    {{ bill.number }}
                                                                <br v-if="index < truck.bills.length - 1" />
                                                                </span>
                                                            </td>
                                                            <td>{{ truck.total_bags }}</td>
                                                        </tr>
                                                        
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('note', 'note', 'Note', order.id )">
                                                    <span v-if="order.note">{{ order.note }}</span>
                                                    <span v-else><i class="fas fa-plus"></i></span>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr v-if="$parent.$route.query.status == 'transit'">
                                            <td>
                                                <div class="dropdown dropright">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <router-link
                                                            :to="{ name: 'Order information', params: { id: order.id } }"
                                                            class="dropdown-item"
                                                            v-if="order.status.id == 3 || order.status.information == true">Order
                                                            information</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-view-" + index'>Documents</button>

                                                        <router-link
                                                            :to="{ name: 'New order', query: { copy_id: order.id } }"
                                                            class="dropdown-item">Copy</router-link>

                                                        <router-link :to="{ name: 'Edit order', params: { id: order.id } }"
                                                            class="dropdown-item">Edit</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-" + index'>Delete</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ order.number }}</td>
                                            <td>{{ (order.shipping ? order.shipping.booking : '') }}</td>
                                            <td>{{ (order.items[0] ? order.items[0].description : '') }}</td>
                                            <td>{{ (order.items[0] ? FormatNumber(order.items[0].net_weight) : '') }}
                                            </td>
                                            <td>{{ (order.loadings ? order.loadings.unloading_location : '') }}</td>
                                            <td>{{ (order.loadings ? FormatDate(order.loadings.end_truck_loading_date) :
                                                '') }}</td>
                                            <td>
                                                <table v-if="order.loadings">
                                                    <thead>
                                                        <tr>
                                                            <th>Loading Location</th>
                                                            <th colspan="2">Unloading Date</th>
                                                            <th>Carrier</th>
                                                            <th>Driver</th>
                                                            <th>License Plate</th>
                                                            <th>Weight</th>
                                                            <th>Bill No</th>
                                                            <th>Total Bags</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-for="truck in order.loadings.vehicles">
                                                        <tr>
                                                            <td>{{ truck.loading_location }}
                                                            </td>
                                                            <td>{{ FormatDate(truck.truck_unloading_datetime) }}</td>
                                                            <td>{{ FormatTime(truck.truck_unloading_datetime) }}</td>
                                                            <td>{{ (truck.carriers ? truck.carriers.name : '') }}</td>
                                                            <td>{{ truck.driver || (truck.drivers ? truck.drivers.name :
                                                                '') }}</td>
                                                            <td>{{ truck.plate }}</td>
                                                            <td>{{ FormatNumberDecimal(truck.wheight) }}</td>
                                                            <td>
                                                                <span v-for="(bill, index) in truck.bills" :key="index">
                                                                    {{ bill.number }}
                                                                <br v-if="index < truck.bills.length - 1" />
                                                                </span>
                                                            </td>
                                                            <td>{{ truck.total_bags }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('note', 'note', 'Note', order.id )">
                                                    <span v-if="order.note">{{ order.note }}</span>
                                                    <span v-else><i class="fas fa-plus"></i></span>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr v-if="$parent.$route.query.status == 'release'">
                                            <td>
                                                <div class="dropdown dropright">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <router-link
                                                            :to="{ name: 'Order information', params: { id: order.id } }"
                                                            class="dropdown-item"
                                                            v-if="order.status.id == 3 || order.status.information == true">Order
                                                            information</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-view-" + index'>Documents</button>

                                                        <router-link
                                                            :to="{ name: 'New order', query: { copy_id: order.id } }"
                                                            class="dropdown-item">Copy</router-link>

                                                        <router-link :to="{ name: 'Edit order', params: { id: order.id } }"
                                                            class="dropdown-item">Edit</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-" + index'>Delete</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ order.number }}</td>
                                            <td>{{ (order.shipping ? order.shipping.booking : '') }}</td>
                                            <td>{{ (order.container_stuffing ? order.container_stuffing.qtd_container : '')
                                            }}</td>
                                            <td>{{ (order.shipping ? order.shipping.free_time_origin : '') }} </td>
                                            <td>{{ (order.container_stuffing ?
                                                order.container_stuffing.dispatch_place_name : '') }}</td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'container_stuffing.empty_release_for_outbound_date', 'Empty Release for Outbound Date', order.id )">
                                                    <span :class="order.dateClasses.empty_release">
                                                        {{ (order.container_stuffing ?
                                                            FormatDate(order.container_stuffing.empty_release_for_outbound_date)
                                                            : '') }}
                                                    </span>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'container_stuffing.stuffing_starting_date', 'Stuffing Starting Date', order.id )">
                                                    <span :class="order.dateClasses.stuffing_start">
                                                    {{ (order.container_stuffing ?
                                                        FormatDate(order.container_stuffing.stuffing_starting_date) : '') }}
                                                </span>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'container_stuffing.stuffing_ending_date', 'Stuffing Ending Date', order.id )">
                                                    <span :class="order.dateClasses.stuffing_end">
                                                        {{ (order.container_stuffing ?
                                                            FormatDate(order.container_stuffing.stuffing_ending_date) : '') }}
                                                    </span>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'mapa.inspection_date', 'M.A.P.A Inspection date', order.id )">
                                                    <span :class="order.dateClasses.mapa">
                                                        {{ FormatDate(order.mapa.inspection_date) }}
                                                    </span>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'fumigation.init', 'Fumigation', order.id )">
                                                    <span :class="order.dateClasses.fumigation">
                                                        {{ FormatDateTime(order.fumigation.init) }}
                                                    </span>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'dateClasses.aeration', 'Aeration', order.id )">
                                                    <span :class="order.dateClasses.aeration">
                                                        {{ FormatDateTime(order.fumigation.end) }}
                                                    </span>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'shipping.draft_deadline', 'Draft Deadline', order.id )">
                                                    <span :class="order.dateClasses.draft">
                                                        {{ (order.shipping ? FormatDateTime(order.shipping.draft_deadline) : '')}}
                                                    </span>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'shipping.loading_deadline', 'Loading Deadline', order.id )">
                                                    <span :class="order.dateClasses.loading">
                                                        {{ (order.shipping ? FormatDateTime(order.shipping.loading_deadline) : '') }}
                                                    </span>
                                                </button>
                                            </td>
                                            <td>
                                                    <span :class="order.dateClasses.free_time"
                                                        :style="order.freeTimeLimit && moment(order.freeTimeLimit).isBefore(moment()) ? 'color: red' : ''">
                                                        {{ (order.container_stuffing && order.freeTimeLimit ? FormatDate(order.freeTimeLimit) : '') }}
                                                    </span>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'shipping.etd', 'ETD', order.id )">
                                                    <span :class="order.dateClasses.etd">
                                                        {{ (order.shipping ? FormatDate(order.shipping.etd) : '') }}
                                                    </span>
                                                </button>
                                            </td>
                                            <td>
                                                {{ (order.mapa.lpco_key ?? '') }}
                                            </td>
                                            <td>
                                                {{ (order.mapa.due_code ?? '') }}
                                            </td>
                                            <td>
                                                {{ order.nf ? split_nfe_key(order.nf) : '' }}
                                            </td>
                                            <td>
                                                {{ (order.mapa.dossie ?? '') }}
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('note', 'note', 'Note', order.id )">
                                                    <span v-if="order.note">{{ order.note }}</span>
                                                    <span v-else><i class="fas fa-plus"></i></span>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr v-if="$parent.$route.query.status == 'aftership'">
                                            <td>
                                                <div class="dropdown dropright">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <router-link
                                                            :to="{ name: 'Order information', params: { id: order.id } }"
                                                            class="dropdown-item"
                                                            v-if="order.status.id == 3 || order.status.information == true">Order
                                                            information</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-view-" + index'>Documents</button>

                                                        <router-link
                                                            :to="{ name: 'New order', query: { copy_id: order.id } }"
                                                            class="dropdown-item">Copy</router-link>

                                                        <router-link :to="{ name: 'Edit order', params: { id: order.id } }"
                                                            class="dropdown-item">Edit</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-" + index'>Delete</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ order.number }}</td>
                                            <td>{{ (order.items[0] ? order.items[0].description : '') }}</td>
                                            <td>{{ (order.items[0] ? FormatNumber(order.items[0].net_weight) : '') }}</td>
                                            <td>{{ order.port_destiny }}</td>
                                            <td>{{ (order.shipping ? order.shipping.booking : '') }}</td>
                                            <td>{{ order.shipping.vessel }}</td>
                                            <td>{{ order.shipping.shipping_line }}</td>
                                            <td>{{ (order.container_stuffing ? order.container_stuffing.qtd_container : '')
                                            }}</td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'shipping.etd', 'ETD', order.id )">
                                                    {{ FormatDate((order.shipping ? order.shipping.etd : '')) }}
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('date', 'shipping.eta', 'ETA', order.id )">
                                                    {{ FormatDate((order.shipping ? order.shipping.eta : '')) }}
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('dhl', 'dhl_tracking_number', 'DHL', order.id )">
                                                    <span v-if="order.dhl_tracking_number">{{ order.dhl_tracking_number }}</span>
                                                    <span v-else><i class="fas fa-plus"></i></span>
                                                </button>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('note', 'note', 'Note', order.id )">
                                                    <span v-if="order.note">{{ order.note }}</span>
                                                    <span v-else><i class="fas fa-plus"></i></span>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr v-if="$parent.$route.query.status == 'filter'">
                                            <td>{{ order.number }}</td>
                                            <td>{{ order.owner.name }}</td>
                                            <td>{{ (order.items[0] ? order.items[0].description : '') }}</td>
                                            <td>{{ (order.items[0] ? order.items[0].container : '') }}</td>
                                            <td>{{ (order.items[0] ? FormatNumber(order.items[0].net_weight) : '') }}
                                            </td>
                                            <td>
                                                {{ order.loadings ? FormatNumber(order.loadings.vehicles.reduce((acc,
                                                    cur) => {
                                                    return acc + cur.wheight;
                                                }, 0)) : 0 }}
                                            </td>
                                            <td>
                                                {{ order.loadings && order.items[0] ?
                                                    FormatNumber(order.items[0].net_weight -
                                                        order.loadings.vehicles.reduce((acc, cur) => {
                                                            return acc + cur.wheight;
                                                        }, 0)) : 0 }}
                                            </td>
                                            <td>
                                                <table v-if="order.loadings">
                                                    <thead>
                                                        <tr>
                                                            <th>Weight</th>
                                                            <th colspan="2">Unloading Date</th>
                                                            <th>Driver</th>
                                                            <th>License Plate</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-for="truck in order.loadings.vehicles">
                                                        <tr>
                                                            <td>{{ FormatNumberDecimal(truck.wheight) }}</td>
                                                            <td>{{ FormatDate(truck.truck_unloading_datetime) }}</td>
                                                            <td>{{ FormatTime(truck.truck_unloading_datetime) }}</td>
                                                            <td>{{ truck.driver || truck.drivers.name }}</td>
                                                            <td>{{ truck.plate }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>{{ order.port_destiny }}</td>
                                            <td>{{ order.shipment }}</td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('note', 'note', 'Note', order.id )">
                                                    <span v-if="order.note">{{ order.note }}</span>
                                                    <span v-else><i class="fas fa-plus"></i></span>
                                                </button>
                                            </td>
                                        </tr>

                                        <tr v-if="$parent.$route.query.status == 'missing_docs'">
                                            <td>
                                                <div class="dropdown dropright">
                                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <router-link
                                                            :to="{ name: 'Order information', params: { id: order.id } }"
                                                            class="dropdown-item"
                                                            v-if="order.status.id == 3 || order.status.information == true">Order
                                                            information</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-view-" + index'>Documents</button>

                                                        <router-link
                                                            :to="{ name: 'New order', query: { copy_id: order.id } }"
                                                            class="dropdown-item">Copy</router-link>

                                                        <router-link :to="{ name: 'Edit order', params: { id: order.id } }"
                                                            class="dropdown-item">Edit</router-link>

                                                        <button type="button" class="dropdown-item" data-toggle="modal"
                                                            :href='"#modal-" + index'>Delete</button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ order.number }}</td>
                                            <td>{{ (order.shipping ? order.shipping.booking : '') }}</td>
                                            <td>
                                                <span class="btn disabled">Draft</span><br /><br />
                                                <span class="btn disabled">Original</span>
                                            </td>

                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.draft_comercial"
                                                    class="btn btn-success" :href="order.drafts.draft_comercial"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span v-else-if="order.required_docs && order.required_docs.draft_comercial"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.comercial"
                                                    class="btn btn-success" :href="order.originals.comercial"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span v-else-if="order.required_docs && order.required_docs.draft_comercial"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.packing_list" class="btn btn-success"
                                                    :href="order.drafts.packing_list" target="_blank"
                                                    title="View document"><i class="fas fa-file"></i></a>
                                                <span v-else-if="order.required_docs && order.required_docs.packing_list"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.packing_list"
                                                    class="btn btn-success" :href="order.originals.packing_list"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span v-else-if="order.required_docs && order.required_docs.packing_list"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.draft_bl" class="btn btn-success"
                                                    :href="order.drafts.draft_bl" target="_blank" title="View document"><i
                                                        class="fas fa-file"></i></a>
                                                <span v-else-if="order.required_docs && order.required_docs.draft_bl"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.bl" class="btn btn-success"
                                                    :href="order.originals.bl" target="_blank" title="View document"><i
                                                        class="fas fa-file"></i></a>
                                                <span v-else-if="order.required_docs && order.required_docs.draft_bl"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.certificate_origin"
                                                    class="btn btn-success" :href="order.drafts.certificate_origin"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_origin"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.certificate_origin"
                                                    class="btn btn-success" :href="order.originals.certificate_origin"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_origin"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.certificate_fumigation"
                                                    class="btn btn-success" :href="order.drafts.certificate_fumigation"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_fumigation"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.certificate_fumigation"
                                                    class="btn btn-success" :href="order.originals.certificate_fumigation"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_fumigation"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.certificate_phyto"
                                                    class="btn btn-success" :href="order.drafts.certificate_phyto"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_phyto"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.certificate_phyto"
                                                    class="btn btn-success" :href="order.originals.certificate_phyto"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_phyto"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.certificate_seguro"
                                                    class="btn btn-success" :href="order.drafts.certificate_seguro"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_seguro"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.certificate_seguro"
                                                    class="btn btn-success" :href="order.originals.certificate_seguro"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_seguro"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.certificate_quality"
                                                    class="btn btn-success" :href="order.drafts.certificate_quality"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_quality"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.certificate_quality"
                                                    class="btn btn-success" :href="order.originals.certificate_quality"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_quality"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.certificate_weight"
                                                    class="btn btn-success" :href="order.drafts.certificate_weight"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_weight"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.certificate_weight"
                                                    class="btn btn-success" :href="order.originals.certificate_weight"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_weight"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.report" class="btn btn-success"
                                                    :href="order.drafts.report" target="_blank" title="View document"><i
                                                        class="fas fa-file"></i></a>
                                                <span v-else-if="order.required_docs && order.required_docs.report"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.report" class="btn btn-success"
                                                    :href="order.originals.report" target="_blank" title="View document"><i
                                                        class="fas fa-file"></i></a>
                                                <span v-else-if="order.required_docs && order.required_docs.report"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.health_certificate"
                                                    class="btn btn-success" :href="order.drafts.health_certificate"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.health_certificate"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.health_certificate"
                                                    class="btn btn-success" :href="order.originals.health_certificate"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.health_certificate"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.drafts && order.drafts.non_gmo_certificate"
                                                    class="btn btn-success" :href="order.drafts.non_gmo_certificate"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.non_gmo_certificate"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.originals && order.originals.non_gmo_certificate"
                                                    class="btn btn-success" :href="order.originals.non_gmo_certificate"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.non_gmo_certificate"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span>
                                            </td>
                                            <td class="text-center">
                                                <a v-if="order.mapa_docs && order.mapa_docs.draft_lpco"
                                                    class="btn btn-success" :href="order.mapa_docs.draft_lpco"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                <span
                                                    v-else-if="order.required_docs && order.required_docs.certificate_phyto"
                                                    class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                                <span v-else class="btn btn-info disabled" title="Document missing"><i
                                                        class="fas fa-minus"></i></span><br /><br />
                                                        
                                                    <a class="btn btn-success" :href="order.mapa_docs.original_lpco ?? '#'"
                                                    target="_blank" title="View document"><i class="fas fa-file"></i></a>
                                                    
                                            </td>
                                            <td class="text-center">
                                                <span class="btn btn-info disabled"><i
                                                        class="fas fa-minus"></i></span><br /><br />

                                                <a v-if="order.mapa_docs && order.mapa_docs.due" target="_blank"
                                                    class="btn btn-success" :href="order.mapa_docs.due"
                                                    title="View document"><i class="fas fa-file"></i></a>
                                                <span v-else class="btn btn-danger disabled" title="Document required"><i
                                                        class="fas fa-times"></i></span>
                                            </td>
                                            <td class="text-center others">
                                                <template
                                                    v-if="(order.draft_others && order.draft_others.length) || (order.required_docs && parseInt(order.required_docs.others))">
                                                    <template v-if="order.draft_others && order.draft_others.length">
                                                        <template v-for="     doc      in      order.draft_others     ">
                                                            <a class="btn btn-success" :href="doc" target="_blank"
                                                                title="View document"><i class="fas fa-file"></i></a>&nbsp;
                                                        </template>
                                                    </template>

                                                    <template
                                                        v-if="order.required_docs && parseInt(order.required_docs.others)">
                                                        <template
                                                            v-for="     n      in      (order.required_docs.others - (order.draft_others && order.draft_others.length <= order.required_docs.others ? order.draft_others.length : order.required_docs.others))     ">
                                                            <span class="btn btn-danger disabled"
                                                                title="Required document"><i
                                                                    class="fas fa-times"></i></span>&nbsp;
                                                        </template>
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    <span class="btn btn-info disabled"><i class="fas fa-minus"></i></span>
                                                </template>
                                                <br /><br />
                                                <template
                                                    v-if="(order.original_others && order.original_others.length) || (order.required_docs && parseInt(order.required_docs.others))">
                                                    <template v-if="order.original_others && order.original_others.length">
                                                        <template v-for="     doc      in      order.original_others     ">
                                                            <a class="btn btn-success" :href="doc.url" target="_blank"
                                                                title="View document"><i class="fas fa-file"></i></a>&nbsp;
                                                        </template>
                                                    </template>

                                                    <template
                                                        v-if="order.required_docs && parseInt(order.required_docs.others)">
                                                        <template
                                                            v-for="     n      in      (order.required_docs.others - (order.original_others && order.original_others.length <= order.required_docs.others ? order.original_others.length : order.required_docs.others))     ">
                                                            <span class="btn btn-danger disabled"
                                                                title="Required document"><i
                                                                    class="fas fa-times"></i></span>&nbsp;
                                                        </template>
                                                    </template>
                                                </template>
                                                <template v-else>
                                                    <span class="btn btn-info disabled"><i class="fas fa-minus"></i></span>
                                                </template>
                                            </td>
                                            <td>
                                                <button type="button" class="edit-button" @click="openModal('note', 'note', 'Note', order.id )">
                                                    <span v-if="order.note">{{ order.note }}</span>
                                                    <span v-else><i class="fas fa-plus"></i></span>
                                                </button>
                                            </td>
                                        </tr>

                                        <div class="modal fade text-left" :id="'modal-' + index">
                                            <div class="modal-dialog modal-danger">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Are you sure?</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Deleting the request will no longer be possible to see
                                                            your data in the panel and is an irreversible action.
                                                        </p>
                                                        <p>Are you sure you want to continue?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger"
                                                            @click="Exclude(order.id)">Confirm exclusion</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade text-left" :id="'modal-view-' + index">
                                            <div class="modal-dialog">
                                                <div class="modal-content modal-lg">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">View</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true">&times;</button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="row">
                                                            <div class="col-12" v-if="order.document_contract_signed">
                                                                <div class="form-group">
                                                                    <label for="contract">Contract Signed</label>
                                                                    <a :href="order.document_contract_signed.url"
                                                                        download>[Download]</a>
                                                                    <select class="form-control" name="select1"
                                                                        v-model="order.document_contract_signed.document_status_id"
                                                                        v-bind:class="error.status != '' && error.status ? 'is-invalid select2-hidden-accessible' : ''">
                                                                        <option value="">Select status</option>
                                                                        <option
                                                                            v-for="     status      in      document_status     "
                                                                            :value="status.id">{{ status.name }}
                                                                        </option>
                                                                    </select>

                                                                </div>
                                                                <div class="form-group"
                                                                    v-if="order.document_contract_signed">
                                                                    <textarea style="height: 50px;" name=""
                                                                        v-if="order.document_contract_signed.document_status_id == 2"
                                                                        v-model="order.document_contract_signed.reason"
                                                                        id="" cols="30" rows="10" class="form-control"
                                                                        placeholder="Reason"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-12" v-if="!order.document_contract_signed">
                                                                <p>Contract Signed</p>
                                                                <div class="alert alert-danger">Customer did not
                                                                    send this document</div>
                                                            </div>
                                                            <div class="col-12" v-if="order.document_proforma_signed">
                                                                <div class="form-group">
                                                                    <label for="proforma">Proforma Signed</label>
                                                                    <a :href="order.document_proforma_signed.url"
                                                                        download>[Download]</a>

                                                                    <select class="form-control" name="select1"
                                                                        v-model="order.document_proforma_signed.document_status_id"
                                                                        v-bind:class="error.status != '' && error.status ? 'is-invalid select2-hidden-accessible' : ''">
                                                                        <option value="">Select status</option>
                                                                        <option
                                                                            v-for="     status      in      document_status     "
                                                                            :value="status.id">{{ status.name }}
                                                                        </option>
                                                                    </select>

                                                                </div>
                                                                <div class="form-group"
                                                                    v-if="order.document_proforma_signed">
                                                                    <textarea style="height: 50px;" name=""
                                                                        v-if="order.document_proforma_signed.document_status_id == 2"
                                                                        v-model="order.document_proforma_signed.reason"
                                                                        id="" cols="30" rows="10" class="form-control"
                                                                        placeholder="Reason"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-12" v-if="!order.document_proforma_signed">
                                                                <p>Proforma Signed</p>
                                                                <div class="alert alert-danger">Customer did not
                                                                    send this document</div>
                                                            </div>
                                                            <div class="col-12" v-if="order.document_swift_advance">
                                                                <div class="form-group">
                                                                    <label for="swift_advance">Swift Advance</label>
                                                                    <a :href="order.document_swift_advance.url"
                                                                        download>[Download]</a>

                                                                    <select class="form-control" name="select1"
                                                                        v-model="order.document_swift_advance.document_status_id"
                                                                        v-bind:class="error.status != '' && error.status ? 'is-invalid select2-hidden-accessible' : ''">
                                                                        <option value="">Select status</option>
                                                                        <option
                                                                            v-for="     status      in      document_status     "
                                                                            :value="status.id">{{ status.name }}
                                                                        </option>
                                                                    </select>

                                                                </div>
                                                                <div class="form-group" v-if="order.document_swift_advance">
                                                                    <textarea style="height: 50px;" name=""
                                                                        v-if="order.document_swift_advance.document_status_id == 2"
                                                                        v-model="order.document_swift_advance.reason" id=""
                                                                        cols="30" rows="10" class="form-control"
                                                                        placeholder="Reason"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-12" v-if="!order.document_swift_advance">
                                                                <p>Swift Advance</p>
                                                                <div class="alert alert-danger">Customer did not
                                                                    send this document</div>
                                                            </div>
                                                            <div class="col-12" v-if="order.document_label_model">
                                                                <div class="form-group">
                                                                    <label for="label_model">Label model</label>
                                                                    <a :href="order.document_label_model.url"
                                                                        download>[Download]</a>

                                                                    <select class="form-control" name="select1"
                                                                        v-model="order.document_label_model.document_status_id"
                                                                        v-bind:class="error.status != '' && error.status ? 'is-invalid select2-hidden-accessible' : ''">
                                                                        <option value="">Select status</option>
                                                                        <option
                                                                            v-for="     status      in      document_status     "
                                                                            :value="status.id">{{ status.name }}
                                                                        </option>
                                                                    </select>

                                                                </div>
                                                                <div class="form-group" v-if="order.document_label_model">
                                                                    <textarea style="height: 50px;" name=""
                                                                        v-if="order.document_label_model.document_status_id == 2"
                                                                        v-model="order.document_label_model.reason" id=""
                                                                        cols="30" rows="10" class="form-control"
                                                                        placeholder="Reason"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-12" v-if="!order.document_label_model">
                                                                <p>Label model</p>
                                                                <div class="alert alert-danger">Customer did not
                                                                    send this document</div>
                                                            </div>
                                                            <div class="col-12"
                                                                v-if="order.document_instructions_documents">
                                                                <div class="form-group">
                                                                    <label for="swift_advance">Instructions
                                                                        Documents</label>
                                                                    <a :href="order.document_instructions_documents.url"
                                                                        download>[Download]</a>

                                                                    <select class="form-control" name="select1"
                                                                        v-model="order.document_instructions_documents.document_status_id"
                                                                        v-bind:class="error.status != '' && error.status ? 'is-invalid select2-hidden-accessible' : ''">
                                                                        <option value="">Select status</option>
                                                                        <option
                                                                            v-for="     status      in      document_status     "
                                                                            :value="status.id">{{ status.name }}
                                                                        </option>
                                                                    </select>

                                                                </div>
                                                                <div class="form-group"
                                                                    v-if="order.document_instructions_documents">
                                                                    <textarea style="height: 50px;" name=""
                                                                        v-if="order.document_instructions_documents.document_status_id == 2"
                                                                        v-model="order.document_instructions_documents.reason"
                                                                        id="" cols="30" rows="10" class="form-control"
                                                                        placeholder="Reason"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-12"
                                                                v-if="!order.document_instructions_documents">
                                                                <p>Instructions Documents</p>
                                                                <div class="alert alert-danger">Customer did not
                                                                    send this document</div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"
                                                            data-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-success"
                                                            @click="UpdateDocumentStatus(order)">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <tr>
                                            <td colspan="11"
                                                v-if="order.shipping && order.shipping.booking && $parent.$route.query.status == 'filter'">
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
                                                        <td>{{ (order.shipping ? order.shipping.booking : '') }}</td>
                                                        <td>{{ (order.container_stuffing ?
                                                            order.container_stuffing.qtd_container : '') }}</td>
                                                        <td>{{ FormatDate(order.mapa) }}</td>
                                                        <td>{{ FormatDateTime(order.fumigation.init) }}</td>
                                                        <td>{{ FormatDateTime(order.fumigation.end) }}</td>
                                                        <td>{{ FormatDate((order.shipping ? order.shipping.draft_deadline :
                                                            '')) }}</td>
                                                        <td>{{ FormatDate((order.shipping ? order.shipping.loading_deadline
                                                            : '')) }}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </template>

                                    <dinamic-modal
                                        v-if="dinamicModal.isOpen"
                                        :modalId="dinamicModal.id"
                                        :modalTitle="dinamicModal.title"
                                        :contentComponent="dinamicModal.component"
                                        :componentProps="dinamicModal.props"
                                        :contentHtml="dinamicModal.html"
                                        :saveAction="dinamicModal.saveAction"
                                        @callback="handleDinamicModalCallback"
                                        @update-data="handleUpdateData"
                                    />

                                    <providers-modal
                                        v-if="providersModalIsOpen"
                                        :type="providerType"
                                        :index="providerIndex"
                                        :data="providerData"
                                        :error="providerError"
                                        @callback="handleProvidersModalCallback"
                                    />

                                    <tr v-if="orders.length && this.$route.query.status == 'pending'">
                                        <th colspan="3"> Total </th>
                                        <th>
                                            {{ total_container }}
                                        </th>
                                        <th colspan="3">{{ FormatNumber(orders.reduce((acc, order) => {
                                            return acc +
                                                parseInt((order.items[0] ? order.items[0].net_weight : 0));
                                        }, 0)) }} </th>
                                    </tr>

                                    <tr v-if="orders.length && this.$route.query.status == 'loading'">
                                        <th colspan="2"> Total </th>
                                        <th>
                                            {{
                                                FormatNumber(orders.reduce((acc, cur) => {
                                                    return acc + (cur.items[0] ? cur.items[0].net_weight : 0);
                                                }, 0))
                                            }}
                                        </th>
                                        <th>
                                            {{
                                                FormatNumber(orders.reduce((acc, cur) => {
                                                    return acc + (cur.loadings && cur.loadings.vehicles ?
                                                        cur.loadings.vehicles.reduce((acc2, cur2) => {
                                                            return acc2 + cur2.wheight;
                                                        }, 0) : 0);
                                                }, 0))
                                            }}
                                        </th>
                                        <th colspan="3">
                                            {{
                                                FormatNumber(orders.reduce((acc, cur) => {
                                                    return acc + (cur.items[0] ? cur.items[0].net_weight : 0) - (cur.loadings &&
                                                        cur.loadings.vehicles ? cur.loadings.vehicles.reduce((acc2, cur2) => {
                                                            return acc2 + cur2.wheight;
                                                        }, 0) : 0);
                                                }, 0))
                                            }}
                                        </th>
                                    </tr>

                                    <tr v-if="orders.length && this.$route.query.status == 'transit'">
                                        <th colspan="3"> Total </th>
                                        <th colspan="6">
                                            {{
                                                FormatNumber(orders.reduce((acc, cur) => {
                                                    return acc + (cur.items[0] ? cur.items[0].net_weight : 0);
                                                }, 0))
                                            }}
                                        </th>
                                    </tr>

                                    <tr v-if="orders.length && this.$route.query.status == 'release'">
                                        <th colspan="2"> Total </th>
                                        <th>
                                            {{
                                                orders.reduce((acc, cur) => {
                                                    return acc + (cur.container_stuffing ?
                                                        parseInt(cur.container_stuffing.qtd_container) || 0 : 0);
                                                }, 0)
                                            }}
                                        </th>
                                        <th colspan="13"> &nbsp; </th>
                                    </tr>

                                    <tr v-if="orders.length && this.$route.query.status == 'aftership'">
                                        <th colspan="2"> Total </th>
                                        <th colspan="3">
                                            {{
                                                FormatNumber(orders.reduce((acc, cur) => {
                                                    return acc + (cur.items[0] ? cur.items[0].net_weight : 0);
                                                }, 0))
                                            }}
                                        </th>
                                        <th colspan="7">
                                            {{
                                                orders.reduce((acc, cur) => {
                                                    return acc + (cur.container_stuffing ?
                                                        parseInt(cur.container_stuffing.qtd_container) || 0 : 0);
                                                }, 0)
                                            }}
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3" v-if="this.$route.query.status == 'release'">
                            <div class="col text-right">
                                Date indicators: <span class="done">Done</span>&emsp;<span class="last-update">Last
                                    Update</span>&emsp;<span class="today">Today</span>&emsp;<span class="next-step">Next Step</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" v-if="orders.length && this.$route.query.status == 'pending'">
                        <button class="btn btn-success btn-block" @click="generateReport()"> Export PDF </button>
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
.scrollme{
    min-height: calc(100vh - 450px) !important;
}
.badge {
    width: 100%;
    max-width: 70px;
    line-height: inherit;
    white-space: normal;
}

.scrollme {
    overflow-x: auto;
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

.card-header .nav-tabs {
    margin-bottom: -0.3rem !important;
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

.btn.disabled {
    opacity: 1 !important;
}

.others {
    white-space: nowrap;
}

.btn-warning,
.btn-warning:hover,
.btn-warning:active {
    color: white;
}

.last-update {
    display: inline-block;
    padding: 4px 10px;
    border: 0;
    border-radius: 20px;
    background-color: rgb(26, 118, 189);
    color: #FFF !Important;
    /* border-bottom: 3px solid blue; */
}

.next-step {
    display: inline-block;
    padding: 4px 10px;
    border: 0;
    border-radius: 20px;
    background-color: rgb(97, 155, 39);
    color: #FFF !Important;
    /* border-bottom: 3px solid lawngreen; */
}

.done {
    display: inline-block;
    padding: 4px 10px;
    border: 0;
    border-radius: 20px;
    background-color: rgb(222, 219, 123);
    
    color: #333!Important;
    /* border-bottom: 3px solid red; */
}

.today {
    display: inline-block;
    padding: 4px 10px;
    border: 0;
    border-radius: 20px;
    background-color: rgb(232, 81, 81);
    color: #ffffff !Important;
    /* border-bottom: 3px solid red; */
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

.filter input {
    padding-right: 30px;
}

.mtest {
    margin-top: 0.2rem;
}

.dropdown-submenu {
  position: relative;
}

.dropdown-submenu>.dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: 0px;
  margin-left: -1px;
  -webkit-border-radius: 6px;
  -moz-border-radius: 6px;
  border-radius: 6px;
}

.dropdown-submenu:hover>.dropdown-menu {
  display: block;
}

.dropdown-submenu>#sub-toogle:after {
  display: block;
  content: " ";
  float: right;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
  border-width: 5px 0 5px 5px;
  border-left-color: #4e4e4e;
  margin-top: 5px;
  margin-right: -10px;
}

.dropdown-submenu:hover>#sub-toogle:after {
  border-left-color: #4e4e4e;
}

.dropdown-submenu.pull-left {
  float: none;
}

.dropdown-submenu.pull-left>.dropdown-menu {
  left: -100%;
  margin-left: 10px;
  -webkit-border-radius: 6px 0 6px 6px;
  -moz-border-radius: 6px 0 6px 6px;
  border-radius: 6px 0 6px 6px;
}
</style>

<script>
import Details from './OrderDetails'
import EtdModal from '../components/EtdModal';
import EtaModal from '../components/EtaModal';
import DhlModal from '../components/DhlModal';
import NoteModal from '../components/NoteModal';
import DateModal from '../components/DateModal';

import queryString from 'query-string'
import moment from 'moment'
import { resolveComponent } from 'vue';

export default {
    components: {
        Details,
        'etd-modal': EtdModal,
        'eta-modal': EtaModal,
        'dhl-modal': DhlModal,
        'note-modal': NoteModal,
        'date-modal': DateModal,
    },
    data() {
        return {
            dateType: [],
            dateTypesOptions: [],
            dateTypesOptionsDefault: [
                { id: 1, name: 'Order creation' },
                { id: 2, name: 'eta' },
                { id: 3, name: 'etd' }
            ],
            dateTypesOptionsInProcessRelease: [
                { id: 4, name: 'M.A.P.A Inspection' },
                { id: 5, name: 'Fumigation' },
                { id: 6, name: 'Aeration' },
                { id: 7, name: 'Draft Deadline' },
                { id: 8, name: 'Loading Deadline' }
            ],
            total_container: 0,
            orders: [],
            formData: {},
            page_orders: [],
            status: [],
            error: [],
            filters: [''],
            globalFilter: '',
            lastStatus: '',
            filterTimeout: null,
            document_status: [
                { name: 'In analysis', id: '1' },
                { name: 'Rejected', id: '2' },
                { name: 'Approved', id: '3' }
            ],
            moment: moment,
            loading: null,
            providersModalIsOpen: false,
            providerType: {},
            providerData: {},
            providerIndex: {},
            providerError: {},
            dinamicModal:{
                isOpen: false,
                id: null,
                title: '',
                component: null,
                props: {},
                html: '',
                saveAction: null
            },
            defaultModalValues: {
                date: {
                    id: 'date',
                    title: 'Edit Date',
                    component: 'date-modal',
                    saveAction: this.SaveDate,
                },
                etd: {
                    id: 'etd',
                    title: 'Edit ETD',
                    component: 'etd-modal',
                    saveAction: this.SaveEtd,
                },
                eta: {
                    id: 'eta',
                    title: 'Edit ETA',
                    component: 'eta-modal',
                    saveAction: this.SaveEta,
                },
                dhl: {
                    id: 'dhl',
                    title: 'Edit DHL number',
                    component: 'dhl-modal',
                    saveAction: this.SaveDhl,
                },
                note: {
                    id: 'note',
                    title: 'Edit Note',
                    component: 'note-modal',
                    saveAction: this.SaveNote,
                },
            },
            orderData: {}
        }
    },

    methods: {
        customLabel ({ name }) {
            return `${name}`
        },
        getAttr(typeValue) {
            const parts = typeValue.split('.');
            if (parts.length > 1) {
                return parts[1];
            } else {
                return typeValue;
            }
        },
        openModal(modalType, typeValue, labelValue, orderId) {

            const modal = this.defaultModalValues[modalType];
            const orderToUpdate = this.orders.find(order => order.id === orderId);

            this.dinamicModal = {
                isOpen: true,
                id: modal.id,
                title: modal.title +' '+ orderToUpdate.number,
                component: this.$options.components[modal.component],
                props: {
                    data: orderToUpdate,
                    error: null,
                    modalIsOpen: this.dinamicModal.isOpen,
                    type: typeValue,
                    field: this.getAttr(typeValue),
                    label: labelValue
                },
                html: modal.html || '',
                saveAction: modal.saveAction,

            };
            
            
            this.$nextTick(() => {
                this.dinamicModal.isOpen = true;
                $('#dinamic-modal').modal('show');
            });
        },

        handleDinamicModalCallback(payload) {
            this.dinamicModal.isOpen = false;
            $('#dinamic-modal').modal('hide');
        },

        getFieldValue(data, path) {
            const keys = path.split('.');
            let current = data;
            for (let key of keys) {
                if (!current[key]) return null;
                current = current[key];
            }
            console.log('getFieldValue: ',current)
            return current;
        },

        updateNestedProperty(data, path, value) {

            const keys = path.length > 1 ? path.split('.') : path;
            let current = data;

            for (let i = 0; i < keys.length - 1; i++) {
                if (!current[keys[i]]) {
                    this.$set(current, keys[i], {});
                }
                current = current[keys[i]];
            }
            
            this.$set(current, keys[keys.length - 1], value);
        },

        handleUpdateData({ type, value, orderId }) {
            const pageOrderToUpdate = this.page_orders.find(order => order.id === orderId);
            
            if (pageOrderToUpdate) {
                this.updateNestedProperty(pageOrderToUpdate, type, value);
            }
        },

        SaveDate(){
            const order = this.dinamicModal.props.data;
            const type = this.dinamicModal.props.type;
            const newDate = this.getFieldValue(order, type)
            console.log(order, type)
            this.$http.post('/api/order/'+order.id+'/date', { date: newDate, type: type })
                .then(() => {
                    this.dinamicModal.isOpen = false;
                    $('#dinamic-modal').modal('hide');
                    this.$toaster.success('Date saved')
                })
                .catch(error => {
                    this.$toaster.error('Error saving date')
                })
        },

        SaveDhl(){
            const orderId = this.dinamicModal.props.data.id;
            const orderDhl = this.dinamicModal.props.data.dhl_tracking_number;

            this.$http.post('/api/order/'+orderId+'/dhl', { dhl: orderDhl })
                .then(() => {
                    $('#dinamic-modal').modal('hide');
                    this.$toaster.success('Dhl saved')
                })
                .catch(error => {
                    this.$toaster.error('Error saving dhl')
                })
        },

        SaveNote(){
            const orderId = this.dinamicModal.props.data.id;
            const orderNote = this.dinamicModal.props.data.note;

            this.$http.post('/api/order/'+orderId+'/note', { note: orderNote })
                .then(() => {
                    $('#dinamic-modal').modal('hide');
                    this.$toaster.success('Note saved')
                })
                .catch(error => {
                    console.log(error.message);
                    this.$toaster.error('Error saving note')
                })
        },
        
        handleProvidersModalCallback({status, data}) {
            data && this._filterOrders();
            $('#provider-modal').modal('hide');
            this.providersModalIsOpen = false;
        },

        openProviderModal(type, data, index) {

            this.providerType = type;
            this.providerIndex = index;
            this.providerData = data;
            this.providersModalIsOpen = true;

            this.$nextTick(() => {
                $('#provider-modal').modal('show');
            });

        },

        badge_color(status) {
            let badge_color;

            switch (status) {
                case "Waiting":
                    badge_color = "bg-primary"
                    break;
                case "Waiting for documents":
                    badge_color = 'bg-secondary'
                    break;
                case "Waiting for shipping approval":
                    badge_color = 'bg-warning'
                    break;
                default: 'bg-primary'
                    break;
            }

            return `badge ${badge_color}`;
        },
        
        FormatNumber: function (value, fixed = 2) {
            let value_fixed = value.toFixed(fixed);
            let [value_decimal, value_fraction] = value_fixed.split(/\./);

            value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");

            value_fixed = value_decimal + ',' + value_fraction;
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
            if (!date) return '';
            var d = new Date(date)
            var month = (d.getUTCMonth() + 1).toString()
            var day = d.getUTCDate().toString()
            var year = d.getUTCFullYear().toString()

            month = month.length < 2 ? "0" + month : month
            day = day.length < 2 ? "0" + day : day

            return month + "/" + day + "/" + year
        },

        FormatDateTime: function (date) {
            if (!date) return '';
            var d = new Date(date)
            var month = (d.getUTCMonth() + 1).toString()
            var day = d.getUTCDate().toString()
            var year = d.getUTCFullYear().toString()
            var hours = d.getUTCHours().toString()
            var minutes = d.getUTCMinutes().toString()
            var seconds = d.getUTCSeconds().toString()

            month = month.length < 2 ? "0" + month : month
            day = day.length < 2 ? "0" + day : day
            hours = hours.length < 2 ? "0" + hours : hours
            minutes = minutes.length < 2 ? "0" + minutes : minutes
            seconds = seconds.length < 2 ? "0" + seconds : seconds

            return month + "/" + day + "/" + year + " " + hours + ":" + minutes
        },

        FormatTime: function (date) {
            if (!date) return '';
            var d = new Date(date)
            var hours = d.getUTCHours().toString()
            var minutes = d.getUTCMinutes().toString()
            var seconds = d.getUTCSeconds().toString()

            hours = hours.length < 2 ? "0" + hours : hours
            minutes = minutes.length < 2 ? "0" + minutes : minutes
            seconds = seconds.length < 2 ? "0" + seconds : seconds

            return hours + ":" + minutes
        },

        Exclude: function (id) {
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
                    self.$data.orders.splice(index, 1)
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

            this.$http.put('/api/document/', data).then(e => {
                if (!e.body.success) {
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

        GetStatus: function (order) {
            return order.status.name
        },

        DateToTimestamp(date) {
            if (!date) return 0;
            let [year, month, day] = date.split(/-/);
            let dt = new Date(year, month, day);
            return dt.getTime();
        },

        forceFileDownload(response, name) {

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
            let url = '/api/reports/pending_orders/export?' + this.getQuery();
            this.generateFile(url, 'relatorio-pending_orders');
        },

        getQuery: function () {

            let params = {
                status: this.$route.query.status || "all",
                filters: this.globalFilter || (this.filters || ''),
                date_type: this.dateType.id,
                initial_date: this.initial_date,
                final_date: this.final_date,
            }
            console.log('params: ', params)
            return queryString.stringify(params, { arrayFormat: 'bracket' });
        },

        clearFilters: function () {
            this.filter = null;
            this.initial_date = null;
            this.final_date = null;

            this._filterOrders();
        },

        _filterOrders: async function () {
            
            this.orders = this.page_orders = [];
            this.loading = true;

            let response = await this.$http.get('/api/orders?' + this.getQuery());

            if (response.body && response.body.orders) {
                this.loading = false;
                let orders = response.body.orders;
                let status = response.body.status;
                let filters = response.body.filters;
                this.total_container = response.body.total_container;

                if (typeof orders == "object" && orders !== null) {
                    orders = Object.values(orders);
                }

                if ((status == this.$route.query.status || status == 'all' &&
                    !this.$route.query.status) &&
                    ((status == 'filter' && filters == this.globalFilter) ||
                    JSON.stringify(filters.filter(Boolean).map(f => f.trim())) == JSON.stringify(this.filters.filter(Boolean).map(f => f.trim())))) {
                    this.orders = this.page_orders = this.sortOrders(orders);
                }

                if (this.$route.query.status == 'release') {

                    this.orders = this.orders.map(function (order) {

                        if (order.container_stuffing && order.container_stuffing.empty_release_for_outbound_date) {
                            const emptyReleaseDate = moment(order.container_stuffing.empty_release_for_outbound_date);
                            const freeTimeOrigin = order.shipping ? parseInt(order.shipping.free_time_origin) : 0;
                            order.freeTimeLimit = emptyReleaseDate.add(freeTimeOrigin, 'days').format('YYYY-MM-DD');
                        } else {
                            order.freeTimeLimit = null;
                        }

                        let dates = {
                            'empty_release': (order.container_stuffing ? order.container_stuffing.empty_release_for_outbound_date : null),
                            'stuffing_start': (order.container_stuffing ? order.container_stuffing.stuffing_starting_date : null),
                            'stuffing_end': (order.container_stuffing ? order.container_stuffing.stuffing_ending_date : ''),
                            'mapa': (order.mapa ? order.mapa.inspection_date : null),
                            'fumigation': (order.fumigation ? order.fumigation.init : null),
                            'aeration': (order.fumigation ? order.fumigation.end : null),
                            'draft': (order.shipping ? order.shipping.draft_deadline : null),
                            'loading': (order.shipping ? order.shipping.loading_deadline : null),
                            'free_time': order.freeTimeLimit,
                            'etd': (order.shipping ? order.shipping.etd : null)
                        };

                        let classes = {};
                        let lastUpdate = null;
                        let nextStep = null;
                        let today = null;

                        for (let [name, date] of Object.entries(dates)) {
                            classes[name] = '';

                            if (date) {

                                if (moment(date).isBefore(moment(), 'day')) {
                                    classes[name] = 'done';
                                    if (!lastUpdate || moment(date).isAfter(moment(dates[lastUpdate]), 'day')) {
                                        lastUpdate = name;
                                    }
                                } else if(moment(date).isSame(moment(), 'day')){
                                    classes[name] = 'today';
                                } else {
                                    if (!nextStep || moment(date).add(1, 'days').isBefore(moment(dates[nextStep]), 'day')) {
                                        nextStep = name;
                                    }
                                }
                            }
                        }

                        classes[lastUpdate] = 'last-update';
                        classes[nextStep] = 'next-step';
                        classes[today] = 'today';

                        order.dateClasses = classes;

                        return order;
                    });
                }
            }
        },

        filterOrders: function () {
            clearTimeout(this.filterTimeout);
            this.filterTimeout = setTimeout(this._filterOrders, 500);
        },

        sortOrders: function (orders) {
            orders = orders.map(function (order) {
                let date = '';

                if (order.hasOwnProperty('loading_deadline') && (order.shipping ? order.shipping.loading_deadline : '')) {
                    date = (order.shipping ? order.shipping.loading_deadline : '');
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

        addFilter() {
            this.$data.filters.push('');
        },

        removeFilter(index) {
            this.$data.filters.splice(index, 1);
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

      this.dateType = this.dateTypesOptions[0];

      today.setDate(today.getDate() + 2);
      var formattedToday = today.toISOString().split('T')[0];
      this.final_date = formattedToday;

      today.setDate(today.getDate() - 30);
      this.initial_date = today.toISOString().split('T')[0];
    },

    mounted: async function () {
        this._filterOrders();
    },

    watch: {
        '$route.query.status': {
            immediate: true,
            handler(newStatus) {
                switch (newStatus) {
                case 'release':
                    this.dateTypesOptions = [...this.dateTypesOptionsDefault, ...this.dateTypesOptionsInProcessRelease];
                    break;
                default:
                    this.dateType = this.dateTypesOptions[0];
                    this.dateTypesOptions = [...this.dateTypesOptionsDefault];
                }
            }
        },
        async '$route.query'() {
            this.filter = '';
            this.globalFilter = '';
            this.lastStatus = this.$route.query.status || "all";
            this._filterOrders();
        },
        filters: function () {
            this.globalFilter = '';
            this.filterOrders();
        },
        globalFilter: function () {
            if (!this.globalFilter) {
                this.filters = [''];
            }
            this.$route.query.status = this.globalFilter ? 'filter' : this.lastStatus;
            this.filterOrders();
        }
    },
}

</script>