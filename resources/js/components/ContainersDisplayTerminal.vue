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
              <th>&nbsp;</th>
            </tr>
            <tbody v-for="(container, index) in containers">
              <tr>
                <td>{{container.name}}</td>
                <td>{{container.tare}} KGS</td>
                <td>{{container.seal}}</td>
                <td class="text-right">
                  <button class="btn btn-success btn-sm" @click="EditContainer(index)">Edit</button>
                  <button class="btn btn-danger btn-sm"  @click="RemoveContainer(index)">Remove</button>
                </td>
              </tr>
              <tr>
                <td colspan="4">
                  <table class="table table-sm" v-if="InContainer(order_bills, container.id).length > 0">
                    <tr>
                      <th>Bill number</th>
                      <th>Key</th>
                      <th>Weight</th>
                    </tr>
                    <tr v-for="bill in InContainer(order_bills, container.id)">
                      <td>{{ bill.number }}</td>
                      <td>{{ bill.key }}</td>
                      <td>{{ bill.weight }}</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>

        </div>

        <button data-toggle="modal" href="#modal-new-container" class="btn btn-success">Add container</button>
        <div class="modal fade" id="modal-new-container">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">New Container</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col">
                    <iconinput label="Container" type="text" v-model="current_container.name" id="name" name="name" icon="fas fa-box" :error="current_container.error.name" />
                    <iconinput label="Tare" type="text" v-model="current_container.tare" id="tare" name="tare" icon="fas fa-weight-hanging" :error="current_container.error.tare" />
                    <iconinput label="Seal" type="text" v-model="current_container.seal" id="seal" name="seal" icon="fas fa-stamp" :error="current_container.error.seal" />
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

<script>
export default {
    props: ['value', 'shipping_id'],
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