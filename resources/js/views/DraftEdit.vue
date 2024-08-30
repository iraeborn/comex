<template>
  <div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="draft_bl">DRAFT BL</label>
          <popcorn-upload name="draft_bl" :allowed_pattern="/^application\/pdf$/" v-model="draft.draft_bl" :file="draft.draft_bl" @upload="RegisterDraftBl" :class="error.draft_bl != '' && error.draft_bl ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.draft_bl" v-for="message in error.draft_bl">{{message}}</p>
        </div>

      </div>

      <div class="col">
        <div class="form-group">
          <label for="draft_comercial">DRAFT COMERCIAL INVOICE</label>
          <popcorn-upload name="draft_comercial" :allowed_pattern="/^application\/pdf$/" v-model="draft.draft_comercial" :file="draft.draft_comercial" @upload="RegisterDraftComercial" class="error.draft_comercial != '' && error.draft_comercial ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.draft_comercial" v-for="message in error.draft_comercial">{{message}}</p>
        </div>
      </div>
    </div>
  
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="packing_list">PACKING LIST</label>
          <popcorn-upload name="packing_list" :allowed_pattern="/^application\/pdf$/" v-model="draft.packing_list" :file="draft.packing_list" @upload="RegisterPackingList" class="error.packing_list != '' && error.packing_list ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.packing_list" v-for="message in error.packing_list">{{message}}</p>
        </div>

      </div>

      <div class="col">
        <div class="form-group">
          <label for="certificate_origin">CERTIFICATE OF ORIGIN</label>
          <popcorn-upload name="certificate_origin" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_origin" :file="draft.certificate_origin" @upload="RegisterCertificateOrigin" class="error.certificate_origin != '' && error.certificate_origin ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_origin" v-for="message in error.certificate_origin">{{message}}</p>
        </div>

      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="certificate_fumigation">FUMIGATION CERTIFICATE</label>
          <popcorn-upload name="certificate_fumigation" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_fumigation" :file="draft.certificate_fumigation" @upload="RegisterCertificateFumigation" class="error.certificate_fumigation != '' && error.certificate_fumigation ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_fumigation" v-for="message in error.certificate_fumigation">{{message}}</p>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="certificate_quality">QUALITY CERTIFICATE</label>
          <popcorn-upload name="certificate_quality" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_quality" :file="draft.certificate_quality" @upload="RegisterCertificateQuality" class="error.certificate_quality != '' && error.certificate_quality ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_quality" v-for="message in error.certificate_quality">{{message}}</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="certificate_weight">WEIGHT CERTIFICATE</label>
          <popcorn-upload name="certificate_weight" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_weight" :file="draft.certificate_weight" @upload="RegisterCertificateWeight" class="error.certificate_weight != '' && error.certificate_weight ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_weight" v-for="message in error.certificate_weight">{{message}}</p>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="certificate_seguro">CERTIFICATE OF INSURANCE</label>
          <popcorn-upload name="certificate_seguro" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_seguro" :file="draft.certificate_seguro" @upload="RegisterCertificateSeguro" class="error.certificate_seguro != '' && error.certificate_seguro ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_seguro" v-for="message in error.certificate_seguro">{{message}}</p>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="certificate_phyto">PHYTO CERTIFICATE</label>
          <popcorn-upload name="certificate_phyto" :allowed_pattern="/^application\/pdf$/" v-model="draft.certificate_phyto" :file="draft.certificate_phyto" @upload="RegisterCertificatePhyto" class="error.certificate_phyto != '' && error.certificate_phyto ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.certificate_phyto" v-for="message in error.certificate_phyto">{{message}}</p>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="report">STUFFING REPORT</label>
          <popcorn-upload name="report" :allowed_pattern="/^application\/pdf$/" v-model="draft.report" :file="draft.report" @upload="RegisterReport" class="error.report != '' && error.report ? 'is-invalid' : ''"></popcorn-upload>
          <p class="invalid-feedback" v-if="error.report" v-for="message in error.report">{{message}}</p>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'popcorn-draft-documents',
  data () {
    return {
      loading: true,
      draft: {},
      error: {}
    }
  },
  mounted: function () {
    this.$http.get('/api/draft/' + this.$route.params.id).then( e => {
      this.draft = e.body
    })
  },
  methods: {
    formatDate: function (val) {
      return val
    },
    RegisterDraftBl: function ( val ) {
      this.$data.draft.draft_bl = val
    },
    RegisterDraftComercial: function ( val ) {
      this.$data.draft.draft_comercial = val
    },
    RegisterPackingList: function ( val ) {
      this.$data.draft.packing_list = val
    },
    RegisterCertificateOrigin: function ( val ) {
      this.$data.draft.certificate_origin = val
    },
    RegisterCertificateFumigation: function ( val ) {
      this.$data.draft.certificate_fumigation = val
    },
    RegisterCertificateQuality: function ( val ) {
      this.$data.draft.certificate_quality = val
    },
    RegisterCertificateWeight: function ( val ) {
      this.$data.draft.certificate_weight = val
    },
    RegisterCertificateSeguro: function ( val ) {
      this.$data.draft.certificate_seguro = val
    },
    RegisterCertificatePhyto: function ( val ) {
      this.$data.draft.certificate_phyto = val
    },
    RegisterReport: function ( val ) {
      this.$data.draft.report = val
    },
    Save: function () {
      this.$http.put('/api/draft/' + this.$route.params.id, this.$data.draft).then( e => {
        if (e.body.success) {
          //this.$toaster.success(e.body.success)
        }
      })
    }
  }
}
</script>