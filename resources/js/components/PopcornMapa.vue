<template>
    <div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label for="inspection_date">MAPA Inspection Date</label>
              <input-icon icon="fas fa-calendar" type="date" name="inspection_date" :allowed_pattern="/^application\/pdf$/" v-model="mapa.inspection_date" :class="error.inspection_date != '' && error.inspection_date ? 'is-invalid' : ''" />
              <p class="invalid-feedback" v-if="error.inspection_date" v-for="message in error.inspection_date">{{message}}</p>
            </div>

          </div>

          <div class="col">
            <div class="form-group">
              <label for="due_code">DU-E Code</label>
              <input-icon icon="fas fa-code" type="text" name="due_code" :allowed_pattern="/^application\/pdf$/" v-model="mapa.due_code" :class="error.due_code != '' && error.due_code ? 'is-invalid' : ''" />
              <p class="invalid-feedback" v-if="error.due_code" v-for="message in error.due_code">{{message}}</p>
            </div>

          </div>

          <div class="col">
            <div class="form-group">
              <label for="ruc_code">RUC Code</label>
              <input-icon icon="fas fa-code" type="text" name="ruc_code" :allowed_pattern="/^application\/pdf$/" v-model="mapa.ruc_code" :class="error.ruc_code != '' && error.ruc_code ? 'is-invalid' : ''" />
              <p class="invalid-feedback" v-if="error.ruc_code" v-for="message in error.ruc_code">{{message}}</p>
            </div>

          </div>

          <div class="col">
            <div class="form-group">
              <label for="access_key">Access Key</label>
              <input-icon icon="fas fa-key" type="text" name="access_key" :allowed_pattern="/^application\/pdf$/" v-model="mapa.access_key" :class="error.access_key != '' && error.access_key ? 'is-invalid' : ''" />
              <p class="invalid-feedback" v-if="error.access_key" v-for="message in error.access_key">{{message}}</p>
            </div>

          </div>

        </div>

        <div class="row">

          <div class="col">
            <div class="form-group">
              <label for="bill_of_lading">Bill of Lading</label>
              <input-icon type="text" icon="fas fa-box" v-model="mapa.bill_of_lading" name="bill_of_lading" :class="error.bill_of_lading != '' && error.bill_of_lading ? 'is-invalid' : ''" id="bill_of_lading" />
              <div class="invalid-feedback" v-if="error.bill_of_lading" v-for="message in error.bill_of_lading">{{message}}</div>
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="currency_fee">Currency Fee (R$)</label>
              <div class="input-group" v-bind:class="error.currency_fee != '' && error.currency_fee ? 'is-invalid' : ''">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <i class="fas fa-money-bill"></i>
                  </div>
                </div>
                <input type="number" name="currency_fee" id="currency_fee" placeholder="Currency Fee" class="form-control" min="1" v-model="mapa.currency_fee" :class="error.currency_fee != '' && error.currency_fee ? 'is-invalid' : ''">
              </div>
              <p class="invalid-feedback" v-if="error.currency_fee" v-for="message in error.currency_fee">{{ message }}</p>
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="date_currency_fee">Currency Fee Date</label>
              <input-icon type="date" icon="fas fa-calendar" v-model="mapa.date_currency_fee" name="date_currency_fee" :class="error.date_currency_fee != '' && error.date_currency_fee ? 'is-invalid' : ''" id="date_currency_fee" />
              <div class="invalid-feedback" v-if="error.date_currency_fee" v-for="message in error.date_currency_fee">{{message}}</div>
            </div>
          </div>

          <div class="col">
            <div class="form-group">
              <label for="date_currency_fee">Dossiê</label>
              <input-icon type="text" icon="fas fa-folder" v-model="mapa.dossie" name="date_currency_fee" :class="error.dossie != '' && error.dossie ? 'is-invalid' : ''" id="dossie" />
              <div class="invalid-feedback" v-if="error.dossie" v-for="message in error.dossie">{{message}}</div>
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-6">
            <div class="form-group" id="observation">
              <label for="observation">Observation:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <i class="fas fa-edit"></i>
                  </div>
                </div>
                <textarea class="form-control" name="observation" id="observation" placeholder="Observation" v-model="mapa.observation"></textarea>
              </div>
            </div>
          </div>

          <div class="col-6">
            <div class="form-group" id="observation">
              <label for="observation">Additional Declaration:</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">
                    <i class="fas fa-edit"></i>
                  </div>
                </div>
                <textarea class="form-control" name="additional_declaration" id="additional_declaration" placeholder="Additional Information" v-model="mapa.additional_declaration"></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="phyto_certificate">Phyto certificate</label>
              <popcorn-upload-new name="phyto_certificate" :allowed_pattern="/^application\/pdf$/" v-model="mapa.phyto_certificate"></popcorn-upload-new>
            </div>
          </div>

          <div class="col-4"></div>

          <div class="col-4">
            <div class="form-group">
              <label for="phyto_certificate_signed">Phyto certificate signed</label><br>
              <popcorn-upload-new name="phyto_certificate_signed" :allowed_pattern="/^application\/pdf$/" v-model="mapa.phyto_certificate_signed"></popcorn-upload-new>
            </div>


          </div>

        </div>
        

        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="lpco_document">LPCO</label>
              <popcorn-upload-new name="lpco_document" :allowed_pattern="/^application\/pdf$/" v-model="mapa.lpco_document" :class="error.lpco_document != '' && error.lpco_document ? 'is-invalid' : ''"></popcorn-upload-new>
              <p class="invalid-feedback" v-if="error.lpco_document" v-for="message in error.lpco_document">{{message}}</p>
            </div>

          </div>

          <div class="col-4">
            <div class="form-group">
              <label for="lpco_key">LPCO Key</label>
              <input-icon icon="fas fa-key" type="text" name="lpco_key" :allowed_pattern="/^application\/pdf$/" v-model="mapa.lpco_key" :class="error.lpco_key != '' && error.lpco_key ? 'is-invalid' : ''" />
              <p class="invalid-feedback" v-if="error.lpco_key" v-for="message in error.lpco_key">{{message}}</p>
            </div>
          </div>

          <div class="col-4">
            <div class="form-group">
              <label for="lpco_document_signed">LPCO Signed</label><br>
              <popcorn-upload-new name="lpco_document_signed" :allowed_pattern="/^application\/pdf$/" v-model="mapa.lpco_document_signed" :class="error.lpco_document_signed != '' && error.lpco_document_signed ? 'is-invalid' : ''"></popcorn-upload-new>
              <p class="invalid-feedback" v-if="error.lpco_document_signed" v-for="message in error.lpco_document_signed">{{message}}</p>
            </div>


          </div>

        </div>

        <div class="row">
          <div class="col-4">
            <div class="form-group">
              <label for="nfe_document">NFE</label>
              <popcorn-upload-new name="nfe_document" :allowed_pattern="/^application\/pdf$/" v-model="mapa.nfe_document" :class="error.nfe_document != '' && error.nfe_document ? 'is-invalid' : ''"></popcorn-upload-new>
              <p class="invalid-feedback" v-if="error.nfe_document" v-for="message in error.nfe_document">{{message}}</p>
            </div>

          </div>

          <div class="col-4">
            <div class="form-group">
              <label for="nfe_key">NFE Key</label>
              <input-icon icon="fas fa-key" type="text" name="nfe_key" :allowed_pattern="/^application\/pdf$/" v-model="mapa.nfe_key" :class="error.nfe_key != '' && error.nfe_key ? 'is-invalid' : ''" />
              <p class="invalid-feedback" v-if="error.nfe_key" v-for="message in error.nfe_key">{{message}}</p>
            </div>
          </div>

          <div class="col-4">
            <div class="form-group">
              <label for="nfe_document">DU-E</label>
              <popcorn-upload-new name="due_document" :allowed_pattern="/^application\/pdf$/" v-model="mapa.due_document" :class="error.due_document != '' && error.due_document ? 'is-invalid' : ''"></popcorn-upload-new>
              <p class="invalid-feedback" v-if="error.due_document" v-for="message in error.due_document">{{message}}</p>
            </div>

          </div>
        </div>
        
        <div class="row" v-if="mapa.id">
            <div class="col">
                <dr-history :url="'/api/mapaObservation/' + mapa.id"></dr-history>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['value', 'n_phyto'],
    name: 'popcorn-mapa',
    data () {
        return {
            mapa: {},
            error: {},
        }
    },
    watch: {
        'mapa.phyto_certificate': function (newValue, oldValue) {
          this.$emit('phyto', newValue)
        },
        'mapa.phyto_certificate_signed': function (newValue, oldValue) {
          this.$emit('phyto_original', newValue);
        }
    },
    mounted: function () {
        if(this.$props.value) {
            this.$data.mapa = JSON.parse(JSON.stringify(this.$props.value));
        }

        if (!this.$data.mapa.phyto_certificate) {
          this.$data.mapa.phyto_certificate = this.$props.n_phyto
        }
    },
    methods: {
        Save: function () {
            this.$http.post('/api/mapa/' + this.$route.params.id, this.$data.mapa).then( e => {
                if (e.body.error) {
                    this.$toaster.danger(e.body.error);
                }
            })
        },
        RegisterPhytoCertificate: function ( value ) {
          this.$data.mapa.phyto_certificate = value;
        },
        RegisterPhytoCertificateSigned: function ( value ) {
          this.$data.mapa.phyto_certificate_signed = value;
        }
    }
}
</script>