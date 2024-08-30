<template>
  <div>
    <div v-if="loading">
      <p class="text-center">Loading shipping data...</p>
    </div>
    <div v-if="!loading">
      <div class="row table-type">
        <div class="col">
          <div class="form-group booking">
            <strong class="title" for="booking">Booking</strong><br>
            <p>{{shipping.booking}}</p>
          </div>
          
        </div>
      </div>
      
      <div class="row">
        <div class="col">
          <table class="table table-sm table-amarelo">
            <tr>
              <th>
                <strong class="title" for="loading_port_id">Port of loading</strong>
              </th>
              <th>
                <strong class="title">Vessel/Voyage</strong>
              </th>
              <th>
                <strong class="title">ETD </strong>
              </th>
              <th>
                <strong class="title">ETA </strong>
              </th>
              <th>
                <strong class="title" for="discharge_port_id">Port of discharge</strong>
              </th>
            </tr>
            <tr>
              <td>
                {{shipping.loading_port}}
              </td>
              <td>
                {{shipping.vessel}}
              </td>
              <td>
                {{FormatDate(shipping.etd)}}
              </td>
              <td>
                {{FormatDate(shipping.eta)}}
              </td>
              <td>
                {{shipping.discharge_port}}
              </td>
            </tr>
          </table>
          <table class="table table-sm table-amarelo">
            <tr>
              <th>
                <strong class="title">Shipping line </strong>
              </th>
              <th>
                <strong class="title">Free time origin</strong>
              </th>
              <th>
                <strong class="title">Free time destination</strong>
              </th>
              <th>
                <strong class="title">Draft Deadline </strong>
              </th>
              <th>
                <strong class="title">Loading Deadline</strong>
              </th>
            </tr>
            <tr>
              <td>
                {{shipping.shipping_line}}
              </td>
              <td>
                {{shipping.free_time_origin}}
              </td>
              <td>
                {{shipping.free_time_destination}}
              </td>
              <td>
                {{FormatDate(shipping.draft_deadline)}}
              </td>
              <td>
                {{FormatDate(shipping.loading_deadline)}}
              </td>
            </tr>
          </table>

        </div>
      </div>

    </div>

    <div class="row" v-if="shipping.approves">
      <div class="col">
        <p>Approvation history:</p>
        <table class="table table-sm table-container">
          <tr>
            <th>
              Old Vessel
            </th>
            <th>
              Old ETD
            </th>
            <th>
              Old ETA
            </th>
            <th>
              Date and Time of approval
            </th>
            <th>
              Approved by
            </th>
            <th>
              Justification for change
            </th>
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

    <div class="row">
      <div class="col text-right">
        <button class="btn btn-danger" data-toggle="modal" href='#modal-reason' v-if="canApprove">Disapprove sending</button>
        <div class="modal fade" id="modal-reason">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">What is the reason for rejection?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <textarea name="" id="" cols="30" rows="10" class="form-control" v-model="shipping.reason"></textarea>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" @click="Disapprove">Save</button>
              </div>
            </div>
          </div>
        </div>

        <button class="btn btn-success" @click="Approve" v-if="canApprove">Approve shipping</button>
      </div>
    </div>

    
    <div class="modal fade" id="modal-lg-primary">
      <div class="modal-dialog modal-primary">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">We had a problem with this order</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">
            {{modalText}}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<style>
.table-type {
  font-size: 13px;
}

.title {
  font-size: 13px;

}

.table-amarelo {
  background-color: #fef101;
}

.table-amarelo th,
.table-amarelo td {
  border: 2px solid #fff;
}

.table-containers tr th {
  border-top: 5px solid #f7f7f7;
}

.table-containers tr td {
  border-top: 3px solid #f7f7f7;
}

.table-containers .table td,
.table-containers .table th {
  border-top: 1px solid #f7f7f7;
  background-color: #fff;
}

.booking strong,
.booking p {
  background-color: #2d3194;
  color: #fff;
  display: inline-block;
  width: 120px;
  padding: 2px 10px;
  margin-bottom: 2px;
}

/* .row {
  margin-top: 5px;
  margin-bottom: 2px;
} */
</style>

<script>
export default {
  name: 'popcorn-shipping',
  data () {
    return {
      current_container: {
        error: {}
      },
      shipping: {},
      error: {},
      ports: null,
      loading: true,
      canApprove: true,
      modalText: null,
    }
  },
  mounted: function () {
    this.$data.loading = true
    
    var loading_count = 2

    this.$http.get('/api/shipping/' + this.$route.params.id).then(e => {
      if(!e.body.containers)
        e.body.containers = []

      this.$data.shipping = e.body

      this.$data.shipping.etd = this.formatDate(this.$data.shipping.etd)
      this.$data.shipping.eta = this.formatDate(this.$data.shipping.eta)

      let lastApprovesIndex = this.$data.shipping.approves.length - 1
      let lastReason = null;
      if (this.$data.shipping.approves[lastApprovesIndex])
        lastReason = this.$data.shipping.approves[lastApprovesIndex].reason;
      if (lastReason && this.$data.shipping.shipping_status_id == 1) {
        this.$data.modalText = lastReason
        $('#modal-lg-primary').modal('show');
      }

      if (!--loading_count)
        this.$data.loading = false
    })

    this.$http.get('/api/ports/').then(e => {
      this.$data.ports = e.body

      if (!--loading_count)
        this.$data.loading = false
    })

  },
  watch: {
    shipping: function (newValue, oldValue) {
        if(newValue) {
          this.$emit('input', this.$data.shipping)
          return
        }
    }
  },
  methods: {
    FormatDate: function (date) {
      let [year, month, day] = date.split(/-/);
      return month + "/" + day + "/" + year
    },
    FormatDateTime: function (date) {
      let [year, month, day, hour, minutes, seconds] = date.split(/[- :]/);
      return month + "/" + day + "/" + year + " " + hour + ":" + minutes
    },
    formatDate: function (val) {
      return val
    },
    Disapprove: function () {
      var reason = this.$data.shipping.reason
      this.$http.post('/api/shipping/reject/' + this.$route.params.id, {reason}).then(e => {
        this.$toaster.success(e.body.success)
      })
    },
    Approve: function () {
      this.$http.post('/api/shipping/accept/' + this.$route.params.id).then(e => {
        this.$toaster.success(e.body.success)
      })
    }
  }
}
</script>