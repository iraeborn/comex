<template>
    <div>
      <table class="table table-sm table-amarelo">
        <tr>
          <td colspan="3"><strong>Start Truck Loading Date</strong></td>
          <td colspan="3"><strong>End Truck Loading Date</strong></td>
          <td colspan="3"><strong>Date PTAX</strong></td>
        </tr>

        <tr>
          <td colspan="3">{{FormatDate(loading_truck.start_truck_loading_date)}}</td>
          <td colspan="3">{{FormatDate(loading_truck.end_truck_loading_date)}}</td>
          <td colspan="3">{{FormatDate(loading_truck.date_ptax)}}</td>
        </tr>

        <tr>
          <td colspan="6" class="spacer"></td>
        </tr>

        <tr>
          <td colspan="3"><strong>Loading Location</strong></td>
          <td colspan="3"><strong>Unloading Location</strong></td>
        </tr>
        <tr>
          <td colspan="3">{{loading_truck.loading_location || "Not informed"}}</td>
          <td colspan="3">{{loading_truck.unloading_location || "Not informed"}}</td>
        </tr>

        <tr>
          <td colspan="6" class="spacer"></td>
        </tr>
  
        <tr>
          <td colspan="3">Estimated Transit Time (DD)</td>
          <td colspan="3"><strong>Quantity of Vehicles Hired</strong></td>
        </tr>
  
        <tr>
          <td colspan="3">
            {{loading_truck.transit_time || "Not informed"}}
          </td>
          <td colspan="3">
            {{ loading_truck.vehicles.length || "Not informed" }}
          </td>
        </tr>
      </table>

      <div class="row" >
        <div class="col">
          <p v-if="loading_truck.vehicles.length == 0">No vehicle was added</p>
          <table class="table table-sm table-containers" v-if="loading_truck.vehicles.length > 0">
            <tr>
              <th>Lisence Plate</th>
              <th>Driver</th>
              <th>Telephone</th>
              <th>&nbsp;</th>
            </tr>
            <tbody v-for="(vehicle, index) in loading_truck.vehicles">
              <tr>
                <td>{{ vehicle.plate }}</td>
                <td>{{ vehicle.driver }}</td>
                <td>{{ vehicle.phone }}</td>
                <td class="text-right"><a class="btn btn-primary btn-sm" data-toggle="modal" href='#modal-vehicle' @click="SetCurrenVehicle(index)">View</a></td>
              </tr>
              <tr class="borderless">
                <td colspan="5">
                  <table class="table table-sm">
                    <tr>
                      <th>Bill number:</th>
                      <th>Key:</th>
                      <th>Weight:</th>
                      <th>Bags:</th>
                      <th>Damaged:</th>
                      <th>Lack:</th>
                      <th>Leftovers:</th>
                      <th>Physical_balance:</th>
                      <th>Stuffed amount:</th>
                    </tr>
                    <tr v-for="bill in vehicle.bills">
                      <td>{{bill.number}}</td>
                      <td style="word-break: break-all;">{{bill.key}}</td>
                      <td>{{bill.weight}}</td>
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
                      <th>{{SumContainerInfo(vehicle.bills, 'weight') }}</th>
                      <th>{{SumContainerInfo(vehicle.bills, 'bags')}}</th>
                      <th>{{SumContainerInfo(vehicle.bills, 'damaged')}}</th>
                      <th>{{SumContainerInfo(vehicle.bills, 'lack')}}</th>
                      <th>{{SumContainerInfo(vehicle.bills, 'leftovers')}}</th>
                      <th>{{SumContainerInfo(vehicle.bills, 'physical_balance')}}</th>
                      <th>{{SumContainerInfo(vehicle.bills, 'total')}}</th>
                    </tr>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>

<div class="modal fade" id="modal-vehicle">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Vehicle</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col">
            <div class="form-group">
              <strong>Lisence Plate:</strong><br>
              <p>{{current_vehicle.plate}}</p>
            </div>
            
          </div>

          <div class="col">
            <div class="form-group">
              <strong>Driver:</strong><br>
              <p>{{current_vehicle.driver}}</p>
            </div>
            
          </div>

          <div class="col">
            <div class="form-group">
              <strong>Telephone:</strong><br>
              <p>{{current_vehicle.phone}}</p>
            </div>
            
          </div>
        </div>

        <div class="row">
          <div class="col">
            <div class="form-group">
              <strong>Wheight (KGS):</strong><br>
              <p>{{current_vehicle.wheight}}</p>
            </div>
            
          </div>
          <div class="col">
            <div class="form-group">
              <strong>Booking key:</strong><br>
              <p>{{current_vehicle.booking_key}}</p>
            </div>
          </div>

        </div>
          
        <div class="row">
          <div class="col">
            <div class="form-group">
              <strong>Estimated time of Stuffing:</strong><br>
              <p>{{current_vehicle.estimated_time}}</p>
            </div>
            
          </div>
        </div>

        <div class="row">

          <div class="col">
            <div class="form-group">
              <strong>Truck Unloading date & time:</strong><br>
              
              <div class="row">
                <div class="col-6">
                  <p>{{FormatDateTime(current_vehicle.truck_unloading_datetime)}}</p>
                </div>
              </div>

            </div>
            
          </div>

        </div>

        <div class="row">
          <div class="col"><strong>Bills</strong></div>
        </div>

        <table class="table-sm table">
          <tr>
            <td>Bill Number</td>
            <td>Key</td>
          </tr>
          <tr v-for="bill in current_vehicle.bills">
            <td>{{ bill.number }}</td>
            <td>{{ bill.key }}</td>
          </tr>
        </table>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

    </div>
</template>

<script>
export default {
    name: 'popcorn-loading',
    props: ['value'],
    data () {
        return {
            loading_truck: {
              vehicles: []
            },
            error: {},
            current_vehicle: {}
        }
    },
    watch: {
        value: function ( new_value, old_value ) {
          if (!new_value) new_value = {}
          if (!new_value.vehicles) new_value.vehicles = []
          this.$data.loading_truck = new_value

        }
    },
    methods: {
      SumContainerInfo(bills, info) {
        if(!bills) return 0;
        return bills.map(bill => {
          return parseInt(bill[info] | 0);
        }).reduce((acc, val) => {
          return acc + val;
        });
      },
      FormatDate: function (date) {
        if (!date) return 'Not informed'
        var year = date.substr(0,4)
        var month = date.substr(5,2)
        var day = date.substr(8,2)

        return month + "/" + day + "/" + year
      },
      FormatDateTime: function (date) {
        if (!date) return 'Not informed'
        var year = date.substr(0,4)
        var month = date.substr(5,2)
        var day = date.substr(8,2)

        var time = date.substr(11,8)

        return month + "/" + day + "/" + year + " " + time
      },
      // Save: function () {
      //     this.$http.post('/api/loading/' + this.$route.params.id, this.$data.loading_truck).then(e => {
      //         console.log(e.body)
      //     })
      // },
      AddVehicle: function () {
        this.$data.current_vehicle = {}
        this.$data.current_vehicle.index = null
        $('#modal-vehicle').modal('show')
      },
      SaveVehicle: function () {
        this.$http.post('/api/vehicle/', this.$data.current_vehicle).then( e => {
          if (e.body.error) return;
          if(this.$data.current_vehicle.index !== null) {
            this.$data.loading_truck.vehicles[this.$data.current_vehicle.index] = JSON.parse(JSON.stringify(this.$data.current_vehicle))
          }

          if(this.$data.current_vehicle.index === null) {
            this.$data.loading_truck.vehicles.push(JSON.parse(JSON.stringify(this.$data.current_vehicle)))
          }

          $('.modal').modal('hide')
          this.$forceUpdate()
        })
      },
      SetCurrenVehicle: function (index) {
        this.$data.current_vehicle = JSON.parse(JSON.stringify(this.loading_truck.vehicles[index]))
        this.$data.current_vehicle.index = index
      }
    }
}
</script>