<template>
    <div class="container-fluid">
        <div id="ui-view"></div>
            <div class="panel panel-success">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                Edit Specification
                            </div>
                        </div>
                    </div>

                    <div class="card-body text-center" v-if="loading">
                        Loading data...
                    </div>

                    <div class="card-body" v-if="!loading">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="qtd_container">Description</label>
                                    <input-icon type="text" icon="fas fa-box" v-model="order.description" name="description"  />
                                </div>
                            </div>
                        </div>
                    </div>

                      <div class="card-footer text-right">
                        <router-link :to="{ name: 'Specifications' }" class="btn btn-danger">Cancel</router-link>
                        <input type="button" value="Save" @click="Store" class="btn btn-success">
  </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.is-invalid~.invalid-feedback {
  display: block;
}

.action-column {
  width: 121px;
}

.caixa-icone {
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
  background-color: #f0f3f5;
  border: 1px solid #e4e7ea;
  padding: 6px 12px;
  width: auto;
  float: left;
  color: #5c6873;
}

.caixa-icone ~ .select2-container {
  width: calc(100% - 39.25px) !important;
}

.table-amarelo {
  background-color: #fef101;
}

.table-amarelo th,
.table-amarelo td {
  border: 2px solid #fff;
}


</style>

<script>
  export default {
    data() {
      return {
        loading: true,
        order: []
      }
    },
    methods:{
    Store: function () {
            this.$data.error = {}
            this.$http.put('/api/specifications/' + this.$route.params.id, this.$data.order).then(function (e) {
            if (!e.body.success) {
            this.$data.error = e.body.errors
            return
            }

            this.$toaster.success(e.body.success)
            this.$router.push('/specifications')
            })
        },
    },
    mounted: function () {
      this.$data.loading = false

      this.$http.get('/api/specifications/' + this.$route.params.id).then(e => {
        this.$data.order = e.body
      })
    }
  }
</script>
