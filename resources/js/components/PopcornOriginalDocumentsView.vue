<template>
  <div>
    <div class="row">
      <div class="col-4">
        <div class="form-group">
          <label for="draft_bl">BL</label><br>
          <a :href="original_document.draft_bl" download class="btn btn-primary" v-if="original_document.draft_bl">Download</a>
          <p v-if="!original_document.draft_bl" class="alert alert-danger">This document has not been submitted yet</p>
        </div>

      </div>

      <div class="col">
        <div class="form-group">
          <label for="draft_comercial">COMERCIAL INVOICE</label><br>
          <a :href="original_document.draft_comercial" download class="btn btn-primary" v-if="original_document.draft_comercial">Download</a>
          <p v-if="!original_document.draft_comercial" class="alert alert-danger">This document has not been submitted yet</p>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="packing_list">PACKING LIST</label><br>
          <a :href="original_document.packing_list" download class="btn btn-primary" v-if="original_document.packing_list">Download</a>
          <p v-if="!original_document.packing_list" class="alert alert-danger">This document has not been submitted yet</p>
        </div>

      </div>

    </div>
    <div class="row">
      
      <div class="col">
        <div class="form-group">
          <label for="certificate_origin">CERTIFICATE OF ORIGIN</label><br>
          <a :href="original_document.certificate_origin" download class="btn btn-primary" v-if="original_document.certificate_origin">Download</a>
          <p v-if="!original_document.certificate_origin" class="alert alert-danger">This document has not been submitted yet</p>
        </div>

      </div>

      <div class="col">
        <div class="form-group">
          <label for="certificate_fumigation">FUMIGATION CERTIFICATE</label><br>
          <a :href="original_document.certificate_fumigation" download class="btn btn-primary" v-if="original_document.certificate_fumigation">Download</a>
          <p v-if="!original_document.certificate_fumigation" class="alert alert-danger">This document has not been submitted yet</p>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="certificate_quality">QUALITY CERTIFICATE</label><br>
          <a :href="original_document.certificate_quality" download class="btn btn-primary" v-if="original_document.certificate_quality">Download</a>
          <p v-if="!original_document.certificate_quality" class="alert alert-danger">This document has not been submitted yet</p>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="form-group">
          <label for="certificate_weight">WEIGHT CERTIFICATE</label><br>
          <a :href="original_document.certificate_weight" download class="btn btn-primary" v-if="original_document.certificate_weight">Download</a>
          <p v-if="!original_document.certificate_weight" class="alert alert-danger">This document has not been submitted yet</p>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="certificate_seguro">CERTIFICATE OF INSURANCE</label><br>
          <a :href="original_document.certificate_seguro" download class="btn btn-primary" v-if="original_document.certificate_seguro">Download</a>
          <p v-if="!original_document.certificate_seguro" class="alert alert-danger">This document has not been submitted yet</p>
        </div>
      </div>

      <div class="col">
        <div class="form-group">
          <label for="certificate_phyto">PHYTO CERTIFICATE</label><br>
          <a :href="original_document.certificate_phyto" download class="btn btn-primary" v-if="original_document.certificate_phyto">Download</a>
          <p v-if="!original_document.certificate_phyto" class="alert alert-danger">This document has not been submitted yet</p>
        </div>
      </div>

    </div>
    <div class="row">
      
      <div class="col-4">
        <div class="form-group">
          <label for="report">STUFFING REPORT</label><br>
          <a :href="original_document.report" download class="btn btn-primary" v-if="original_document.report">Download</a>
          <p v-if="!original_document.report" class="alert alert-danger">This document has not been submitted yet</p>
        </div>

      </div>
      
      <div class="col-4">
        <div class="form-group">
          <label for="health_certificate">HEALTH CERTIFICATE</label><br>
          <a :href="original_document.health_certificate" download class="btn btn-primary" v-if="original_document.health_certificate">Download</a>
          <p v-if="!original_document.health_certificate" class="alert alert-danger">This document has not been submitted yet</p>
        </div>

      </div>
  </div>

  <div class="row" v-if="original_document.others.length > 0">
      <div class="col">
        <label for="other">OTHER DOCUMENTS</label>
      </div>
  </div>
  <div class="row" v-for="(other, index) in original_document.others">
      <div class="col-6">
        <div class="form-group">
          <a :href="original_document.others[index]" download class="btn btn-primary" v-if="original_document.others[index]">Download</a>
          <p v-if="!original_document.others[index]" class="alert alert-danger">This document has not been submitted yet</p>
        </div>

      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'popcorn-original-documents',
  data () {
    return {
      loading: true,
      original_document: {},
      error: {}
    }
  },
  mounted: function () {
    this.$http.get('/api/original-documents/' + this.$route.params.id).then( e => {
      if(!e.body.others) e.body.others = [];
      this.original_document = e.body
    })
  },
  methods: {}
}
</script>