<template>
    <div class="container-fluid">
        <div id="ui-view"></div>
            <div class="panel panel-success">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                Service Edit
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="qtd_container">Name</label>
                                    <input-icon type="text" icon="fas fa-user" v-model="group.name" name="name"  />
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                  <div class="form-group">
                                        <label for="">Active</label><br>
                                        <div class="form-check form-check-inline mr-1">
                                            <input class="form-check-input" id="authentication1" v-model="group.active" type="radio" value="0" name="authentication">
                                            <label class="form-check-label" for="authentication1">No</label>
                                        </div>
                                        <div class="form-check form-check-inline mr-1">
                                            <input class="form-check-input" id="authentication2" v-model="group.active" type="radio" value="1" name="authentication">
                                            <label class="form-check-label" for="authentication2">Yes</label>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                      <div class="card-footer text-right">
                        <router-link :to="{ name: 'Services' }" class="btn btn-danger">Cancel</router-link>
                        <input type="button" value="Save" @click="Store" class="btn btn-success">
  </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.is-invalid ~ .invalid-feedback {
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
      sendingEmail: null,
      group: {
        name: null,
        active:null,
      },
      error: {},
    };
  },
  mounted: function () {
    this.$http.get("/api/services/" + this.$route.params.id).then(function (e) {
      this.$data.group = e.body;
    });
  },
  methods: {
    RegisterUrl: function (val) {
      this.$data.signature.url = val;
    },


    Store: function () {
      if (!this.$data.group.name) {
        this.$toaster.error("Service name field is required");
        return;
      }

      this.$data.error = {};
      this.$http
        .post("/api/services/" + this.$route.params.id, this.$data.group)
        .then(function (e) {
          if (!e.body.success) {
            this.$data.error = e.body.errors;
            return;
          }

          this.$toaster.success(e.body.success);
        });
    },
  },
};
</script>
