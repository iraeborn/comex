<template>
  <div>
    <div class="row">
      <div class="col">
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="terminal">Cross Docking Terminal</label>
              <textarea-icon type="text" icon="fas fa-box" v-model="container_stuffing.terminal" name="terminal" :class="error.terminal != '' && error.terminal ? 'is-invalid' : ''" id="terminal" />
              <div class="invalid-feedback" v-if="error.terminal" v-for="message in error.terminal">{{message}}</div>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="depot_pick">Depot Pick up Empty Container</label>
              <textarea-icon type="text" icon="fas fa-box" v-model="container_stuffing.depot_pick" name="depot_pick" :class="error.depot_pick != '' && error.depot_pick ? 'is-invalid' : ''" id="depot_pick" />
              <div class="invalid-feedback" v-if="error.depot_pick" v-for="message in error.depot_pick">{{message}}</div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="dispatch_place_name">Dispatch Place</label>
              <input-icon type="text" icon="fas fa-box" v-model="container_stuffing.dispatch_place_name" name="dispatch_place_name" :class="error.dispatch_place_name != '' && error.dispatch_place_name ? 'is-invalid' : ''" id="dispatch_place_name" />
              <div class="invalid-feedback" v-if="error.dispatch_place_name" v-for="message in error.dispatch_place_name">{{message}}</div>
            </div>
          </div>
          <div class="col">
            <div class="form-group">
              <label for="dispatch_place_code">Dispatch Place Code</label>
              <input-icon type="text" icon="fas fa-box" v-model="container_stuffing.dispatch_place_code" name="dispatch_place_code" :class="error.dispatch_place_code != '' && error.dispatch_place_code ? 'is-invalid' : ''" id="dispatch_place_code" />
              <div class="invalid-feedback" v-if="error.dispatch_place_code" v-for="message in error.dispatch_place_code">{{message}}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label for="qtd_container">Quantity Container</label>
              <input-icon type="text" icon="fas fa-box" v-model="container_stuffing.qtd_container" name="qtd_container" :class="error.qtd_container != '' && error.qtd_container ? 'is-invalid' : ''" id="qtd_container" />
              <div class="invalid-feedback" v-if="error.qtd_container" v-for="message in error.qtd_container">{{message}}</div>
            </div>

          </div>
          <div class="col-12">
            <div class="form-group">
              <label for="container_type">Container type</label>
              <input-icon type="text" icon="fas fa-box" v-model="container_stuffing.container_type" name="container_type" :class="error.container_type != '' && error.container_type ? 'is-invalid' : ''" id="container_type" />
              <div class="invalid-feedback" v-if="error.container_type" v-for="message in error.container_type">{{message}}</div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="quantity_per_container">Quantity per container (BAGS)</label>
          <input-icon type="text" icon="fas fa-box" v-model="container_stuffing.quantity_per_container" name="quantity_per_container" :class="error.quantity_per_container != '' && error.quantity_per_container ? 'is-invalid' : ''" id="quantity_per_container" />
          <div class="invalid-feedback" v-if="error.quantity_per_container" v-for="message in error.quantity_per_container">{{message}}</div>
        </div>
      </div>
      <div class="col">
        <div class="form-group">
          <label for="number_of_bags">Quantity total (BAGS)</label>
          <input-icon type="number" icon="fas fa-shopping-bag" v-model="container_stuffing.number_of_bags" name="number_of_bags" :class="error.number_of_bags != '' && error.number_of_bags ? 'is-invalid' : ''" id="number_of_bags" />
          <div class="invalid-feedback" v-if="error.number_of_bags" v-for="message in error.number_of_bags">{{message}}</div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="weight">Quantity per container (KGS)</label>
          <input-icon type="number" icon="fas fa-weight-hanging" v-model="container_stuffing.weight" name="weight" :class="error.weight != '' && error.weight ? 'is-invalid' : ''" id="weight" />
          <div class="invalid-feedback" v-if="error.weight" v-for="message in error.weight">{{message}}</div>
        </div>
      </div>
      <div class="col">
        <div class="form-group">
          <label for="quantity_total">Quantity total (KGS)</label>
          <input-icon type="number" icon="fas fa-box" v-model="container_stuffing.quantity_total" name="quantity_total" :class="error.quantity_total != '' && error.quantity_total ? 'is-invalid' : ''" id="quantity_total" />
          <div class="invalid-feedback" v-if="error.quantity_total" v-for="message in error.quantity_total">{{message}}</div>
        </div>
      </div>

    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="empty_release_for_outbound_date">Empty Release for Outbound Date</label>
          <input-icon type="date" icon="fas fa-calendar" v-model="container_stuffing.empty_release_for_outbound_date" name="empty_release_for_outbound_date" :class="error.empty_release_for_outbound_date != '' && error.empty_release_for_outbound_date ? 'is-invalid' : ''" id="empty_release_for_outbound_date" />
          <div class="invalid-feedback" v-if="error.empty_release_for_outbound_date" v-for="message in error.empty_release_for_outbound_date">{{message}}</div>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="stuffing_starting_date">Stuffing Starting Date</label>
          <input-icon type="date" icon="fas fa-calendar" v-model="container_stuffing.stuffing_starting_date" name="stuffing_starting_date" :class="error.stuffing_starting_date != '' && error.stuffing_starting_date ? 'is-invalid' : ''" id="stuffing_starting_date" />
          <div class="invalid-feedback" v-if="error.stuffing_starting_date" v-for="message in error.stuffing_starting_date">{{message}}</div>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="stuffing_ending_date">Stuffing Ending Date</label>
          <input-icon type="date" icon="fas fa-calendar" v-model="container_stuffing.stuffing_ending_date" name="stuffing_ending_date" :class="error.stuffing_ending_date != '' && error.stuffing_ending_date ? 'is-invalid' : ''" id="stuffing_ending_date" />
          <div class="invalid-feedback" v-if="error.stuffing_ending_date" v-for="message in error.stuffing_ending_date">{{message}}</div>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="containers_return_date">Containers Return Date</label>
          <input-icon type="date" icon="fas fa-calendar" v-model="container_stuffing.containers_return_date" name="containers_return_date" :class="error.containers_return_date != '' && error.containers_return_date ? 'is-invalid' : ''" id="containers_return_date" />
          <div class="invalid-feedback" v-if="error.containers_return_date" v-for="message in error.containers_return_date">{{message}}</div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="terminal_observations">Terminal Observations</label>
          <input-icon type="text" icon="fas fa-edit" v-model="container_stuffing.terminal_observations" name="terminal_observations" :class="error.terminal_observations != '' && error.terminal_observations ? 'is-invalid' : ''" id="terminal_observations" />
          <div class="invalid-feedback" v-if="error.terminal_observations" v-for="message in error.terminal_observations">{{message}}</div>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="terminal_user">Terminal User</label>
          <input-icon type="text" icon="fas fa-user" v-model="container_stuffing.terminal_user" name="terminal_user" :class="error.terminal_user != '' && error.terminal_user ? 'is-invalid' : ''" id="terminal_user" />
          <div class="invalid-feedback" v-if="error.terminal_user" v-for="message in error.terminal_user">{{message}}</div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="col">
        <containers-display v-model="container_stuffing.containers" :shipping_id="shipping.id"></containers-display>
      </div>
    </div>

    <!-- <div class="row">
      <div class="col">
        <p>Per Container</p>
      </div>
    </div> -->
    
    <!--<div class="row">
      <div class="col" v-if="container_stuffing">
        <p v-if="!container_stuffing.containers">No containers were added<br>You can add a container in the Shipping tab!</p>
        <table class="table table-sm table-containers" v-if="container_stuffing.containers">
          <tr>
            <th>Container</th>
            <th>Tare</th>
            <th>Seal</th>
            <th>&nbsp;</th>
          </tr>
          <tr v-for="(container, index) in container_stuffing.containers">
            <td>
              {{ container.name }}
            </td>
            <td>
              {{ container.tare }}
            </td>
            <td>
              {{ container.seal }}
            </td>
            <td style="width: 150px;">
            </td>
          </tr>
        </table>
      </div>
    </div>-->

    <div class="row" v-if="container_stuffing.id">
      <div class="col">
          <dr-history :url="'/api/containerObservation/' + container_stuffing.id"></dr-history>
      </div>
    </div>

  </div>
</template>

<script>
export default {
    name: 'popcorn-container-stuffing',
    props: ['value', 'shipping'],
    data () {
        return {
            container_stuffing: {
              containers: null,
              selected: []
            },
            bill_list: [],
            error: {},
        }
    },
    watch: {
        value: function ( new_value, old_value ) {

          if (!new_value) new_value = {}
          if (!new_value.vehicles) new_value.vehicles = []
            
          this.$data.container_stuffing = new_value

          if(this.$data.container_stuffing)
            this.$data.original_containers = this.$data.container_stuffing.containers
        },
    },
    async mounted() {
      this.$data.container_stuffing = this.$props.value

      if(this.$data.container_stuffing)
        this.$data.original_containers = this.$data.container_stuffing.containers

      this.$data.shipping = (await this.$http.get('/api/shipping/' + this.$route.params.id)).body;

      this.$data.container_stuffing.containers = this.$data.shipping.containers;
    },
    methods: {
        Save: function () {
          this.$http.put('/api/stuffing/' + this.$route.params.id, this.$data.container_stuffing).then(e => {
            for (var i in this.$refs.container_extra) {
              this.$refs.container_extra[i].ReloadBills()
            }
            if(e.body.errors) {
              this.$data.error = e.body.errors
              this.$toaster.error('There are problems in Container stuffing')
            }
          })
        },
        Revert: function ( index ) {
          this.$data.container_stuffing.containers[index] = this.$data.original_containers[index]
        },
        AddExtraField: function ( index ) {
          if(typeof this.$data.container_stuffing.containers[index].extra_fields != 'object')
            this.$data.container_stuffing.containers[index].extra_fields = []

          this.$data.container_stuffing.containers[index].extra_fields.push({
            name: null,
            value: null
          })

          this.$forceUpdate()
        },
        Update: function (selected, container_id) {
          this.$emit('select', selected, container_id)
        },
        ForceUpdate: function () {
          this.$forceUpdate()
        }
    }
}
</script>