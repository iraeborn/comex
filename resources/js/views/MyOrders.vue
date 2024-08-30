<template>
    <div class="container-fluid">

            <div id="ui-view">

                <div class="panel panel-success">
                    
<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col">
            My orders
        </div>
        <div class="col-3 text-right">
            <div class="input-group">
                <input type="text" placeholder="Filter" class="form-control form-control-sm" v-model="filter">
                <div class="input-group-append">
                    <button type="button" class="btn btn-danger btn-sm" @click="filter = ''">X</button>
                </div>
            </div>
        </div>

    </div>

  </div>
  <div class="card-body">

    <div class="card-body text-center" v-if="loading">
      Loading data...
    </div>
    <p v-if="!orders.length && !loading" class="text-center">
      You don`t have any order!
    </p>
  
    <div v-if="orders.length && !loading">
      <table class="table table-sm table-containers">
        <tr>
          <th>PO</th>
          <th>Product</th>
          <th>Booking</th>
          <th>Cntrs Amount</th>
          <th>ETD</th>
          <th>ETA</th>
          <th style="width: 274px;">&nbsp;</th>
        </tr>
        <tbody v-for="(order, index) in filtered">
            <tr>
                <td>{{ order.number }}</td>
                <td v-if="order.items.length">
                    <p v-for="item in order.items">{{item.description}}</p>
                </td>
                <td v-if="!order.items.length">Not informed</td>
                <!-- <td>{{ FormatDate(order.created_at) }}</td> -->
                <td>{{ order.booking || "Not informed" }}</td>
                <td>{{ order.cntrs_amount || "Not informed" }}</td>
                
                <td v-if="order.shipping && order.shipping.etd">{{ FormatDate(order.shipping.etd) }}</td>
                <td v-else>Shipping not informed</td>
                <td v-if="order.shipping && order.shipping.eta">{{ FormatDate(order.shipping.eta) }}</td>
                <td v-else>Shipping not informed</td>
                            <!-- <td>{{ order.status.name }}</td> -->
                <td class="text-right">
                  <router-link :to="{ name: 'View my order info', params: { id: order.id } }" v-if="order.order_status_id == 3" class="btn btn-success btn-sm">View shipping details</router-link>
                  <router-link :to="{ name: 'View my order', params: { id: order.id } }" class="btn btn-success btn-sm">View order details</router-link>
                    <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" :href='"#modal-" + index'>File</button> -->
                    <!-- <button type="button" class="btn btn-success btn-sm" data-toggle="modal" :href='"#modal-obs-" + index'>Observations</button> -->

                </td>
            </tr>
        </tbody>

        <tbody v-for="(order, index) in orders" v-if="false">
          <tr>
            <td>{{ order.number }}</td>
            <td v-if="order.shipping">{{ order.shipping.booking }}</td>
            <td v-if="!order.shipping">&nbsp;</td>
            <!-- <td>{{ FormatDateTime(order.created_at) }}</td> -->
            <!-- <td>{{ order.status.name }}</td> -->
            <td v-if="order.shipping">{{ FormatDate(order.shipping.etd) }}</td>
            <td v-if="!order.shipping">&nbsp;</td>
            <td v-if="order.shipping">{{ FormatDate(order.shipping.eta) }}</td>
            <td v-if="!order.shipping">&nbsp;</td>
            <td class="text-right">
              <router-link :to="{ name: 'View my order info', params: { id: order.id } }" v-if="order.shipping" class="btn btn-success btn-sm">View shipping details</router-link>
              <router-link :to="{ name: 'View my order', params: { id: order.id } }" class="btn btn-success btn-sm">View order details</router-link>
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

        </tbody>
      </table>
    </div>
  </div>


  <div class="card-footer text-right">
    &nbsp;
  </div>

</div>



                </div>

            </div>

<!-- <popcorn-upload></popcorn-upload> -->
    </div>

</template>

<script>
  export default {
    props: ['current_user'],
    data() {
      return {
        orders: [],
        filter: null,
        user: null,
        loading: false,
      }
    },
    computed: {
        filtered: function () {
            if(!this.$data.filter) return this.$data.orders;
            let filter = this.$data.filter;
            return this.$data.orders.filter(order => {
                if(!order) return false;
                let filterPattern = new RegExp(filter.toLowerCase());

                if (order.booking && !!order.booking.toLowerCase().match(filterPattern)) return true;
                if (order.number  && !!order.number.toLowerCase().match(filterPattern)) return true;

                if (order.cntrs_amount  && !!order.cntrs_amount.toLowerCase().match(filterPattern)) return true;

                let items =  order.items.filter(item => {
                    return !!item.description.toLowerCase().match(filterPattern);
                });

                if(items.length) return true;

                if(!order.shipping) return false;

                if (order.shipping.eta  && !!this.FormatDate(order.shipping.eta).toLowerCase().match(filterPattern)) return true;
                if (order.shipping.etd  && !!this.FormatDate(order.shipping.etd).toLowerCase().match(filterPattern)) return true;

                return false;
            });
        }
    },
    mounted: function () {
      this.$http.get('/api/my-orders').then(function (e) {
        this.$data.orders = e.body
      })
    },
    methods: {
      FormatDateTime: function (date) {
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

          return month + "/" + day + "/" + year
      },
      Store: function () {
        this.$data.error = {}
        this.$http.put('/api/orders', this.$data.user).then(function (e) {
          if (!e.body.success) {
            this.$data.error = e.body.errors
            return;
          }

          this.$toaster.success(e.body.success)
          this.$router.push('/user/me')
        })
      },
    }
  }
</script>