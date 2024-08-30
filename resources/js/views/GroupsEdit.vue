<template>
    <div class="container-fluid">
        <div id="ui-view"></div>
            <div class="panel panel-success">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                Group Edit
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
                        </div>

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                          <li class="nav-item">
                            <a
                              class="nav-link active"
                              id="smtp-tab"
                              data-toggle="tab"
                              href="#smtp"
                              role="tab"
                              aria-controls="smtp"
                              aria-selected="false"
                              >SMTP</a
                            >
                          </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                          <div
                            class="tab-pane fade show active"
                            id="smtp"
                            role="tabpanel"
                            aria-labelledby="addsmtpress-tab"
                            v-if="group.smtp"
                          >
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">E-mail from</label>
                                        <input type="text" class="form-control" v-model="group.smtp.from_email" placeholder="E-mail from">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="">Name from</label>
                                        <input type="text" class="form-control" v-model="group.smtp.from_name" placeholder="Name from">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="">HOST</label>
                                        <input type="text" class="form-control" v-model="group.smtp.host" placeholder="SMTP">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="">SMTP username</label>
                                        <input type="text" class="form-control" v-model="group.smtp.user" placeholder="SMTP password">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="">SMTP password</label>
                                        <input type="password" class="form-control" v-model="group.smtp.password" placeholder="SMTP password">
                                    </div>
                                </div>
                                 <div class="col-2">
                                    <div class="form-group">
                                        <label for="">Port</label>
                                        <input type="number" class="form-control" v-model="group.smtp.port" placeholder="Port">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="">SMTP Authentication</label><br>
                                                <div class="form-check form-check-inline mr-1">
                                                    <input class="form-check-input" id="authentication1" v-model="group.smtp.authentication" type="radio" value="0" name="authentication">
                                                    <label class="form-check-label" for="authentication1">No</label>
                                                </div>
                                                <div class="form-check form-check-inline mr-1">
                                                    <input class="form-check-input" id="authentication2" v-model="group.smtp.authentication" type="radio" value="1" name="authentication">
                                                    <label class="form-check-label" for="authentication2">Yes</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col col-form-label">
                                            <label for="">SMTP encryption</label><br>
                                            <div class="form-check form-check-inline mr-1">
                                                <input class="form-check-input" v-model="group.smtp.encryption" id="encryption1" type="radio" value="none" name="encryption">
                                                <label class="form-check-label" for="encryption1">None</label>
                                            </div>
                                            <div class="form-check form-check-inline mr-1">
                                                <input class="form-check-input" v-model="group.smtp.encryption" id="encryption2" type="radio" value="ssl" name="encryption">
                                                <label class="form-check-label" for="encryption2">SSL</label>
                                            </div>
                                            <div class="form-check form-check-inline mr-1">
                                                <input class="form-check-input" v-model="group.smtp.encryption" id="encryption3" type="radio" value="tls" name="encryption">
                                                <label class="form-check-label" for="encryption3">TLS</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col text-right">
                                    <input type="button" class="btn btn-primary" :value="sendingEmail !== null ? 'Sending email...' : 'Send email test'" :disabled="sendingEmail !== null" @click="SendTest">
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>

                      <div class="card-footer text-right">
                        <router-link :to="{ name: 'Groups' }" class="btn btn-danger">Cancel</router-link>
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
      sendingEmail:null,
      group: {
        name: null,
        smtp: {
          from_name :null,
          from_email :null,
          user :null,
          password :null,
          host :null,
          port :587,
          encryption :'tls',
          authentication : 1,
        },
      },
      error: {},
    };
  },
  mounted: function () {
    this.$http.get("/api/groups/" + this.$route.params.id).then(function (e) {
      this.$data.group = e.body;
    });
  },
  methods: {
    RegisterUrl: function (val) {
      this.$data.signature.url = val;
    },

    SendTest(){

      if (!this.$data.group.smtp.user) {
        this.$toaster.error("SMTP USERNAME field is required");
        return;
      }

      if (!this.$data.group.smtp.password) {
        this.$toaster.error("SMTP PASSWORD field is required");
        return;
      }

      if (!this.$data.group.smtp.host) {
        this.$toaster.error("SMTP HOST field is required");
        return;
      }

      if (!this.$data.group.smtp.port) {
        this.$toaster.error("SMTP PORT field is required");
        return;
      }

      this.$data.error = {};
      this.$data.sendingEmail = 'Sending email...'
      this.$http
        .post("/api/groups/smtp-test", this.$data.group)
        .then(function (e) {

          this.$data.sendingEmail = null
  
          if (!e.body.success) {
            this.$toaster.error(e.body.error);
            return;
          }

          this.$toaster.success(e.body.success);
        });
    },

    Store: function () {
      if (!this.$data.group.name) {
        this.$toaster.error("Group name field is required");
        return;
      }

      this.$data.error = {};
      this.$http
        .put("/api/groups/" + this.$route.params.id, this.$data.group)
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
