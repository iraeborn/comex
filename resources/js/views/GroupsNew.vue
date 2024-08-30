<template>
    <div class="container-fluid">
        <div id="ui-view"></div>
            <div class="panel panel-success">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                New Signature
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="qtd_container">Name</label>
                                    <input-icon type="text" icon="fas fa-user" v-model="signature.text" name="name"  />
                                </div>
                            </div>
                           <div class="col-12">
                                <div class="form-group">
                                    <label for="qtd_container">Term (Part 1)</label>
                                    <textarea-icon type="textarea" icon="fas fa-list" v-model="signature.term1" name="term1"  />
                                    
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="qtd_container">Term (Part 2)</label>
                                    <textarea-icon type="textarea" icon="fas fa-list" v-model="signature.term2" name="term2"  />
                                    
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="qtd_container">Signature Image (jpg,png,gif)</label>
                                     <popcorn-upload-new 
                                        name="url" 
                                        allowed_pattern="image" 
                                        v-model="signature.url" 
                                        :file="signature.url" 
                                        @upload="RegisterUrl" 
                                        :class="error.url != '' && error.url ? 'is-invalid' : ''"
                                      />
                                </div>
                            </div>
                        </div>
                    </div>

                      <div class="card-footer text-right">
                        <router-link :to="{ name: 'Signatures' }" class="btn btn-danger">Cancel</router-link>
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
      signature: {
        text: null,
        url: null,
        term1:null,
        term2:null
      },
      error: {},
    };
  },
  methods: {
    RegisterUrl: function (val) {
      this.$data.signature.url = val;
    },

    Store: function () {

      if(!this.$data.signature.url){
        this.$toaster.error('Signature image field is required');
        return;
      }

      if(!this.$data.signature.text){
        this.$toaster.error('Name field is required');
        return;
      }

      this.$data.error = {};
      this.$http
        .post("/api/signatures", this.$data.signature)
        .then(function (e) {
          if (!e.body.success) {
            this.$data.error = e.body.errors;
            return;
          }

          this.$toaster.success(e.body.success);
          this.$router.push("/signatures");
        });
    },
  },
};
</script>
