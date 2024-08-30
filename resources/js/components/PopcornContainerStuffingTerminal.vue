<template>
  <div class="V2">
    <table class="table table-sm table-amarelo">
      <tr>
        <td colspan="3" style="width: 25%"><strong for="terminal">Cross Docking Terminal</strong></td>
        <td colspan="3" style="width: 25%"><strong for="terminal">Depot Pick up Empty Container</strong></td>
        <td colspan="3" style="width: 25%"><strong for="terminal">Quantity Container</strong></td>
        <td colspan="3" style="width: 25%"><strong>Container type</strong></td>
      </tr>
      <tr>
        <td colspan="3"><p>{{ container_stuffing.terminal || "Not informed" }}</p></td>
        <td colspan="3"><p>{{ container_stuffing.depot_pick || "Not informed" }}</p></td>
        <td colspan="3"><p>{{ container_stuffing.qtd_container || "Not informed" }}</p></td>
        <td colspan="3">{{ container_stuffing.container_type || "Not informed" }}</td>
      </tr>
      <tr><td class="spacer" colspan="12"></td></tr>
      <tr>
        <td colspan="6"><strong>Quantity per container (BAGS)</strong></td>
        <td colspan="6"><strong for="number_of_bags">Quantity total (BAGS)</strong></td>
      </tr>
      <tr>
        <td colspan="6">{{ container_stuffing.quantity_per_container || "Not informed" }}</td>
        <td colspan="6">{{ container_stuffing.number_of_bags || "Not informed" }}</td>
      </tr>
      <tr><td class="spacer" colspan="12"></td></tr>
      <tr>
        <td colspan="6"><strong for="weight">Quantity per container (KGS)</strong></td>
        <td colspan="6"><strong>Quantity total (KGS)</strong></td>
      </tr>
      <tr>
        <td colspan="6">{{ container_stuffing.weight || "Not informed" }}</td>
        <td colspan="6">{{ container_stuffing.quantity_total || "Not informed" }}</td>
      </tr>
      <tr><td class="spacer" colspan="12"></td></tr>
      <tr>
        <td colspan="4"><strong for="empty_release_for_outbound_date">Empty Release for Outbound Date</strong></td>
        <td colspan="4"><strong for="stuffing_starting_date">Stuffing Starting Date</strong></td>
        <td colspan="4"><strong for="stuffing_ending_date">Stuffing Ending Date</strong></td>
      </tr>
      <tr>
        <td colspan="4">{{ DateFormat(container_stuffing.empty_release_for_outbound_date) }}</td>
        <td colspan="4">{{ DateFormat(container_stuffing.stuffing_starting_date) }}</td>
        <td colspan="4">{{ DateFormat(container_stuffing.stuffing_ending_date) }}</td>
      </tr>
      <tr><td class="spacer" colspan="12"></td></tr>
      <tr>
        <td colspan="6"><strong for="terminal_observations">Terminal Observations</strong></td>
        <td colspan="6"><strong for="terminal_user">Terminal User</strong></td>
      </tr>
      <tr>
        <td colspan="6">{{ container_stuffing.terminal_observations || "Not informed" }}</td>
        <td colspan="6">{{ container_stuffing.terminal_user || "Not informed" }}</td>
      </tr>
  
    </table>
    <div class="row">
      <div class="col">
        <p>Containers</p>
      </div>
      <div class="col text-right">
        <button @click="OpenAddContainerModal" class="btn btn-success btn-sm" v-if="can.container">Add new container</button>

        <div class="modal fade text-left" id="modal-current-container">
          <div class="modal-dialog modal-primary">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Container</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label for="name">Container number</label>
                  <input-icon type="text" icon="fas fa-box" v-model="current_container.name" name="name" :class="error.name != '' && error.name ? 'is-invalid' : ''" id="name" />
                  <div class="invalid-feedback" v-if="error.name" v-for="message in error.name">{{message}}</div>
                </div>

                <div class="form-group">
                  <label for="tare">Tare</label>
                  <input-icon type="number" icon="fas fa-box" v-model="current_container.tare" name="tare" :class="error.tare != '' && error.tare ? 'is-invalid' : ''" id="tare" />
                  <div class="invalid-feedback" v-if="error.tare" v-for="message in error.tare">{{message}}</div>
                </div>

                <div class="form-group">
                  <label for="seal">Seal</label>
                  <input-icon type="text" icon="fas fa-box" v-model="current_container.seal" name="seal" :class="error.seal != '' && error.seal ? 'is-invalid' : ''" id="seal" />
                  <div class="invalid-feedback" v-if="error.seal" v-for="message in error.seal">{{message}}</div>
                </div>

                <div class="form-group">
                  <label for="cbm">CBM</label>
                  <input-icon type="number" icon="fas fa-box" v-model="current_container.cbm" name="cbm" :class="error.cbm != '' && error.cbm ? 'is-invalid' : ''" id="cbm" />
                  <div class="invalid-feedback" v-if="error.cbm" v-for="message in error.cbm">{{message}}</div>
                </div>

                <div class="form-group">
                  <label for="withdrawal_date">Withdrawal Date</label>
                  <input-icon type="date" icon="fas fa-box" v-model="current_container.withdrawal_date" name="withdrawal_date" :class="error.withdrawal_date != '' && error.withdrawal_date ? 'is-invalid' : ''" id="withdrawal_date" />
                  <div class="invalid-feedback" v-if="error.withdrawal_date" v-for="message in error.withdrawal_date">{{message}}</div>
                </div>

                <div class="form-group">
                  <label for="return_date">Return Date</label>
                  <input-icon type="date" icon="fas fa-box" v-model="current_container.return_date" name="return_date" :class="error.return_date != '' && error.return_date ? 'is-invalid' : ''" id="return_date" />
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
    </div>
    
    <div class="row">
      <div class="col" v-if="container_stuffing">
        <p v-if="!container_stuffing.containers">No containers were added</p>
        <table class="table table-sm table-containers" v-if="container_stuffing.containers">
          <tr>
            <th>Container</th>
            <th>Tare</th>
            <th>Seal</th>
            <th>CBM</th>
            <th>Withdrawal Date</th>
            <th>Return Date</th>
            <th>&nbsp;</th>
          </tr>
          <tbody v-for="(container, index) in container_stuffing.containers">
            <tr>
              <td>
                {{ container.name }}
              </td>
              <td>
                {{ container.tare }}
              </td>
              <td>
                {{ container.seal }}
              </td>
              <td>
                {{ container.cbm }}
              </td>
              <td>
                {{ DateFormat(container.withdrawal_date) }}
              </td>
              <td>
                {{ DateFormat(container.return_date) }}
              </td>
              <td style="width: 150px;">
                <button v-if="can.container" class="btn btn-danger btn-sm float-right" style="margin-left: 5px;" @click="DeleteContainer(index)">Remove</button>
                <button v-if="can.container" class="btn btn-success btn-sm float-right" @click="EditContainer(container, index)">Edit</button>
              </td>
            </tr>
            <tr>
              <td colspan="7">
                <p v-if="!InContainer(bill_list, container.id).length > 0" class="text-center">No bills for this container</p>
                <table class="table table-sm" v-if="InContainer(bill_list, container.id).length > 0">
                  <tr>
                    <th>Bill number</th>
                    <th>Key</th>
                    <th>Weight</th>
                    <!-- <th>Bags</th> -->
                    <th>Damaged</th>
                    <th>Lack</th>
                    <th>Leftovers</th>
                    <th>Physical balance</th>
                    <th>Stuffed amount</th>
                    <th>&nbsp;</th>
                  </tr>
                  <tr v-for="(bill, index) in InContainer(bill_list, container.id)">
                    <td>{{ bill.number }}</td>
                    <td>{{ bill.key }}</td>
                    <td>{{ bill.weight }}</td>
                    <!-- <td>{{ bill.bags }}</td> -->
                    <td>{{ bill.damaged }}</td>
                    <td>{{ bill.lack }}</td>
                    <td>{{ bill.leftovers }}</td>
                    <td>{{ bill.physical_balance }}</td>
                    <td>{{ bill.total }}</td>
                    <td class="text-right">
                      <button class="btn btn-success btn-sm" @click="OpenBillModal(bill, index)">Edit</button>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">&nbsp;</td>
                    <th>{{SumContainerInfo(bill_list, container.id, 'weight') }}</th>
                    <!-- <th>{{SumContainerInfo(bill_list, container.id, 'bags')}}</th> -->
                    <th>{{SumContainerInfo(bill_list, container.id, 'damaged')}}</th>
                    <th>{{SumContainerInfo(bill_list, container.id, 'lack')}}</th>
                    <th>{{SumContainerInfo(bill_list, container.id, 'leftovers')}}</th>
                    <th>{{SumContainerInfo(bill_list, container.id, 'physical_balance')}}</th>
                    <th>{{SumContainerInfo(bill_list, container.id, 'total')}}</th>
                    <td>&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>


    <div class="modal fade" id="modal-bill">
      <div class="modal-dialog modal-primary">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Bill number: {{ current_bill.number }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            
            <p><strong>Bags:</strong> {{ current_bill.bags }}</p>

            <div class="form-group">
              <label for="seal">Damaged (BAGS)</label>
              <input-icon type="number" icon="fas fa-box" v-model="current_bill.damaged" name="damaged" :class="error.damaged != '' && error.damaged ? 'is-invalid' : ''" id="seal" />
            </div>

            <div class="form-group">
              <label for="seal">Lack (BAGS)</label>
              <input-icon type="number" icon="fas fa-box" v-model="current_bill.lack" name="seal" :class="error.lack != '' && error.lack ? 'is-invalid' : ''" id="seal" />
            </div>

            <div class="form-group">
              <label for="seal">Leftovers (BAGS)</label>
              <input-icon type="number" icon="fas fa-box" v-model="current_bill.leftovers" name="seal" :class="error.leftovers != '' && error.leftovers ? 'is-invalid' : ''" id="seal" />
            </div>

            <div class="form-group">
              <label for="seal">Stuffed amount (BAGS)</label>
              <input-icon type="number" icon="fas fa-box" v-model="current_bill.total" name="seal" :class="error.total != '' && error.physical_balance ? 'is-invalid' : ''" id="seal" />
            </div>

            <div class="form-group">
              <strong><label for="seal">Physical balance (BAGS)</label></strong>
              <!-- <input-icon type="number" icon="fas fa-box" v-model="current_bill.physical_balance" name="seal" :class="error.physical_balance != '' && error.physical_balance ? 'is-invalid' : ''" id="seal" /> -->
              <p>{{ GetBalance() }}</p>

            </div>


          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" @click="SaveBill()">Save</button>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <button class="btn btn-primary float-right" @click="Save">Save</button>
      </div>
    </div>

    <div class="row" v-if="container_stuffing.id">
      <div class="col">
          <dr-history :url="'/api/containerObservation/' + container_stuffing.id"></dr-history>
      </div>
    </div>

  </div>
</template>

<style scoped>
.spacer {
  background-color: #fff;
  border: 2px solid #fff;
}

strong {
  font-size: 13px;
}
</style>

<script>
export default {
    name: 'popcorn-container-stuffing-v2',
    data () {
      return {
        can: {
          container: true
        },
        current_bill: {
          damaged: 0,
          lack: 0,
          leftovers: 0,
          physical_balance: 0,
          total: 0,
        },
        shipping: {},
        current_container: {},
        container_stuffing: {
          containers: null,
          selected: []
        },
        bill_list: [],
        error: {},
      }
    },
    mounted: async function () {
      let container_stuffing = await this.GetStuffing();
      this.$data.container_stuffing = container_stuffing.body;
      let shipping = await this.GetShipping(this.$data.shipping);
      this.$data.shipping = shipping.body;
      this.$data.container_stuffing.containers = this.$data.shipping.containers;

      let bills = await this.GetBills();
      this.$data.bill_list = bills.body;

      this.$forceUpdate();
    },
    methods: {
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
      async GetStuffing() {
        return this.$http.get('/api/stuffing/' + this.$route.params.id);
      },
      async GetShipping() {
        return this.$http.get('/api/shipping/' + this.$route.params.id);
      },
      async GetBills() {
        return this.$http.get('/api/bill/' + this.$route.params.id);
      },
      async SaveBill() {
        let index = this.$data.current_bill.index;
        this.$data.bill_list[index] = JSON.parse(JSON.stringify(this.$data.current_bill));
        this.$http.post('/api/bill/' + this.$data.current_bill.id, this.$data.current_bill);
        this.$data.current_bill = {};
        $('#modal-bill').modal('hide');
      },
      GetBalance() {
        let bags      = parseInt(this.$data.current_bill.bags);
        let damaged   = parseInt(this.$data.current_bill.damaged);
        let lack      = parseInt(this.$data.current_bill.lack);
        let leftovers = parseInt(this.$data.current_bill.leftovers);

        if(isNaN(bags))      bags      = 0;
        if(isNaN(damaged))   damaged   = 0;
        if(isNaN(lack))      lack      = 0;
        if(isNaN(leftovers)) leftovers = 0;

        this.$data.current_bill.physical_balance = bags - damaged - lack - leftovers;

        return this.$data.current_bill.physical_balance;
      },
      OpenBillModal(bill, index) {
        this.$data.current_bill = JSON.parse(JSON.stringify(bill));
        this.$data.current_bill.index = index;
        $('#modal-bill').modal('show');
      },
      InContainer: function (bills, container_id) {
        if(!bills) return null;
        return bills.filter((bill) => {
          return bill.container_id == container_id && bill.container_id !== null;
        })
      },
      FormatDate: function (date) {
        if (!date) return 'Not informed';
        let [year, month, day] = date.split(/-/);
        return month + "/" + day + "/" + year;
      },
      OpenAddContainerModal: function () {
        this.current_container = {
          name: null,
          seal: null,
          tare: null,
          shipping_id: this.$data.shipping.id,
          index: null,
          withdrawal_date: this.$data.container_stuffing.empty_release_for_outbound_date,
          return_date: this.$data.container_stuffing.containers_return_date
        };

        $('#modal-current-container').modal('show');
      },
      EditContainer: function (container, index) {
        this.current_container = JSON.parse(JSON.stringify(container));
        this.current_container.index = index;
        $('#modal-current-container').modal('show');
      },
      SaveContainer: function () {
        if (this.current_container.index == null) {
          this.$data.container_stuffing.containers.push(this.current_container);
          this.current_container = {
            name: null,
            seal: null,
            tare: null,
            shipping_id: this.$data.shipping.id,
            index: null,
          }
          return;
        }
        this.$data.container_stuffing.containers[this.$data.current_container.index] = JSON.parse(JSON.stringify(this.$data.current_container))
        this.$forceUpdate();
      },
      DeleteContainer: function (index) {
        this.$data.container_stuffing.containers.splice(index, 1);
        this.$forceUpdate();
      },
      DateFormat: function (date) {
        if(!date) return 'Not informed';
        let [year, month, day] = date.split(/-/);
        return month + "/" + day + "/" + year;
      },
      Save: function () {
        this.$http.put('/api/containers/' + this.$route.params.id, this.$data.container_stuffing.containers).then(e => {
          if(e.body.errors) {
            this.$data.error = e.body.errors
            this.$toaster.error('There are problems in Container stuffing')
            return;
          }

          this.$toaster.success('Containers saved!')
        })
      },
    }
}
</script>