<template>
  <div>
    <div v-if="loading">
      <p class="text-center">Loadding shipping data...</p>
    </div>
    <div v-if="!loading && shipping">
      <div class="row">
        <div class="col">
          <iconinput
            type="text"
            icon="fas fa-clipboard"
            v-model="shipping.booking"
            id="booking"
            name="booking"
            :error="error.booking"
            label="Booking:"
          />
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="loading_port_id">Port of loading:</label>
            <select-icon
              icon="fas fa-map-marker-alt"
              v-model="shipping.loading_port_id"
              id="loading_port_id"
              name="loading_port_id"
              :class="
                error.loading_port_id != '' && error.loading_port_id
                  ? 'is-invalid'
                  : ''
              "
            >
              <option v-for="port in ports" :value="port.id">
                {{ port.name }}
              </option>
            </select-icon>

            <div
              class="invalid-feedback"
              v-if="error.loading_port_id"
              v-for="message in error.loading_port_id"
            >
              {{ message }}
            </div>
          </div>
        </div>

        <div class="col">
          <label for="">
            Port of discharge:
            <button
              type="button"
              class="btn btn-success btn-sm"
              data-toggle="modal"
              :href="'#modal-port-add'"
            >
              Add
            </button>
          </label>

          <popcorn-port-add modal="modal-port-add" v-if="!$data.loading" :getPorts="getPorts" />
          
          <div
            class="input-group"
            v-bind:class="
              error.discharge_port_id != '' && error.discharge_port_id
                ? 'is-invalid'
                : ''
            "
          >
            <!-- <div class="input-group-prepend">
              <div class="input-group-text">
                <i class="fas fa-map-marker-alt"></i>
              </div>
            </div> -->

            <select-icon
              icon="fas fa-map-marker-alt"
              v-model="shipping.discharge_port_id"
              id="discharge_port_id"
              name="discharge_port_id"
              :class="
                error.discharge_port_id != '' && error.discharge_port_id
                  ? 'is-invalid'
                  : ''
              "
            >
              <option v-for="port in ports" :value="port.id">
                {{ port.name }}
              </option>
            </select-icon>

            <!-- <vue-select2
              class="vue-select1 form-control"
              name="select1"
              :options="ports"
              :model.sync="shipping.discharge_port_id"
              v-model="shipping.discharge_port_id"
              :class="
                error.discharge_port_id != '' && error.discharge_port_id
                  ? 'is-invalid select2-hidden-accessible'
                  : ''
              "
            /> -->
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <iconinput
            type="text"
            v-model="shipping.vessel"
            id="vessel"
            name="vessel"
            icon="fas fa-clipboard"
            :error="error.vessel"
            label="Vessel/Voyage:"
          />
        </div>

        <div class="col">
          <help-tips button-id="help_etd" tooltip-content="ETD (Estimated Time of Departure) Estimated date and time for the ship's departure from the port terminal."></help-tips>
          <iconinput
            label="ETD:"
            type="date"
            v-model="shipping.etd"
            id="etd"
            name="etd"
            icon="fas fa-calendar"
            :error="error.etd"
          />
        </div>

        <div class="col">
          <help-tips button-id="help_eta" tooltip-content="ETA (Estimated Time of Arrival) Estimated date and time for the ship’s arrival at the port terminal"></help-tips>
          <iconinput
            label="ETA:"
            type="date"
            v-model="shipping.eta"
            id="eta"
            name="eta"
            icon="fas fa-calendar"
            :error="error.eta"
          />
        </div>
      </div>
      <div class="row">
        <div class="col">
          <iconinput
            label="Shipping line:"
            type="text"
            v-model="shipping.shipping_line"
            id="shipping_line"
            name="shipping_line"
            icon="fas fa-dolly"
            :error="error.shipping_line"
          />
        </div>

        <div class="col">
          <iconinput
            label="Free time origin:"
            type="text"
            v-model="shipping.free_time_origin"
            id="free_time_origin"
            name="free_time_origin"
            icon="fas fa-clock"
            :error="error.free_time_origin"
          />
        </div>

        <div class="col">
          <iconinput
            label="Free time destination:"
            type="text"
            v-model="shipping.free_time_destination"
            id="free_time_destination"
            name="free_time_destination"
            icon="fas fa-clock"
            :error="error.free_time_destination"
          />
        </div>
      </div>

      <div class="row">
        <div class="col">
          <iconinput
            label="Draft deadline:"
            type="date"
            v-model="shipping.draft_deadline"
            id="draft_deadline"
            name="draft_deadline"
            icon="fas fa-calendar"
            :error="error.draft_deadline"
          />
        </div>

        <div class="col">
          <iconinput
            label="Loading deadline:"
            type="date"
            v-model="shipping.loading_deadline"
            id="loading_deadline"
            name="loading_deadline"
            icon="fas fa-calendar"
            :error="error.loading_deadline"
          />
        </div>
      </div>

      <div class="row">
        <div class="col">
          <iconinput
            label="Freight:"
            type="text"
            v-model="shipping.freight"
            id="freight"
            name="freight"
            icon="fas fa-calendar"
            :error="error.freight"
          />
        </div>
      </div>

      <div class="row" v-if="shipping.approves">
        <div class="col">
          <p>Approvation history:</p>
          <table class="table table-sm table-container">
            <tr>
              <th>Old Vessel</th>
              <th>Old ETD</th>
              <th>Old ETA</th>
              <th>Date and Time of approval</th>
              <th>Approved by</th>
              <th>Justification for change</th>
            </tr>
            <tr v-for="(approve, index) in shipping.approves">
              <td>
                {{ approve.vessel }}
              </td>
              <td>
                {{ FormatDate(approve.etd) }}
              </td>
              <td>
                {{ FormatDate(approve.eta) }}
              </td>
              <td>
                {{ FormatDateTime(approve.created_at) }}
              </td>
              <td>
                {{ approve.name }}
              </td>
              <td>
                {{ approve.reason }}
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-reason">
      <div class="modal-dialog modal-danger">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Date changed</h4>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-hidden="true"
            >
              &times;
            </button>
          </div>
          <div class="modal-body">
            <textarea
              name=""
              id=""
              cols="30"
              rows="10"
              class="form-control"
              v-model="shipping.reason"
            ></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">
              Close
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="ValidateAndSave()"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PopcornPortAdd from "./PopcornPortAdd";

export default {
  props: ["shipping_info","sending_emails"],
  components: { PopcornPortAdd },
  name: "popcorn-shipping",
  data() {
    return {
      current_container: {
        error: {},
      },
      shipping: {},
      error: {},
      ports: null,
      loading: true,
    };
  },
  mounted: function () {
    this.$data.loading = true;

    var loading_count = 1;

    this.$data.shipping = this.$props.shipping_info;
    this.getPorts();
  },
  watch: {
    shipping: function (newValue, oldValue) {
      if (newValue) {
        this.$emit("input", this.$data.shipping);
        return;
      }
    },
    shipping_info: function (newValue, oldValue) {
      if (newValue) {
        this.$data.shipping = JSON.parse(
          JSON.stringify(this.$props.shipping_info)
        );
        return;
      }
    },
  },
  methods: {
    tooltip(msg) {
      this.tooltipMessage = msg;
      this.$refs.tooltip.$emit('open')
    },

    getPorts(port_id) {
      this.$http.get("/api/ports/").then((e) => {
        let arrayPorts = [];

        e.body.forEach((item) => {
          arrayPorts.push({
            text: item.name,
            value: item.id,
            id: item.id,
            name: item.name,
          });

          if (port_id && port_id == item.id) {
            this.shipping.discharge_port_id = item.id;
          }
        });

        this.$data.ports = arrayPorts;
        this.$data.loading = false;
      });
    },

    formatDate: function (val) {
      return val;
    },
    AddContainer: function () {
      this.$data.current_container.error = {};
      this.$http
        .post("/api/container", this.$data.current_container)
        .then(function (e) {
          if (e.body.success) {
            if (typeof this.$data.current_container.edit == "undefined") {
              this.$data.shipping.containers.push(
                JSON.parse(JSON.stringify(this.$data.current_container))
              );
            }

            if (typeof this.$data.current_container.edit != "undefined") {
              this.$data.shipping.containers[
                this.$data.current_container.edit
              ] = JSON.parse(JSON.stringify(this.$data.current_container));
            }

            this.$emit("container", this.$data.shipping.containers);
            this.$data.current_container = { error: {} };

            $("#modal-new-container").modal("hide");
          }
          if (e.body.errors) {
            this.$data.current_container.error = e.body.errors;
          }
        });
    },
    Edit: function (i) {
      this.$data.current_container = JSON.parse(
        JSON.stringify(this.$data.shipping.containers[i])
      );
      this.$data.current_container.edit = i;
      this.$data.current_container.error = {};

      $("#modal-new-container").modal("show");
    },
    Remove: function (e) {
      this.$data.shipping.containers.splice(e, 1);
    },
    ClearContainer: function () {
      this.$data.current_container = { error: {} };
    },
    Save: function () {
      this.$data.error = {};
      this.$data.shipping.quantity_containers = this.$data.shipping.containers.length;
      this.$data.shipping.sending_emails = this.$props.sending_emails;

      let etaChanged = this.$props.shipping_info.eta != this.$data.shipping.eta;
      let etdChanged = this.$props.shipping_info.etd != this.$data.shipping.etd;
      let vesselChanged =
        this.$props.shipping_info.vessel != this.$data.shipping.vessel;

      if (
        (etaChanged || etdChanged || vesselChanged) &&
        this.$data.shipping.shipping_status_id == 2
      ) {
        $('.nav-tabs a[href="#shipping"]').tab("show");
        $("#modal-reason").modal("show");
        return;
      }

      this.SaveAll();
    },
    ValidateAndSave: function () {
      if (this.$data.shipping.reason) {
        $("#modal-reason").modal("hide");
        this.SaveAll();
      }
    },
    SaveAll: function () {
      this.$http
        .put("/api/shipping/" + this.$route.params.id, this.$data.shipping)
        .then((e) => {
          if (e.body.errors) {
            this.$data.error = e.body.errors;
            this.$toaster.error("There are problems in shipping");
          }
        });
    },
    FormatDate: function (value) {
      let [year, month, day] = value.split(/-/);

      return month + "/" + day + "/" + year;
    },
    FormatDateTime: function (value) {
      let [year, month, day, hour, minutes, seconds] = value.split(/[- :]/);

      return month + "/" + day + "/" + year + " " + hour + ":" + minutes;
    },
  },
};
</script>