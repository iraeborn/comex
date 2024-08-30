<template>
  <div v-if="loading_truck && loading_truck !== null">
    <div class="row">
      <div class="col">
        <iconinput label="Start Truck Loading Date:" type="date" icon="fas fa-calendar"
          v-model="loading_truck.start_truck_loading_date" id="start_truck_loading_date" name="start_truck_loading_date"
          :error="error.start_truck_loading_date" />
      </div>

      <div class="col">
        <iconinput label="End Truck Loading Date:" type="date" icon="fas fa-calendar"
          v-model="loading_truck.end_truck_loading_date" id="end_truck_loading_date" name="end_truck_loading_date"
          :error="error.end_truck_loading_date" />
      </div>
    </div>

    <div class="modal fade" data-backdrop="static" id="modal-loading" style="top: 50%">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" style="background: none !important; border: none">
          <div class="modal-body">
            <div class="progress">
              <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75"
                aria-valuemin="0" aria-valuemax="100" style="width: 75%">
                <span>SAVING THE LOADING, PLEASE WAIT A FEW SECONDS.</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group" id="loading_location">
          <label for="loading_location">Loading Location:</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="fas fa-map-marker-alt"></i>
              </div>
            </div>
            <textarea class="form-control" name="loading_location" id="loading_location" placeholder="Loading Location:"
              v-model="loading_truck.loading_location"></textarea>
          </div>
        </div>
        <!-- <iconinput label="Loading Location:" type="text" icon="fas fa-map-marker-alt" v-model="loading_truck.loading_location" id="loading_location" name="loading_location" :error="error.loading_location" /> -->
      </div>

      <div class="col">
        <div class="form-group" id="unloading_location">
          <label for="unloading_location">Unloading Location:</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="fas fa-map-marker-alt"></i>
              </div>
            </div>
            <textarea type="text" class="form-control" name="unloading_location" id="unloading_location"
              placeholder="Unloading Location:" v-model="loading_truck.unloading_location"></textarea>
          </div>
        </div>
        <!-- <iconinput label="Unloading Location:" type="text" icon="fas fa-map-marker-alt" v-model="loading_truck.unloading_location" id="unloading_location" name="unloading_location" :error="error.unloading_location" /> -->
      </div>
    </div>

    <!-- <div class="row">
      <div class="col">
        <iconinput
          label="Estimated Transit Time (DD):"
          type="text"
          icon="fas fa-clock"
          v-model="loading_truck.transit_time"
          id="transit_time"
          name="transit_time"
          :error="error.transit_time"
        />
      </div>
    </div> -->

    <!-- <div class="row">
      <div class="col">
        <iconinput
          label="Address"
          type="text"
          icon="fas fa-clock"
          v-model="loading_truck.address"
          :error="error.address"
        />
      </div>
    </div> -->

    <div class="row">
      <div class="col">
        <iconinput label="Taxa PTAX" type="number" icon="fas fa-percent" v-model="loading_truck.tax_ptax" id="tax_ptax"
          name="tax_ptax" :error="error.tax_ptax" />
      </div>
      <div class="col">
        <iconinput label="Date PTAX:" type="date" icon="fas fa-calendar" v-model="loading_truck.date_ptax" id="date_ptax"
          name="date_ptax" :error="error.date_ptax" />
      </div>
    </div>

    <div class="row">
      <div class="col">
        Quantity of Vehicles Hired: {{ loading_truck.vehicles.length }}
      </div>
    </div>

    <div class="row">
      <div class="col">
        <p v-if="loading_truck.vehicles.length == 0">No vehicle was added</p>
        <table class="table table-sm table-containers" v-if="loading_truck.vehicles.length > 0">
          <tr>
            <th>License Plate</th>
            <th>Weight</th>
            <th>&nbsp;</th>
          </tr>

          <tbody v-for="(vehicle, index) in loading_truck.vehicles">

            <tr>
              <td>{{ vehicle.plate }}</td>
              <td>{{ vehicle.wheight }} KGS</td>

              <td class="text-right">

                <a v-for="(document, index) in vehicle.documents" v-if="document.entity === 'Shipping'"
                  :href="document.url" target="_blank" class="btn btn-success btn-sm">
                  <i class="fas fa-eye"></i> Shipping
                </a>

                <a v-for="(document, index) in vehicle.documents" v-if="document.entity === 'Order'"
                :href="document.url" target="_blank" class="btn btn-success btn-sm">
                  <i class="fas fa-eye"></i> Order
                </a>

                <a class="btn btn-primary btn-sm" data-toggle="modal" href="#modal-vehicle"
                  @click="SetCurrenVehicle(index)">Edit</a>

                <button class="btn btn-danger btn-sm" @click="RemoveVehicle(index)">
                  Remove
                </button>
              </td>

            </tr>

            <tr v-if="vehicle.bills && vehicle.bills.length > 0" class="borderless">
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
                    <td>{{ bill.number }}</td>
                    <td>{{ bill.key }}</td>
                    <td>{{ bill.weight }}</td>
                    <td>{{ parseInt(bill.bags | 0) }}</td>
                    <td>{{ parseInt(bill.damaged | 0) }}</td>
                    <td>{{ parseInt(bill.lack | 0) }}</td>
                    <td>{{ parseInt(bill.leftovers | 0) }}</td>
                    <td>{{ parseInt(bill.physical_balance | 0) }}</td>
                    <td>{{ parseInt(bill.total | 0) }}</td>
                  </tr>
                  <tr>
                    <th colspan="2" class="text-right">Total</th>
                    <!-- <td>{{ bill.key }}</td> -->
                    <th>{{ SumContainerInfo(vehicle.bills, "weight") }}</th>
                    <th>{{ SumContainerInfo(vehicle.bills, "bags") }}</th>
                    <th>{{ SumContainerInfo(vehicle.bills, "damaged") }}</th>
                    <th>{{ SumContainerInfo(vehicle.bills, "lack") }}</th>
                    <th>{{ SumContainerInfo(vehicle.bills, "leftovers") }}</th>
                    <th>
                      {{ SumContainerInfo(vehicle.bills, "physical_balance") }}
                    </th>
                    <th>{{ SumContainerInfo(vehicle.bills, "total") }}</th>
                  </tr>
                </table>
              </td>
            </tr>
          </tbody>
        </table>

        <button class="btn btn-success btn-xs" @click="AddVehicle">
          Add new vehicle
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">Value Product (Ton): {{ totalPriceProduct }}</div>
      <div class="col-md-4">Weight Total Order: {{ total_net }}</div>
      <div class="col-md-4">Value Loading: {{ valueLoading }}</div>
    </div>

    <div class="modal fade" id="modal-vehicle" data-backdrop="static">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Vehicle</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col">
                <iconinput label="License Plate One:" type="text" icon="fas fa-truck-moving"
                  v-model="current_vehicle.plate" id="plate" name="plate" :error="error.plate" />
              </div>
              <div class="col">
                <iconinput label="License Plate Two:" type="text" icon="fas fa-truck-moving"
                  v-model="current_vehicle.plate_two" id="plate_two" name="plate_two" :error="error.plate_two" />
              </div>
              <div class="col">
                <iconinput label="License Plate Three:" type="text" icon="fas fa-truck-moving"
                  v-model="current_vehicle.plate_three" id="plate_three" name="plate_three" :error="error.plate_three" />
              </div>
            </div>

            <div class="row">
              <div class="col">
                <iconinput label="Weight (KGS):" type="number" icon="fas fa-weight-hanging"
                  v-model="current_vehicle.wheight" id="wheight" name="wheight" :error="error.wheight" />
              </div>

              <!-- <div class="col">
                <iconinput
                  label="Estimated time of Stuffing:"
                  type="text"
                  icon="fas fa-clock"
                  v-model="current_vehicle.estimated_time"
                  id="estimated_time"
                  name="estimated_time"
                  :error="error.estimated_time"
                />
              </div> -->
            </div>

            <div class="row">
              <!-- <div class="col">
            <div class="form-group">
              <label for="name">Carrier</label>
              <div class="form-group reto">
                <div class="caixa-icone"><i class="fas fa-user"></i></div>
                  <vue-select2 icon="fas fa-user" class="vue-select1 form-control" name="select1" :options="carriers" :model.sync="current_vehicle.carrier_id" v-model="current_vehicle.carrier_id" v-bind:class="error.carriers != '' && error.carriers ? 'is-invalid select2-hidden-accessible' : ''">
                    <option value="">Select Carriers</option>
                  </vue-select2>
                <div class="invalid-feedback" v-if="error.carriers" v-for="message in error.carriers">{{ message }}</div>
              </div>
              <div class="invalid-feedback" v-if="error.carriers" v-for="message in error.carriers">{{ message }}</div>
            </div>
          </div> -->

              <div class="col">
                <label for="">Carrier</label>
                <div class="input-group" v-bind:class="error.carrier_id != '' && error.carrier_id
                  ? 'is-invalid'
                  : ''
                  ">

                  <vue-select2 class="vue-select1 form-control" name="select1" :options="carriers"
                    :model.sync="current_vehicle.carrier_id" v-model="current_vehicle.carrier_id" :class="error.carrier_id != '' && error.carrier_id
                      ? 'is-invalid select2-hidden-accessible'
                      : ''
                      " />

                </div>
              </div>
              <div class="col">
                <label for="">Driver</label>
                <div class="input-group" v-bind:class="error.driver_id != '' && error.driver_id ? 'is-invalid' : ''">
                  <vue-select2 class="vue-select1 form-control" name="select1" :options="drivers"
                    :model.sync="current_vehicle.driver_id" v-model="current_vehicle.driver_id" :class="error.driver_id != '' && error.driver_id
                      ? 'is-invalid select2-hidden-accessible'
                      : ''
                      " />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="truck_unloading_datetime">Truck Unloading date & time:</label>

                  <div class="row">
                    <div class="col">
                      <div class="form-group" id="start_truck_loading_date">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">
                              <i class="fas fa-calendar"></i>
                            </div>
                          </div>
                          <input type="date" class="form-control" v-model="current_vehicle.truck_unloading_date"
                            id="truck_unloading_date" name="truck_unloading_date" :class="current_vehicle_error.truck_unloading_date !=
                              '' &&
                              current_vehicle_error.truck_unloading_date
                              ? 'is-invalid'
                              : ''
                              " />
                        </div>
                      </div>
                    </div>
                    <div class="col">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">
                            <i class="fas fa-clock"></i>
                          </div>
                        </div>
                        <input type="time" class="form-control" v-model="current_vehicle.truck_unloading_time"
                          id="truck_unloading_time" name="truck_unloading_time" :class="current_vehicle_error.truck_unloading_time !=
                            '' &&
                            current_vehicle_error.truck_unloading_time
                            ? 'is-invalid'
                            : ''
                            " />
                      </div>
                    </div>
                  </div>

                  <div class="invalid-feedback" v-if="current_vehicle_error.truck_unloading_datetime"
                    v-for="message in current_vehicle_error.truck_unloading_datetime">
                    {{ message }}
                  </div>
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col">
                <iconinput ref="freight" label="Freight Price" type="number" icon="fas fa-money-bill-alt"
                  v-model="current_vehicle.freight" id="freight" name="freight" :error="error.freight" />
              </div>

              <div class="col">
                <iconinput label="Tara Bags" type="number" icon="fas fa-money-bill-alt"
                  v-model="current_vehicle.tara_bags" id="tara_bags" name="tara_bags" :error="error.tara_bags" />
              </div>
            </div>

            <div class="row">
              <div class="col">
                <div id="select-contract-container">
                  <label for="name">Contract:</label>
                  <div class="form-group reto">
                    <vue-select2 icon="fas fa-money-bill-alt" class="vue-select1 form-control" name="select1"
                      :options="carrier_contracts" placeholder="Select contract" v-model="selected_contract"
                      :model.sync="selected_contract" :class="error.freight != '' && error.freight
                        ? 'is-invalid select2-hidden-accessible'
                        : ''
                        ">
                      <option value="">Select contract</option>
                    </vue-select2>
                  </div>
                </div>
              </div>

              <div class="col">
                <label for="loading_location_user_id">Loading Location</label>

                <vue-select2 class="vue-select1 form-control" name="loading_location_user_id" label="Loading Location" :options="terminalAgents"
                  placeholder="Select Loading Location" :model.sync="current_vehicle.loading_location_user_id" v-model="current_vehicle.loading_location_user_id" :class="error.loading_location_user_id != '' &&
                    error.loading_location_user_id
                    ? 'is-invalid select2-hidden-accessible'
                    : ''
                    ">
                  <option value="">Select Loading Location</option>
                </vue-select2>

              </div>

            </div>

            <div class="row">
              <div class="col">
                <iconinput label="Authorization" type="text" icon="fas fa-user" v-model="current_vehicle.authorization"
                  id="authorization" name="authorization" :error="error.authorization" />
              </div>
            </div>

            <div class="row">
              <div class="col">
                <iconinput label="Notes" type="text" icon="fas fa-user" v-model="current_vehicle.note" id="note"
                  name="note" :error="error.note" />
              </div>
            </div>

            <p v-if="!current_vehicle.bills">No bill founded</p>

            <table class="table table-sm mt-2" v-if="current_vehicle.bills">
              <tr>
                <th>Bill number</th>
                <th>Key</th>
                <th>Weight (KGS):</th>
                <th>Bags</th>
                <th>
                  <button class="btn btn-success btn-sm my-1 w-100" @click="AddBill">
                  Add bill
                </button>
                </th>
              </tr>
              <tr v-for="(bill, bill_index) in current_vehicle.bills">
                <td>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-calculator"></i>
                      </div>
                    </div>
                    <input type="text" class="form-control" v-model="bill.number" />
                  </div>
                </td>
                <td>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-key"></i>
                      </div>
                    </div>
                    <input type="text" class="form-control" v-model="bill.key" />
                  </div>
                </td>
                <td>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-weight-hanging"></i>
                      </div>
                    </div>
                    <input type="number" class="form-control" v-model="bill.weight" @input="UpdateBillWeight"
                      step="0.01" />
                  </div>
                </td>
                <td>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fas fa-weight-hanging"></i>
                      </div>
                    </div>
                    <input type="text" class="form-control" v-model="bill.bags" />
                  </div>
                </td>
                <td class="text-right">
                  <button class="btn btn-danger btn-sm" @click="RemoveBill(bill_index)">
                    Remove
                  </button>
                </td>
              </tr>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Cancel
            </button>
            <button type="button" class="btn btn-primary" @click="SaveVehicle">
              {{ current_vehicle.id ? "Save Vehicle" : "Add Vehicle" }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.form-group,
label {
    margin-top: 0.5rem !important;
    margin-bottom: 0 !important;
}

textarea {
  min-height: 150px;
}
.borderless>td,
.borderless>th {
  border-top: 0 none;
}

.caixa-icone-reduced {
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
  background-color: #f0f3f5;
  border: 1px solid #e4e7ea;
  padding: 6px 11px;
  width: auto;
  float: left;
  color: #5c6873;
}

.select2-container {
  width: calc(100% - 42px) !important;
  max-width: calc(100% - 42px) !important;
}
</style>

<script>
export default {
  name: "popcorn-loading",
  props: ["value"],
  data() {
    return {
      loading_truck: {
        vehicles: [],
        remove_vehicles: [],
      },
      users: [],
      terminalAgents: [],
      error: {},
      current_vehicle: {},
      current_vehicle_error: {},
      total_net: 0,
      valueLoading: 0,
      carriers: [],
      drivers: [],
      contracts: [],
      carrier_contracts: [],
      selected_contract: {},
      totalPriceProduct: 0,
    };
  },
  watch: {
    value: function (new_value, old_value) {
      if (!new_value) new_value = {};
      if (!new_value.vehicles) new_value.vehicles = [];
      else if (
        new_value !== null &&
        typeof new_value == "object" &&
        new_value.hasOwnProperty("vehicles")
      ) {
        for (var i in new_value.vehicles) {
          var date = new_value.vehicles[i].truck_unloading_datetime.split(" ");
          new_value.vehicles[i].truck_unloading_date = date[0];
          new_value.vehicles[i].truck_unloading_time = date[1];
        }
      }

      this.$data.loading_truck = new_value;
    },
    selected_contract: function (contractId) {
      if (contractId) {
        let contract = {
          contract_services: [{ billing_value: 0 }],
        };

        for (var i in this.$data.contracts) {
          if (this.$data.contracts[i].id == contractId) {
            contract = this.$data.contracts[i];
            break;
          }
        }

        this.$data.current_vehicle.freight =
          contract.contract_services[0].billing_value;
        this.$data.current_vehicle.provider_contract_id = contract.id;
      }
    },
    "current_vehicle.carrier_id": function (carrierId) {
      let contracts = this.$data.contracts.filter(function (contract) {
        return contract.provider_id == carrierId;
      });

      contracts.unshift({ id: "", text: "", provider_id: "" });

      this.$data.carrier_contracts = contracts;
    },
    loading_truck: function (newValue) {
      if (newValue === null && this.$props.value !== null) {
        this.$data.loading_truck = this.$props.value;
      }

      var totalByVehicle = 0;

      if (this.$data.loading_truck && this.$data.loading_truck.vehicles) {
        var weightByVehicle = this.$data.loading_truck.vehicles.map((e) => {
          return e.bills.reduce((acc, val) => {
            return acc + val.weight;
          }, 0);
        });

        totalByVehicle = weightByVehicle.reduce(function (acc, current) {
          return acc + current;
        }, 0);
      }

      if (
        this.$data.loading_truck &&
        this.$data.loading_truck.order &&
        this.$data.loading_truck.order.items
      ) {
        this.$data.total_net = this.$data.loading_truck.order.items.reduce(
          function (sum, item) {
            return sum + item.net_weight;
          },
          0
        );

        this.$data.valueLoading = this.$data.total_net - totalByVehicle;

        this.$data.totalPriceProduct = this.$data.loading_truck.order.items.reduce(
          function (acc, current) {
            return current.net_weight;
          },
          0
        );
      }

      if (this.$data.loading_truck && this.$data.loading_truck.vehicles) {
        for (var i in this.$data.loading_truck.vehicles) {
          var date = this.$data.loading_truck.vehicles[
            i
          ].truck_unloading_datetime.split(" ");
          this.$data.loading_truck.vehicles[i].truck_unloading_date = date[0];
          this.$data.loading_truck.vehicles[i].truck_unloading_time = date[1];
        }
      }
      this.$forceUpdate();
    },
  },
  mounted: function () {
    this.$data.loading_truck = this.$props.value;
    var totalByVehicle = 0;

    if (this.$data.loading_truck && this.$data.loading_truck.vehicles) {
      var weightByVehicle = this.$data.loading_truck.vehicles.map((e) => {
        return e.bills.reduce((acc, val) => {
          return acc + val.weight;
        }, 0);
      });

      totalByVehicle = weightByVehicle.reduce(function (acc, current) {
        return acc + current;
      }, 0);
    }

    if (
      this.$data.loading_truck &&
      this.$data.loading_truck.order &&
      this.$data.loading_truck.order.items
    ) {
      this.$data.total_net = this.$data.loading_truck.order.items.reduce(
        function (sum, item) {
          return sum + item.net_weight;
        },
        0
      );

      this.$data.valueLoading = this.$data.total_net - totalByVehicle;

      this.$data.totalPriceProduct = this.$data.loading_truck.order.items.reduce(
        function (acc, current) {
          return current.net_weight;
        },
        0
      );
    }

    this.$http.get("/api/users/carriers").then((e) => {
      let arrayCarriers = []
      
      e.body.forEach((item) => {
        arrayCarriers.push({
          'text': item.name,
          'value': item.id,
          'id': item.id,
          'name': item.name
        })
      })

      this.$data.carriers = arrayCarriers;
      this.$data.current_vehicle.carrier_id = null;
    });

    // get api users and save in $data.users
    this.$http.get("/api/users").then((e) => {
      let arrayUsers = []

      e.body.forEach((item) => {
        arrayUsers.push({
          'text': item.name,
          'value': item.id,
          'id': item.id,
          'name': item.name
        })
      })

      let terminalAgents = []
      const usersTerminalAgents = e.body.filter($user => $user.role == 'Terminal Agent');

      usersTerminalAgents.forEach((item) => {
      terminalAgents.push({
          'text': item.name,
          'value': item.name,
          'id': item.id,
          'name': item.name
        })
      })

      this.$data.terminalAgents = terminalAgents;
      this.$data.users = arrayUsers;
      this.$data.selected_loading_location = null;
    });
    // this.$http.get("/api/users").then(function (e) {
    //   this.$data.users = e.body;
    // });

    this.$http.get("/api/drivers").then((e) => {
      let arrayDrivers = []

      e.body.forEach((item) => {
        arrayDrivers.push({
          'text': item.name,
          'value': item.id,
          'id': item.id,
          'name': item.name
        })
      })

      this.$data.drivers = arrayDrivers;
      this.$data.current_vehicle.driver_id = null;
    });

    this.$http.get("/api/providers/contracts").then((e) => {
      function currency(text) {
        if (text == "BRL") {
          return "R$";
        } else if (text == "USD") {
          return "US$";
        }
      }

      let contracts = [];

      e.body.forEach(contract => {

        contract.text = contract.identifier;

        if (contract.contract_services && contract.contract_services.length) {
          contract.text += " (" +
            currency(contract.contract_services[0].currency_type) +
            contract.contract_services[0].billing_value +
            " " +
            contract.contract_services[0].factor_display_name +
            ")";
        }

        contracts.push(contract);
      });

      contracts.unshift({ id: "", text: "", provider_id: "" });

      this.$data.contracts = contracts;
      this.$data.selected_contract = null
    });

    if (this.$data.loading_truck && this.$data.loading_truck.vehicles) {
      for (var i in this.$data.loading_truck.vehicles) {
        var date = this.$data.loading_truck.vehicles[
          i
        ].truck_unloading_datetime.split(" ");
        this.$data.loading_truck.vehicles[i].truck_unloading_date = date[0];
        this.$data.loading_truck.vehicles[i].truck_unloading_time = date[1];
      }
    }

    var that = this;

    $(this.$refs.freight.$el)
      .find("input")
      .on("focus", function () {
        that.ShowSelectContract();
      });
  },
  methods: {
    GenerateLoadingDocs: async function (truckId, index){
      console.log(this.loading_truck.order.id, this.loading_truck, truckId, index);

      this.$http
        .post("/api/generateLoadingDocs/" + this.loading_truck.order.id, {...this.loading_truck, selected_truck_id: truckId, loading_number: index})
        .then((e) => {
          if (e.body.errors) {
            this.error = e.body.errors;
            this.$toaster.error("There are problems in Truck Loading");
            this.$toaster.error(e.body.errors);
          } else {
            this.$router.go(0);
          }
        });
    },

    SumContainerInfo(bills, info) {
      if (!bills) return 0;
      return bills
        .map((bill) => {
          return parseInt(bill[info] | 0);
        })
        .reduce((acc, val) => {
          return acc + val;
        });
    },
    Save: function (selected_bills) {
      
      $("#modal-loading").modal("show");
      for (var i_bills in selected_bills) {
        for (var i_vehicle in this.$data.loading_truck.vehicles) {
          for (var i_bills_vehicle in this.$data.loading_truck.vehicles[
            i_vehicle
          ].bills) {
            if (
              selected_bills[i_bills].indexOf(
                this.$data.loading_truck.vehicles[i_vehicle].bills[
                  i_bills_vehicle
                ].id
              ) > -1
            ) {
              this.$data.loading_truck.vehicles[i_vehicle].bills[
                i_bills_vehicle
              ].container_id = i_bills;
            }
          }
        }
      }
      this.$http
        .post("/api/loading/" + this.$route.params.id, this.$data.loading_truck)
        .then((e) => {

          if (e.body.errors) {
            $("#modal-loading").modal("hide");
            this.$data.error = e.body.errors;
            this.$toaster.error("There are problems in Truck Loading");
            this.$toaster.error(e.body.errors);
          } else {
            this.$router.go(0);
          }
        });
    },
    UpdateBillWeight: function () {
      this.current_vehicle.wheight = this.$data.current_vehicle.bills
        .map((bill) => {
          let value = parseFloat(
            bill.weight.replace(/,/g, ".").replace(/[^\d.]/g, "")
          );
          if (!value) return 0;
          return value;
        })
        .reduce((a, b) => {
          return a + b;
        });
    },
    AddBill: function () {
      if (this.$data.current_vehicle.bills == null)
        this.$data.current_vehicle.bills = [];

      this.$data.current_vehicle.bills.push({});

      this.$forceUpdate();
    },
    RemoveBill: function (index) {
      this.$data.current_vehicle.bills.splice(index, 1);
      this.UpdateBillWeight();

      this.$forceUpdate();
    },
    AddVehicle: function () {
      this.$data.current_vehicle = {};
      this.$data.selected_contract = null;
      this.$data.current_vehicle.index = null;
      this.$forceUpdate();

      this.ShowSelectContract();
      $("#modal-vehicle").modal("show");
    },

    SaveVehicle: function () {
      this.$http.post("/api/vehicle", this.$data.current_vehicle).then((e) => {
        if (e.body.errors) {
          this.$data.current_vehicle_error = e.body.errors;
          this.$data.error = e.body.errors;
          return;
        }

        if (this.$data.current_vehicle.index !== null) {
          this.$data.loading_truck.vehicles[
            this.$data.current_vehicle.index
          ] = JSON.parse(JSON.stringify(this.$data.current_vehicle));
        }

        if (this.$data.current_vehicle.index === null) {
          this.$data.loading_truck.vehicles.push(
            JSON.parse(JSON.stringify(this.$data.current_vehicle))
          );
        }

        $(".modal").modal("hide");
        this.$forceUpdate();
      });
    },
    RemoveVehicle: function (index) {
      if (!this.$data.loading_truck.remove_vehicles) {
        this.$data.loading_truck.remove_vehicles = [];
      }
      this.$data.loading_truck.remove_vehicles.push(
        this.$data.loading_truck.vehicles[index].id
      );

      this.$data.loading_truck.vehicles.splice(index, 1);
    },
    SetCurrenVehicle: function (index) {
      this.$data.current_vehicle = JSON.parse(
        JSON.stringify(this.loading_truck.vehicles[index])
      );

      this.$data.current_vehicle.index = index;
      this.$data.selected_contract = null;
      if (this.$data.current_vehicle.freight) {
        this.ShowFreight();
      } else {
        this.ShowSelectContract();
      }
    },
    ShowSelectContract: function () {
      $("#select-contract-container").show();
      $("#freight").hide();
    },
    ShowFreight: function () {
      $("#freight").show();
      $("#select-contract-container").hide();
    },
  },
};
</script>
