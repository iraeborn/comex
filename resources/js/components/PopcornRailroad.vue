<template>
  <div class="row" v-if="railroad">
    <div class="col">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="withdrawal_date">Depot to pick empty container</label>
            <div class="input-group" v-bind:class="error.depot_empty != '' && error.depot_empty ? 'is-invalid' : ''">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
              </div>
              <textarea name="withdrawal_date" id="withdrawal_date" placeholder="Depot to pick empty container"
                class="form-control" v-model="railroad.depot_empty"
                :class="error.depot_empty != '' && error.depot_empty ? 'is-invalid' : ''">
              </textarea>
            </div>
            <div class="invalid-feedback" v-if="error.depot_empty" v-for="message in error.depot_empty">{{ message }}
            </div>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <iconinput label="Date pick up empty container in the depot" type="date" icon="fas fa-calendar"
            v-model="railroad.withdrawal_date" id="withdrawal_date" name="withdrawal_date"
            :error="error.withdrawal_date" />

        </div>

        <div class="col-6">
          <iconinput label="Date of empty container loading on train" type="date" icon="fas fa-calendar"
            v-model="railroad.start_date" id="start_date" name="start_date" :error="error.start_date" />
        </div>
      </div>

      <div class="row">
        <div class="col-6">
          <iconinput label="Date of arrival of the container in Rondonópolis" type="date" icon="fas fa-calendar"
            v-model="railroad.arrival_date" id="arrival_date" name="arrival_date" :error="error.arrival_date" />
        </div>

        <div class="col-6">
          <iconinput label="Container stuffing starting date" type="date" icon="fas fa-calendar"
            v-model="railroad.final_date" id="final_date" name="final_date" :error="error.final_date" />
        </div>
      </div>

      <div class="row">
        <div class="col-6">
          <iconinput label="Full container train loading date (to Port)" type="date" icon="fas fa-calendar"
            v-model="railroad.withdrawal_start_date" id="withdrawal_start_date" name="withdrawal_start_date"
            :error="error.withdrawal_start_date" />
        </div>

        <div class="col-6">
          <iconinput label="Estimated date full container arrive in Cubatão SP" type="date" icon="fas fa-calendar"
            v-model="railroad.estimated_time" id="estimated_time" name="estimated_time" :error="error.estimated_time" />
        </div>
      </div>

      <!-- <div class="row">
        <div class="col">
            <iconinput label="Loading location" type="text" icon="fas fa-map-marker-alt" v-model="railroad.loading_location" id="loading_location" name="loading_location" :error="error.loading_location" />
        </div>
      </div> -->

      <div class="row">
        <div class="col">
          <iconinput label="Container transfer to port terminal date" type="date" icon="fas fa-calendar"
            v-model="railroad.transfer_terminal_date" id="transfer_terminal_date" name="transfer_terminal_date"
            :error="error.transfer_terminal_date" />
        </div>

        <div class="col">
          <iconinput label="Terminal inbound confirmation date" type="date" icon="fas fa-calendar"
            v-model="railroad.terminal_confirmation_date" id="terminal_confirmation_date"
            name="terminal_confirmation_date" :error="error.terminal_confirmation_date" />
        </div>

        <!-- <div class="col">
            <iconinput label="End of Freetime Origin Date" type="date" icon="fas fa-calendar" v-model="railroad.freetime_date" id="freetime_date" name="freetime_date" :error="error.freetime_date" />
        </div> -->
      </div>

      <div class="row">
        <div class="col">
          <dr-history :url="'/api/railwayObservation/' + railroad.id"></dr-history>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
// transfer_terminal_date
// terminal_confirmation_date
// freetime_date
export default {
  props: ['value'],
  name: 'popcorn-railroad',
  data() {
    return {
      error: {},
      loading: true,
      railroad: null,
      current_vehicle: {},
      current_vehicle_error: 'oi'
    }
  },
  mounted: function () {
    this.$data.loading = false
    this.$data.railroad = this.$props.value

    // if(!this.$data.railroad) {
    //   this.$data.railroad = {}
    //   this.$data.railroad.vehicles = []
    // }
  },
  watch: {
    value: function (new_value, old_value) {
      this.$data.railroad = new_value
    }
  },
  methods: {
    EditVehicle: function (index) {
      this.$data.current_vehicle = JSON.parse(JSON.stringify(this.$data.railroad.vehicles[index]))
      this.$data.current_vehicle.index = index
      $('#manage-vehicle').modal('show')
    },
    RemoveVehicle: function (index) {
      this.$data.railroad.vehicles.splice(index, 1)
    },
    SaveVehicle: function (index) {
      this.$data.current_vehicle_error = {}

      this.$http.post('/api/vehicle', this.$data.current_vehicle).then(e => {
        if (e.body.success) {
          $('.modal').modal('hide')

          if (!isNaN(index)) {
            this.$data.railroad.vehicles[index] = this.$data.current_vehicle
          }

          if (isNaN(index)) {
            this.$data.current_vehicle.index = this.$data.railroad.vehicles.length
            this.$data.railroad.vehicles.push(this.$data.current_vehicle)
          }

          this.$data.current_vehicle = {}
          this.$forceUpdate()
          // this.emit('input', this.$data.railroad)
          return
        }

        if (e.body.errors) {
          this.$data.current_vehicle_error = e.body.errors
        }
      })
    },
    Save: function () {
      this.error = {}
      if (!this.$data.railroad.order_id) this.$data.railroad.order_id = this.$route.params.id
      this.$http.put('/api/railroad/' + this.$route.params.id, this.$data.railroad).then(e => {
        if (e.body.success) return //this.$toaster.success(e.body.success)

        this.$data.error = e.body.errors
        this.$toaster.error('There are problems in railroad')
      })
    },
    CountVehicles: function () {
      if (!this.$data.railroad.vehicles) return 0
      return this.$data.railroad.vehicles.length
    }
  }
}
</script>