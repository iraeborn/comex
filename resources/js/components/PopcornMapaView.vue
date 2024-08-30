<template>
    <div>
      <div v-if="!mapa">
        No data
      </div>
      <div v-if="mapa">
        <table class="table table-sm table-amarelo">
          <tr>
            <th>
                <strong for="inspection_date">MAPA Inspection Date</strong>
                <p>{{ FormatDate(mapa.inspection_date) }}</p>
            </th>
            <th>
              <strong>DU-E Code</strong>
              <p>{{ mapa.due_code }}</p>
            </th>
            <th>
              <strong>RUC Code</strong>
              <p>{{ mapa.ruc_code }}</p>
            </th>
            <th>
              <strong>Access Key</strong>
              <p>{{ mapa.access_key }}</p>
            </th>
            <th>
              <strong>LPCO key</strong>
              <p>{{ mapa.lpco_key }}</p>
            </th>
          </tr>
        </table>

        <div class="row" style="display: none;">
          <div class="col">
            <div class="form-group">
              <strong for="mapa_document">Requerimento MAPA</strong>
              <div class="alert alert-danger" v-if="!mapa.mapa_document">Document not sent</div>
              <p v-if="mapa.mapa_document">
                  <a :href="mapa.mapa_document" class="btn btn-primary" download>Download</a>
              </p>
            </div>

          </div>

          <div class="col">
            <div class="form-group">
              <strong for="mapa_document_signed">Requerimento MAPA Assinado</strong>
              <div class="alert alert-danger" v-if="!mapa.mapa_document_signed">Document not sent</div>
              <a v-if="mapa.mapa_document_signed" class="btn btn-primary" :href="mapa.mapa_document_signed" download>
                  Download
              </a>
            </div>

          </div>

        </div>
        
        <div class="row">
          <div class="col">
            <div class="form-group">
              <strong for="phyto_document">Phyto certificate</strong>
              <div class="alert alert-danger" v-if="!mapa.phyto_document">Document not sent</div>
              <p v-if="mapa.phyto_document">
                  <a :href="mapa.phyto_document" class="btn btn-primary" download>Download</a>
              </p>
            </div>

          </div>

          <div class="col">
            <div class="form-group">
              <strong for="phyto_document_signed">Phyto certificate signed</strong>
              <div class="alert alert-danger" v-if="!mapa.phyto_document_signed">Document not sent</div>
              <p v-if="mapa.phyto_document_signed">
                <a class="btn btn-primary" :href="mapa.phyto_document_signed" download>Download</a>
              </p>
            </div>

          </div>

        </div>
        
        <div class="row">
          <div class="col">
            <div class="form-group">
              <strong for="lpco_document">LPCO</strong>
              <div class="alert alert-danger" v-if="!mapa.lpco_document">Document not sent</div>
              <p v-if="mapa.lpco_document">
                  <a :href="mapa.lpco_document" class="btn btn-primary" download>Download</a>
              </p>
            </div>

          </div>

          <div class="col">
            <div class="form-group">
              <strong for="lpco_document_signed">LPCO Signed</strong>
              <div class="alert alert-danger" v-if="!mapa.lpco_document_signed">Document not sent</div>
              <p v-if="mapa.lpco_document_signed">
                <a class="btn btn-primary" :href="mapa.lpco_document_signed" download>Download</a>
              </p>
            </div>

          </div>

        </div>
        
        <div class="row" v-if="mapa.id">
            <div class="col">
                <dr-history :url="'/api/mapaObservation/' + mapa.id"></dr-history>
            </div>
        </div>
      </div>
    </div>
</template>

<script>
export default {
    props: ['value'],
    name: 'popcorn-mapa',
    data () {
        return {
            mapa: null,
            error: {},
        }
    },
    watch: {
        value: function (newValue, oldValue) {
            this.$data.mapa = newValue;
        }
    },
    mounted: async function () {
      this.$data.mapa = this.$props.value;
      
      let drafts = await this.$http.get('/api/draft/' + this.$route.params.id);
      let original = await this.$http.get('/api/original-documents/' + this.$route.params.id);
      
      this.$data.mapa.phyto_document = drafts.body.certificate_phyto;
      this.$data.mapa.phyto_document_signed = original.body.certificate_phyto;

      this.$forceUpdate();
    },
    methods: {
        FormatDate: function ( date ) {
            if(!date) return '';
            let date_array = date.split(/-/);
            return date_array[1] + "/" + date_array[2] + "/" + date_array[0];
        },
        GetFileName: function ( file ) {
          if(!file) return '';
            var file_name = file.match(/[^/]+$/);
            return file_name;
        }
    }
}
</script>