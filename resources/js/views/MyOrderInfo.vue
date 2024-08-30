<template>
    <div class="container-fluid nav-left">

            <div id="ui-view">

                <div class="panel panel-success">
                    
<div class="card">
  <div class="card-header">
    <div class="row">
        <div class="col">
            Order Information
        </div>
    </div>

  </div>
  <div class="card-body">

    <div class="page-container">

<ul class="nav nav-tabs">
  <li class="nav-item active">
    <a href="#shipping" class="nav-link active" data-toggle="tab">Shipping</a>
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
  <!-- <li class="nav-item">
    <a href="#fumigation" class="nav-link" data-toggle="tab">Fumigation</a>
  </li> -->
  <li class="nav-item">
    <a href="#draft_documents" class="nav-link" data-toggle="tab">Draft documents</a>
  </li>
  <li class="nav-item">
    <a href="#original" class="nav-link" data-toggle="tab">Original documents</a>
  </li>
  <li class="nav-item">
    <a href="#tracking" class="nav-link" data-toggle="tab">Tracking number</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
<div class="tab-pane active" id="shipping">
  <popcorn-shipping-view ref="shipping"></popcorn-shipping-view>
</div>
<div class="tab-pane fade" id="draft_documents">
  <popcorn-draft-documents-view ref="draft"></popcorn-draft-documents-view>
</div>
<div class="tab-pane fade" id="original">
  <popcorn-original-documents-view ref="original"></popcorn-original-documents-view>
</div>
<!-- <div class="tab-pane fade" id="railroad">
  <popcorn-railroad-view ref="railroad" v-model="railroad"></popcorn-railroad-view>
</div> -->
<!-- <div class="tab-pane fade" id="loading">
  <popcorn-loading-view ref="loading" v-model="loading_truck"></popcorn-loading-view>
</div> -->
<div class="tab-pane fade" id="stuffing">
  <popcorn-container-stuffing-view ref="loading" v-model="container_stuffing"></popcorn-container-stuffing-view>
</div>
<!-- <div class="tab-pane fade" id="inspection">
  <popcorn-inspection-agency-view ref="inspection" v-model="inspection"></popcorn-inspection-agency-view>
</div> -->
<!-- <div class="tab-pane fade" id="fumigation">
  <popcorn-fumigation-view ref="fumigation" v-model="fumigation"></popcorn-fumigation-view>
</div> -->
<div class="tab-pane fade" id="tracking">
  <popcorn-traking-client ref="traking" :container_data="container_stuffing.containers" v-if="container_stuffing"></popcorn-traking-client>
</div>
</div>



    </div>
  </div>


  <div class="card-footer text-right">
    &nbsp;
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
        fumigation: null,
      }
    },
    methods: {
      Save: function () {
        this.$refs.shipping.Save()
      },
    },
    mounted: function () {
      this.$http.get('/api/railroad/' + this.$route.params.id).then( e => {
        this.$data.railroad = e.body
      })

      // this.$http.get('/api/loading/' + this.$route.params.id).then(e => {
      //   if(!e.body.vehicles)
      //     e.body.vehicles = []

      //     this.$data.loading_truck = e.body

      //     this.$forceUpdate()
      // })

      this.$http.get('/api/shipping/' + this.$route.params.id).then(se => {
        if(!se.body.containers)
          se.body.containers = []


        this.$http.get('/api/stuffing/' + this.$route.params.id).then(e => {
          if(!e.body.vehicles)
            e.body.vehicles = []

          e.body.containers = se.body.containers
          this.$data.container_stuffing = e.body
          
          this.$forceUpdate()
        })

      })

      // this.$http.get('/api/inspection/' + this.$route.params.id).then(e => {
      //     this.$data.inspection = e.body

      //     this.$forceUpdate()
      // })

      this.$http.get('/api/mapa/' + this.$route.params.id).then(e => {
          this.$data.mapa = e.body

          this.$forceUpdate()
      })

      // this.$http.get('/api/fumigation/' + this.$route.params.id).then(e => {
      //     this.$data.fumigation = e.body

      //     this.$forceUpdate()
      // })

    }
  }
</script>