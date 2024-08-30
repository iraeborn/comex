<template>
  <div>
    <div class="row" v-if="containers">
      <div class="col">
        <label for="loading_port_id"><strong>Qtd Containers:</strong></label>
        {{containers.length}}
      </div>
    </div>
    <div class="row" v-if="containers">
      <div class="col">
        <p v-if="!containers.length">No containers were added</p>

        <div v-if="containers.length">
          <table class="table table-sm table-containers">
            <tr>
              <th>Container</th>
              <th>Tare</th>
              <th>Seal</th>
              <th>CBM</th>
              <th>Withdrawal Date</th>
              <th>Return Date</th>
              <!-- <th>NFE balance</th>
              <th>Failure</th>
              <th>Lack</th>
              <th>Leftovers</th>
              <th>Physical Balance</th>
              <th>Stuffed amount</th> -->
              <th>&nbsp;</th>
            </tr>
            <tbody v-for="(container, index) in containers">
              <tr>
                <td>{{container.name}}</td>
                <td>{{container.tare}} KGS</td>
                <td>{{container.seal}}</td>
                <td>{{container.cbm}}</td>
                <td>{{ FormatDate(container.withdrawal_date) }}</td>
                <td>{{ FormatDate(container.return_date) }}</td>
                <!-- <td>{{ parseInt(container.nfe_balance | 0) }}</td>
                <td>{{ parseInt(container.failure | 0) }}</td>
                <td>{{ parseInt(container.lack | 0) }}</td>
                <td>{{ parseInt(container.leftovers | 0) }}</td>
                <td>{{ parseInt(container.physical_balance | 0) }}</td>
                <td>{{ parseInt(container.total | 0) }}</td> -->
                <td class="text-right">
                  <button class="btn btn-success btn-sm" @click="EditContainer(index)">Edit</button>
                  <button class="btn btn-danger btn-sm"  @click="RemoveContainer(index)">Remove</button>
                </td>
              </tr>
              <tr>
                <td colspan="7">
                  <p v-if="!InContainer(order_bills, container.id).length > 0" class="text-center">No bills for this container</p>
                  <table class="table table-sm" v-if="InContainer(order_bills, container.id).length > 0">
                    <tr>
                      <th>Bill number</th>
                      <th>Key</th>
                      <th>Weight</th>
                      <th>Bags:</th>
                      <th>Damaged:</th>
                      <th>Lack:</th>
                      <th>Leftovers:</th>
                      <th>Physical_balance:</th>
                      <th>Stuffed amount:</th>
                    </tr>
                    <tr v-for="bill in InContainer(order_bills, container.id)">
                      <td>{{ bill.number }}</td>
                      <td>{{ bill.key }}</td>
                      <td>{{ bill.weight }}</td>
                      <td>{{parseInt(bill.bags|0)}}</td>
                      <td>{{parseInt(bill.damaged|0)}}</td>
                      <td>{{parseInt(bill.lack|0)}}</td>
                      <td>{{parseInt(bill.leftovers|0)}}</td>
                      <td>{{parseInt(bill.physical_balance|0)}}</td>
                      <td>{{parseInt(bill.total|0)}}</td>
                    </tr>
                    <tr>
                      <th colspan="2" class="text-right">Total</th>
                      <!-- <td>{{ bill.key }}</td> -->
                      <th>{{SumContainerInfo(order_bills, container.id, 'weight') }}</th>
                      <th>{{SumContainerInfo(order_bills, container.id, 'bags')}}</th>
                      <th>{{SumContainerInfo(order_bills, container.id, 'damaged')}}</th>
                      <th>{{SumContainerInfo(order_bills, container.id, 'lack')}}</th>
                      <th>{{SumContainerInfo(order_bills, container.id, 'leftovers')}}</th>
                      <th>{{SumContainerInfo(order_bills, container.id, 'physical_balance')}}</th>
                      <th>{{SumContainerInfo(order_bills, container.id, 'total')}}</th>
                    </tr>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>

        </div>

        <!-- <button data-toggle="modal" href="#modal-new-container" class="btn btn-success" v-if="display_add">Add container</button> -->
        <div class="modal fade" id="modal-new-container">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">New Container</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col">
                    <table class="table table-sm table-amarelo">
                      <tr>
                        <th><strong>Container name</strong></th>
                        <th><strong>Tare</strong></th>
                        <th><strong>Seal</strong></th>
                        <th><strong>CBM</strong></th>
                        <th><strong>Withdrawal Date</strong></th>
                        <th><strong>Return Date</strong></th>
                      </tr>
                      <tr>
                        <td>{{ current_container.name }}</td>
                        <td>{{ current_container.tare }}</td>
                        <td>{{ current_container.seal }}</td>
                        <td>{{ current_container.cbm }}</td>
                        <td>{{ FormatDate(current_container.withdrawal_date) }}</td>
                        <td>{{ FormatDate(current_container.return_date) }}</td>
                      </tr>
                    </table>
                    <table class="table table-sm">
                      <tr>
                        <td>
                          &nbsp;
                        </td>
                        <td>
                          Associated container
                        </td>
                        <td>
                          Bill Number
                        </td>
                        <td>
                          Bill Key
                        </td>
                        <td>
                          Weight
                        </td>
                        <td>
                          Truck Plate
                        </td>
                      </tr>

                      <tr v-for="(bill, index) in order_bills">
                        <td>
                          <input type="checkbox" v-model="current_container.selected" :value="bill.id" @change="UpdateBill(bill, current_container)">
                        </td>
                        <td>
                          {{ GetContainerNameById(bill.container_id) }}
                        </td>
                        <td>
                          {{ bill.number }}
                        </td>
                        <td>
                          {{ bill.key }}
                        </td>
                        <td>
                          {{ bill.weight }} KGS
                        </td>
                        <td>
                          {{ bill.truck.plate }}
                        </td>
                      </tr>
                    </table>
         
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" @click="ClearContainer">Cancel</button>
                <button type="button" class="btn btn-primary" @click="AddContainer">Save</button>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>
<style scoped>
.table .table tr td:nth-child(2) {
  word-break: break-all;
}
</style>
<script>
export default {
    props: ['value', 'shipping_id', 'display_add'],
    name: 'container-display',
    watch: {
        value: function (newValue, oldValue) {
            this.$data.containers = newValue
        }
    },
    data() {
        return {
            containers: null,
            order_bills: null,
            current_container: {
              selected: [],
              error: {}
            },
        }
    },
    mounted: function () {
        this.$data.containers = this.$props.value
        this.LoadBills();
    },
    methods: {
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
      SumContainerInfo(bills, container_id, info) {
        if(!bills) return null;
        return bills.filter((bill) => {
          return bill.container_id == container_id;
        }).map(bill => {
          return parseInt(bill[info] | 0);
        }).reduce((acc, val) => {
          return acc + val;
        });
      },
      ClearContainer: function () {
        this.$data.current_container = { error:{}, selected:[] }
      },
      AddContainer: function () {
        this.$data.current_container.error = {}
        this.$data.current_container.shipping_id = this.$props.shipping_id
        this.$http.post('/api/container', this.$data.current_container).then(function (e) {
          if (e.body.success) {
            if(typeof this.$data.current_container.edit == 'undefined') {
              this.$data.containers.push(e.body.success)
            }

            if(typeof this.$data.current_container.edit != 'undefined') {
              this.$data.containers[this.$data.current_container.edit] = e.body.success
            }

            this.$emit('container', this.$data.containers)
            this.$data.current_container = { error: {}}

            $('#modal-new-container').modal('hide')
          }
          if (e.body.errors) {
            this.$data.current_container.error = e.body.errors
          }
        })

      },
      EditContainer: function (index) {
        this.$data.current_container = JSON.parse(JSON.stringify(this.$data.containers[index]))
        this.$data.current_container.edit = index
        this.$data.current_container.error = {}

        if(!this.$data.current_container.selected)
          this.$data.current_container.selected = [];

        $('#modal-new-container').modal('show')
      },
      RemoveContainer: function (index) {
        this.$http.delete('/api/container/' + this.$data.containers[index].id).then(function (e) {})
        this.$data.containers.splice(index, 1)
      },
      LoadBills: function () {
        this.$http.get('/api/bill/' + this.$route.params.id).then( e => {
          this.$data.order_bills = e.body
        })

      },
      GetContainerNameById: function (container_id) {
        if (container_id === null) return "Not associated";
        if (container_id == this.$data.current_container.id) {
          return this.$data.current_container.name
        }

        for (var i in this.$data.containers) {
          if (this.$data.containers[i].id == container_id) return this.$data.containers[i].name
        }

      return 'Not associated'
      },
      InContainer: function (bills, container_id) {
        if(!bills) return null;
        return bills.filter((bill) => {
          return bill.container_id == container_id;
        })
      },
      UpdateBill: function (bill, container) {
        if (container.selected.indexOf(bill.id) >= 0) {
          bill.container_id = container.id;
          return;
        }
        bill.container_id = null;
      }
    }
}
</script>