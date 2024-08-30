<template>
    <div class="container-fluid">

            <div id="ui-view">

                <div class="panel panel-success">
                    
<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col">
            Dashboard
        </div>
    </div>
  </div>
  <div class="card-body">
    
    <table class="table table-sm">
      <tr>
        <th>PO</th>
        <th>NF</th>
        <th>Booking</th>
        <th>Total Amount</th>
        <th>Current Balance</th>
        <th>Expiration date</th>
        <th>Status</th>
        <th>&nbsp;</th>
      </tr>
      <tbody v-for="(order, index) in orders">
        <tr>
          <td>{{ order.number }}</td>
          <td>{{ order.nf }}</td>
          <td>{{ order.shipping.booking }}</td>
          <td>U$ {{ NumberFormat(-order.transactions.value * 100) }}</td>
          <td>U$ {{ NumberFormat(order.sum) }}</td>
          <td>{{ FormatDate(order.expiry_date) }}</td>
          <td>{{ order.status_payment }}</td>
          <td class="text-right">
            <router-link :to="{ name: 'Transaction', params: { id: order.id } }" class="btn btn-success btn-sm">View</router-link>
          </td>
        </tr>
      </tbody>
    </table>

    
  </div>
</div>

<div class="card align-bottom">
  <div class="card-header">
    <div class="row">
        <div class="col">
            Total
        </div>
    </div>
  </div>
  <div class="card-body">
    <p>Current Balance: $ {{ NumberFormat(GetTotal1(orders)) }}</p>
  </div>  
</div>


                </div>

            </div>

    </div>

</template>

<script>
export default {
  data() {
    return {
      orders: [],
    }
  },
  methods: {
    GetTotal1: function (orders) {
      if (!orders) return 0;
      if (orders.length == 0) return 0;
      let sum = orders.map((order) => {
        return order.sum
      }).reduce((acc, order_value) => {
        return acc + order_value
      })

      return sum;
    },
    GetTotal2: function (orders) {
      if (!orders) return 0;
      if (orders.length == 0) return 0;
      let sum = orders.map((order) => {
        return order.sum2
      }).reduce((acc, order_value) => {
        return acc + order_value
      })

      return sum;
    },
    NumberFormat: function (value) {
      let fixed_value = value / 100;
      let [decimal, fraction] = fixed_value.toFixed(2).split('.');

      decimal = decimal.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

      return decimal + "." + fraction;
    },
    FormatDate: function (date) {
      let [year, month, day, hour, minutes, seconds] = date.split(/[- :]/g);

      return month + "/" + day + "/" + year;
    },
    GetInitialBalance: function (transactions) {
      return -(transactions.value);
    }
  },
  mounted: async function () {
    let orders = await this.$http.get('/api/balances-client');

    this.$data.orders = orders.body;
  }
}
</script>