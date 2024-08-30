<template>
  <div class="container-fluid">
    <div id="ui-view">
      <div class="panel panel-success">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">Transactions</div>
              <div class="col text-right">
                <router-link :to="{ name: 'Transactions Report' }" class="btn btn-success btn-sm">Report</router-link>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-2">
                <label for="initial_date">Initial Date</label>
                <input type="date" id="initial_date" class="form-control" v-model="initial_date" />
              </div>
              <div class="col-2">
                <label for="final_date">Final Date</label>
                <input type="date" id="final_date" class="form-control" v-model="final_date" />
              </div>

              <div :class="'col'">
                <label for="filter">
                  Filters
                  <a href="#" @click="addFilter()">
                    <i class="icon-plus" title="Add filter"></i>
                  </a>
                </label>
                <div class="row" id="filters">
                  <div class="col" v-for="(filter, index) in filters">
                    <div class="filter" v-if="filters.length > 1">
                      <i class="icon-close text-danger" @click="removeFilter(index)"></i>
                      <input type="text" :id="'filter-'+filters.length" class="form-control" v-model="filters[index]" placeholder="Filter" />
                    </div>
                    <input type="text" id="filter" class="form-control" v-else v-model="filters[index]" placeholder="Filter" />
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col" id="tabs">
                <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'all' ||
                      !this.$route.query.status
                      ? 'active'
                      : ''
                      " :to="{ name: 'Transactions', query: { status: 'all' } }">&emsp;All&emsp;</router-link>
                  </li>
                  <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'toboard' ? 'active' : ''
                      " :to="{
                              name: 'Transactions',
                              query: { status: 'toboard' },
                            }">To Board</router-link>
                  </li>
                  <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'receivable' ? 'active' : ''
                      " :to="{
                              name: 'Transactions',
                              query: { status: 'receivable' },
                            }">Receivable</router-link>
                  </li>
                  <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'available' ? 'active' : ''
                      " :to="{
                              name: 'Transactions',
                              query: { status: 'available' },
                            }">Available</router-link>
                  </li>
                  <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'paid' ? 'active' : ''
                      " :to="{ name: 'Transactions', query: { status: 'paid' } }">&emsp;Paid&emsp;</router-link>
                  </li>
                  <li class="nav-item">
                    <router-link class="nav-link-alt" :class="this.$route.query.status == 'foreign' ? 'active' : ''
                      " :to="{ name: 'Transactions', query: { status: 'foreign' } }">Foreign Account</router-link>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="card-body">

            <table class="table table-sm" v-if="this.$route.query.status == 'available' && this.$route.query.status != 'toboard'">
              <tr>
                <th>PO</th>
                <th>NF</th>
                <th>Importer</th>
                <th>Date</th>
                <th>Bank</th>
                <th>Description</th>
                <th>Value</th>
                <th>Reference</th>
                <th></th>
              </tr>

              <tbody v-for="(transaction, index) in filteredOperations.transactions">
                <tr>
                  <td>{{ transaction.number }}</td>
                  <td>{{ transaction.nf }}</td>
                  <td>
                    {{
                      transaction.name
                      ? transaction.name
                      : ""
                    }}
                  </td>
                  <td>{{
                    transaction.data
                    ? dataCalc(transaction)
                    : ""
                  }}
                  </td>
                  <td>{{ transaction.bank }}</td>
                  <td>{{ transaction.description }}</td>
                  <td>{{ NumberFormat3(transaction.value) }}</td>
                  <td>{{ transaction.reference }}</td>
                  <td class="text-right">
                    <router-link :to="{
                      name: 'Transaction',
                      params: { id: transaction.order_id },
                    }" class="btn btn-success btn-sm">View</router-link>
                  </td>
                </tr>
              </tbody>
            </table>

            <table class="table table-sm" v-else-if="(this.$route.query.status != 'toboard' && this.$route.query.status != 'foreign')">
              <tr>
                <th>PO</th>
                <th style="width: 5%">NF</th>
                <th>Product</th>
                <th>Importer</th>
                <th>Booking</th>
                <th>Total Amount</th>
                <th>Current Balance</th>
                <th>Available Value</th>
                <th>Expiration date</th>
                <th>ETA</th>
                <th>&nbsp;</th>
              </tr>

              <tbody v-for="(order, index) in filteredOrders">
                <tr>
                  <td>{{ order.number }}</td>
                  <td v-html="order.nf ? order.nf.replace(/\//g, '\n') : ''"></td>
                  <td>{{ order.items[0] ? order.items[0].description : "" }}</td>
                  <td>{{ order.owner ? order.owner.name : "" }}</td>
                  <td>{{ order.shipping ? order.shipping.booking : "" }}</td>

                  <td>{{ order.transactions ? calculateTotal(order.transactions) : "0" }}</td>
                  <td>{{ order.sum ? NumberFormat(order.sum * -1) : "0" }}</td>

                  <td>{{ order.sum2 ? NumberFormat(order.sum2) : '0' }}</td>
                  <td>{{ 
                  (order.expiry_date != '0000-00-00 00:00:00' && !order.shipping && order.shipping?.eta == 0) ? AddDaysAndFormat(order.expiry_date) : AddDaysAndFormat(order.shipping?.eta, 14)
                  }}</td>
                  <td>{{ order.shipping?.eta ? AddDaysAndFormat(order.shipping?.eta) : '-'}}</td>
                  <td class="text-right">
                    <router-link :to="{ name: 'Transaction', params: { id: order.id } }"
                      class="btn btn-success btn-sm">View</router-link>
                  </td>
                </tr>
              </tbody>
            </table>

            <table class="table table-sm" v-else-if="this.$route.query.status == 'toboard'">
              <tr>
                <th>PO</th>
                <th>NF</th>
                <th>Product</th>
                <th>Importer</th>
                <th>Destiny</th>
                <th>Booking</th>
                <th>Advance Payment</th>
                <th>Total Price</th>
                <th>Expiration date</th>
                <th>&nbsp;</th>
              </tr>

              <tbody v-for="(order, index) in filteredOrders">
                <tr>
                  <td>{{ order.number }}</td>
                  <td>{{ order.nf }}</td>
                  <td>
                    {{ order.items[0] ? order.items[0].description : "" }}
                  </td>
                  <td>{{ order.owner ? order.owner.name : "" }}</td>
                  <td>{{ order.port_destiny ? order.port_destiny : "" }}</td>
                  <td>{{ order.shipping ? order.shipping.booking : "" }}</td>
                  <td>{{ NumberFormat2(order.items[0].advance_payment) }}</td>
                  <td>{{ NumberFormat2(order.items[0].total_price) }}</td>
                  <td>{{ FormatDate(order.expiry_date) }}</td>
                  <td class="text-right">
                    <router-link :to="{ name: 'Transaction', params: { id: order.id } }"
                      class="btn btn-success btn-sm">View</router-link>
                  </td>
                </tr>
              </tbody>
            </table>

            <template v-else>
              <h3>Transactions</h3>
              <table class="table table-sm">
                <tr>
                  <th>PO</th>
                  <th>NF</th>
                  <th>Importer</th>
                  <th>Available Value</th>
                  <th>Date</th>
                </tr>
                <tbody v-for="(
                    transaction, index
                  ) in filteredOperations.transactions">
                  <tr>
                    <td>
                      {{ transaction.order ? transaction.order.number : "" }}
                    </td>
                    <td>{{ transaction.order ? transaction.order.nf : "" }}</td>
                    <td>
                      {{
                        transaction.order ? transaction.order.owner.name : ""
                      }}
                    </td>
                    <td>U$ {{ NumberFormat(transaction.value * 100) }}</td>
                    <td>
                      {{
                        FormatDate(
                          transaction.data
                            ? transaction.data
                            : transaction.created_at
                        )
                      }}
                    </td>
                  </tr>
                </tbody>
              </table>

              <h3>Payments</h3>
              <table class="table table-sm">
                <tr>
                  <th>Bill</th>
                  <th>Importer</th>
                  <th>Address</th>
                  <th>Country</th>
                  <th>Value</th>
                  <th>Date</th>
                  <th>Description</th>
                  <th>&nbsp;</th>
                </tr>
                <tbody v-for="(posting, index) in filteredOperations.postings">
                  <tr>
                    <td>{{ posting.invoice_id }}</td>
                    <td>{{ posting.supplier ? posting.supplier.name : "" }}</td>
                    <td>
                      {{ posting.supplier ? posting.supplier.address : "" }}
                    </td>
                    <td>
                      {{
                        posting.supplier
                        ? posting.supplier.country.toUpperCase()
                        : ""
                      }}
                    </td>
                    <td>U$ {{ NumberFormat(posting.invoice_amount * 100) }}</td>
                    <td>
                      {{
                        FormatDate(
                          posting.due_date
                            ? posting.due_date
                            : posting.created_at
                        )
                      }}
                    </td>
                    <td>{{ posting.description }}</td>
                    <td class="action-column">
                      <button class="btn btn-sm btn-danger pull-right" @click="DeletePosting(index)">
                        delete
                      </button>
                      <button class="btn btn-sm btn-success pull-right" data-toggle="modal"
                        :href="'#modal-edit-posting-' + index" @click="
                          current_posting = JSON.parse(JSON.stringify(posting))
                          ">
                        edit
                      </button>

                      <div class="modal fade" :id="'modal-edit-posting-' + index">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit Payment</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                &times;
                              </button>
                            </div>

                            <div class="modal-body">
                              <div class="row">
                                <div class="col">
                                  <label for="supplier_id">Supplier</label>
                                  <vue-select2 class="vue-select1 form-control" :id="'supllier'+ index" name="select2" :options="suppliers"
                                    :value="current_posting.supplier_id" placeholder="Choose a supplier"
                                    v-model="current_posting.supplier_id">
                                  </vue-select2>
                                </div>

                                <div class="col">
                                  <div class="form-group">
                                    <label for="description">Description</label>
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <div class="input-group-text">
                                          <i class="fas fa-keyboard"></i>
                                        </div>
                                      </div>
                                      <input type="text" name="description" :id="'description'+ index" placeholder="Description"
                                        class="form-control" v-model="current_posting.description" />
                                    </div>
                                  </div>
                                </div>

                                <div class="col">
                                  <div class="form-group">
                                    <label for="invoice_id">Invoice Identifier</label>
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <div class="input-group-text">
                                          <i class="fas fa-pen"></i>
                                        </div>
                                      </div>
                                      <input type="text" name="invoice_id" :id="'invoice_id'+ index"
                                        placeholder="Invoice Identifier" class="form-control"
                                        v-model="current_posting.invoice_id" />
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col">
                                  <label for="currency_type">Currency Type</label>
                                  <div class="form-group">
                                    <div class="caixa-icone">
                                      <i class="fas fa-info-circle"></i>
                                    </div>
                                    <vue-select2 icon="fas fa-info-circle" class="vue-select1 form-control"
                                      name="currency_type" :id="'posting_currency_types'+ index" :options="posting_currency_types" :model.sync="current_posting.currency_type
                                        " v-model="current_posting.currency_type">
                                      <option value="">
                                        Select a currency type
                                      </option>
                                    </vue-select2>
                                  </div>
                                </div>

                                <div class="col">
                                  <div class="form-group">
                                    <label for="invoice_amount">Amount</label>
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <div class="input-group-text">
                                          <i class="fas fa-dollar-sign"></i>
                                        </div>
                                      </div>
                                      <input type="number" name="invoice_amount" :id="'invoice_amount'+ index"
                                        placeholder="Advance Payment" class="form-control"
                                        v-model="current_posting.invoice_amount" />
                                    </div>
                                  </div>
                                </div>

                                <div class="col">
                                  <div class="form-group">
                                    <label for="currency_fee">Currency Fee</label>
                                    <div class="input-group">
                                      <div class="input-group-prepend">
                                        <div class="input-group-text">
                                          <i class="fas fa-dollar-sign"></i>
                                        </div>
                                      </div>
                                      <input type="number" name="currency_fee" :id="'currency_fee'+ index"
                                        placeholder="Currency_fee" class="form-control"
                                        v-model="current_posting.currency_fee" />
                                    </div>
                                  </div>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col">
                                  <div class="form-group">
                                    <iconinput label="Due Date:" type="date" v-model="current_posting.due_date"
                                      :id="'due_date'+ index" name="due_date" icon="fas fa-calendar" />
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- modal-body -->

                            <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal"
                                @click="current_posting = {}">
                                Close
                              </button>
                              <button type="button" class="btn btn-primary" @click.prevent="StorePosting(index)">
                                Save
                              </button>
                            </div>
                            <!-- modal-footer -->
                          </div>
                          <!-- modal-content -->
                        </div>
                        <!-- modal-dialog modal-lg -->
                      </div>
                      <!-- modal-fade -->
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="modal fade" id="modal-new-posting">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">New Payment</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                      </button>
                    </div>

                    <div class="modal-body">
                      <div class="row">
                        <div class="col">
                          <label for="supplier_id" class="d-block">Supplier</label>
                          <vue-select2 class="vue-select1 form-control" id="supplier_id" name="select1" :options="suppliers"
                            placeholder="Choose a supplier" v-model="current_posting.supplier_id">
                          </vue-select2>
                        </div>

                        <div class="col">
                          <div class="form-group">
                            <label for="description">Description</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  <i class="fas fa-keyboard"></i>
                                </div>
                              </div>
                              <input type="text" name="description" id="description" placeholder="Description"
                                class="form-control" v-model="current_posting.description" />
                            </div>
                          </div>
                        </div>

                        <div class="col">
                          <div class="form-group">
                            <label for="invoice_id">Invoice Identifier</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  <i class="fas fa-pen"></i>
                                </div>
                              </div>
                              <input type="text" name="invoice_id" id="invoice_id" placeholder="Invoice Identifier"
                                class="form-control" v-model="current_posting.invoice_id" />
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col">
                          <label for="currency_type">Currency Type</label>
                          <div class="form-group">
                            <div class="caixa-icone">
                              <i class="fas fa-info-circle"></i>
                            </div>
                            <vue-select2 icon="fas fa-info-circle" class="vue-select1 form-control" id="currency_type" name="currency_type"
                              :options="posting_currency_types" :model.sync="current_posting.currency_type"
                              v-model="current_posting.currency_type" placeholder="Choose a currency type">
                            </vue-select2>
                          </div>
                        </div>

                        <div class="col">
                          <div class="form-group">
                            <label for="invoice_amount">Amount</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  <i class="fas fa-dollar-sign"></i>
                                </div>
                              </div>
                              <input type="number" name="invoice_amount" id="invoice_amount" placeholder="Advance Payment"
                                class="form-control" v-model="current_posting.invoice_amount" />
                            </div>
                          </div>
                        </div>

                        <div class="col">
                          <div class="form-group">
                            <label for="currency_fee">Currency Fee</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <div class="input-group-text">
                                  <i class="fas fa-dollar-sign"></i>
                                </div>
                              </div>
                              <input type="number" name="currency_fee" id="currency_fee" placeholder="Currency_fee"
                                class="form-control" v-model="current_posting.currency_fee" />
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <iconinput label="Due Date:" type="date" v-model="current_posting.due_date" id="due_date"
                              name="due_date" icon="fas fa-calendar" />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- modal-body -->

                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">
                        Close
                      </button>
                      <button type="button" class="btn btn-primary" @click.prevent="StoreNewPosting">
                        Save
                      </button>
                    </div>
                    <!-- modal-footr -->
                  </div>
                  <!-- modal-content -->
                </div>
              </div>
            </template>
          </div>

          <div class="card-footer" v-if="this.$route.query.status == 'foreign'">
            <button class="btn btn-success pull-right" data-toggle="modal" href="#modal-new-posting"
              @click="current_posting = {}">
              Add Payment
            </button>
          </div>
        </div>

        <div class="card align-bottom">
          <div class="card-header">
            <div class="row">
              <div class="col">Total</div>
            </div>
          </div>
          <div class="card-body" v-if="this.$route.query.status != 'foreign' &&
            this.$route.query.status != 'available'
            ">
            <p>
              Current Balance: $ {{ totalCurrent(filteredOrders) > 0 ? NumberFormat(totalCurrent(filteredOrders)) : 0 }}
            </p>
            <p>
              Available Value: $ {{ NumberFormat(GetTotal2(filteredOrders)) }}
            </p>
          </div>

          <div class="card-body" v-else-if="this.$route.query.status != 'available'">
            <p>
              Balance: $
              {{ NumberFormat(GetForeignBalance(filteredOperations)) }}
            </p>
          </div>
          <div class="card-body" v-else>
            <p>
              Available Value:
              {{ NumberFormat3(this.filteredOperations.totalOrdersValue) }}
            </p>
          </div>
        </div>

        <button class="btn btn-success btn-block" @click="generateReport()"
          v-if="this.$route.query.status == 'receivable'">
          Export PDF</button><br />
      </div>
    </div>
  </div>
</template>

<style scoped>

/*
.nav-link-alt {
  display: block;
  padding: 0.6rem 1rem;
}

.nav-tabs .nav-item {
    margin-bottom: 5px;
}

.nav-tabs .nav-link-alt {
  border: 1px solid transparent;
  border-top-left-radius: 0.25rem;
  border-top-right-radius: 0.25rem;
  color: #73818f;
}

.nav-tabs .nav-link-alt:hover {
  cursor: pointer;
  border-color: #e4e7ea #e4e7ea #c8ced3;
  text-decoration: none;
}

.nav-tabs .nav-link-alt.active {
  color: #23282c;
  background: #fff;
  border-color: #7ccc6e #7ccc6e transparent #7ccc6e;
} */

#tabs {
  padding-top: 20px;
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
</style>

<script>
import moment from "moment";
import queryString from "query-string";

export default {
  data() {
    return {
      orders: [],
      filteredOrders: [],
      operations: [],
      filteredOperations: [],
      suppliers: [],
      current_posting: [],
      posting_currency_types: [],
      initial_date: "",
      final_date: "",
      filters: [""],
      filterTimeout: null,
    };
  },
  watch: {
    async "$route.query.status"() {
      let status = this.$route.query.status || "all";
      let orders = await this.$http.get("/api/balances?status=" + status);

      if (status != "foreign" && status != "available") {
        this.$data.orders = orders.body;
      }
      else {
        this.$data.operations = orders.body;
      }
    },
    initial_date: function (date) {
      if (
        this.$route.query.status != "foreign" &&
        this.$route.query.status != "available"
      ) {
        this.$data.filteredOrders = this.FilterOrders();
      } else {
        this.$data.filteredOperations = this.FilterOperations();
      }
    },
    final_date: function (date) {
      if (
        this.$route.query.status != "foreign" &&
        this.$route.query.status != "available"
      ) {
        this.$data.filteredOrders = this.FilterOrders();
      } else {
        this.$data.filteredOperations = this.FilterOperations();
      }
    },
    orders: function (orders) {
      this.$data.filteredOrders = this.FilterOrders();
    },
    operations: function (operations) {
      this.$data.filteredOperations = this.FilterOperations();
    },
    filters: function (orders) {
      var that = this;
      clearTimeout(this.$data.filterTimeout);

      this.$data.filterTimeout = setTimeout(function () {
        if (
          that.$route.query.status != "foreign" &&
          that.$route.query.status != "foreign"
        ) {
          that.$data.filteredOrders = that.FilterOrders();
        } else {
          that.$data.filteredOperations = that.FilterOperations();
        }
      }, 500);
    },
    "current_posting.supplier_id": function (id) {
      if (id) {
        let suppliers = JSON.parse(JSON.stringify(this.$data.suppliers));
        let description = suppliers.filter(function (supplier) {
          return supplier.id == id;
        });
        let supplier = description[0];

        this.$data.current_posting.description =
          supplier.contracts &&
            supplier.contracts.length &&
            supplier.contracts[0].contract_services &&
            supplier.contracts[0].contract_services.length &&
            supplier.contracts[0].contract_services[0].description
            ? supplier.contracts[0].contract_services[0].description
            : "";
      }
    },
  },
  methods: {
    calculateTotal(transactions) {
        if (!transactions || transactions.length === 0) {
            return "0";
        }

        const total = transactions.reduce((sum, transaction) => {
            return sum + transaction.value;
        }, 0);

        return this.NumberFormat(-total);
    },
    dataCalc(transaction) {
      let data = transaction.created_at.split(/\s/)[0];
      if (transaction.data) data = transaction.data;

      let [year, month, day] = data.split(/-/);
      return `${month}/${day}/${year}`;
    },

    totalCurrent: function (orders) {
      if (!orders) return 0;
      if (orders.length === 0) return 0;
      let total = 0;
      this.orders.forEach(function (orders) {
        total += Math.abs(orders.sum);
      });

      return total;
    },
    GetTotal1: function (orders) {
      if (!orders) return 0;
      if (orders.length === 0) return 0;

      let array_orders = JSON.parse(JSON.stringify(orders));

      let sum = array_orders
        .map((order) => {
          return Math.abs(order.sum);
        })
        .reduce((acc, order_value) => {
          return acc + order_value;
        });

      return Math.abs(sum);
    },
    GetTotal2: function (orders) {
      if (!orders) return 0;
      if (orders.length == 0) return 0;

      let array_orders = JSON.parse(JSON.stringify(orders));

      let sum = array_orders
        .map((order) => {
          return Math.abs(order.sum2);
        })
        .reduce((acc, order_value) => {
          return acc + order_value;
        });

      return sum;
    },
    GetAvailableValue: function (operations) {

      if (!operations) return 0;
      if (operations.length == 0) return 0;

      let array_transactions = JSON.parse(
        JSON.stringify(operations.transactions)
      );

      let transaction_sum = array_transactions
        .map((transaction) => {
          return transaction.value * 100;
        })
        .reduce((acc, value) => {
          return acc + value;
        }, 0);

      let balance = transaction_sum;

      return balance;
    },

    GetForeignBalance: function (operations) {
      if (!operations) return 0;
      if (operations.length == 0) return 0;

      let array_transactions = JSON.parse(
        JSON.stringify(operations.transactions)
      );

      let transaction_sum = array_transactions
        .map((transaction) => {
          return transaction.value * 100;
        })
        .reduce((acc, value) => {
          return acc + value;
        }, 0);

      let array_postings = JSON.parse(JSON.stringify(operations.postings));

      let posting_sum = array_postings
        .map((posting) => {
          return posting.invoice_amount * 100;
        })
        .reduce((acc, value) => {
          return acc + value;
        }, 0);

      let balance = transaction_sum - posting_sum;

      return balance;
    },

    // NumberFormat_old: function (value) {
    //   let fixed_value = value / 100;
    //   let [decimal, fraction] = fixed_value.toFixed(2).split(".");

    //   decimal = decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

    //   return decimal + "." + fraction;
    // },

    FormatNumberDecimal: function (value) {
        let value_fixed = parseFloat(value).toFixed(2);
        let [value_decimal, value_fraction] = value_fixed.split(/\./);

        value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

        value_fixed = value_decimal + '.' + value_fraction;
        return value_fixed;

    },

    NumberFormat: function (value) {
      let value_fixed = value.toFixed(2);
      let [value_decimal, value_fraction] = value_fixed.split(/\./);

      value_decimal = value_decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1.");

      value_fixed = value_decimal + "," + value_fraction;
      return value_fixed;
    },

    NumberFormat3: function (value) {
      value = value / 100;
      let formattedValue = value.toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
      return formattedValue;
    },
    
    addZeroes: function (num) {
      const dec = num.split('.')[1]
      const len = dec && dec.length > 2 ? dec.length : 2
      return Number(num).toFixed(len)
    },
    formatMoney: function (number) {
      return number.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
    },
    NumberFormat2: function (value) {
      return this.formatMoney(value);
    },

    AddDaysAndFormat: function (date, plusDays){
      return plusDays ? moment(date).add(plusDays, 'days').format('DD/MM/YYYY') : moment(date).format('DD/MM/YYYY');
    },

    FormatDate: function (date) {
      let [year, month, day, hour, minutes, seconds] = date.split(/[- :]/g);

      return month + "/" + day + "/" + year;
    },
    GetInitialBalance: function (transactions) {
      return -transactions.value;
    },
    FilterByInitialDate: function (orders, initial_date) {
      if (!initial_date) return orders;
      return orders.filter((order) => {
        let date = moment(order.expiry_date);
        let init_date = moment(initial_date);

        return date.isSameOrAfter(init_date, "day");
      });
    },
    FilterByFinalDate: function (orders, final_date) {
      if (!final_date) return orders;
      return orders.filter((order) => {
        let date = moment(order.expiry_date);
        let end_date = moment(final_date);

        return date.isSameOrBefore(end_date, "day");
      });
    },
    FilterByTerm: function (orders, term) {
      if (!term) return orders;
      term = term.toLowerCase();

      return orders.filter((order) => {
        return (
          (order.number ? order.number.toLowerCase().includes(term) : false) ||
          (order.items[0]
            ? order.items[0].description.toLowerCase().includes(term)
            : false) ||
          (order.nf ? order.nf.toLowerCase().includes(term) : false) ||
          (order.owner && order.owner.name
            ? order.owner.name.toLowerCase().includes(term)
            : false) ||
          (order.shipping && order.shipping.booking
            ? order.shipping.booking.toLowerCase().includes(term)
            : false)
        );
      });
    },
    FilterOrders: function () {
      var orders = this.$data.orders;
      var that = this;
      orders = this.FilterByInitialDate(orders, this.$data.initial_date);
      orders = this.FilterByFinalDate(orders, this.$data.final_date);
      this.$data.filters.forEach(function (filter) {
        orders = that.FilterByTerm(orders, filter);
      });

      return orders;
    },

    FilterOperationsByInitialDate: function (operations, initial_date) {
      if (!initial_date) return operations;
      return {
        transactions: operations.transactions.filter((transaction) => {
          let date = moment(transaction.created_at);
          if (transaction.data) {
            date = moment(transaction.data);
          }
          let init_date = moment(initial_date);

          return date.isSameOrAfter(init_date, "day");
        }),
        postings: operations.postings.filter((posting) => {
          let date = moment(posting.created_at);
          if (posting.due_date) {
            date = moment(posting.due_date);
          }
          let init_date = moment(initial_date);

          return date.isSameOrAfter(init_date, "day");
        }),
      };
    },
    FilterOperationsByFinalDate: function (operations, final_date) {
      if (!final_date) return operations;
      return {
        transactions: operations.transactions.filter((transaction) => {
          let date = moment(transaction.created_at);
          if (transaction.data) {
            date = moment(transaction.data);
          }
          let end_date = moment(final_date);

          return date.isSameOrBefore(end_date, "day");
        }),
        postings: operations.postings.filter((posting) => {
          let date = moment(posting.created_at);
          if (posting.due_date) {
            date = moment(posting.due_date);
          }
          let end_date = moment(final_date);

          return date.isSameOrBefore(end_date, "day");
        }),
      };
    },
    FilterOperationsByTerm: function (operations, term) {
      if (!term) return operations;
      term = term.toLowerCase();

      return {
        transactions: operations.transactions.filter((transaction) => {
          return (
            (transaction.order && transaction.order.number
              ? transaction.order.number.toLowerCase().includes(term)
              : false) ||
            (transaction.order && transaction.order.nf
              ? transaction.order.nf.toLowerCase().includes(term)
              : false) ||
            (transaction.order &&
              transaction.order.owner &&
              transaction.order.owner.name
              ? transaction.order.owner.name.toLowerCase().includes(term)
              : false)
          );
        }),
        postings: operations.postings.filter((posting) => {
          return (
            (posting.invoice_id
              ? posting.invoice_id.toLowerCase().includes(term)
              : false) ||
            (posting.description
              ? posting.description.toLowerCase().includes(term)
              : false) ||
            (posting.supplier && posting.supplier.name
              ? posting.supplier.name.toLowerCase().includes(term)
              : false) ||
            (posting.supplier && posting.supplier.address
              ? posting.supplier.address.toLowerCase().includes(term)
              : false) ||
            (posting.supplier && posting.supplier.country
              ? posting.supplier.country.toUpperCase().includes(term)
              : false)
          );
        }),
      };
    },
    FilterOperations: function () {
      var operations = this.$data.operations;
      var that = this;
      operations = this.FilterOperationsByInitialDate(
        operations,
        this.$data.initial_date
      );
      operations = this.FilterOperationsByFinalDate(
        operations,
        this.$data.final_date
      );
      this.$data.filters.forEach(function (filter) {
        operations = that.FilterOperationsByTerm(operations, filter);
      });

      return operations;
    },

    StoreNewPosting: function () {
      this.$http
        .post(`/api/balance/posting`, this.current_posting)
        .then(function (e) {
          if (e.body.errors) {
            return;
          }

          this.operations.postings.push(e.body);

          $("#modal-new-posting").modal("hide");
          this.current_posting = {};
        });
    },

    StorePosting: function (index) {
      const posting = this.operations.postings[index];
      this.$http
        .put(`/api/balance/posting/${posting.id}`, this.current_posting)
        .then((result) => {
          this.$set(this.operations.postings, index, result.body);
        })
        .catch((error) => console.error(error));

      $(".modal").modal("hide");
      this.current_posting = {};
    },

    DeletePosting: function (index) {
      const posting = this.operations.postings[index];

      this.$http
        .delete(`/api/balance/posting/${posting.id}`)
        .then((result) => {
          this.operations.postings.splice(index, 1);
        })
        .catch((error) => console.log(error));
    },

    getQuery: function () {
      let params = {
        initial_date: this.initial_date || "",
        final_date: this.final_date || "",
        filters: this.filters || "",
      };

      return queryString.stringify(params, { arrayFormat: "bracket" });
    },

    forceFileDownload(response, name) {
      let filename = name + ".pdf";
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = document.createElement("a");
      link.href = url;
      link.setAttribute("download", filename);
      document.body.appendChild(link);
      link.click();
    },

    generateFile: function (url, name) {
      this.$http({
        method: "get",
        url: url,
        responseType: "arraybuffer",
      })
        .then((response) => {
          this.forceFileDownload(response, name);
        })
        .catch(() => console.log("error occured"));
    },

    generateReport: function () {
      let url = "/api/reports/receivable_orders/export?" + this.getQuery();

      this.generateFile(url, "relatorio-receivable_orders");
    },

    addFilter() {
      this.$data.filters.push("");
    },

    removeFilter(index) {
      this.$data.filters.splice(index, 1);
    },
  },
  computed: {
    formatNf() {
      return (nf) => nf.replace(/(\r\n|\n\r|\r|\n)/g, '<br>');
    }
  },
  mounted: async function () {
    let orders = await this.$http.get(
      "/api/balances?status=" + this.$route.query.status
    );

    if (this.$route.query.status != "foreign" && this.$route.query.status != "available") {
      this.$data.orders = orders.body;
    } else {
      this.$data.operations = orders.body;
    }

    let suppliers = await this.$http.get("/api/users");
    suppliers = suppliers.body.map(function (supplier) {
      supplier.text = supplier.name;
      return supplier;
    });

    suppliers.unshift({ id: "", text: "" });
    this.$data.suppliers = suppliers;

    this.posting_currency_types = [
      {
        id: "",
        text: "",
      },
      {
        id: "BRL",
        text: "Real",
      },
      {
        id: "USD",
        text: "Dólar",
      },
    ];
  },
};
</script>
