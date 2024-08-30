<template>
    <div class="container-fluid">

            <div id="ui-view">

              <div class="row">
                <div class="col-9">

                  <div class="panel panel-success">
                      
                    <div class="card">
                      <div class="card-header">
                        <div class="row">
                            <div class="col">
                                View orders
                            </div>
                        </div>

                      </div>
                      <div class="card-body">

                        <div class="card-body text-center" v-if="loading">
                          Loading data...
                        </div>

                        <p v-if="!order && !loading" class="text-center">
                          This orders don`t exists in database
                        </p>
                      
                        <div v-if="order && !loading">
                          <table class="table table-sm table-containers">
                            <tr>
                              <th>Description</th>
                              <th>Total Price</th>
                              <th>&nbsp;</th>
                            </tr>
                            <tr v-for="(item, index) in order.items">
                              <td>{{ item.description }}</td>
                              <td>US$ {{ item.total_price }}</td>
                              <td class="text-right">
                                <a class="btn btn-success btn-sm" data-toggle="modal" :href="'#modal-' + index">View</a>
                      
                                <div class="modal fade text-left" :id="'modal-' + index">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h4 class="modal-title">{{ item.description }}</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                      </div>
                                      <div class="modal-body">
                                        <strong>Item description</strong>
                                        <p>{{ item.description }}</p>

                                        <table class="table table-sm table-branca">
                                          <tr>
                                            <td>
                                              <strong>Crop Year</strong>
                                            </td>
                                            <td>
                                              <strong>Gross Weight</strong>
                                            </td>
                                            <td>
                                              <strong>Net Weight</strong>
                                            </td>
                                          </tr>

                                          <tr>
                                            <td>{{ item.crop_year }}</td>
                                            <td>{{ item.gross_weight }} KGS</td>
                                            <td>{{item.net_weight}} KGS</td>
                                          </tr>
                                        </table>
                                          
                                        <table class="table table-sm table-branca">
                                          <tr>
                                            <td>
                                              <strong>Quantity:</strong>
                                            </td>
                                            <td>
                                              <strong>Total Bags:</strong>
                                            </td>
                                            <td>
                                              <strong>Unit Price:</strong>
                                            </td>
                                            <td>
                                              <strong>Total Price:</strong>
                                            </td>
                                          </tr>

                                          <tr>
                                            <td>{{item.quantity}}</td>
                                            <td>{{item.total_bags}}</td>
                                            <td>US$ {{item.unit_price}}</td>
                                            <td>US$ {{item.total_price}}</td>
                                          </tr>
                                        </table>


                                        <strong>Value in words:</strong>
                                        <p>{{item.value}}</p>

                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>

                              </td>
                            </tr>
                          </table>

                          <div class="row">
                            <div class="col">
                              <p><strong>Dear customer, please add all the mandatory documents.</strong></p>
                            </div>
                          </div>


                  
                          <div v-if="!order.proforma || !order.contract" class="alert alert-danger">
                            Wait until {{ this.$root.appName }} sends the contract and proforma
                          </div>

                          <div v-if="order.proforma && order.contract">

                            <div class="row">
                              <div class="col-6">
                                <strong>Contract Signed</strong>
                                <div v-if="order.order_status_id == 3" class="alert alert-success">Document approved by {{ this.$root.appName }}</div>
                                <popcorn-upload v-if="order.order_status_id != 3" name="document_contract_signed" :allowed_pattern="/^application\/pdf$/" @upload="RegisterContractSigned" :file="order.document_contract_signed" class="is-invalid"></popcorn-upload>
                                <p class="invalid-feedback" v-if="error.document_contract_signed" v-for="message in error.document_contract_signed">{{ message }}</p>
                              </div>
                              <div class="col-6">
                                <strong>Proforma Signed</strong>
                                <div v-if="order.order_status_id == 3" class="alert alert-success">Document approved by {{ this.$root.appName }}</div>
                                <popcorn-upload v-if="order.order_status_id != 3" name="document_proforma_signed" :allowed_pattern="/^application\/pdf$/" @upload="RegisterProformaSigned" :file="order.document_proforma_signed" class="is-invalid"></popcorn-upload>
                                <p class="invalid-feedback" v-if="error.document_proforma_signed" v-for="message in error.document_proforma_signed">{{ message }}</p>
                              </div>
                              <div class="col-6">
                                <strong>Swift Advance</strong>
                                <popcorn-upload name="document_swift_advance" :allowed_pattern="/^application\/pdf$/" @upload="RegisterSwiftAdvance" :file="order.document_swift_advance" class="is-invalid"></popcorn-upload>
                                <p class="invalid-feedback" v-if="error.document_swift_advance" v-for="message in error.document_swift_advance">{{ message }}</p>
                              </div>

                              <div class="col-6">
                                <strong>Label Model</strong>
                                <popcorn-upload name="label_model" :allowed_pattern="/^application\/pdf$/" @upload="RegisterLabelmodel" :file="order.document_label_model" class="is-invalid"></popcorn-upload>
                                <p class="invalid-feedback" v-if="error.document_label_model" v-for="message in error.document_label_model">{{ message }}</p>
                              </div>

                              <div class="col-6">
                                <strong>Instruction Document</strong>
                                <popcorn-upload name="document_instructions_documents" :allowed_pattern="/^application\/pdf$/" @upload="RegisterInstructionDocument" :file="order.document_instructions_documents" class="is-invalid"></popcorn-upload>
                                <p class="invalid-feedback" v-if="error.document_instructions_documents" v-for="message in error.document_instructions_documents">{{ message }}</p>
                              </div>
                            </div>
                          
                          </div>

                        </div>
                      </div>


                      <div class="card-footer text-right">
                        <router-link :to="{ name: 'My orders' }" class="btn btn-danger">Cancel</router-link>
                        <input type="button" value="Save" @click="Store" class="btn btn-success">
                      </div>

                    </div>



                  </div>


                </div>

                <div class="col-3">

                  <div class="card">
                    <div class="card-header">
                      <div class="row">
                          <div class="col">
                              Documents from {{ this.$root.appName }}
                          </div>
                      </div>

                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <div class="card-body text-center" v-if="loading">
                            Loading data...
                          </div>
                          <div v-if="order && !loading">
                            <p v-if="order.proforma">
                              <a :href="order.proforma" download class="btn btn-primary btn-block">Download Proforma</a>
                            </p>
                            <div v-if="!order.proforma">
                              <div class="alert alert-danger">
                                Proforma was not sent by {{ this.$root.appName }}
                              </div>
                            </div>
                            <p v-if="order.contract">
                              <a :href="order.contract" download class="btn btn-primary btn-block">Download Contract</a>
                            </p>
                            <div v-if="!order.contract">
                              <div class="alert alert-danger">
                                Contract was not sent by {{ this.$root.appName }}
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

<!-- <popcorn-upload></popcorn-upload> -->
    </div>

</template>

<style>
.table-branca th,
.table-branca td {
  background-color: #fff;
}
</style>

<script>
  export default {
    props: ['current_user'],
    data() {
      return {
        order: null,
        user: null,
        loading: false,
        error: {},
      }
    },
    mounted: function () {
      this.$data.loading = true
      this.$http.get('/api/order/' + this.$route.params.id).then(function (e) {
        this.$data.order = e.body
        if(!this.$data.order.document_contract_signed)        this.$data.order.document_contract_signed = null
        if(!this.$data.order.document_proforma_signed)        this.$data.order.document_proforma_signed = null
        if(!this.$data.order.document_swift_advance)          this.$data.order.document_swift_advance   = null
        if(!this.$data.order.document_instructions_documents) this.$data.order.document_instructions_documents   = null
        if(!this.$data.order.document_label_model)            this.$data.order.document_label_model     = null
        this.$data.loading = false
      })
    },
    methods: {
      FormatDate: function (date) {
          var d = new Date(date)
          var month = (d.getUTCMonth() + 1).toString()
          var day = d.getUTCDate().toString()
          var year = d.getUTCFullYear().toString()
          var hours = d.getUTCHours().toString()
          var minutes = d.getUTCMinutes().toString()
          var seconds = d.getUTCSeconds().toString()

          month   = month.length < 2   ? "0" + month   : month
          day     = day.length < 2     ? "0" + day     : day
          hours   = hours.length < 2   ? "0" + hours   : hours
          minutes = minutes.length < 2 ? "0" + minutes : minutes
          seconds = seconds.length < 2 ? "0" + seconds : seconds

          return month + "/" + day + "/" + year + " " + hours + ":" + minutes
      },
      RegisterContractSigned: function (document) {
        if(document) {
          this.$data.order.document_contract_signed = document.url
          return
        }

        this.$data.order.document_contract_signed = null
      },
      RegisterProformaSigned: function (document) {
        if(document) {
          this.$data.order.document_proforma_signed = document.url
          return
        }

        this.$data.order.document_proforma_signed = null
      },
      RegisterSwiftAdvance: function (document) {
        if(document) {
          this.$data.order.document_swift_advance = document.url
          return
        }

        this.$data.order.document_swift_advance = null
      },
      RegisterInstructionDocument: function (document) {
        if(document) {
          this.$data.order.document_instructions_documents = document.url
          return
        }

        this.$data.order.document_instructions_documents = null
      },
      RegisterLabelmodel: function (document) {
        if(document) {
          this.$data.order.document_label_model = document.url
          return
        }

        this.$data.order.document_label_model = null
      },
      Store: function () {
        this.$data.error = {}
        this.$http.put('/api/my-orders', this.$data.order).then(function (e) {
          if (!e.body.success) {
            this.$data.error = e.body.errors
            return;
          }

          this.$toaster.success(e.body.success)
          this.$router.push('/order')
        })
      },
    }
  }
</script>