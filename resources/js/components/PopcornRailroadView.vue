<template>
  <div class="row" v-if="railroad">
    <div class="col">
      <div class="row">
        <div class="col">

          <table class="table table-sm table-amarelo">
            <tr>
              <th colspan="2">Depot to pick empty container</th>
            </tr>
            <tr>
              <td colspan="2" v-html="CLRF2BR(railroad.depot_empty || 'Not informed')"></td>
            </tr>
            <tr>
              <td class="spacer" colspan="2"></td>
            </tr>
            <tr>
              <th>Date pick up empty container in the depot</th>
              <th>Date of empty container loading on train</th>
            </tr>
            <tr>
              <td>
                <p v-if="!railroad.withdrawal_date">Not informed</p>
                <p v-if="railroad.withdrawal_date">{{ FormatDate ( railroad.withdrawal_date ) }}</p>
              </td>
              <td>
                <p v-if="!railroad.start_date">Not informed</p>
                <p v-if="railroad.start_date">{{ FormatDate ( railroad.start_date ) }}</p>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="spacer"></td>
            </tr>
            <tr>
              <th>Date of arrival of the container in Rondonópolis</th>
              <th>Container stuffing starting date</th>
            </tr>
            <tr>
              <td>
                <p v-if="!railroad.arrival_date">Not informed</p>
                <p v-if="railroad.arrival_date">{{ FormatDate ( railroad.arrival_date ) }}</p>
              </td>
              <td>
                <p v-if="!railroad.final_date">Not informed</p>
                <p v-if="railroad.final_date">{{ FormatDate ( railroad.final_date ) }}</p>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="spacer"></td>
            </tr>
            <tr>
              <th>Full container train loading date (to Port)</th>
              <th>Estimated date full container arrive in Cubatão SP</th>
            </tr>
            <tr>
              <td>
                <p v-if="!railroad.withdrawal_start_date">Not informed</p>
                <p v-if="railroad.withdrawal_start_date">{{ FormatDate ( railroad.withdrawal_start_date ) }}</p>
              </td>
              <td>
                <p v-if="!railroad.estimated_time">Not informed</p>
                <p v-if="railroad.estimated_time">{{ FormatDate ( railroad.estimated_time ) }}</p>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="spacer"></td>
            </tr>
            <tr>
              <th>Container transfer to port terminal date</th>
              <th>Terminal inbound confirmation date</th>
            </tr>
            <tr>
              <td>
                <p>{{ FormatDate(railroad.transfer_terminal_date) }}</p>
              </td>
              <td>
                {{ FormatDate(railroad.terminal_confirmation_date) }}
              </td>
            </tr>
            <tr>
              <td colspan="2" class="spacer"></td>
            </tr>
          </table>
          
        </div>

      </div>

      <div class="row">
        <div class="col">
          <dr-history :url="'/api/railwayObservation/' + railroad.id"></dr-history>
        </div>

      </div>

    </div>

  </div>
</template>

<style scoped lang="scss">
$border-width: 2px;

p {
  margin-bottom: 0;
}
.spacer {
  background-color: #fff;
  height: 5px;
}

.table-amarelo th {
  border: $border-width solid #fef101;
}
.table-amarelo td {
  background-color: #fff;
  border: $border-width solid #fef101;
}

.table-amarelo td.spacer {
  border: $border-width solid #fff;
}

</style>

<script>
export default {
  props: ['value'],
  name: 'popcorn-railroad',
  data () {
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
    value: function ( new_value, old_value ) {
      this.$data.railroad = new_value
    }
  },
  methods: {
    FormatDate: function ( date ) {
      if(!date) return "Not informed";
      let [year, month, day, hours, minutes] = date.split(/[- :]/g);
      return month + "/" + day + "/" + year
    },
    CountVehicles: function () {
      if (!this.$data.railroad.vehicles) return 0
      return this.$data.railroad.vehicles.length
    },
    CLRF2BR: function ( value ) {
      if (!value) return '';
      return value.replace(/[\r\n]/, '<br>');
    }
  }
}
</script>