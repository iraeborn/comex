<template>
    <div class="container-fluid">
        <div id="ui-view"></div>
            <div class="panel panel-success">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                New Driver
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="qtd_container">Name</label>
                                    <input-icon type="text" icon="fas fa-user" v-model="driver.name" name="name"  />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="qtd_container">Phone</label>
                                    <input-icon type="text" icon="fas fa-phone" v-model="driver.phone" name="phone"  />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <iconinput label="Born" type="date" icon="fas fa-calendar" v-model="driver.born_at" id="born_at" name="born_at" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="qtd_container">CPF</label>
                                    <input-icon type="text" icon="fas fa-user" v-model="driver.cpf" name="cpf"  />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="qtd_container">RG</label>
                                    <input-icon type="text" icon="fas fa-user" v-model="driver.rg" name="rg"  />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="qtd_container">CNH</label>
                                    <input-icon type="text" icon="fas fa-user" v-model="driver.cnh" name="cnh"  />
                                </div>
                            </div>
                        </div>
                    </div>

                      <div class="card-footer text-right">
                        <router-link :to="{ name: 'Drivers' }" class="btn btn-danger">Cancel</router-link>
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
        driver: {
          name: '',
          phone: '',
          cnh: '',
          rg: '',
          cpf: '',
          born_at: '',
        }
      }
    },
    methods:{
      Store: function () {
        this.$data.error = {}
        this.$http.post('/api/drivers', this.$data.driver).then(function (e) {
          if (!e.body.success) {
           this.$data.error = e.body.errors
           return
          }

          this.$toaster.success(e.body.success)
          this.$router.push('/drivers')
        })
      },
    }
  }
</script>
