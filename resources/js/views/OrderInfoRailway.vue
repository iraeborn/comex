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
  <!-- <li class="nav-item">
    <a href="#stuffing" class="nav-link active show" data-toggle="tab">Container Stuffing</a>
  </li> -->
  <li class="nav-item">
    <a href="#shipping" class="nav-link active show" data-toggle="tab">Shipping</a>
  </li>
  <li class="nav-item">
    <a href="#railroad" class="nav-link" data-toggle="tab">Railway</a>
  </li>
  <li class="nav-item">
    <a href="#loading" class="nav-link" data-toggle="tab">Truck Loading</a>
  </li>
  <li class="nav-item">
    <a href="#mapa" class="nav-link" data-toggle="tab">MAPA</a>
  </li>
  <li class="nav-item">
    <a href="#fumigation" class="nav-link" data-toggle="tab">Fumigation</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <!-- <div class="tab-pane fade" id="stuffing">
    <popcorn-container-stuffing-v2 ref="container_stuffing" v-model="container_stuffing"></popcorn-container-stuffing-v2>
  </div> -->

  <div class="tab-pane fade" id="railroad">
    <popcorn-railroad-view ref="railroad" v-model="railroad"></popcorn-railroad-view>
  </div>

  <div class="tab-pane fade active show" id="shipping">
    <popcorn-shipping-view-v2 ref="shipping"></popcorn-shipping-view-v2>
  </div>

  <div class="tab-pane fade" id="mapa">
    <popcorn-mapa-view-v2 ref="mapa" v-model="mapa"></popcorn-mapa-view-v2>
  </div>

  <div class="tab-pane fade" id="loading">
    <popcorn-loading-view ref="loading" v-model="loading"></popcorn-loading-view>
  </div>


  <div class="tab-pane fade" id="fumigation">
    <popcorn-fumigation-view ref="fumigation" v-model="fumigation"></popcorn-fumigation-view>
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
/*        this.$http.put('/api/forwarding-profile/' + this.$route.params.id, this.$data.order.document[0]).then(e => {
         this.$toaster.success('Saved!');
        })
*/
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
    mounted () {
      this.$http.get('/api/mapa/' + this.$route.params.id).then(e => {
        this.$data.mapa = e.body

        this.$forceUpdate()
      })

      this.$http.get('/api/railroad/' + this.$route.params.id).then(e => {
        if(!e.body.vehicles)
          e.body.vehicles = []

          this.$data.railroad = e.body

          this.$forceUpdate()
      })
      
    this.$http.get('/api/loading/' + this.$route.params.id).then(e => {
      if(!e.body.vehicles)
        e.body.vehicles = []

        this.$data.loading = e.body

        this.$forceUpdate()
    })


    }
  }
</script>