<template>
    <div class="container-fluid nav-left">

            <div id="ui-view">

                <div class="panel panel-success">
                    
<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col" v-if="order">
            Order Information {{ order.number }}
        </div>
    </div>

  </div>
  <div class="card-body">

    <div class="page-container">

<ul class="nav nav-tabs">
  <li class="nav-item">
    <a href="#shipping" class="nav-link active show" data-toggle="tab">Shipping</a>
  </li>
  <li class="nav-item">
    <a href="#inspection" class="nav-link" data-toggle="tab">Inspection Agency</a>
  </li>
  <!-- <li class="nav-item">
    <a href="#railroad" class="nav-link" data-toggle="tab">Railway</a>
  </li> -->
  <!-- <li class="nav-item">
    <a href="#loading" class="nav-link" data-toggle="tab">Truck Loading</a>
  </li> -->
  <li class="nav-item">
    <a href="#stuffing" class="nav-link" data-toggle="tab">Container Stuffing</a>
  </li>
  <!-- <li class="nav-item">
    <a href="#inspection" class="nav-link" data-toggle="tab">Inspection Agency</a>
  </li> -->
  <li class="nav-item">
    <a href="#draft_documents" class="nav-link" data-toggle="tab">Draft documents</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane fade active show" id="shipping">
    <popcorn-shipping-view-v2></popcorn-shipping-view-v2>
  </div>
  <div class="tab-pane fade" id="inspection">
    <popcorn-inspection-agency-v2 ref="inspection_agency" v-model="inspection"></popcorn-inspection-agency-v2>
  </div>
  <div class="tab-pane fade" id="draft_documents">
    <div class="row">
      <div class="col-6" v-if="draft.draft_bl_access">
        <div class="form-group">
          <p>DRAFT BL</p>
          <a :href="draft.draft_bl" download class="btn btn-primary" v-if="draft.draft_bl">Downlaod</a>
          <p class="alert alert-danger" v-if="!draft.draft_bl">File not sent</p>
        </div>
      </div>

      <div class="col-6" v-if="draft.draft_comercial_access">
        <div class="form-group">
          <p>DRAFT COMERCIAL INVOICE</p>
          <a :href="draft.draft_comercial" download class="btn btn-primary" v-if="draft.draft_comercial">Downlaod</a>
          <p class="alert alert-danger" v-if="!draft.draft_comercial">File not sent</p>
        </div>
      </div>
    </div>
    <div class="row">

      <div class="col-6" v-if="draft.packing_list_access">
        <div class="form-group">
          <p>PACKING LIST</p>
          <a :href="draft.packing_list" download class="btn btn-primary" v-if="draft.packing_list">Downlaod</a>
          <p class="alert alert-danger" v-if="!draft.packing_list">File not sent</p>
        </div>
      </div>
      
      <div class="col-6" v-if="draft.certificate_origin_access">
        <div class="form-group">
          <p>CERTIFICATE OF ORIGIN</p>
          <a :href="draft.certificate_origin" download class="btn btn-primary" v-if="draft.certificate_origin">Downlaod</a>
          <p class="alert alert-danger" v-if="!draft.certificate_origin">File not sent</p>
        </div>
      </div>

      <div class="col-6" v-if="0">
        <div class="form-group">
          <label for="certificate_fumigation">FUMIGATION CERTIFICATE</label>
          <div class="badge badge-primary" v-if="draft.certificate_fumigation_status == 1 && !!draft.certificate_fumigation">Waiting for approval</div>
          <div class="badge badge-success" v-if="draft.certificate_fumigation_status == 2">Accepted</div>
          <div class="badge badge-danger" v-if="draft.certificate_fumigation_status == 3">Rejected</div>
          <popcorn-upload name="certificate_fumigation" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_fumigation" :file="draft.certificate_fumigation" @upload="RegisterCertificateFumigation" class="error.certificate_fumigation != '' && error.certificate_fumigation ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_fumigation" v-for="message in error.certificate_fumigation">{{message}}</p>
          <div class="alert alert-danger" v-if="draft.certificate_fumigation_status == 3"><strong>Reason:</strong> {{draft.certificate_fumigation_reason}}</div>
        </div>
      </div>

      <div class="col-6" v-if="draft.certificate_quality_access">
        <div class="form-group">
          <p>QUALITY CERTIFICATE</p>
          <a :href="draft.certificate_quality" download class="btn btn-primary" v-if="draft.certificate_quality">Downlaod</a>
          <p class="alert alert-danger" v-if="!draft.certificate_quality">File not sent</p>
        </div>
      </div>

      <div class="col-6" v-if="draft.certificate_weight_access">
        <div class="form-group">
          <p>WEIGHT CERTIFICATE</p>
          <a :href="draft.certificate_weight" download class="btn btn-primary" v-if="draft.certificate_weight">Downlaod</a>
          <p class="alert alert-danger" v-if="!draft.certificate_weight">File not sent</p>
        </div>
      </div>

      <div class="col-6" v-if="draft.certificate_seguro_access">
        <div class="form-group">
          <p>CERTIFICATE OF INSURANCE</p>
          <a :href="draft.certificate_seguro" download class="btn btn-primary" v-if="draft.certificate_seguro">Downlaod</a>
          <p class="alert alert-danger" v-if="!draft.certificate_seguro">File not sent</p>
        </div>
      </div>

      <div class="col-6" v-if="draft.certificate_phyto_access">
        <div class="form-group">
          <p>PHYTO CERTIFICATE</p>
          <a :href="draft.certificate_phyto" download class="btn btn-primary" v-if="draft.certificate_phyto">Downlaod</a>
          <p class="alert alert-danger" v-if="!draft.certificate_phyto">File not sent</p>
        </div>
      </div>

      <div class="col-6" v-if="draft.report_access">
        <div class="form-group">
          <p>STUFFING REPORT</p>
          <a :href="draft.report" download class="btn btn-primary" v-if="draft.report">Downlaod</a>
          <p class="alert alert-danger" v-if="!draft.report">File not sent</p>
        </div>

      </div>

      <div class="col-6" v-if="draft.health_certificate_access">
        <div class="form-group">
          <p>HEALTH_CERTIFICATE</p>
          <a :href="draft.health_certificate" download class="btn btn-primary" v-if="draft.health_certificate">Downlaod</a>
          <p class="alert alert-danger" v-if="!draft.health_certificate">File not sent</p>
        </div>

      </div>
    </div>

  </div>
  <div class="tab-pane fade" id="railroad">
    <popcorn-railroad-view ref="railroad" v-model="railroad"></popcorn-railroad-view>
  </div>
  <!-- <div class="tab-pane fade" id="loading">
    <popcorn-loading-view ref="loading" v-model="loading_truck"></popcorn-loading-view>
  </div> -->
  <div class="tab-pane fade" id="stuffing">
    <popcorn-container-stuffing-view ref="loading" v-model="container_stuffing"></popcorn-container-stuffing-view>
  </div>
  <!-- <div class="tab-pane fade" id="inspection">
    <popcorn-inspection-agency-view ref="inspection" v-model="inspection"></popcorn-inspection-agency-view>
  </div> -->

</div>



    </div>
  </div>


  <div class="card-footer text-right">
    <button class="btn btn-primary" @click="Save">Save</button>
  </div>
</div>



                </div>

            </div>
    </div>

</template>

<!-- <style scoped>
.nav-left .page-container {
  display: flex;
}

.nav-left .tab-content {
  width: 100%;
  margin-left: -1px;
  margin-bottom: -1px;
  margin-top: 0;
}

.nav-left .nav-tabs .nav-link.active {
  border-bottom: 5px solid #2d3194 !important;
  border-right: 0 none;
  border-top-left-radius: .25em;
  border-bottom-left-radius: .25em;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}

.nav-left .nav-tabs .nav-link:hover {
  border-bottom: 1px solid #e4e7ea;
  border-top-left-radius: .25em;
  border-bottom-left-radius: .25em;
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;

}

.nav-left .nav.nav-tabs {
  float: left;
  display: block;
  margin-right: -5px;
  padding-right: 0;
  z-index: 1;
  border-bottom: 0;
  border-right: 1px solid transparent;
}

.booking strong, .booking p {
    width: 160px;
}

.booking {
    margin-bottom: 7px;
}

.table-amarelo p,
.table-amarelo label {
  margin-bottom: 0;
}
</style> -->

<script>
  export default {
    data() {
      return {
        loading: false,
        loading_truck: null,
        order: null,
        ports: null,
        errors: {},
        railroad: null,
        container_stuffing: null,
        inspection: null,
        mapa: null,
        fumigation: {},
        draft: {},
        error: {},
      }
    },
    methods: {
      Save: function () {
        this.$http.put('/api/inspection-profile/' + this.$route.params.id, this.$data.inspection).then(e => {
          this.$toaster.success('Saved!');
        })

      },
      FormatDateTime: function (value) {
        if(!value) return '';
        let [year, month, day, hour, minutes, seconds] = value.split(/[- :]/);

        return month + "/" + day + "/" + year + " " + hour + ":" + minutes
      },
      FormatDate: function (value) {
        if(!value) return '';
        let [year, month, day, hour, minutes, seconds] = value.split(/[- :]/);

        return month + "/" + day + "/" + year
      },
      RegisterCertificateFumigation: function () {}
    },
    mounted: function () {

      this.$http.get('/api/railroad/' + this.$route.params.id).then( e => {
        this.$data.railroad = e.body
      })

      this.$http.get('/api/stuffing/' + this.$route.params.id).then(e => {
        if(!e.body.vehicles)
          e.body.vehicles = []

        //e.body.containers = se.body.containers
        this.$data.container_stuffing = e.body
        
        this.$forceUpdate()
      })

      /*this.$http.get('/api/shipping/' + this.$route.params.id).then(e => {
        if(!e.body.containers)
          e.body.containers = []

        this.$data.shipping = e.body

        this.$data.shipping.etd = this.formatDate(this.$data.shipping.etd)
        this.$data.shipping.eta = this.formatDate(this.$data.shipping.eta)

        this.$http.get('/api/stuffing/' + this.$route.params.id).then(e => {
          if(!e.body.vehicles)
            e.body.vehicles = []

          this.$data.container_stuffing = e.body
          this.$data.container_stuffing.containers = this.$data.shipping.containers

          this.$forceUpdate()
        })

      })*/


      this.$http.get('/api/order-mapa/' + this.$route.params.id).then( e => {
          this.$data.order = e.body
      })


      this.$http.get('/api/draft/' + this.$route.params.id).then(e => {
        
        this.$data.draft = e.body

        this.$forceUpdate()
      })

      this.$http.get('/api/inspection/' + this.$route.params.id).then(e => {
          this.$data.inspection = e.body

          this.$forceUpdate()
      })

    }
  }
</script>