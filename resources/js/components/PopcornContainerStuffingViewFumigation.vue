<template>
  <div>
    <table class="table table-sm table-amarelo">
      <tr>
        <td colspan="2" style="width: 33.33%"><strong for="terminal">Terminal</strong></td>
        <td colspan="2" style="width: 33.33%"><strong for="weight">Quantity (KGS)</strong></td>
        <td colspan="2" style="width: 33.33%"><strong for="number_of_bags">Quantity (Number of Bags)</strong></td>
      </tr>
      <tr>
        <td colspan="2"><p>{{ container_stuffing.terminal || "Not informed" }}</p></td>
        <td colspan="2">{{ container_stuffing.weight || "Not informed" }}</td>
        <td colspan="2">{{ container_stuffing.number_of_bags || "Not informed" }}</td>
      </tr>
      <tr><td class="spacer" colspan="6"></td></tr>
      <tr>
        <td colspan="2"><strong for="empty_release_for_outbound_date">Empty Release for Outbound Date</strong></td>
        <td colspan="2"><strong for="stuffing_starting_date">Stuffing Starting Date</strong></td>
        <td colspan="2"><strong for="stuffing_ending_date">Stuffing Ending Date</strong></td>
      </tr>
      <tr>
        <td colspan="2">{{ DateFormat(container_stuffing.empty_release_for_outbound_date) }}</td>
        <td colspan="2">{{ DateFormat(container_stuffing.stuffing_starting_date) }}</td>
        <td colspan="2">{{ DateFormat(container_stuffing.stuffing_ending_date) }}</td>
      </tr>
      <tr><td class="spacer" colspan="6"></td></tr>
      <tr>
        <td colspan="3"><strong for="terminal_observations">Terminal Observations</strong></td>
        <td colspan="3"><strong for="terminal_user">Terminal User</strong></td>
      </tr>
      <tr>
        <td colspan="3">{{ container_stuffing.terminal_observations || "Not informed" }}</td>
        <td colspan="3">{{ container_stuffing.terminal_user || "Not informed" }}</td>
      </tr>
  
    </table>
  

    <div class="row">
      <div class="col">
        <strong>Per Container</strong>
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
            <th>NFE balance</th>
            <th>Failure</th>
            <th>Lack</th>
            <th>Leftovers</th>
            <th>Physical Balance</th>
            <th>Stuffed amount</th>
            <th style="width: 80px;">&nbsp;</th>
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
                {{ parseInt(container.nfe_balance | 0) }}
              </td>
              <td>
                {{ parseInt(container.failure | 0) }}
              </td>
              <td>
                {{ parseInt(container.lack | 0) }}
              </td>
              <td>
                {{ parseInt(container.leftovers | 0) }}
              </td>
              <td>
                {{ parseInt(container.physical_balance | 0) }}
              </td>
              <td>
                {{ parseInt(container.total | 0) }}
              </td>
              <td>
                <!-- <a class="btn btn-primary btn-sm float-right" data-toggle="modal" :href="'#modal-' + index" @click="SetContainer(index)">Edit</a> -->
                <div class="modal fade" :id="'modal-' + index">
                  <div class="modal-dialog modal-primary">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Container Stuffing</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="nfe_balance">NFE Balance</label>
                              <input-icon type="number" icon="fas fa-balance-scale" v-model="current_container.nfe_balance" name="nfe_balance" :class="error.nfe_balance != '' && error.nfe_balance ? 'is-invalid' : ''" id="nfe_balance" />
                              <div class="invalid-feedback" v-if="error.nfe_balance" v-for="message in error.nfe_balance">{{message}}</div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="failure">Failure</label>
                              <input-icon type="number" icon="fas fa-frown" v-model="current_container.failure" name="failure" :class="error.failure != '' && error.failure ? 'is-invalid' : ''" id="failure" />
                              <div class="invalid-feedback" v-if="error.failure" v-for="message in error.failure">{{message}}</div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="lack">Lack</label>
                              <input-icon type="number" icon="fas fa-star-half-alt" v-model="current_container.lack" name="lack" :class="error.lack != '' && error.lack ? 'is-invalid' : ''" id="lack" />
                              <div class="invalid-feedback" v-if="error.lack" v-for="message in error.lack">{{message}}</div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="leftovers">Leftovers</label>
                              <input-icon type="number" icon="fab fa-stack-overflow" v-model="current_container.leftovers" name="leftovers" :class="error.leftovers != '' && error.leftovers ? 'is-invalid' : ''" id="leftovers" />
                              <div class="invalid-feedback" v-if="error.leftovers" v-for="message in error.leftovers">{{message}}</div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="physical_balance">Physical balance</label>
                              <input-icon type="number" icon="fas fa-balance-scale" v-model="current_container.physical_balance" name="physical_balance" :class="error.physical_balance != '' && error.physical_balance ? 'is-invalid' : ''" id="physical_balance" />
                              <div class="invalid-feedback" v-if="error.physical_balance" v-for="message in error.physical_balance">{{message}}</div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label for="total">Stuffed amount</label>
                              <input-icon type="number" icon="fas fa-shopping-bag" v-model="current_container.total" name="total" :class="error.total != '' && error.total ? 'is-invalid' : ''" id="total" />
                              <div class="invalid-feedback" v-if="error.total" v-for="message in error.total">{{message}}</div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success btn-sm" data-dismiss="modal" @click="UpdateContainer(index)">Save</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="10">
                <table class="table table-sm">
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
                  <tr v-for="(bill, index) in InContainer($data.order_bills, container.id)">
                      <td>{{ bill.number }}</td>
                      <td style="word-break: break-all;">{{ bill.key }}</td>
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
                    <th>{{SumContainerInfo($data.order_bills, container.id, 'weight') }}</th>
                    <th>{{SumContainerInfo($data.order_bills, container.id, 'bags')}}</th>
                    <th>{{SumContainerInfo($data.order_bills, container.id, 'damaged')}}</th>
                    <th>{{SumContainerInfo($data.order_bills, container.id, 'lack')}}</th>
                    <th>{{SumContainerInfo($data.order_bills, container.id, 'leftovers')}}</th>
                    <th>{{SumContainerInfo($data.order_bills, container.id, 'physical_balance')}}</th>
                    <th>{{SumContainerInfo($data.order_bills, container.id, 'total')}}</th>
                  </tr>

                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>


  </div>
</template>

<style scoped>
p {
  margin-bottom: 0;
}

.spacer {
  background-color: #fff;
}

.table-containers > th {
  border-top: 5px solid #fef101;
}

.table-containers > td {
  border-top: 3px solid #fef101;
}
</style>

<script>
export default {
    name: 'popcorn-container-stuffing-view-fumigation',
    props: ['value'],
    data () {
        return {
            current_container: {},
            container_stuffing: {
              containers: null,
              selected: []
            },
            bill_list: [],
            error: {},
        }
    },
    mounted: function () {
      this.$data.container_stuffing = this.$props.value;

      this.LoadBills();

      this.$http.get('/api/containers/' + this.$route.params.id).then(e => {
        this.$data.container_stuffing.containers = e.body

        if(this.$data.container_stuffing)
          this.$data.original_containers = this.$data.container_stuffing.containers

        this.$forceUpdate();
      });
    },
    watch: {
        value: function ( new_value, old_value ) {
          if (!new_value) return;

          this.$data.container_stuffing = JSON.parse(JSON.stringify(new_value));

          this.$http.get('/api/containers/' + this.$route.params.id).then(e => {
            this.$data.container_stuffing.containers = e.body

            if(this.$data.container_stuffing)
              this.$data.original_containers = this.$data.container_stuffing.containers

            this.$forceUpdate();
          });


        },
    },
    methods: {
      SumContainerInfo(bills, container_id, info) {
        if(!bills) return null;
        let b = bills.filter((bill) => {
          return bill.container_id == container_id;
        })

        if(!b.length) return 0;

        b = b.map(bill => {
          return parseInt(bill[info] | 0);
        })

        if(!b.length) return 0;

        b = b.reduce((acc, val) => {
          return acc + val;
        });

        return b;
      },
      ForceUpdate: function () {
        this.$forceUpdate()
      },
      DateFormat: function (date) {
        if (!date) return "Not informed";
        let [year, month, day] = date.split(/-/);
        return month + "/" + day + "/" + year;
      },
      SetContainer: function (index) {
        this.$data.current_container = JSON.parse(JSON.stringify(this.$data.container_stuffing.containers[index]));
      },
      UpdateContainer: function (index) {
        this.$data.container_stuffing.containers[index] = this.$data.current_container = JSON.parse(JSON.stringify(this.$data.current_container));
      },
      Save: function () {
        this.$http.put('/api/containers-stuffing/' + this.$route.params.id, this.$data.container_stuffing.containers).then(e => {
          
        });
      },
      LoadBills: function () {
        this.$http.get('/api/bill/' + this.$route.params.id).then( e => {
          this.$data.order_bills = e.body
        });

        this.$forceUpdate();
      },
      InContainer: function (bills, container_id) {
        if(!bills) return null;
        return bills.filter((bill) => {
          return bill.container_id == container_id;
        })
      },

    },
}
</script>