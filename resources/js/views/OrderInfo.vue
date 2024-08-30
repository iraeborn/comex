<template>
  <div class="container-fluid nav-left">

    <div id="ui-view">

      <div class="panel panel-success">

        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">
                Order Information {{ insurance.order.number }}
                <register-initial-balance class="float-right"></register-initial-balance>
              </div>
            </div>

          </div>
          <div class="card-body">

            <div v-if="shipping">
              <div class="alert alert-success" v-if="shipping.shipping_status_id == 2">Shipping information has accepted
                by customer</div>
              <div class="alert alert-danger" v-if="shipping.shipping_status_id == 3">Shipping information has rejected by
                customer:<br>{{ shipping.reason }}</div>
            </div>

            <div class="alert alert-danger" v-if="acceptedDraftFiles()">Drafts documents has accepted by customer!</div>
            <div class="alert alert-danger" v-if="rejectedDraftFiles()">Some drafts documents were rejected!</div>

            <div class="page-container">


              <ul class="nav nav-tabs">
                <li class="nav-item active">
                  <a href="#shipping" class="nav-link active" data-toggle="tab" @click="ForceUpdate()">Shipping</a>
                </li>
                <li class="nav-item" v-if="insurance.order && insurance.order.railway_agent_id">
                  <a href="#railroad" class="nav-link" data-toggle="tab">Railway</a>
                </li>
                <li class="nav-item">
                  <a href="#loading" class="nav-link" data-toggle="tab">Truck<br>Loading</a>
                </li>
                <li class="nav-item">
                  <a href="#stoving" class="nav-link" data-toggle="tab" @click="ForceUpdateStuffing()">Container<br>Stuffing</a>
                </li>
                <li class="nav-item">
                  <a href="#inspection" class="nav-link" data-toggle="tab">Inspection<br>Agency</a>
                </li>
                <li class="nav-item">
                  <a href="#insurance" class="nav-link" data-toggle="tab">Insurance<br>Agency</a>
                </li>
                <li class="nav-item">
                  <a href="#map" class="nav-link" data-toggle="tab">M.A.P.A./RFB</a>
                </li>
                <li class="nav-item">
                  <a href="#fumigation" class="nav-link" data-toggle="tab">Fumigation</a>
                </li>
                <li class="nav-item">
                  <a href="#draft_documents" class="nav-link" data-toggle="tab">Draft<br>documents</a>
                </li>
                <li class="nav-item">
                  <a href="#original" class="nav-link" data-toggle="tab">Original<br>documents</a>
                </li>
                <li class="nav-item">
                  <a href="#tracking" class="nav-link" data-toggle="tab">Tracking</a>
                </li>

                <li class="nav-item">
                  <a href="#exportation_docs" class="nav-link" data-toggle="tab">Exportation<br>Docs</a>
                </li>

              </ul>

              <!-- Tab panes -->
              <div class="tab-content">
                <div class="tab-pane active" id="shipping">
                  <popcorn-shipping ref="shipping" :shipping_info="shipping" @container="AddContainerFromShipping"
                    :sending_emails="sending_emails"></popcorn-shipping>
                </div>
                <div class="tab-pane fade" id="draft_documents">
                  <popcorn-draft-documents ref="draft_documents" @phyto="SetMapaPhytoCertificate"
                    :sending_emails="sending_emails" v-model="draft_documents"></popcorn-draft-documents>
                </div>
                <div class="tab-pane fade" id="operation">Operation</div>
                <div class="tab-pane fade" id="original">
                  <popcorn-original-documents ref="original" @phyto="SetMapaPhytoCertificateSigned"
                    :sending_emails="sending_emails"></popcorn-original-documents>
                </div>
                <div class="tab-pane fade" id="railroad" v-if="insurance.order && insurance.order.railway_agent_id">
                  <popcorn-railroad ref="railroad" v-model="railroad"></popcorn-railroad>
                </div>
                <div class="tab-pane fade" id="loading">
                  <popcorn-loading ref="loading" v-model="loading_truck"></popcorn-loading>
                </div>
                <div class="tab-pane fade" id="stoving">
                  <popcorn-container-stuffing ref="container_stuffing" v-model="stuffing" :shipping="shipping"
                    @container="AddContainerFromStuffing" @select="SelectBills"></popcorn-container-stuffing>
                </div>
                <div class="tab-pane fade" id="inspection">
                  <popcorn-inspection-agency ref="inspection_agency" v-model="inspection"></popcorn-inspection-agency>
                </div>
                <div class="tab-pane fade" id="insurance">
                  <popcorn-insurance-agency ref="insurance_agency" v-model="insurance"
                    @change="DraftComercialInvoice"></popcorn-insurance-agency>
                </div>
                <div class="tab-pane fade" id="map">
                  <popcorn-mapa ref="mapa" v-model="mapa" :n_phyto="draft_documents.certificate_phyto"
                    @phyto="SetDraftCertificatePhyto" @phyto_original="SetOriginalCertificatePhyto"></popcorn-mapa>
                </div>
                <div class="tab-pane fade" id="fumigation">
                  <popcorn-fumigation ref="fumigation" v-model="fumigation"></popcorn-fumigation>
                </div>
                <div class="tab-pane fade" id="tracking">
                  <popcorn-traking ref="traking" :container_data="stuffing.containers" v-if="stuffing"
                    v-model="order"></popcorn-traking>
                  <!-- <a href="https://www.hapag-lloyd.com/en/online-business/tracing/tracing-by-container.html?container=BMOU++2728050">BMOU2728050</a> -->
                  <!-- <sea-rates v-model="tracking_api_data"></sea-rates> -->
                </div>

                <div class="tab-pane fade" id="exportation_docs">
                  <popcorn-exportation-docs ref="exportation_docs" :order="insurance.order"></popcorn-exportation-docs>
                </div>

              </div>



            </div>
          </div>


          <div class="card-footer">


            <div class="row">
              <div class="col-8 text-left pt-2">
                <b>Send Email: </b>
                <label class="mr-2">
                  <input type="checkbox" v-model="sending_emails" name="sending_emails" value="draft_documents"> Draft
                  Documents
                </label>

                <label class="mr-2">
                  <input type="checkbox" v-model="sending_emails" name="sending_emails" value="shipment_forecast_details">
                  Shipment Forecast Details
                </label>

                <label class="mr-2">
                  <input type="checkbox" v-model="sending_emails" name="sending_emails" value="original_documents">
                  Original Documents
                </label>

                <!-- <label class="mr-2">
            <input type="checkbox" v-model="sending_emails" name="sending_emails" value="inspection_agency"> Inspection Agency
          </label> -->

                <!-- <label class="mr-2">
            <input type="checkbox" v-model="sending_emails" name="sending_emails" value="insurance_agency"> Insurance Agency
          </label> -->

                <label class="mr-2">
                  <input type="checkbox" v-model="sending_emails" name="sending_emails" value="fumigation"> Fumigation
                </label>
              </div>
              <div class="col-4 text-right">
                <router-link :to="{ name: 'Orders' }" class="btn btn-danger">Cancel</router-link>
                <input type="button" value="Save" @click="Save()" class="btn btn-success">
              </div>
            </div>


          </div>
        </div>



      </div>

    </div>
  </div>
</template>



<script>

function fSendEmail() {
  $.post("demo_test_post.asp",
    {
      name: "Donald Duck",
      city: "Duckburg"
    },
    function (data, status) {
      alert("Data: " + data + "\nStatus: " + status);
    });
}

export default {
  data() {
    return {
      loading: false,
      loading_truck: null,
      order: null,
      ports: null,
      errors: {},
      shipping: null,
      railroad: null,
      stuffing: null,
      containers: null,
      inspection: null,
      mapa: null,
      fumigation: null,
      code: null,
      draft_documents: null,
      insurance: {
        id: null,
        name: null,
        order: null,
        draft_documents: null,
      },
      sending_emails: [],
      tracking_api_data: {},
    }
  },
  watch: {
    sending_emails() { }
  },
  methods: {
    Save: function () {
      this.$refs.shipping.Save()
      this.$refs.draft_documents.Save()

      if (this.$refs.railroad) {
        this.$refs.railroad.Save()
      }

      this.$refs.original.Save()
      this.$refs.loading.Save(this.$data.selected_bills)
      this.$refs.container_stuffing.Save()
      this.$refs.inspection_agency.Save()
      this.$refs.mapa.Save()
      this.$refs.fumigation.Save()
      this.$refs.insurance_agency.Save()
      this.$refs.traking.Save()
    },
    AddContainerFromShipping: function (val) {
      this.$data.stuffing.containers = val
    },
    AddContainerFromStuffing: function (val) {
      this.$data.shipping.containers = val
    },
    ForceUpdate: function () {
      this.$forceUpdate()
    },
    ForceUpdateStuffing: function () {
      this.$refs.container_stuffing.ForceUpdate()
    },
    SelectBills: function (selected, container_id) {
      if (typeof this.$data.selected_bills == 'undefined') {
        this.$data.selected_bills = [];
      }

      this.$data.selected_bills[container_id] = selected;
    },
    formatDate: function (val) {
      return val
    },
    rejectedDraftFiles: function () {
      return false;
    },
    acceptedDraftFiles: function () {
      return false;
    },
    SetDraftCertificatePhyto: function (value) {
      this.$refs.draft_documents.RegisterCertificatePhyto(value);
    },
    SetOriginalCertificatePhyto: function (value) {
      this.$refs.original.RegisterCertificatePhyto(value, true);
    },
    SetMapaPhytoCertificate: function (value) {
      this.$refs.mapa.RegisterPhytoCertificate(value);
    },
    SetMapaPhytoCertificateSigned: function (value) {
      this.$refs.mapa.RegisterPhytoCertificateSigned(value);
    },
    // DraftComercialInvoice: function (val) {
    //   // console.log(this.$refs.draft_documents)
    // },
    RegisterStartBalance: function () {
      $('#modal-account').modal('show');
    },

    GetMapa: async function () {
      await this.$http.get('/api/mapa/' + this.$route.params.id)
      .then(data => {
        this.$data.mapa = JSON.parse(data.bodyText); //JSON.parse(data.bodyText);
      });
    },

    GetDraft: async function (){
      await this.$http.get('/api/draft/' + this.$route.params.id)
      .then(e => {

        if (!e.body.others){
          e.body.others = [];
        }

        this.$data.draft_documents = e.body
        this.$data.insurance.draft_documents = e.body
      })
    },

    GetShipping: async function (){
      await this.$http.get('/api/shipping/' + this.$route.params.id)
      .then(e => {
        if (!e.body.containers){
          e.body.containers = []
        }

        this.$data.shipping = e.body

        this.$data.shipping.etd = this.formatDate(this.$data.shipping.etd)
        this.$data.shipping.eta = this.formatDate(this.$data.shipping.eta)

        this.GetStuffing();
      })
    },

    GetStuffing: async function (){
      await this.$http.get('/api/stuffing/' + this.$route.params.id)
      .then(e => {
        if (!e.body.vehicles){
          e.body.vehicles = []
        }

        this.$data.stuffing = JSON.parse(JSON.stringify(e.body));
        this.$data.stuffing.containers = this.$data.shipping.containers

        this.$forceUpdate()
      })
    },

    GetLoading: async function (){
      await this.$http.get('/api/loading/' + this.$route.params.id)
      .then(e => {
        if (!e.body.vehicles)
          e.body.vehicles = []

        this.$data.loading_truck = e.body

        this.$forceUpdate()
      })
    },

    GetInspection: async function (){
      await this.$http.get('/api/inspection/' + this.$route.params.id)
      .then(e => {
        this.$data.inspection = e.body;
        this.$forceUpdate();
      })
    },

    GetFumigation: async function (){
      await this.$http.get('/api/fumigation/' + this.$route.params.id)
      .then(e => {
        this.$data.fumigation = e.body
        this.$forceUpdate()
      })
    },

    GetOrder: async function () {
      await this.$http.get('/api/order/' + this.$route.params.id)
      .then(e => {
        this.$data.insurance.order = e.body

        if (e.body.railway_agent_id) {
          this.$http.get('/api/railroad/' + this.$route.params.id).then(e => {
            if (!e.body.vehicles)
              e.body.vehicles = []

            this.$data.railroad = e.body

            this.$forceUpdate()
          })
        }
      })
    },

    GetInsurance: function (){
      this.$http.get('/api/insurance/' + this.$route.params.id)
      .then(e => {
        this.$data.insurance.id = this.$route.params.id

        this.$data.insurance.name = e.body
        this.$forceUpdate()
      })
    }

  },
  mounted: async function () {
    /*let data = await this.$http.get('/api/admin-data/' + this.$route.params.id);
    this.$data.draft_documents = data.body.draft_documents;
    this.$data.shipping        = data.body.shipping;
    this.$data.stuffing        = data.body.stuffing;
    this.$data.loading_truck   = data.body.loading_truck;
    this.$data.inspection      = data.body.inspection;
    this.$data.mapa            = '';
    this.$data.fumigation      = '';
    this.$data.vehicles        = '';
    this.$data.insurance       = '';*/

    this.GetMapa();
    this.GetDraft();
    this.GetShipping();
    this.GetLoading();
    this.GetInspection();
    this.GetFumigation();
    this.GetOrder();
    this.GetInsurance();
    
    /*let api_key = 'DEMO';
    let code = 'MSCU1111333';
    // let code = 'BMOU2728050';

    this.$http.get('/api/tracking/' + code).then(e => {
      this.$data.tracking_api_data = e.body.response
    });*/


  }
}
</script>
